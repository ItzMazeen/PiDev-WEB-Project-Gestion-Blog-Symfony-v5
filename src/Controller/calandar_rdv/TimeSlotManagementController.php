<?php

namespace App\Controller\calandar_rdv;



use App\Entity\TimeSlot;
use App\Entity\CalandarDay;
use App\Repository\TimeSlotRepository;
use App\Repository\CalandarDayRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class TimeSlotManagementController extends AbstractController
{
    #[Route('/time/slot/management', name: 'app_time_slot_management')]
    public function index(): Response
    {
        $theUserRole=null;
        if($this->getUser()){
           $theUserRole= $this->getUser()->getRoles()[0];
        }
        

        return $this->render('time_slot_management/index.html.twig', [

            'roleunique' => $theUserRole
        ]);
    }


    #[Route('/time/slot/management/delete/old/slots', name: 'app_time_slot_management_delete_slot')]
    public function delete_old_slots(TimeSlotRepository $timeSlotRepo): Response
    {
        $theUserRole=null;
        if($this->getUser()){
           $theUserRole= $this->getUser()->getRoles()[0];
        }


        $qb = $timeSlotRepo->createQueryBuilder('ts')
            ->delete()
            ->where('ts.status = :status')
            ->andWhere('cd.date < :date')
            ->setParameter('status', 'available')
            ->setParameter('date', new \DateTime());

        $qb->join('ts.calandarDay', 'cd')
            ->addSelect('cd');

        $qb->getQuery()->execute();

        $this->addFlash('success', "all old slots with status available has been deleted ");

        return $this->render('time_slot_management/index.html.twig', [
          
            'roleunique' => $theUserRole
        ]);
    }


    #[Route('/time/slot/management/delete/old/calandars', name: 'app_time_slot_management_delete_calandar')]
    public function delete_old_calandar(CalandarDayRepository $repoCalandar): Response
    {

        $theUserRole=null;
        if($this->getUser()){
           $theUserRole= $this->getUser()->getRoles()[0];
        }

        $date = new \DateTimeImmutable('-3 months');

        $queryBuilder = $repoCalandar->createQueryBuilder('c');
        $queryBuilder->delete(CalandarDay::class, 'c')
            ->where('c.date < :date')
            ->setParameter('date', $date);

        $queryBuilder->getQuery()->execute();

        $this->addFlash('success', "all calandarDays older than 3 months are deleted");

        return $this->render('time_slot_management/index.html.twig', [

    
            'roleunique' => $theUserRole
        ]);
    }
}
