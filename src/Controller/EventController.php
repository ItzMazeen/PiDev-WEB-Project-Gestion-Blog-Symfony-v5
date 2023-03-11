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

#[Route('/event')]
class EventController extends AbstractController
{
    #[Route('/', name: 'app_event_index', methods: ['GET'])]
    public function index(EventRepository $eventRepository, Request $request, PaginatorInterface $paginator): Response
    {
        $role=null;
        if($this->getUser()){
           $role= $this->getUser()->getRoles()[0];
        }
        // else{
        //     return $this->redirectToRoute('app_wasta');
        // }
        
        
        // if( $role === "ROLE_ADMIN")
        // {   


            $event = $eventRepository->findAll();
            
            $event = $paginator->paginate(
                $event, /* query NOT result */
                $request->query->getInt('page', 1),
                2
            );
            return $this->render('event/index.html.twig', [
                'events' => $event,
            ]);


        // }
        // else{
        //     return $this->redirectToRoute('app_front_home');
        // }

    }

    #[Route('/new', name: 'app_event_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EventRepository $eventRepository): Response
    {


        $role=null;
        if($this->getUser()){
           $role= $this->getUser()->getRoles()[0];
        }
        // else{
        //     return $this->redirectToRoute('app_front_home');
        // }
        
        
        // if( $role === "ROLE_ADMIN")
        // {   

            

        $event = new Event();
        $form = $this->createForm(EventType::class, $event);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $eventRepository->save($event, true);
            
            return $this->redirectToRoute('app_event_index', [], Response::HTTP_SEE_OTHER);
        }
        
        return $this->renderForm('event/new.html.twig', [
            'event' => $event,
            'form' => $form,
        ]);

    // }
    // else{
    //     return $this->redirectToRoute('app_wasta');
    // }


    }


    

    #[Route('/{id}', name: 'app_event_show', methods: ['GET'])]
    public function show(Event $event): Response
    {
        $role=null;
        if($this->getUser()){
           $role= $this->getUser()->getRoles()[0];
        }
        // else{
        //     return $this->redirectToRoute('app_wasta');
        // }
        
        
        // if( $role === "ROLE_ADMIN")
        // {   

            return $this->render('event/show.html.twig', [
                'event' => $event,
            ]);


        // }
        // else{
        //     return $this->redirectToRoute('app_wasta');
        // } 


    }

    #[Route('/{id}/edit', name: 'app_event_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Event $event, EventRepository $eventRepository): Response
    {


        $role=null;
        if($this->getUser()){
           $role= $this->getUser()->getRoles()[0];
        }
        // else{
        //     return $this->redirectToRoute('app_wasta');
        // }
        
        
        // if( $role === "ROLE_ADMIN")
        // {   

            $form = $this->createForm(EventType::class, $event);
            $form->handleRequest($request);
    
            if ($form->isSubmitted() && $form->isValid()) {
                $eventRepository->save($event, true);
    
                return $this->redirectToRoute('app_event_index', [], Response::HTTP_SEE_OTHER);
            }
    
            return $this->renderForm('event/edit.html.twig', [
                'event' => $event,
                'form' => $form,
            ]);



        // }
        // else{
        //     return $this->redirectToRoute('app_front_home');
        // } 



    }

    #[Route('/{id}', name: 'app_event_delete', methods: ['POST'])]
    public function delete(Request $request, Event $event, EventRepository $eventRepository): Response
    {
        $role=null;
        if($this->getUser()){
           $role= $this->getUser()->getRoles()[0];
        }
        // else{
        //     return $this->redirectToRoute('app_wasta');
        // }
        
        
        // if( $role === "ROLE_ADMIN")
        // {   


            if ($this->isCsrfTokenValid('delete'.$event->getId(), $request->request->get('_token'))) {
                $eventRepository->remove($event, true);
            }
    
            return $this->redirectToRoute('app_event_index', [], Response::HTTP_SEE_OTHER);


        // }
        // else{
        //     return $this->redirectToRoute('app_wasta');
        // } 



    }



    #[Route('/{id}/donne', name: 'app_event_donne', methods: ['GET'])]
    public function view(Event $event, EventRepository $eventRepository): Response
    {


        $role=null;
        if($this->getUser()){
           $role= $this->getUser()->getRoles()[0];
        }
        // else{
        //     return $this->redirectToRoute('app_wasta');
        // }
        
        
        // if( $role === "ROLE_ADMIN")
        // {   


            $events = $eventRepository->findAll();
            
            return $this->render('event/donne_event.html.twig', [
                'event' => $event, 
            ]);

        // }
        // else{
        //     return $this->redirectToRoute('app_wasta');
        // } 



    }



}
