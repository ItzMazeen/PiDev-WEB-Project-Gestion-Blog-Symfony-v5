<?php

namespace App\Service;

use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\PHPMailer;


class EmailCustom
{
    public ?PHPMailer $mail ;

    public function __construct( $userName = '2a21group5@gmail.com',$password = 'mxuzbqtvsklxvkxj' )
    {
        $this->mail = new PHPMailer(true);
        //Server settings
        $this->mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
        $this->mail->isSMTP();                                            //Send using SMTP
        $this->mail->Host       = 'smtp.gmail.com';        //Set the SMTP server to send through
        $this->mail->SMTPDebug  = 0; // debug choice                          
        $this->mail->SMTPAuth   = true;                                   //Enable SMTP authentication
        $this->mail->Username   = $userName;                     //SMTP username
        $this->mail->Password   = $password ;                               //SMTP password
        $this->mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
        $this->mail->Port       = 465; 

    
    }

    public function addRecipients(array $recipients ,$fromEmail="yourDoctor@gmail.com",$fromName="yourDoctor" ){
        
        $this->mail->setFrom($fromEmail, $fromName); //Name is optional
        $this->mail->addReplyTo('no_reply@gmail.com', 'no-reply');

        //Recipients
        foreach ($recipients as $recipient) {
            $this->mail->addAddress($recipient);
        }
    }

    public function sendEmail($bodyHtml,$subject='notification',array $recipient=null ,array $attachments=null ,$fromEmail="yourDoctor@gmail.com",$fromName="yourDoctor"){
        try {
           
            //Recipients if passed directly here
            if($recipient){
                $this->addRecipients($recipient,$fromEmail,$fromName);
            }

            //attachments if any 
            if($attachments) 
                foreach ($attachments as $attachment) {
                    $this->mail->addAttachment($attachment );
                    // $this->mail->addAttachment('/tmp/image.jpg', 'new.jpg');    //Optional name
                }

            //Content
            $this->mail->isHTML(true);                                  //Set email format to HTML
            $this->mail->Subject = $subject ;
            $this->mail->Body    = $bodyHtml;
            $this->mail->AltBody = 'unfortunatly i am not going to bother with this get a new phone or laptop';

            $this->mail->send();
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }



    // this is only as reference do not call this function
    public function index()
    {
        //this is the default implementation for testing only 
        
        $mail = new PHPMailer(true);
        $userName = '2a21group5@gmail.com';
        $password = 'mxuzbqtvsklxvkxj';
        //Server settings
        $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
        $mail->isSMTP();                                            //Send using SMTP
        $mail->Host       = 'smtp.gmail.com';        //Set the SMTP server to send through
        $mail->SMTPDebug = 2; // debug choice                          
        $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
        $mail->Username   = $userName;                     //SMTP username
        $mail->Password   = $password ;                               //SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
        $mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`
        
        //Recipients
            $mail->setFrom('2a21group5@gmail.com', 'sender');
            $mail->addAddress('rabbehs@gmail.com', 'Joe User');     //Add a recipient
            $mail->addAddress('rabbehseif@gmail.com');               //Name is optional
            $mail->addReplyTo('no_reply@gmail.com', 'no reply');
            // $mail->addCC('cc@example.com');
            // $mail->addBCC('bcc@example.com');
            
            try {
            // //Attachments
            // $mail->addAttachment('/var/tmp/file.tar.gz');         //Add attachments
            // $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    //Optional name

            //Content
            $mail->isHTML(true);                                  //Set email format to HTML
            $mail->Subject = 'Here is the subject';
            // $mail->Body    = $this->renderView('sending_email/email_template.html.twig', [
            //     'doctor' => "docIam",
            //     'patientName' => "thePatientName",
            //     'message' => "your appointment has being cancled"
            // ]);
            $mail->Body = "i am the content of your email";
            $mail->AltBody = 'infortunatly i am not going to bother with this get a new phone or laptop';

            $mail->send();
         
            echo 'Message has been sent';
        } catch (\Exception $e) {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}"; 
        }
    }
}
