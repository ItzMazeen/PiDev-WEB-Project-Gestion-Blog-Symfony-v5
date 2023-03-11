<?php

namespace App\Controller\gestion_dossier;

use App\Entity\DossierMedical;
use App\Form\DossierMedical1Type;
use App\Repository\DossierMedicalRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Dompdf\Dompdf;
use Dompdf\Options;
use Symfony\Component\Serializer\SerializerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\JsonResponse;


//use Symfony\Component\OptionsResolver\Options as Options;


#[Route('/dossier')]
class DossierController extends AbstractController
{
    #[Route('/', name: 'app_dossier_index', methods: ['GET'])]

    public function index(Request $request, DossierMedicalRepository $dossierMedicalRepository, PaginatorInterface $paginator ): Response
    {
        $theUserRole="ROLE_USER";
        // if($this->getUser()){
        //    $theUserRole= $this->getUser()->getRoles()[0];
        // }

        $dossier_medicals = $dossierMedicalRepository->findAll();

        $dossier_medicals = $paginator->paginate(
            $dossier_medicals, /* query NOT result */
            $request->query->getInt('page', 1),
            3
        );
        return $this->render('dossier/index.html.twig', [
            'dossier_medicals' => $dossier_medicals,
            'roleunique' => $theUserRole
        ]);
    }
#[Route('/search', name: 'app_dossier_search', methods: ['GET'])]
public function search(Request $request, DossierMedicalRepository $dossierMedicalRepository): JsonResponse
{
   


    $firstName = $request->query->get('firstName');
    $dossier_medicals = $dossierMedicalRepository->findByFirstName($firstName);
    $data = [];
    foreach ($dossier_medicals as $dossier_medical) {
        $data[] = [
            'id' => $dossier_medical->getId(),
            'firstName' => $dossier_medical->getFirstName(),
            'lastName' => $dossier_medical->getLastName(),
            'email' => $dossier_medical->getEmail(),
            'date_naissance' => $dossier_medical->getDateNaissance(),
            'maladies' => $dossier_medical->getMaladies(),
            'allergies' => $dossier_medical->getAllergies(),
            'vaccins' => $dossier_medical->getVaccins(),
            'analyses' => $dossier_medical->getAnalyses(),
            'intervention_chirurgicale' => $dossier_medical->getInterventionChirurgicale(),
            // Ajouter d'autres champs si nécessaire
        ];
       
    }

    return $this->json($data);
}

    
   
    #[Route('/calcul', name: 'app_dossier_calcul', methods: ['GET', 'POST'])]
    public function calcul(Request $request ): Response
    {
        $theUserRole="ROLE_USER";
        // if($this->getUser()){
        //    $theUserRole= $this->getUser()->getRoles()[0];
        // }
        $imc = null;
        if ($request->isMethod('POST')) {
            $poids = $request->request->get('poids');
            $taille = $request->request->get('taille');
            if ($taille > 0) {
                $imc = $poids / ($taille * $taille);
            }
        }
      
        return $this->render('dossier/calcul.html.twig', [
            'imc' => $imc,'roleunique' => $theUserRole
        ]);}
     public function calculConseils(Request $request)
        {
            $theUserRole="ROLE_USER";
            // if($this->getUser()){
            //    $theUserRole= $this->getUser()->getRoles()[0];
            // }
            $vitamineD = $request->get('vitamineD');
            $fer = $request->get('fer');
            $calcium = $request->get('calcium');
            $magnesium = $request->get('magnesium');
            $zinc = $request->get('zinc');
           
            
            return  $this->render('dossier/calcul.html.twig', [
                'vitamineD' => $vitamineD,
                'fer' => $fer,
                'calcium' => $calcium,
                'magnesium' => $magnesium,
                'zinc' => $zinc,
                'roleunique' => $theUserRole
                
            ]);
        }
    
        public function calculcaories(Request $request)
        {
            $theUserRole="ROLE_USER";
            // if($this->getUser()){
            //    $theUserRole= $this->getUser()->getRoles()[0];
            // }
            $Âge = $request->get('Âge');
            $Taille = $request->get('Taille');
            $Poids = $request->get('Poids');
            return  $this->render('dossier/calcul.html.twig', [
                'Âge' => $Âge,
                'Taille' => $Taille,
                'Poids' => $Poids, 
                'roleunique' => $theUserRole 
            ]);
        }

