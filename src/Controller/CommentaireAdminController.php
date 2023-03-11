<?php

namespace App\Controller;

use App\Entity\Commentaire;
use App\Form\CommentaireType;
use App\Repository\CommentaireRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;


#[Route('/adminCommentaires')]
class CommentaireAdminController extends AbstractController
{
// Index of comments :
    #[Route('/', name: 'app_commentaire_index1', methods: ['GET'])]
    public function index(Request $request,CommentaireRepository $commentaireRepository,PaginatorInterface $paginator): Response
    {
        $commentaires = $commentaireRepository->findAll();



        // Get the number of articles
        $numComments = count($commentaires);
        $now = new \DateTime();
        // Get the start of the current week (Sunday)
        $weekStart = clone $now;
        $weekStart->modify('last sunday');

        // Get the start of today
        $todayStart = clone $now;
        $todayStart->setTime(0, 0, 0);

        // Get the end of today
        $todayEnd = clone $now;
        $todayEnd->setTime(23, 59, 59);
        $entityManager = $this->getDoctrine()->getManager();
        $query = "SELECT COUNT(DISTINCT c.userId) FROM App\Entity\Commentaire c";
        $countUsers = $entityManager->createQuery($query)->getSingleScalarResult();
        // Get the count of articles created this week
        $query = "SELECT COUNT(*) FROM commentaire WHERE created_at >= '{$weekStart->format('Y-m-d H:i:s')}'";
        $weekCount = $entityManager->getConnection()->query($query)->fetchOne();
        // Get the count of articles created today
        $query = "SELECT COUNT(*) FROM commentaire WHERE created_at BETWEEN '{$todayStart->format('Y-m-d H:i:s')}' AND '{$todayEnd->format('Y-m-d H:i:s')}'";
        $todayCount = $entityManager->getConnection()->query($query)->fetchOne();

        //pagination feature
        $commentaires = $paginator->paginate(
            $commentaires, /* query NOT result */
            $request->query->getInt('page', 1)/*page number*/,
            3/*limit per page*/
        );
        $commentaires->setCustomParameters([
            'align' => 'center', # center|right (for template: twitter_bootstrap_v4_pagination)
            'style' => 'bottom',
            'span_class' => 'whatever',
        ]);
        return $this->render('commentaire_admin/index.html.twig', [
            'commentaires' => $commentaires,
            'numComments' => $numComments,
            'todayCount' => $todayCount,
            'weekCount' => $weekCount,
            'countUsers' => $countUsers
        ]);
    }

    #[Route('/adminAPI', name: 'app_commentaire_commentsAPI', methods: ['GET'])]
    public function commentsAPI(NormalizerInterface $normalizer,Request $request,CommentaireRepository $commentaireRepository,PaginatorInterface $paginator): Response
    {
        $commentaires = $commentaireRepository->findAll();
        // Get the number of articles
        $numComments = count($commentaires);
        $now = new \DateTime();
        // Get the start of the current week (Sunday)
        $weekStart = clone $now;
        $weekStart->modify('last sunday');

        // Get the start of today
        $todayStart = clone $now;
        $todayStart->setTime(0, 0, 0);

        // Get the end of today
        $todayEnd = clone $now;
        $todayEnd->setTime(23, 59, 59);
        $entityManager = $this->getDoctrine()->getManager();
        $query = "SELECT COUNT(DISTINCT c.userId) FROM App\Entity\Commentaire c";
        $countUsers = $entityManager->createQuery($query)->getSingleScalarResult();
        // Get the count of articles created this week
        $query = "SELECT COUNT(*) FROM commentaire WHERE created_at >= '{$weekStart->format('Y-m-d H:i:s')}'";
        $weekCount = $entityManager->getConnection()->query($query)->fetchOne();
        // Get the count of articles created today
        $query = "SELECT COUNT(*) FROM commentaire WHERE created_at BETWEEN '{$todayStart->format('Y-m-d H:i:s')}' AND '{$todayEnd->format('Y-m-d H:i:s')}'";
        $todayCount = $entityManager->getConnection()->query($query)->fetchOne();

        $data = [
            'numComments' => $numComments,
            'countUsers' => $countUsers,
            'weekCount' => $weekCount,
            'todayCount' => $todayCount
        ];
        
        $articlesNormalizer = $normalizer->normalize($data, 'json' , []);
        $json = json_encode($articlesNormalizer);
        return new Response($json);
    }
// Show a specific Comment Details :
    #[Route('/{id}', name: 'app_commentaire_show1', methods: ['GET'])]
    public function show(Commentaire $commentaire): Response
    {
        return $this->render('commentaire_admin/show.html.twig', [
            'commentaire' => $commentaire,
        ]);
    }
// Delete a specific Comment :
    #[Route('/{id}', name: 'app_commentaire_delete1', methods: ['POST'])]
    public function delete(Request $request, Commentaire $commentaire, CommentaireRepository $commentaireRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$commentaire->getId(), $request->request->get('_token'))) {
            $commentaireRepository->remove($commentaire, true);
        }
        return $this->redirectToRoute('app_commentaire_index1', [], Response::HTTP_SEE_OTHER);
    }
}
