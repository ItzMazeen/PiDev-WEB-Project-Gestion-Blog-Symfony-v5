<?php

namespace App\Controller\stat;

use App\Entity\TimeSlot;
use App\Entity\User;
use App\Repository\TimeSlotRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\AppointmentRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class JsonPieDataController extends AbstractController
{
    #[Route('/piedata', name: 'app_json_pie_data')]
    public function index(AppointmentRepository $repApp , TimeSlotRepository $repTime ,EntityManagerInterface $em): JsonResponse
    {   
        // just to test if not working fetching data
        $countTimeslots = 99;
        $countAppointments = 1;
        
        if($this->getUser()){
            $user = $em->getRepository(User::class)->findOneBy(['email' => $this->getUser()->getUserIdentifier()]);
            $doctorId=$user->getId();
            
            //----------count of total appointments of one doctor 
            $countAppointments = $repApp->count([ 'doctor'=> $doctorId]);
            
            //--------------count all Timeslot for a certain doctor -----------------
            $qb = $em->createQueryBuilder();
            $qb->select('t')
            ->from(TimeSlot::class, 't')
            ->join('t.calandarDay', 'cd')
            ->where('cd.doctor = :doctor')
            ->andWhere('t.status = :status')
            ->andWhere('t.reason = :reason')
            ->setParameter('doctor', $doctorId)
            ->setParameter('status', 'available')
            ->setParameter('reason', 'unbooked');
            $query = $qb->getQuery();
            $countTimeslots = count($query->getResult()) ;
        }
                
        return new JsonResponse([
            "labelmsg" => "appointments/timeSlots",
            "totalAvailableTimeSlots" => $countTimeslots,
            "totalAppointment" => $countAppointments,
        ]);
    }
}




