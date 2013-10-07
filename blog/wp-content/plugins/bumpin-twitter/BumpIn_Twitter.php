<?php
/*
	Plugin Name: BumpIn Twitter Connect
	Plugin URI: http://www.bumpin.com
	Description: Install BumpIn widgets for your wordpress and give an interaction mechanism to people visiting your website.
	Author: BumpIn Team (team@bumpin.com)
	Version: 1.0
	Author URI: http://www.bumpin.com/
*/

/*  Copyright 2008  BumpIn  (email : info@bumpin.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/


function render_bumpin_twitter( $opt, $before_title, $after_title) {

	$bumpin_code = apply_filters( 'widget_text', $opt['bumpin_code'] );	

	if(!defined('BUMIPN_BAR_RENDERED')){
		define('BUMIPN_BAR_RENDERED','rendered');
		echo $bumpin_code;
	}
	else{
		//echo "done 1";
	}
}

function widget_bumpin_twitter_control( $args=null ) {
	$options = $newoptions = get_option('BumpIn');

	if ( $_POST["submit_code"] ) {
		$newoptions['bumpin_code'] = stripslashes($_POST["bumpin_code"]);
	}
	if ( $options != $newoptions ) {
		$options = $newoptions;
		update_option('BumpIn', $options);
	}

	$bumpin_code = format_to_edit($options['bumpin_code']);
	
	if ( $bumpin_code && $bumpin_code != "" ){
?>
		<p style="text-align:justify;">It seems you are already using BumpIn SocialBar widgets. </p>
                <p style="text-align:justify; color:red;">If you dont see the "Twitter App" in the SocialBar at your site, Please follow the steps below.</p>
		<span style="text-align:justify;">

                    <ol>
                        <li><a href = "http://socialbar.ticketmy.com/user_session/new" target = "_blank">Login to SocialBar Admin page</a>. Your login name is the email id you used for registration.</li>
                        <li>Click on the "Edit Socialbar" link, click the "Twitter" app in the "Select Applications" tab.</li>
                        <li>Provide your Twitter username.</li>
                        <li>Hit the "Update" button and refresh your website to start using Twitter in the BumpIn SocialBar.</li>
                   </ol>
		</span>
		<p>
			<textarea style="width: 590px; height: 150px;" id="bumpin_code" name="bumpin_code"><?php echo $bumpin_code; ?></textarea>
			<input type="hidden" id="submit_code" name="submit_code" value="1" />
		</p>
		<p style="text-align:justify;">
			<span style="font-family:times;color:#585858;font-size:16px;font-weight:bold">Troubleshoot: </span>
			If the above text-area is empty, you would not be able to use the SocialBar on your site. Please copy the SocialBar install code from "Install Again" tab in SocialBar admin site and paste it in the above text-area to start using the BumpIn SocialBar on your site/blog.
		</p>
		<p style="text-align:justify;">
			<span style="font-family:times;color:#585858;font-size:16px;font-weight:bold">Admin: </span>
			You can add more applications to the SocialBar and customize theme of the SocialBar according to your site from the admin site. Your login name is the email you used for registration. The changes you make to the SocialBar from the admin dashboard will be automatically reflected on your blog/site.<a href = "http://socialbar.ticketmy.com/user_session/new" target = "_blank">Click Here to go to SocialBar Admin Site</a> 
		</p>
<?php
	}
	else{
?>

	<p style="text-align:justify;">Enabling the BumpIn widgets on your blog is a two step process:</p>
	<p style="text-align:justify;">
		<span style="font-family:times;color:#FF6600;font-size:16px;font-weight:bold">1) Customize: </span> 
		Customize the colors, size and placement of the selected widget according to the look&feel of your blog/website. At the end of this process you will be given a short snippet of code 			which you can cut and paste in step 2 below. (Please use a valid email id when registering the widget since it will be used for verification.)
	</p>
	<p style="text-align:justify;">
		<span style="font-family:times;color:#FF6600;font-size:16px;font-weight:bold">2) Embed: </span>
		Cut and paste the code you got from BumpIn site in the following text box and click the "Save Changes" button. Please do not modify the code!
	</p>
	<p style="text-align:justify;">
		<span style="font-family:times;color:black;font-size:16px;font-weight:bold">- BumpIn Socialbar:</span> 
		To get the code for the bar <a href = "http://socialbar.ticketmy.com/checkout?wordpress=true&plugin=true&twitter_updates=true" target = "_blank">Click Here</a>
	</p>
	<p>
		<textarea style="width: 590px; height: 150px;" id="bumpin_code" name="bumpin_code"><?php echo $bumpin_code; ?></textarea>
		<input type="hidden" id="submit_code" name="submit_code" value="1" />
	</p>
	<p style="text-align:justify;">
		<span style="font-family:times;color:#585858;font-size:16px;font-weight:bold">Verification: </span>
		Once step 1 & 2 are successfully done, you will receive an email from BumpIn on the same id you used for registering. Please click on the link in the email to set your password for 			administrative rights to the widget.
	</p>
	<p style="text-align:justify;">
		<span style="font-family:times;color:#585858;font-size:16px;font-weight:bold">Admin: </span>
		You can login to moderate and re-customize the widget. Your login name is the email you used for registration. The changes you make to the SocialBar widgets from the admin dashboard 			will be automatically reflected on your blog/site.<a href = "http://socialbar.ticketmy.com/user_session/new" target = "_blank">Click Here for Socialbar Admin</a> 
	</p>
	<p style="text-align:justify;">
		<span style="font-family:times;color:#FF6600;font-size:16px;font-weight:bold">* </span>
		If you have forgotten your password, you can reset it <a href="http://socialbar.ticketmy.com/site/forgot_password/" target="_blank">(Click to reset password)</a>, by verifying your email 		address.
	</p>

<?php
	}
}

function widget_bumpin_twitter($args) {
	extract($args);
	$options = get_option('BumpIn');
	echo $before_widget;
	render_bumpin_twitter($options,$before_title,$after_title); 
  	echo $after_widget;
}

function bumpin_twitter_init()
{
	register_sidebar_widget(__('Twitter Connect'), 'widget_bumpin_twitter');   
	register_widget_control('Twitter Connect','widget_bumpin_twitter_control',600,600);    /* width, height*/
}
add_action("plugins_loaded", "bumpin_twitter_init");
?>
