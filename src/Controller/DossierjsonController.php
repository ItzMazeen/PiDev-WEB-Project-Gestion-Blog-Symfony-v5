<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use App\Entity\DossierMedical;
use App\Form\DossierMedical1Type;
use App\Repository\DossierMedicalRepository;
use App\Entity\Ficheconsultation;
use App\Form\Ficheconsultation1Type;
use App\Repository\FicheconsultationRepository;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints\DateTime;

class DossierjsonController extends AbstractController
{
    #[Route('/dossierjson', name: 'app_dossierjson')]
    public function index(): Response
    {
        return $this->render('dossierjson/index.html.twig', [
            'controller_name' => 'DossierjsonController',
        ]);
    }
    #[Route('/allDossier', name: 'app_dossier_list')]
    public function getDossierMedical( SerializerInterface $serializer): Response
    {
        $repository=$this->getDoctrine()->getRepository(DossierMedical::class);
        $organisations=$repository->findAll();
        $data=$serializer->normalize($organisations,'json',['groups'=> 'post:read']);

        return new Response(json_encode($data));
    }
    #[Route("/Dossier/{id}", name: "student")]
    public function DossierId($id, NormalizerInterface $normalizer, DossierMedicalRepository $repo)
    {
        $dossier_medicals = $repo->find($id);
        $dossier_medicalsNormalises = $normalizer->normalize($dossier_medicals, 'json', ['groups' => "post:read"]);
        return new Response(json_encode($dossier_medicalsNormalises));
    }

    #[Route("/addDossierJSON/new", name: "app_dossier_add")]
    public function addDossierJSON(Request $req,   NormalizerInterface $Normalizer)
    {

        $em = $this->getDoctrine()->getManager();
       // Récupération de la valeur de date_naissance depuis la requête
    $date_naissance_str = $req->get('date_naissance');
    // Conversion de la chaîne de caractères en objet DateTime
    $date_naissance = \DateTime::createFromFormat('Y-m-d', $req->get('date_naissance'));
    if (!$date_naissance) {
        throw new \InvalidArgumentException("Invalid date format: $date_naissance_str");
    }
        $dossier_medicals = new DossierMedical();
        $dossier_medicals->setFirstName($req->get('firstName'));
        $dossier_medicals->setLastName($req->get('lastName'));
        $dossier_medicals->setEmail($req->get('email'));
        $dossier_medicals->setDateNaissance($date_naissance);
        $dossier_medicals->setVaccins($req->get('vaccins'));
        $dossier_medicals->setMaladies($req->get('maladies'));
        $dossier_medicals->setAnalyses($req->get('analyses'));
        $dossier_medicals->setInterventionChirurgicale($req->get('intervention_chirurgicale'));
        $dossier_medicals->setAllergies($req->get('allergies'));
        $em->persist($dossier_medicals);
        $em->flush();

        $jsonContent = $Normalizer->normalize($dossier_medicals, 'json', ['groups' => "post:read"]);
        return new Response(json_encode($jsonContent));
    }

    #[Route("/updateDossierJSON/{id}", name: "app_dossier_update")]
    public function updateDossierJSON(Request $req, $id, NormalizerInterface $Normalizer)
    {

        $em = $this->getDoctrine()->getManager();
        $date_naissance_str = $req->get('date_naissance');
    // Conversion de la chaîne de caractères en objet DateTime
    $date_naissance = \DateTime::createFromFormat('Y-m-d', $req->get('date_naissance'));
    //if (!$date_naissance) {
      //  throw new \InvalidArgumentException("Invalid date format: $date_naissance_str");
   // }
        $dossier_medicals = $em->getRepository(DossierMedical::class)->find($id);
        $dossier_medicals->setFirstName($req->get('firstName'));
        $dossier_medicals->setLastName($req->get('lastName'));
        $dossier_medicals->setEmail($req->get('email'));
        $dossier_medicals->setDateNaissance($date_naissance);
        $dossier_medicals->setVaccins($req->get('vaccins'));
        $dossier_medicals->setMaladies($req->get('maladies'));
        $dossier_medicals->setAnalyses($req->get('analyses'));
        $dossier_medicals->setInterventionChirurgicale($req->get('intervention_chirurgicale'));
        $dossier_medicals->setAllergies($req->get('allergies'));

        $em->flush();

        $jsonContent = $Normalizer->normalize($dossier_medicals, 'json', ['groups' => "post:read"]);
        return new Response("Dossier updated successfully " . json_encode($jsonContent));
    }

    #[Route("/deleteDossierJSON/{id}", name: "app_dossier_supp")]
    public function deleteDossierJSON(Request $req, $id, NormalizerInterface $Normalizer)
    {

        $em = $this->getDoctrine()->getManager();
        $dossier_medicals = $em->getRepository(DossierMedical::class)->find($id);
        if (!$dossier_medicals) {
            return new Response("Dossier not found");
        }
        $em->remove($dossier_medicals);
        $em->flush();
        $jsonContent = $Normalizer->normalize($dossier_medicals, 'json', ['groups' => "post:read"]);
        return new Response("Dossier deleted successfully " . json_encode($jsonContent));
    }
}
