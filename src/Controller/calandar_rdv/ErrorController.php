<?php

namespace App\Controller\calandar_rdv;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ErrorController extends AbstractController
{

    #[Route('404', name: 'error_404')]
    public function error_404(): Response
    {

        return $this->render('error/404.html.twig', [
            'errormsg' => "      Not Found     ",
            'code' => 404
        ]);
    }

    #[Route('401', name: 'error_false_argument')]
    public function error_false_argument(): Response
    {

        return $this->render('error/404.html.twig', [
            'errormsg' => "       Unauthorized        ",
            'code' => 401
        ]);
    }


    #[Route('403', name: 'error_forbidden')]
    public function error_forbidden(): Response
    {

        return $this->render('error/404.html.twig', [
            'errormsg' => "       Forbidden        ",
            'code' => 403
        ]);
    }
}
