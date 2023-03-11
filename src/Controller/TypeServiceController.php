<?php

namespace App\Controller;

use App\Entity\TypeService;
use App\Form\TypeServiceType;
use App\Repository\TypeServiceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/type/service')]
class TypeServiceController extends AbstractController
{
    #[Route('/', name: 'app_type_service_index', methods: ['GET'])]
    public function index(TypeServiceRepository $typeServiceRepository): Response
    {
        $role=null;
        // if($this->getUser()){
        //    $role= $this->getUser()->getRoles()[0];
        // }        
        return $this->render('type_service/index.html.twig', [
            'type_services' => $typeServiceRepository->findAll(),
            'roleunique' => $role
        ]);

        
    }

    #[Route('/new', name: 'app_type_service_new', methods: ['GET', 'POST'])]
    public function new(Request $request, TypeServiceRepository $typeServiceRepository): Response
    {
        $role=null;
        // if($this->getUser()){
        //    $role= $this->getUser()->getRoles()[0];
        // }
        $typeService = new TypeService();
        $form = $this->createForm(TypeServiceType::class, $typeService);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $typeServiceRepository->save($typeService, true);

            return $this->redirectToRoute('app_type_service_index', ['roleunique' => $role], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('type_service/new.html.twig', [
            'type_service' => $typeService,
            'form' => $form,
            'roleunique' => $role
        ]);
    }

    #[Route('/{id}', name: 'app_type_service_show', methods: ['GET'])]
    public function show(TypeService $typeService): Response
    {
        $role=null;
        // if($this->getUser()){
        //    $role= $this->getUser()->getRoles()[0];
        // }
        return $this->render('type_service/show.html.twig', [
            'type_service' => $typeService,
            'roleunique' => $role
        ]);
    }

    #[Route('/{id}/edit', name: 'app_type_service_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, TypeService $typeService, TypeServiceRepository $typeServiceRepository): Response
    {
        $role=null;
        // if($this->getUser()){
        //    $role= $this->getUser()->getRoles()[0];
        // }
        $form = $this->createForm(TypeServiceType::class, $typeService);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $typeServiceRepository->save($typeService, true);

            return $this->redirectToRoute('app_type_service_index', ['roleunique' => $role], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('type_service/edit.html.twig', [
            'type_service' => $typeService,
            'form' => $form,
            'roleunique' => $role
        ]);
    }

    #[Route('/{id}', name: 'app_type_service_delete', methods: ['POST'])]
    public function delete(Request $request, TypeService $typeService, TypeServiceRepository $typeServiceRepository): Response
    {
        $role=null;
        // if($this->getUser()){
        //    $role= $this->getUser()->getRoles()[0];
        // }
        if ($this->isCsrfTokenValid('delete'.$typeService->getId(), $request->request->get('_token'))) {
            $typeServiceRepository->remove($typeService, true);
        }

        return $this->redirectToRoute('app_type_service_index', ['roleunique' => $role], Response::HTTP_SEE_OTHER);
    }
}
