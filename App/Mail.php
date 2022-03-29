<?php

namespace App;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
use App\Config;

/*require 'C:\xampp\htdocs\php-mvc\vendor\phpmailer\src\Exception.php';
require 'C:\xampp\htdocs\php-mvc\vendor\phpmailer\src\PHPMailer.php';
require 'C:\xampp\htdocs\php-mvc\vendor\phpmailer\src\SMTP.php';*/

require '../vendor/phpmailer/src/Exception.php';
require '../vendor/phpmailer/src/PHPMailer.php';
require '../vendor/phpmailer/src/SMTP.php';



	/**
	 * Mail
	 * 
	 * PHP version 7.0
	 */
	class Mail
	{
		/**
		 * Send a message
		 *
		 * @param string $to Recipient
		 * @param string $subject Subject
		 * @param string $text Text-only content of the message
		 * @param string $html HTML content of the message
		 *
		 * @return mixed
		 */
		public static function send($to, $subject, $text, $html)
		{
			$mail = new PHPMailer(true);
			$mail->CharSet = "UTF-8";
			
			$me = 'chomar.testphp@gmail.com';
			try {
				//Server settings
				//$mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
				$mail->isSMTP();                                            //Send using SMTP
				$mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
				$mail->SMTPAuth   = true;                                   //Enable SMTP authentication
				$mail->Username   = $me;                     //SMTP username
				$mail->Password   = 'TestujemyPHP';                               //SMTP password
				$mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
				$mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`
	
				//Recipients
				$mail->setFrom($me, 'PHP Test');
				$mail->addAddress($to, 'Martyna Cho');     //Add a recipient
				//$mail->addAddress('ellen@example.com');               //Name is optional
				$mail->addReplyTo($me, 'Information');
				//$mail->addCC('cc@example.com');
				//$mail->addBCC('bcc@example.com');

				//Attachments
				//$mail->addAttachment('/var/tmp/file.tar.gz');         //Add attachments
				//$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    //Optional name

				//Content
				$mail->isHTML(true);                                  //Set email format to HTML
				$mail->Subject = $subject;
				$mail->Body    = $html;
				$mail->AltBody = $text;

				$mail->send();
			} catch (Exception $e) {
				echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
			}
		}
	}