<?php
/**
 * This example shows making an SMTP connection with authentication.
 */
//Import the PHPMailer class into the global namespace
use PHPMailer\PHPMailer\PHPMailer;
//SMTP needs accurate times, and the PHP time zone MUST be set
//This should be done in your php.ini, but this is how to do it if you don't have access to that
//date_default_timezone_set('Etc/UTC');

use PHPMailer\PHPMailer\Exception;

require 'Exception.php';
require 'PHPMailer.php';
require 'SMTP.php';
 
// 'secure' => 'tls' ou 'ssl' ou 'starttls' ou absent
 
$mailerA = array(
 	'name' => '[Alterconsos A]',
 	'host'=> 'compote.o2switch.net',
 	'port' => '465',
 	'username' => 'app@alterconsos.fr',
 	'password' => 'lesroses2015',
 	'secure' => 'ssl',
 	'auth' => true
);
 
$mailerB = array(
 	'name' => '[Alterconsos B]',
 	'host'=> 'auth.smtp.1and1.fr',
 	'port' => '587',
 	'username' => 'hayjp@alterconsos.sportes.fr',
 	'password' => 'lesroses2015',
 	'secure' => 'tls',
 	'auth' => true
);
 
$mailers = array(
	'A' => $mailerA,
 	'B' => $mailerB
);

$arg = $_POST;
 
$ok = isset($arg['cle']) && $arg['cle'] == "rosesetmarguerites";


if ($ok && isset($arg['mailer'])) {
 	$mailer = $mailers[$arg['mailer']];
 	if (isset($mailer))
 		$from = $mailer['username'];
 	else
 		$ok = false;
} else 
 	$ok = false;
 
if ($ok && isset($arg['to'])) {
 	$to = explode(",", $arg['to']);
 	if (!isset($to) || count($to) <= 0)
 		$ok = false;
} else 
 	$ok = false;
 
if ($ok && isset($arg['subject']))
 	$subject = $arg['subject'];
else
 	$ok = false;
 
if ($ok && isset($arg['text']))
 	$text = $arg['text'];
else
 	$ok = false;
 
date_default_timezone_set('Europe/Paris');
$date = date('Y-m-d H:i:s', time());
 
if ($ok) {
 	try {
		$mail = new PHPMailer;
 		$mail->CharSet = "UTF-8";
		
		//Tell PHPMailer to use SMTP : ne marche pas chez 1and1 ;
		$mail->isSMTP(); 
		// $mail->isMail(); // SMTP marche pas chez 1and1 ;	
		
		$mail->SMTPOptions = array(
			'ssl' => array(
				'verify_peer' => false,
				'verify_peer_name' => false,
				'allow_self_signed' => true
			)
		);

		//Enable SMTP debugging
		// 0 = off (for production use)
		// 1 = client messages
		// 2 = client and server messages
		if (isset($arg['debug']))
			$mail->SMTPDebug = $arg['debug'];

 		$mail->Host = $mailer['host']; // Specify main and backup server
		$mail->Port = $mailer['port'];
 		$mail->SMTPAuth = true; // Enable SMTP authentication
 		$mail->Username = $mailer['username']; // SMTP username
 		$mail->Password = $mailer['password']; // SMTP password
  		if (isset($mailer['secure']))
 			$mail->SMTPSecure = $mailer['secure']; // 'tls' Enable encryption, 'ssl' also accepted
  		
 		$mail->From = $mailer['username'];
 		$mail->FromName = $mailer['name'];
 		for($x = 0; $x < count($to); $x++){
 			$mail->addAddress($to[$x]); // Add a recipient
 		}
 		$mail->Subject = $subject;
 		$mail->isHTML(true); // Set email format to HTML
 		
 		$mail->Body = $text;
 		
 		if(!$mail->send()) {
 			$err = "KO : ".$mail->ErrorInfo;
 			// error_log($err);
 		} else
 			$err = "OK : ".$date;


 	} catch (Exception $e) {
 		$err = "KO : ".$e->getMessage();
 	}
}

if ($ok)
	echo $err;
else 
	echo $date;
  
?>