<?php

namespace App\Controller\calandar_rdv;


use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class IntermediaryController extends AbstractController
{
    #[Route('/', name: 'app_intermediary')]
    public function index(UserRepository $repoUser): Response
    {

        $theUserRole=null;
        if($this->getUser()){
           $theUserRole= $this->getUser()->getRoles()[0];
        }

        if ($this->getUser() === null)
            return $this->redirectToRoute('app_front_home', [

                
                'roleunique' => $theUserRole
            ]);

        if ($this->getUser() !== null) {

            $email = $this->getUser()->getUserIdentifier();
            $user = $repoUser->findOneBy(['email' => $email]);
            $roles = $this->getUser()->getRoles();

            if (count($roles) === 1 && $roles[0] === "ROLE_USER")
                return $this->redirectToRoute('app_front_home', [
                    'userid' => $user->getId(),
                  
                    'roleunique' => $theUserRole
                ]);
            elseif ((in_array('ROLE_DOCTOR', $roles, true) || in_array('ROLE_Nurse', $roles, true)))
                return $this->redirectToRoute('app_calandar_day_index', [
                   
                    'roleunique' => $theUserRole
                ]);
            elseif (in_array('ROLE_ADMIN', $this->getUser()->getRoles(), true))
                return $this->redirectToRoute('app_time_slot_management', [
                    'user' => $user ,
                   
                    'roleunique' => $theUserRole
                ]);
            else
                return $this->redirectToRoute('app_front_home', [

                   
                    'roleunique' => $theUserRole
                ]);
        }
    }
}
