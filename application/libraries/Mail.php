<?php
/**
 * This example shows settings to use when sending via Google's Gmail servers.
 * The IMAP section shows how to save this message to the 'Sent Mail' folder using IMAP commands.
 */


class Mail{
    // function register($to=null,$toTitle=null,$subject=null,$html=null,$txtBody=null){
    //     date_default_timezone_set('Etc/UTC');

    //     require_once 'mail/PHPMailerAutoload.php';

    //     $mail = new PHPMailer;
    //     $mail->isSMTP();
    //     $mail->SMTPDebug = 1;
    //     $mail->Debugoutput = 'html';
    //     $mail->Host = 'tpigaerp.com';
    //     $mail->Port = 587;
    //     $mail->SMTPSecure = 'tls';

    //     $mail->SMTPAuth = true;
    //     $mail->Username = "admin.tofap@tpigaerp.com";
    //     $mail->Password = "tofap123456789";
    //     $mail->setFrom('admin.tofap@tpigaerp.com', 'PT. TUGU PRATAMA INDONESIA');
    //     $mail->addAddress($to, $toTitle);
    //     $mail->Subject = $subject;

        
    //         $mail->msgHTML($html);
    //     $mail->AltBody = $txtBody;

    //     if (!$mail->send()) {
    //         return "Mailer Error: " . $mail->ErrorInfo;
    //     } else {
    //         return '1';
    //     }
    // }    

    function register($to=null,$toTitle=null,$subject=null,$html=null,$txtBody=null){
        date_default_timezone_set('Etc/UTC');

        require_once 'mail/PHPMailerAutoload.php';

        $mail = new PHPMailer;
        $mail->isSMTP();
        $mail->SMTPDebug = 0;
        $mail->Debugoutput = 'html';
        $mail->Host = 'smtp.gmail.com';
        $mail->Port = 587;
        $mail->SMTPSecure = 'tls';

        $mail->SMTPAuth = true;
        $mail->Username = "tpigaerp@gmail.com";
        $mail->Password = "afedigi4321";
        $mail->setFrom('tpigaerp@gmail.com', 'ADMIN TOFAP');
        $mail->addAddress($to, $toTitle);
        $mail->Subject = $subject;

        
            $mail->msgHTML($html);
        $mail->AltBody = $txtBody;

        if (!$mail->send()) {
            return "Mailer Error: " . $mail->ErrorInfo;
        } else {
            return '1';
        }
    }

    function forgotPassword($to=null,$toTitle=null,$subject=null,$html=null,$txtBody=null){
        date_default_timezone_set('Etc/UTC');

        require_once 'mail/PHPMailerAutoload.php';

        $mail = new PHPMailer;
        $mail->isSMTP();
        $mail->SMTPDebug = 0;
        $mail->Debugoutput = 'html';
        $mail->Host = 'smtp.gmail.com';
        $mail->Port = 587;
        $mail->SMTPSecure = 'tls';

        $mail->SMTPAuth = true;
        $mail->Username = "tpigaerp@gmail.com";
        $mail->Password = "afedigi4321";
        $mail->setFrom('tpigaerp@gmail.com', 'ADMIN TOFAP');
        $mail->addAddress($to, $toTitle);
        $mail->Subject = $subject;

        
            $mail->msgHTML($html);//file_get_contents('contents.html'), dirname(__FILE__));
        $mail->AltBody = $txtBody;
        //$mail->addAttachment('images/phpmailer_mini.png');

        if (!$mail->send()) {
            return "Mailer Error: " . $mail->ErrorInfo;
        } else {
            return '1';
        }
    }
}

