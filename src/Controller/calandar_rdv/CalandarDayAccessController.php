<?php

namespace App\Controller\calandar_rdv;

use App\Entity\User;

use App\Repository\UserRepository;
use App\Repository\CalandarDayRepository;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/calandarday/access')]
class CalandarDayAccessController extends AbstractController
{

    // USER SIDE CONTROLLER NOT IMPLIMENTED ON THE OTHER CLASS CALANDARDAYcontroller

    // _*****/calandarday***app_calandar_day_index***list_all_calandar_for a doctor ***************

    #[Route('/{id}', name: 'app_calandar_access_day_index', methods: ['GET'])]
    public function index(User $id, CalandarDayRepository $calandarDayRepository, UserRepository $repoUser): Response
    {

        $theUserRole=null;
        if($this->getUser()){
           $theUserRole= $this->getUser()->getRoles()[0];
        }
        

        // if not logged in no place for him
        if ($this->getUser() === null)
            return $this->redirectToRoute("app_login",[
                'roleunique' => $theUserRole
            ]);

        // excluding everyone excep a doctor or a nurse
        if (!in_array('ROLE_USER', $this->getUser()->getRoles(), true))
            return $this->redirectToRoute('error_404', [
                'errormsg' => "404-maybe-you-have-no-right-for-now"
            ]); // redirect to booking better


        // excluding everyone excep a doctor or a nurse
        if (in_array('ROLE_DOCTOR', $this->getUser()->getRoles(), true))
            return $this->redirectToRoute(
                'app_calandar_day_index' ,[ 'role_user' => "ROLE_DOCTOR"  ,
              
                'roleunique' => $theUserRole]
             ); // redirect to booking better

        // getting user 
        $userEmail = $this->getUser()->getUserIdentifier();
        $user = $repoUser->findOneBy(['email' => $userEmail]);

        $role_user = $this->getUser()->getRoles()[0];
        return $this->render('calandar_day/index.html.twig', [
            'calandar_days' => $calandarDayRepository->findBy([
                "doctor" => $id  // getId is not an error,
              
            ]),
            'doctor' => $id->getId(),
            'name' => $id->getLastName(),
            'user' => $id->getId(),
            'role_user'=> $role_user,
           
            'roleunique' => $theUserRole

        ]);
    }


 

    // those ARE UNUSED KEEP IT IN CASE I MISAKENLY IMPLEMENTED IN IT CERTAIN LOGIC
    // IN PLACE OF THE OTHER ROUTER

    // // _****show ****/{id}*********  **app_calandar_day_show***************************


    // #[Route('/{id}', name: 'app_calandar_day_show', methods: ['GET'])]
    // public function show(CalandarDay $calandarDay): Response
    // {

    //     return $this->render('calandar_day/show.html.twig', [
    //         'calandar_day' => $calandarDay,
    //     ]);
    // }


    // // _****/{id}/edit**********app_calandar_day_edit** *************

    // #[Route('/{id}/edit', name: 'app_calandar_day_edit', methods: ['GET', 'POST'])]
    // public function edit(CalandarDay $calandarDay, Request $request, CalandarDayRepository $calandarDayRepository): Response
    // {
    //     // getting user from calandar wich is very cool
    //     $doctor = $calandarDay->getDoctor();
    //     $roles = $doctor->getRoles();

    //     dump($calandarDay);
    //     if ($this->getUser()->getUserIdentifier() !== $doctor->getEmail())
    //         return $this->redirectToRoute('error_404');
    //     if (!(in_array('ROLE_DOCTOR', $roles, true) || in_array('ROLE_Nurse', $roles, true)))
    //         return $this->redirectToRoute('error_404', [
    //             'errormsg' => "404 access-denied please avoid such behavior ."
    //         ]);


    //     $form = $this->createForm(CalandarDayType::class, $calandarDay, [
    //         'id' => $doctor
    //     ]);
    //     $form->handleRequest($request);

    //     if ($form->isSubmitted() && $form->isValid()) {
    //         $calandarDayRepository->save($calandarDay, true);

    //         return $this->redirectToRoute('app_calandar_day_index', [], Response::HTTP_SEE_OTHER);
    //     }

    //     return $this->renderForm('calandar_day/edit.html.twig', [
    //         'calandar_day' => $calandarDay,
    //         'form' => $form,
    //     ]);
    // }


    // // _*****/delete/{id}******* ****app_calandar_day_delete**************

    // #[Route('/delete/{id}', name: 'app_calandar_day_delete', methods: ['POST'])]
    // public function delete(Request $request, CalandarDay $calandarDay, CalandarDayRepository $calandarDayRepository): Response
    // {
    //     // getting user from calandar wich is very cool
    //     $doctor = $calandarDay->getDoctor();
    //     $roles = $doctor->getRoles();

    //     dump($calandarDay);
    //     if ($this->getUser()->getUserIdentifier() !== $doctor->getEmail())
    //         return $this->redirectToRoute('error_404');
    //     //this is innecessary only when we grap user from the calandar in other situation not valid maybe
    //     if (!(in_array('ROLE_DOCTOR', $roles, true) || in_array('ROLE_Nurse', $roles, true)))
    //         return $this->redirectToRoute('error_404');

    //     if ($this->isCsrfTokenValid('delete' . $calandarDay->getId(), $request->request->get('_token'))) {
    //         $calandarDayRepository->remove($calandarDay, true);
    //     }

    //     return $this->redirectToRoute('app_calandar_day_index', [], Response::HTTP_SEE_OTHER);
    // }
}
