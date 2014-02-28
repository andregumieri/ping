<?php
	function gmail($to, $subject, $message) {
		$mail = new PHPMailer();
		$mail->IsSMTP();
		$mail->CharSet = 'iso-8859-1';

		$mail->Host       = "smtp.gmail.com"; // SMTP server example
		$mail->SMTPDebug  = 0;                     // enables SMTP debug information (for testing)
		$mail->SMTPAuth   = true;                  // enable SMTP authentication
		$mail->SMTPSecure = "tls";
		$mail->Port       = 587;                    // set the SMTP port for the GMAIL server
		$mail->Username   = GMAIL_USER; // SMTP account username example
		$mail->Password   = GMAIL_PASS;        // SMTP account password example

		$mail->addAddress($to);

		$mail->From = GMAIL_USER;
		$mail->FromName = utf8_decode('Ping');

		$mail->isHTML(true);
		$mail->Body = $message;
		$mail->Subject = $subject;

		if(!$mail->send()) {
			return false;
		}

		return true;
	}
?>
