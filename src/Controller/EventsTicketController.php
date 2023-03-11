<?php

namespace App\Controller;

use App\Entity\EventTicket;
use App\Form\EventTicketType;
use App\Repository\EventTicketRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Dompdf\Dompdf;
use Dompdf\Options;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\Notifier\Notification\Notification;
use Symfony\Component\Notifier\NotifierInterface;



#[Route('/events/ticket')]
class EventsTicketController extends AbstractController
{
    #[Route('/', name: 'app_events_ticket_index', methods: ['GET'])]
    public function index(EventTicketRepository $eventTicketRepository, Request $request, PaginatorInterface $paginator): Response
    {
        $role=null;
        if($this->getUser()){
           $role= $this->getUser()->getRoles()[0];
        }
        

        $event_tickets = $eventTicketRepository->findAll();

        $event_tickets = $paginator->paginate(
            $event_tickets, /* query NOT result */
            $request->query->getInt('page', 1),
            3   
        );



        return $this->render('events_ticket/index.html.twig', [
            'event_tickets' => $event_tickets,
            'roleunique' => $role
        ]);
    }

    #[Route('/new', name: 'app_events_ticket_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EventTicketRepository $eventTicketRepository, NotifierInterface $notifier): Response
    {

        $role=null;
        if($this->getUser()){
           $role= $this->getUser()->getRoles()[0];
        }
        

        $eventTicket = new EventTicket();
        $form = $this->createForm(EventTicketType::class, $eventTicket);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $eventTicketRepository->save($eventTicket, true);

            $notifier->send(new Notification('Le ticket a été réservé avec succès', ['browser']));
            

            return $this->redirectToRoute('app_events_ticket_index',[],Response::HTTP_SEE_OTHER); }

        return $this->renderForm('events_ticket/new.html.twig', [
            'event_ticket' => $eventTicket,
            'form' => $form,
            'roleunique' => $role
        ]);
    }

    #[Route('/{id}', name: 'app_events_ticket_show', methods: ['GET'])]
    public function show(EventTicket $eventTicket): Response
    {

        $role=null;
        if($this->getUser()){
           $role= $this->getUser()->getRoles()[0];
        }
       

        return $this->render('events_ticket/show.html.twig', [
            'event_ticket' => $eventTicket,
            'roleunique' => $role
        ]);
    }

    #[Route('/{id}/edit', name: 'app_events_ticket_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, EventTicket $eventTicket, EventTicketRepository $eventTicketRepository): Response
    {
        $role=null;
        if($this->getUser()){
           $role= $this->getUser()->getRoles()[0];
        }
        

        $form = $this->createForm(EventTicketType::class, $eventTicket);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $eventTicketRepository->save($eventTicket, true);

            return $this->redirectToRoute('app_events_ticket_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('events_ticket/edit.html.twig', [
            'event_ticket' => $eventTicket,
            'form' => $form,
            'roleunique' => $role
        ]);
    }

    #[Route('/{id}', name: 'app_events_ticket_delete', methods: ['POST'])]
    public function delete(Request $request, EventTicket $eventTicket, EventTicketRepository $eventTicketRepository): Response
    {
        $role=null;
        if($this->getUser()){
           $role= $this->getUser()->getRoles()[0];
        }
        

        if ($this->isCsrfTokenValid('delete'.$eventTicket->getId(), $request->request->get('_token'))) {
            $eventTicketRepository->remove($eventTicket, true);
        }

        return $this->redirectToRoute('app_events_ticket_index', ['roleunique' => $role], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{id}/donnes', name: 'app_events_ticket_donne', methods: ['GET'])]
    public function view( EventTicket $eventTicket, EventTicketRepository $eventTicketRepository): Response
    {

        $role=null;
        if($this->getUser()){
           $role= $this->getUser()->getRoles()[0];
        }
        

        $event_tickets = $eventTicketRepository->findAll();
       

        return $this->render('events_ticket/donne.html.twig', [
            'event_ticket' => $eventTicket,
            'roleunique' => $role
        ]);

    }

   

   
    #[Route('/{id}/donnes/pdf', name: 'app_events_ticket_pdf', methods: ['GET'])]
    public function down(EventTicket $eventTicket, EventTicketRepository $eventTicketRepository): Response
    {
        $role=null;
        if($this->getUser()){
           $role= $this->getUser()->getRoles()[0];
           
           // On définit les options du PDF
           $pdfOptions = new Options();
           
           // Police par défaut
           $pdfOptions->set('defaultFont', 'Arial');
           $pdfOptions->setIsRemoteEnabled(true);
           
           // On instancie Dompdf
           $dompdf = new Dompdf($pdfOptions);
           
           
           
        $event_tickets = $eventTicketRepository->findAll();
        $context = stream_context_create([
            'ssl' => [
                'verify_peer' => FALSE,
                'verify_peer_name' => FALSE,
                'allow_self_signed' => TRUE
                ]
            ]);
            $dompdf->setHttpContext($context);
            
            // On génère le html
            $html = $this->renderView('events_ticket/pdf_ticket.html.twig', [
                'event_ticket' => $eventTicket,
                
            ]);
            
            $dompdf->loadHtml($html);
            $dompdf->setPaper('A4', 'portrait');
            $dompdf->render();
            
            // On génère un nom de fichier
            $fichier = 'ticket'.'.pdf';
            
            // On envoie le PDF au navigateur
            $dompdf->stream($fichier, [
                'Attachment' => true
            ]);
            
            
            return new Response();
        // }
        // else
        // return  new Response("ERROR NOT ALLOWED ");


        
    }


   

    }


}





 

   


