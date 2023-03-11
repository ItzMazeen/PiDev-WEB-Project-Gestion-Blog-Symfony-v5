<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FrontHomeController extends AbstractController
{
    #[Route('/home', name: 'app_front_home')]
    public function index(UserRepository $repoUser): Response
    {
        $theUserRole=null;
        if($this->getUser()){
           $theUserRole= $this->getUser()->getRoles()[0];
        }
        

        return $this->render('front_home/index.html.twig', [
            'connected' => $this->getUser() !== null,
            'roleunique' => $theUserRole
        ]);
    }
}
