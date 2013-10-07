<?php

$notify_email = 'info@restylesource.com';

$headers = "Content-Type: text/plain; charset=us-ascii\r\n";
$headers.= "Content-Transfer-Encoding: 7bit\r\n";
$headers.= "From: signup@restylesource.com\r\n";

mail($notify_email, 'SignUp - RESTYLE SOURCE', 'Name: ' . $_POST['name'] . '
' . 'Email: ' . $_POST['email'] . '
I am: ' . $_POST['iam'], $headers);

echo('<h1>THANK YOU!</h1>
				<h3><strong>COMING SOON:</strong> A NEW WAY TO CONNECT TO LIFESTYLE, DESIGN, &amp; RETAIL SOURCES IN YOUR COMMUNITY</h3>
				<h4>WE WILL INFORM YOU WHEN WE LAUNCH?</h4>');

?>