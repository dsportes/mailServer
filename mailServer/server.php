<?php
 require 'PHPMailerAutoload.php';
 
 $mailerA = array(
 	'name' => '[Alterconsos A]',
 	'host'=> 'mail.gandi.net',
 	'port' => '587',
 	'username' => 'l-hay@alterconsos.net',
 	'password' => '$pwd1',
 	'auth' => true
 );
 
 $mailerB = array(
 	'name' => '[Alterconsos B]',
 	'host'=> 'auth.smtp.1and1.fr',
 	'port' => '587',
 	'username' => 'hayjp@alterconsos.sportes.fr',
 	'password' => '$pwd3',
 	// 'secure' => 'tls' or 'ssl' ; // (fac)
 	'auth' => true
 );

 $mailerC = array(
 		'name' => '[Alterconsos App]',
 		'host'=> 'ssl0.ovh.net',
 		'port' => '465',
 		'username' => 'app@alterconsos.fr',
 		'password' => '$pwd2',
 		'auth' => true
 );
 
 $mailers = array(
 	'A' => $mailerC,
 	'B' => $mailerC
 );
 
 $test = array(
 	'cle' => "$pwd4",
 	'mailer' => "B",
 	'to' => "daniel@sportes.fr",
 	'subject' => "TEST - Mon sujet",
 	'text' => "TEST - Mon texte"
 );
 
 $arg = $_POST;
 // $arg = $test;
 
 $ok = isset($arg['cle']) && $arg['cle'] == "$pwd4";
 
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
 		// $mail->isSMTP(); // Set mailer to use SMTP : ne marche pas chez 1and1 ;
		$mail->isMail(); // marche chez 1and1 ;	
 		$mail->Host = $mailer['host']; // Specify main and backup server
 		$mail->SMTPAuth = true; // Enable SMTP authentication
 		$mail->Username = $mailer['username']; // SMTP username
 		$mail->Password = $mailer['password']; // SMTP password
 		if (isset($mailer['secure']))
 			$mail->SMTPSecure = $mailer['secure']; // 'tls' Enable encryption, 'ssl' also accepted
 		$mail->Port = $mailer['port'];
 		
 		$mail->From = $mailer['username'];
 		$mail->FromName = $mailer['name'];
 		for($x = 0; $x < count($to); $x++){
 			$mail->addAddress($to[$x]); // Add a recipient
 		}
 		$mail->isHTML(true); // Set email format to HTML
 		
 		$mail->Subject = $subject;
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
