<?php

namespace App\Controller\calandar_rdv;

use App\Entity\Appointment;
use App\Entity\TimeSlot;
use App\Form\AppointmentType;
use App\Repository\AppointmentRepository;
use App\Repository\TimeSlotRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use App\Service\EmailCustom;

#[Route('/appointment/user')]
class AppointmentController extends AbstractController
{
    #[Route('/list', name: 'app_appointment_index', methods: ['GET'])]
    public function index(AppointmentRepository $appointmentRepository, UserRepository $repoUser): Response
    {

        $theUserRole=null;
        if($this->getUser()){
           $theUserRole= $this->getUser()->getRoles()[0];
        }
        


        $email = $this->getUser()->getUserIdentifier();
        $user = $repoUser->findOneBy(['email' => $email]);
        if (count($user->getRoles()) === 1 && $user->getRoles()[0] === "ROLE_USER"){
            $appointments = $appointmentRepository->findby([
                'patient' => $user->getId() ,
                'date' => new \DateTime('today')
            ]);

            $appointments = $appointmentRepository->createQueryBuilder('a')
            ->where('a.patient = :patient')
            ->andWhere('a.date >= :date')
            ->setParameter('patient', $user->getId())
            ->setParameter('date', new \DateTime('today'))
            ->orderBy('a.date','ASC')
            ->getQuery()
            ->getResult();

            return $this->render('appointment/list_user_side.html.twig', [
                'appointments' => $appointments,
                'roleunique' => $theUserRole
            ]);
        }



        if ((in_array('ROLE_DOCTOR', $user->getRoles(), true) || in_array('ROLE_Nurse', $user->getRoles(), true))){
            $appointments = $appointmentRepository->findby([
                'doctor' => $user->getId(),
                'date' => new \DateTime('-6 months')
            ],[
                'date'=> 'DESC'
            ] );

            $appointments = $appointmentRepository->createQueryBuilder('a')
            ->where('a.doctor = :doctor')
            ->andWhere('a.date >= :date')
            ->setParameter('doctor', $user->getId())
            ->setParameter('date', new \DateTime('-6 months'))
            ->orderBy('a.date','DESC')
            ->getQuery()
            ->getResult();
            
            return $this->render('appointment/list_doctor_side.html.twig', [
                'appointments' => $appointments,
               
                'roleunique' => $theUserRole
            ]);
        }
        
        return $this->redirectToRoute('error_404', [
            'errormsg' => " not authorised " ,
            'code' => 404
        ]);

    }

    //only user can do this 
    #[Route('/new/{id}', name: 'app_appointment_new', methods: ['GET', 'POST'])]
    public function new(TimeSlot $id, Request $request, AppointmentRepository $appointmentRepository, UserRepository $repoUser, TimeSlotRepository $repTimeSlot): Response
    {

        $theUserRole=null;
        if($this->getUser()){
           $theUserRole= $this->getUser()->getRoles()[0];
        }

        // relationship
        $email = $this->getUser()->getUserIdentifier();
        $user = $repoUser->findOneBy(['email' => $email]);
        $calandar = $id->getCalandarDay();
        $doctor = $calandar->getDoctor();

        $appointment = new Appointment();
        $form = $this->createForm(AppointmentType::class, $appointment, [
            'user_id' => $user->getId(),
            'doctor_id' => $doctor->getId(),
            'time_slot_id' => $id->getId(),
            'date_app' => $calandar->getDate(),
            'time_app' => $id->getStartTime(),
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // appointment filling up
            $appointment->setDate($calandar->getDate());
            $appointment->setHour($id->getStartTime());
            $appointment->setDoctor($doctor);
            $appointment->setPatient($user);
            $appointment->setTimeSlot($id);
            $appointmentRepository->save($appointment, true);
            // updating slot status
            $id->setStatus('not-available');
            $id->setReason('booked');
            $repTimeSlot->save($id, true);

            return $this->redirectToRoute('app_calandar_access_day_index', [
                'id' => $doctor->getId(),
                'roleunique' => $theUserRole
            ], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('appointment/new.html.twig', [
            'appointment' => $appointment,
            'form' => $form,
            'id' => $doctor->getId(),
           
            'roleunique' => $theUserRole
        ]);
    }

    #[Route('/{id}', name: 'app_appointment_show', methods: ['GET'])]
    public function show(Appointment $appointment): Response
    {
        $theUserRole=null;
        if($this->getUser()){
           $theUserRole= $this->getUser()->getRoles()[0];
        }
        return $this->render('appointment/show.html.twig', [
            'appointment' => $appointment,
        
            'roleunique' => $theUserRole
        ]);
    }

    #[Route('/{id}/edit', name: 'app_appointment_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Appointment $appointment, AppointmentRepository $appointmentRepository): Response
    {

        $theUserRole=null;
        if($this->getUser()){
           $theUserRole= $this->getUser()->getRoles()[0];
        }

        $form = $this->createForm(AppointmentType::class, $appointment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $appointmentRepository->save($appointment, true);

            return $this->redirectToRoute('app_appointment_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('appointment/edit.html.twig', [
            'appointment' => $appointment,
            'form' => $form,
           
            'roleunique' => $theUserRole
        ]);
    }


    
    #[Route('/{id}', name: 'app_appointment_delete', methods: ['POST'])]
    public function delete(Request $request, Appointment $appointment, AppointmentRepository $appointmentRepository, TimeSlotRepository $repoTImeSlot): Response
    {
        $theUserRole=null;
        if($this->getUser()){
           $theUserRole= $this->getUser()->getRoles()[0];
        }

        if ($this->isCsrfTokenValid('delete' . $appointment->getId(), $request->request->get('_token'))) {
             //getting data of email directly from timeSlot
             $useremail=$appointment->getPatient()->getEmail();
             // creating and setting the email body and adding the patients
             $customEmail=new EmailCustom();
             $htmlBody= $this->renderView('sending_email/email_template.html.twig', [
                         'doctor' => $appointment->getDoctor()->getLastName(),
                         'patientName' => $appointment->getPatient()->getLastName()." ".$appointment->getPatient()->getFirstName(),
                         'message' => "your appointment has being cancled .",
                         'roleunique' => $theUserRole
                      ]);;
            // regular array not associative btw
             $customEmail->addRecipients([ $useremail ] );
             //sending mails a single patient no need to inform the doctor
             $msg=null;
             if($customEmail->sendEmail($htmlBody))
                 $msg="email sent successfully";
             else 
                 $msg ="FAILED  -______-  ".$customEmail->mail->ErrorInfo; # code... 
             $this->addFlash("notification",$msg);// do something with it i don't have time now
             
            $timeSlot = $appointment->getTimeSlot();
            $appointmentRepository->remove($appointment, true);

            // updating the time slot for future appointment and exact statistics
            $timeSlot->setStatus('available');
            $timeSlot->setReason('unbooked');
            $timeSlot->setNote(null);
            $repoTImeSlot->save($timeSlot, true);
        }

        return $this->redirectToRoute('app_appointment_index', [
           
            'roleunique' => $theUserRole
        ], Response::HTTP_SEE_OTHER);
    }
}
