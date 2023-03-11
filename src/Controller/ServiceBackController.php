<?php

namespace App\Controller;

use App\Entity\Service;
use App\Form\ServiceType;
use App\Repository\ServiceRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/service/back')]
class ServiceBackController extends AbstractController
{
    #[Route('/', name: 'app_service_back')]
    public function index(Request $request, ServiceRepository $serviceRepository, PaginatorInterface $paginator): Response
    {
        $role=null;
        if($this->getUser()){
           $role= $this->getUser()->getRoles()[0];

            if( $this->getUser()->getRoles()[0] === "ROLE_USER" )
                return new Response('ERROR NOT AUTHORIZED' );

            // if( $this->getUser()->getRoles()[0] === "ROLE_ADMIN" )
            //     return new Response('ERROR NOT AUTHORIZED' );


        }
        else
            return new Response('ERROR NOT AUTHORIZED' );

        
        
        $services = $serviceRepository->findAll();
        //logic paginator
        $services = $paginator->paginate(
            $services,
            $request->query->getInt('page',1),
            4
        );
        return $this->render('service_back/index.html.twig', [
            'services' => $services,
            'roleunique' => $role
        ]);
    }

    #[Route('/new', name: 'app_service_back_new', methods: ['GET', 'POST'])]
    public function new(Request $request, ServiceRepository $serviceRepository): Response
    {
        $role=null;
        // if($this->getUser()){
        //    $role= $this->getUser()->getRoles()[0];
        // }
        $service = new Service();
        $form = $this->createForm(ServiceType::class, $service);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $serviceRepository->save($service, true);

            return $this->redirectToRoute('app_service_back', ['roleunique' => $role], Response::HTTP_SEE_OTHER);
        }


        return $this->renderForm('service/new.html.twig', [
            'service' => $service,
            'form' => $form,
            'roleunique' => $role
        ]);


    }

    #[Route('/{id}', name: 'app_service_back_show', methods: ['GET'])]
    public function show(Service $service): Response
    {
        $role=null;
        // if($this->getUser()){
        //    $role= $this->getUser()->getRoles()[0];
        // }
        return $this->render('service/show.html.twig', [
            'service' => $service,
            'roleunique' => $role
        ]);

    }

    #[Route('/{id}/edit', name: 'app_service_back_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Service $service, ServiceRepository $serviceRepository): Response
    {
        $role=null;
        // if($this->getUser()){
        //    $role= $this->getUser()->getRoles()[0];
        // }
        $form = $this->createForm(ServiceType::class, $service);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $serviceRepository->save($service, true);

            return $this->redirectToRoute('app_service_back', ['roleunique' => $role], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('service/edit.html.twig', [
            'service' => $service,
            'form' => $form,
            'roleunique' => $role
        ]);
    }

    #[Route('/{id}', name: 'app_service_back_delete', methods: ['POST'])]
    public function delete(Request $request, Service $service, ServiceRepository $serviceRepository): Response
    {
        $role=null;
        // if($this->getUser()){
        //    $role= $this->getUser()->getRoles()[0];
        // }
        if ($this->isCsrfTokenValid('delete'.$service->getId(), $request->request->get('_token'))) {
            $serviceRepository->remove($service, true);
        }

        return $this->redirectToRoute('app_service_back', ['roleunique' => $role], Response::HTTP_SEE_OTHER);
    }


    


}