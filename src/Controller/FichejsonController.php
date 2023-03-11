<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\SerializerInterface;
use App\Repository\FicheconsultationRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints\DateTime;
use App\Entity\Ficheconsultation;

class FichejsonController extends AbstractController
{
    #[Route('/fichejson', name: 'app_fichejson')]
    public function index(): Response
    {
        return $this->render('fichejson/index.html.twig', [
            'controller_name' => 'FichejsonController',
        ]);
    }

    #[Route('/allfiche', name: 'app_fiche_list')]
    public function getFicheconsultation( FicheconsultationRepository $repo ,SerializerInterface $serializer): Response
    {
        $repository=$this->getDoctrine()->getRepository(Ficheconsultation::class);
        $organisations=$repository->findAll();
        $data=$serializer->normalize($organisations,'json',['groups'=> 'post:read']);

        return new Response(json_encode($data));
    }

    #[Route("/Fiche/{id}", name: "app_fiche_id")]
    public function ficheId($id, NormalizerInterface $normalizer, FicheconsultationRepository $repo)
    {
        $ficheconsultations = $repo->find($id);
        $dossier_medicalsNormalises = $normalizer->normalize($ficheconsultations, 'json', ['groups' => "post:read"]);
        return new Response(json_encode($dossier_medicalsNormalises));
    }

    #[Route("/addFicheJSON/new", name: "app_fiche_add")]
    public function addFicheJSON(Request $req,   NormalizerInterface $Normalizer)
    {

        $em = $this->getDoctrine()->getManager();
     //Récupération de la valeur de date_naissance depuis la requête
       $date_consultation_str = $req->get('date_consultation');
     //Conversion de la chaîne de caractères en objet DateTime
     $date_consultation = \DateTime::createFromFormat('Y-m-d', $req->get('date_consultation'));
    if (!$date_consultation) {
        throw new \InvalidArgumentException("Invalid date format: $date_consultation_str");
    }
        $ficheconsultations = new Ficheconsultation();
        $ficheconsultations->setFirstName($req->get('firstName'));
        $ficheconsultations->setLastName($req->get('lastName'));
        $ficheconsultations->setDateConsultation($date_consultation);
        $ficheconsultations->setSpecialite($req->get('specialite'));
        $ficheconsultations->setTraitement($req->get('traitement'));
        $ficheconsultations->setReccomendation($req->get('reccomendation'));
        $em->persist($ficheconsultations);
        $em->flush();

        $jsonContent = $Normalizer->normalize($ficheconsultations, 'json', ['groups' => "post:read"]);
        return new Response(json_encode($jsonContent));
    }

    #[Route("/updateFicheJSON/{id}", name: "app_Fiche_update")]
    public function updateDossierJSON(Request $req, $id, NormalizerInterface $Normalizer)
    {

        $em = $this->getDoctrine()->getManager();
        //Récupération de la valeur de date_naissance depuis la requête
          $date_consultation_str = $req->get('date_consultation');
        //Conversion de la chaîne de caractères en objet DateTime
        $date_consultation = \DateTime::createFromFormat('Y-m-d', $req->get('date_consultation'));
       //if (!$date_consultation) {
          // throw new \InvalidArgumentException("Invalid date format: $date_consultation_str");
       //}
           $ficheconsultations = new Ficheconsultation();
           $ficheconsultations->setFirstName($req->get('firstName'));
           $ficheconsultations->setLastName($req->get('lastName'));
           $ficheconsultations->setDateConsultation($date_consultation);
           $ficheconsultations->setSpecialite($req->get('specialite'));
           $ficheconsultations->setTraitement($req->get('traitement'));
           $ficheconsultations->setReccomendation($req->get('reccomendation'));
           $em->persist($ficheconsultations);
           $em->flush();
   
           $jsonContent = $Normalizer->normalize($ficheconsultations, 'json', ['groups' => "post:read"]);
           return new Response(json_encode($jsonContent));

        $jsonContent = $Normalizer->normalize($dossier_medicals, 'json', ['groups' => "post:read"]);
        return new Response("Dossier updated successfully " . json_encode($jsonContent));
    }

    #[Route("/deleteFicheJSON/{id}", name: "app_fiche_supp")]
    public function deleteDossierJSON(Request $req, $id, NormalizerInterface $Normalizer)
    {

        $em = $this->getDoctrine()->getManager();
        $ficheconsultations = $em->getRepository(Ficheconsultation::class)->find($id);
        if (!$ficheconsultations) {
            return new Response("Dossier not found");
        }
        $em->remove($ficheconsultations);
        $em->flush();
        $jsonContent = $Normalizer->normalize($ficheconsultations, 'json', ['groups' => "post:read"]);
        return new Response("Dossier deleted successfully " . json_encode($jsonContent));
    }
}


