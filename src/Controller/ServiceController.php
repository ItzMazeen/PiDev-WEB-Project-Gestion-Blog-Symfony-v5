<?php

namespace App\Controller;

use App\Entity\Service;
use App\Form\ServiceType;
use App\Repository\ServiceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;

#[Route('/service')]
class ServiceController extends AbstractController
{
    #[Route('/', name: 'app_service_index', methods: ['GET'])]
    public function index(Request $request, ServiceRepository $serviceRepository, PaginatorInterface $paginator): Response
    {   
        $role=null;
        if($this->getUser()){
           $role= $this->getUser()->getRoles()[0];
        }

        $services = $serviceRepository->findAll();
        $services = $paginator->paginate(
            $services,
            $request->query->getInt('page',1),
            4
        );
        
        return $this->render('service/index.html.twig', [
            'services' => $services,
            'roleunique' => $role
        ]);
    }

    #[Route('/new', name: 'app_service_new', methods: ['GET', 'POST'])]
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

            return $this->redirectToRoute('app_service_index', ['roleunique' => $role], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('service/new.html.twig', [
            'service' => $service,
            'form' => $form,
            'roleunique' => $role
        ]);
    }

    #[Route('/{id}', name: 'app_service_show', methods: ['GET'])]
    public function show(Service $service): Response
    {
        $role=null;
        if($this->getUser()){
           $role= $this->getUser()->getRoles()[0];
        }
        return $this->render('service/show.html.twig', [
            'service' => $service,
            'roleunique' => $role
        ]);
    }

    #[Route('/{id}/edit', name: 'app_service_edit', methods: ['GET', 'POST'])]
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

            return $this->redirectToRoute('app_service_index', ['roleunique' => $role], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('service/edit.html.twig', [
            'service' => $service,
            'form' => $form,
            'roleunique' => $role
        ]);
    }

    #[Route('/{id}', name: 'app_service_delete', methods: ['POST'])]
    public function delete(Request $request, Service $service, ServiceRepository $serviceRepository): Response
    {
        $role=null;
        // if($this->getUser()){
        //    $role= $this->getUser()->getRoles()[0];
        // }
        if ($this->isCsrfTokenValid('delete'.$service->getId(), $request->request->get('_token'))) {
            $serviceRepository->remove($service, true);
        }

        return $this->redirectToRoute('app_service_index', [
            'roleunique' => $role,
        ], Response::HTTP_SEE_OTHER);


    }
}
