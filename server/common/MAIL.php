<?php

include_once __DIR__ . "/phpmailer/class.phpmailer.php";
include_once "CONFIG.php";
include_once "UTILS.php";

class MAIL {

    public static function SendMail($mail_host, $mail_username, $mail_password, $to, $subject, $body, $from = null) {
        $mail = new PHPMailer;

        //Disable SMTP debugging. 
        $mail->SMTPDebug = false;

        //Set PHPMailer to use SMTP.
        $mail->isSMTP();

        //Set SMTP host name                          
        $mail->Host = $mail_host;

        //Set this to true if SMTP host requires authentication to send email
        $mail->SMTPAuth = true;

        //Provide username and password     
        $mail->Username = $mail_username;
        $mail->Password = $mail_password;

        //SMTP requires TLS encryption then set it
        $mail->SMTPSecure = "tls";

        //Set TCP port to connect to 
        $mail->Port = 587;

        if (UTILS::wppIsNull($from)) {
            $mail->From = $mail->Username;
        } else {
            $mail->From = $from;
        }
        //$mail->FromName = "";

        $mail->addAddress($to);

        //$mail->isHTML(true);

        $mail->Subject = $subject;
        $mail->Body = $body;

        if (!$mail->send()) {
            error_log("Mailer Error: " . $mail->ErrorInfo);
        }
    }

}
