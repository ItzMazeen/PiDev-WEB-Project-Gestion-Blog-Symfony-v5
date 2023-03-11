<?php

namespace App\Controller;

use App\Entity\Event;
use App\Form\EventType;
use App\Repository\EventRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;



#[Route('/teste')]
class EventFrontController extends AbstractController
{
    #[Route('/', name: 'app_event_show', methods: ['GET'])]
    public function index(EventRepository $eventRepository, Request $request, PaginatorInterface $paginator): Response
    {
       
        $role=null;
        if($this->getUser()){
           $role= $this->getUser()->getRoles()[0];
        }
       

    $event = $eventRepository->findAll();

        $event = $paginator->paginate(
            $event, /* query NOT result */
            $request->query->getInt('page', 1),
            2
        );
        return $this->render('event_show/index1.html.twig', [
            'events' => $event,
            'roleunique' => $role
        ]);
    }
}


