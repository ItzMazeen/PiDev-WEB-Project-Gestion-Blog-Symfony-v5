<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class BackHomeController extends AbstractController
{
    #[Route('/admin', name: 'app_back_home')]
    public function index(): Response
    {
        return $this->render('back_home/index.html.twig', [
            'connected' => $this->getUser() !== null
        ]);
    }
}
