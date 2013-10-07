<?

class sqlSession
{

	/// Database link
	var $db;

	/// Max unauthorized users can be. After that session and session vars will be deleted
	var $MAX_UNAUTH_IDLE = 3600;	// 1h
	
	/// How many seconds user may be loged in to the system. After that user will be logged out.
	var $MAX_AUTH_IDLE = 1800; // 30min
	
	/// Session id
	var $session;
	
	/// Session ID code lenght. (MAX lenght 32 chars)
	var $session_code_length=20;
	
	/// Load vars from database to GLOBALS or not? 1 = true, 0 = false;
	var $load_vars = 1;
	
	/// Set to GLOBALS $SID or not? 1 = true, 0 = false; (not tested feature yet)
	var $globalID = 0;
	
	/// Session table
	var $session_table = "auth_session";
	
	/// Session vars table
	var $session_vars_table = "auth_session_vars";
	
	/// inner array for variables
	var $variables;

	/// userID
	var $userID = 0;


	/*!
		Class constructor.
		To constructor you should forward database link
		From cookies get session id and class variable $session set this ID, after that 
		proceed demand_session()
	*/
	function sqlSession() 
	{
		global $g_db;
		$this->db = & $g_db;
		
		if (isset($_COOKIE['session'])) 
		{ 
			//echo("Thinks we have a session: " . $_COOKIE['session'] ." <BR>");
			$this->session = $_COOKIE['session'];
			
			if ($this->globalID == 1) 
			{
				$GLOBALS['SID'] = $this->session;
			}
			
		} 
		else 
		{
			//echo("Thinks we DONT have a session <BR>");
			$this->session = false; 
		}
		
		mt_srand ((double) microtime() * 1000000);
		if (mt_rand(0, 20) < 3) { $this->killold(); }
		$this->demand_session();
	}

	/*!
		Begin session:
		validating session ID if isset ID loadvars from database to globals (if,
		$this->loadvars == 1 ), else if not set session ID or session ID corrupted
		created new session		
	*/	
	function demand_session()
	{
 		if (!$this->valid_session($this->session))
		{
	  		$this->session=$this->begin_session();
  		}
		else 
		{
  			if ($this->load_vars == 1) 
			{
  				 $this->loadvars($this->session);
  			}
		 	$this->s_touch($this->session);
  		}
	}
	
	
	/*!
		Validating session ID. Is this ID in database?	
	*/
	function valid_session($sess)
	{
		 $this->killold();

		 if (!$sess) return 0;
		
		 $tmp = $this->db->get_result("SELECT * FROM $this->session_table WHERE id='$sess'");

		 if (!empty($tmp)) 	$this->userID = $tmp[0]['userID'];
		 return $tmp;
	}


