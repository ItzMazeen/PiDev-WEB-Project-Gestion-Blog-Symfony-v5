<?php

namespace App\Controller\gestion_dossier;

use App\Entity\Ficheconsultation;
use App\Form\Ficheconsultation1Type;
use App\Repository\FicheconsultationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Validator\Constraints\DateTime ;
use Knp\Component\Pager\PaginatorInterface;
use App\Doctrine\DateMutableType;


#[Route('/fiche')]
class FicheController extends AbstractController

{
    private $ficheconsultationRepository;

    #[Route('/', name: 'app_fiche_index', methods: ['GET','POST'])]
    public function index(Request $request,FicheconsultationRepository $ficheconsultationRepository, PaginatorInterface $paginator): Response
    {
        $dateConsultation = $request->query->get('dateConsultation');
   
    $queryBuilder = $ficheconsultationRepository->createQueryBuilder('fc');
    $queryBuilder->orderBy('fc.date_consultation', 'DESC');
    
    if ($dateConsultation) {
        $dateString = $dateConsultation . ' 00:00:00';
        $date = date_create_from_format('Y-m-d H:i:s', $dateString);
        $queryBuilder->andWhere("DATE_FORMAT(fc.date_consultation, '%Y-%m-%d') = :date")
            ->setParameter('date', $date->format('Y-m-d'));
         
    }

    $query = $queryBuilder->getQuery();
    dump($query->getSQL());
    $ficheconsultations = $paginator->paginate(
        $query,
        $request->query->getInt('page', 1),
        2
    );

    return $this->render('fiche/index.html.twig', [
        'ficheconsultations' => $ficheconsultations,
    ]);
   
    }



    #[Route('/new', name: 'app_fiche_new', methods: ['GET', 'POST'])]
    public function new(Request $request, FicheconsultationRepository $ficheconsultationRepository): Response
    {
        $ficheconsultation = new Ficheconsultation();
        $form = $this->createForm(Ficheconsultation1Type::class, $ficheconsultation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $ficheconsultationRepository->save($ficheconsultation, true);

            return $this->redirectToRoute('app_fiche_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('fiche/new.html.twig', [
            'ficheconsultation' => $ficheconsultation,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_fiche_show', methods: ['GET'])]
    public function show(Ficheconsultation $ficheconsultation): Response
    {
        return $this->render('fiche/show.html.twig', [
            'ficheconsultation' => $ficheconsultation,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_fiche_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Ficheconsultation $ficheconsultation, FicheconsultationRepository $ficheconsultationRepository): Response
    {
        $form = $this->createForm(Ficheconsultation1Type::class, $ficheconsultation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $ficheconsultationRepository->save($ficheconsultation, true);

            return $this->redirectToRoute('app_fiche_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('fiche/edit.html.twig', [
            'ficheconsultation' => $ficheconsultation,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_fiche_delete', methods: ['POST'])]
    public function delete(Request $request, Ficheconsultation $ficheconsultation, FicheconsultationRepository $ficheconsultationRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$ficheconsultation->getId(), $request->request->get('_token'))) {
            $ficheconsultationRepository->remove($ficheconsultation, true);
        }

        return $this->redirectToRoute('app_fiche_index', [], Response::HTTP_SEE_OTHER);
    }
}