    #[Route('/new', name: 'app_dossier_new', methods: ['GET', 'POST'])]
    public function new(Request $request, DossierMedicalRepository $dossierMedicalRepository): Response
    {
        $theUserRole="ROLE_USER";
        // if($this->getUser()){
        //    $theUserRole= $this->getUser()->getRoles()[0];
        // }
        $dossierMedical = new DossierMedical();
        $form = $this->createForm(DossierMedical1Type::class, $dossierMedical);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $dossierMedicalRepository->save($dossierMedical, true);

            return $this->redirectToRoute('app_dossier_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('dossier/new.html.twig', [
            'dossier_medical' => $dossierMedical,
            'form' => $form,
            'roleunique' => $theUserRole
        ]);
    }

    #[Route('/{id}', name: 'app_dossier_show', methods: ['GET'])]
    public function show(DossierMedical $dossierMedical): Response
    {
        $theUserRole="ROLE_USER";
        // if($this->getUser()){
        //    $theUserRole= $this->getUser()->getRoles()[0];
        // }
        return $this->render('dossier/show.html.twig', [
            'dossier_medical' => $dossierMedical,
            'roleunique' => $theUserRole
        ]);
    }
    #[Route('/{id}/donnes', name: 'app_dossier_donnes', methods: ['GET'])]
    public function view(DossierMedical $dossierMedical,DossierMedicalRepository $dossierMedicalRepository): Response
    {
        $theUserRole="ROLE_USER";
        // if($this->getUser()){
        //    $theUserRole= $this->getUser()->getRoles()[0];
        // }
        $dossier_medicals = $dossierMedicalRepository->findAll();

        return $this->render('dossier/donnes.html.twig', [
            'dossier_medical' => $dossierMedical,
            'roleunique' => $theUserRole
        ]);
    }
   
    #[Route('/{id}/donnes/download', name: 'app_dossier_download', methods: ['GET'])]
    public function down(DossierMedical $dossierMedical, DossierMedicalRepository $dossierMedicalRepository): Response
    {
            // On définit les options du PDF
            $pdfOptions = new Options();

            // Police par défaut
            $pdfOptions->set('defaultFont', 'Arial');
            $pdfOptions->setIsRemoteEnabled(true);
    
            // On instancie Dompdf
            $dompdf = new Dompdf($pdfOptions);
            //$dossierMedicalRepository = $this->getDoctrine()->getRepository(DossierMedical::class);
            $dossier_medicals = $dossierMedicalRepository->findAll();
            $context = stream_context_create([
                'ssl' => [
                    'verify_peer' => FALSE,
                    'verify_peer_name' => FALSE,
                    'allow_self_signed' => TRUE
                ]
            ]);
            $dompdf->setHttpContext($context);
    
            // On génère le html
            $html = $this->renderView('dossier/download.html.twig', [
                'dossier_medical' => $dossierMedical,
                
            ]);
            
            $dompdf->loadHtml($html);
            $dompdf->setPaper('A4', 'portrait');
            $dompdf->render();
    
            // On génère un nom de fichier
            $fichier = 'donnes-dossier'.'.pdf';
    
            // On envoie le PDF au navigateur
            $dompdf->stream($fichier, [
                'Attachment' => true
            ]);
            
            return new Response();
        }


    #[Route('/{id}/edit', name: 'app_dossier_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, DossierMedical $dossierMedical, DossierMedicalRepository $dossierMedicalRepository): Response
    {
        $theUserRole="ROLE_USER";
        // if($this->getUser()){
        //    $theUserRole= $this->getUser()->getRoles()[0];
        // }
        $form = $this->createForm(DossierMedical1Type::class, $dossierMedical);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $dossierMedicalRepository->save($dossierMedical, true);

            return $this->redirectToRoute('app_dossier_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('dossier/edit.html.twig', [
            'dossier_medical' => $dossierMedical,
            'form' => $form,
            'roleunique' => $theUserRole
        ]);
    }

    #[Route('/{id}', name: 'app_dossier_delete', methods: ['POST'])]
    public function delete(Request $request, DossierMedical $dossierMedical, DossierMedicalRepository $dossierMedicalRepository): Response

    { $theUserRole="ROLE_USER";
        // if($this->getUser()){
        //    $theUserRole= $this->getUser()->getRoles()[0];
        // }
        if ($this->isCsrfTokenValid('delete'.$dossierMedical->getId(), $request->request->get('_token'))) {
            $dossierMedicalRepository->remove($dossierMedical, true);
        }

        return $this->redirectToRoute('app_dossier_index', ['roleunique' => $theUserRole], Response::HTTP_SEE_OTHER);
    }
}
