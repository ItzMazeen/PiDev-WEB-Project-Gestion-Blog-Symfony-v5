<?php

namespace App\Controller\email;

use App\Repository\AppointmentRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use App\Service\EmailCustom;
use App\Service\PatientEmail;

class SendingEmailController extends AbstractController
{
    #[Route('/sending/email/{email}/{date}/{doctorName}/{doctorEmail}', name: 'app_sending_email', 
    defaults:['email'=>null ,'date'=>null,'doctorName'=>null , 'doctorEmail'=>""] ) ]
    public function index($email=null, $date=null, $doctorName=null,$doctorEmail="", AppointmentRepository $appRepo): Response
    {
        // this is only a testing envirement
        $usersData[]=$email;
        

        // $doctorid=7;  // for old testing purpose
        $calandarDayDate=$date;
        
        if($this->getUser())
        if($this->getUser()->getUserIdentifier()==$doctorEmail)
        {

            
            //getting data of email ready must have as data doctor id and an exact date 
            $PatientEmail=new PatientEmail();
            // this was for test when delete a calandar day ignore it 
            // $usersData=$PatientEmail->patientEmailsbookedInThisDay($doctorid,$calandarDayDate,$appRepo);
            
            // can add email and password to constructor directly if you chose to
            $customEmail=new EmailCustom();

            $htmlBody= $this->renderView('sending_email/email_template.html.twig', [
                'doctor' => $doctorName,
                'patientName' => "Patient",
                'message' => "your have an appointment with us on ".$date." , we
                 would love that you confirm your attendance by calling or emailing .
                 our email email : ".$doctorEmail." ."
                ]);
            
                // $customEmail->addRecipients(["rabbehseif@gmail.com", "rabbehs@gmail.com"]);
                $customEmail->addRecipients($usersData,$doctorEmail,$doctorName);
                
                if($customEmail->sendEmail($htmlBody))
                $msg="email sent successfully";
                else 
                $msg ="FAILED  -______-  ".$customEmail->mail->ErrorInfo; # code...
                
                return $this->render('sending_email/index.html.twig', [
                    'message' => $msg,
                    
                ]);
                
            }
            else{
                return $this->redirectToRoute('error_404', [
                    'errormsg' => "      Not Found/not authorized     ",
                    'code' => 404
                ]);
                
            }
                
                
    }
}
