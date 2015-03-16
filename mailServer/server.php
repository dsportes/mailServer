<?php
 require 'PHPMailerAutoload.php';
 
 $mailerA = array(
 	'name' => '[Alterconsos A]',
 	'host'=> 'mail.gandi.net',
 	'port' => '587',
 	'username' => 'l-hay@alterconsos.net',
 	'password' => 'lesroses',
 	'auth' => true
 );
 
 $mailerB = array(
 	'name' => '[Alterconsos B]',
 	'host'=> 'auth.smtp.1and1.fr',
 	'port' => '587',
 	'username' => 'hayjp@alterconsos.sportes.fr',
 	'password' => 'lesmarguerites',
 	// 'secure' => 'tls' or 'ssl' ; // (fac)
 	'auth' => true
 );
 
 $mailers = array(
 	'A' => $mailerA,
 	'B' => $mailerB
 );
 $ok = isset($_POST['cle']) && $_POST['cle'] == "rosesetmarguerites";
 
 if ($ok && isset($_POST['mailer'])) {
 	$mailer = $mailers[$_POST['mailer']];
 	if (isset($mailer))
 		$from = $mailer['username'];
 	else
 		$ok = false;
 } else 
 	$ok = false;
 
 if ($ok && isset($_POST['to'])) {
 	$to = explode(",", $_POST['to']);
 	if (!isset($to) || count($to) <= 0)
 		$ok = false;
 } else 
 	$ok = false;
 
 if ($ok && isset($_POST['subject']))
 	$subject = $_POST['subject'];
 else
 	$ok = false;
 
 if ($ok && isset($_POST['text']))
 	$text = $_POST['text'];
 else
 	$ok = false;
 
 date_default_timezone_set('Europe/Paris');
 $date = date('Y-m-d H:i:s', time());
 
 if ($ok) {
 	try {
 		$mail = new PHPMailer;
 		$mail->CharSet = "UTF-8";
 		// $mail->isSMTP(); // Set mailer to use SMTP
		$mail->isMail(); // isSMTP() ne marche pas chez 1and1 ; Set mailer to use SMTP	
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
 		
 		if(!$mail->send())
 			$err = "KO : ".$mail->ErrorInfo;
 		else
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