	/*!
		Kill old sessions and session vars and logout users from system after MAX_AUTH_IDLE.	
	*/
	function killold()
	{
		// Kill old sessions where idle MAX_UNAUTH_IDLE seconds
		$result = $this->db->get_result("SELECT vars.id 
					FROM $this->session_table AS sess, $this->session_vars_table AS vars 
					WHERE sess.id=vars.session AND sess.LastAction < ". (time() - $this->MAX_UNAUTH_IDLE));
		 
		// DELETE old sessions VARS
		if (is_array($result)) 
		{
			while (list($key,$val) = each($result)) 
			{
		 	 	$this->db->query("DELETE FROM $this->session_vars_table 
					WHERE id=".$val['id']);
		 	}
		}
		 
		// kill Sessions after MAX_UNAUTH_IDLE for the sake of resources!
		$this->db->query("DELETE FROM $this->session_table
					WHERE LastAction < ".(time()-$this->MAX_UNAUTH_IDLE));
		
		// log users out if idle for MAX_AUTH_IDLE seconds
		$this->db->query("UPDATE $this->session_table 
					SET userID=NULL 
					WHERE LastAction < ".(time()-$this->MAX_AUTH_IDLE));

	}

	/*!
		Generating new session ID, inserting to database and set cookie to user
	*/
	function begin_session()
	{
		$sesscode = $this->gencode();
		
		$this->db->query("INSERT INTO $this->session_table 
					VALUES ('$sesscode',".time().",'".$_SERVER['REMOTE_ADDR']."',NULL)");
		
		setcookie("session",$sesscode, false, "/");
		 
		if ($this->globalID == 1) 
		{
			$GLOBALS['SID'] = $sesscode;
		}
		 
		 return $sesscode;
	}
	

	/*!
		Generating sessionID (look at $this->session_code_lenght)

		2001.07.11 js
			+ $sid initialisation
			+ more unique srand
	*/
	function gencode()
	{
	
		$this->killold();
		$sid = 0;
		mt_srand ((double) microtime() * 1000000);
		$Puddle = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
		
		for($index=0; $index < $this->session_code_length - 1; $index++)
		{
			$sid .= substr($Puddle, (mt_rand()%(strlen($Puddle))), 1);
		}
		
		// If by some miracle this id exists, return 0. It will not pass
		// when it is checked next.
		if ($this->valid_session($sid)) { $sid = 'INVALID'; }

		return $sid;
	}
	
	/*!
		Get data (variables) from database and make it GLOBALS if $this->loadvars is set to 1
	*/
	function loadvars($session)
	{
		$result = $this->db->get_result("SELECT * FROM $this->session_vars_table 
					WHERE session='$session'");
		
		if($result)
		{
		 	while(list($key,$val) = each($result)) 
			{
				$GLOBALS[$val['name']] = $val['value'];
				$this->variables[$val['name']] = $val['value'];
			}
		}
	}

	/*!
		Update session LastAction
	*/	
	function s_touch($sess)
	{
		 $this->db->query("UPDATE $this->session_table 
					SET LastAction=".time()." 
					WHERE id='$sess'");
	}
	
	/*!
		Set new session variable. All variables stored in 
		\param $this->session_vars_table table
		\param $varname - variable name
		\param $value - value of variable
	*/
	function set_var($varname, $value)
	{
		  $sql = "SELECT id FROM {$this->session_vars_table} WHERE name='$varname' AND session='{$this->session}'";
		  if ($this->db->is_empty($sql)) {
		  	
		  	$this->db->query("INSERT INTO {$this->session_vars_table} 
		  			(name,session,value,id) VALUES
		  			('$varname', '$this->session', '$value', NULL)");
		  			
		  } else {
		  	
		  	$mas["value"] = $value;
		  	$ids["session"] = $this->session;
		  	$ids["name"] = $varname;
		  	
		  	$this->db->update_query($mas,$this->session_vars_table,$ids);
		  	
		  }
		  
		  $GLOBALS[$varname] = $value;
		  $this->variables[$varname] = $value;
	}

	/*!
		Get variable value from inner array
		\param $varname - variable name 	
	*/
	function get_var($varname)
	{
		if (isset($this->variables[$varname])) 
		{
			return $this->variables[$varname];
		}
		else
		{
			return false;
		}
	}	
	
	/*!
		Drop variable from user session.
		+ nk, 2001.12.13 ( added this function )
	*/
	
	function drop_var($varname) 
	{
		$sql = "DELETE from {$this->session_vars_table} WHERE name='$varname' AND session='{$this->session}'";
		$this->db->query($sql);
		
		$GLOBALS[$varname] = "";
		$this->variables[$varname] = "";
		
		return true;	
	}
	
	/*!
		Is variable registered for this session
	*/
	function is_registered($varname)
	{
		$this->db->query("SELECT * FROM $this->session_vars_table 
					WHERE session='$this->session' AND name='$varname'");
		  		
		return $this->db->not_empty();
	}

	/*!
		Return session ID
	*/	
	function return_sessionID()
	{
		return $this->session;	
	}
	
	/*!
		Login User into the system. 
	*/
	function user_login($userID)
	{
		 $this->demand_session();
					
		if ($userID > 0) 
		{
		 	$this->db->query("UPDATE $this->session_table 
						SET userID = $userID
						WHERE id='$this->session'");
		}
		else
		{
			return "<font color=red><strong>Login error!</strong></font>";
		}
		
	}

	/*!
		Logout user from system and delete all session vars and session ID
		Kill session
	*/
	function logout()
	{
		$this->db->query("DELETE FROM $this->session_table 
					WHERE id='$this->session'");
		
		$this->db->query("DELETE FROM $this->session_vars_table 
					WHERE session='$this->session'");

		setcookie('cookie_username', "",time()+60*60*24*30*12,'/');
		setcookie('cookie_password', "",time()+60*60*24*30*12,'/');
		
	 	return true;
	}
	
	/*!
		Is user logged in into system, and if so method returns user ID
	*/
	function user_logged_in()
	{
		$this->killold();

		$result = $this->db->get_array("SELECT userID 
					FROM $this->session_table 
					WHERE id='$this->session'");

		if (!empty($result['userID'])) 
		{
			return $result['userID'];
		} 
		else 
		{
			return false;
		}
	}

	/*!
		Returns how many users online
	*/
	function users_online($who = "all", $interval=1800)
	{
		switch ($who) 
		{
			case "all":
					$query = "SELECT COUNT(*) AS kiek 
						FROM $this->session_table 
						WHERE LastAction > ". (time()-$interval);
				break;

			case "registered":
					$query = "SELECT COUNT(*) AS kiek 
						FROM $this->session_table 
						WHERE userID IS NOT NULL AND LastAction > ". (time()-$interval);
				break;
		}
		
		if (empty($query))
		{
				$query= "SELECT COUNT(*) as kiek FROM ".$this->session_table." WHERE LastAction > ". (time()-$interval);
		}

		$result = $this->db->get_array($query);

		if ($result)
		{
			return $result['kiek'];
		}
		else
		{
			return false;
		}
	}

} //end of class

?>
