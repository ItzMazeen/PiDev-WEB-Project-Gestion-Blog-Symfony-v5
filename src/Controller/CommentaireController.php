<?php

namespace App\Controller;

use App\Entity\Commentaire;
use App\Entity\Article;
use App\Entity\User;

use App\Form\CommentaireType;
use App\Repository\CommentaireRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

#[Route('/articles/{id}/commentaires')]
class CommentaireController extends AbstractController
{

// Comments Index :
    #[Route('/', name: 'app_commentaire_index', methods: ['GET'])]
    public function index(CommentaireRepository $commentaireRepository): Response
    {
        $theUserRole="ROLE_USER";
        // if($this->getUser()){
        //    $theUserRole= $this->getUser()->getRoles()[0];
        // }
  
        return $this->render('commentaire/index.html.twig', [
            'id'=>$commentaire->getId(),

        'roleunique' => $theUserRole
        ]);
    }

// Creating new comment :
    #[Route('/new', name: 'app_commentaire_new', methods: ['GET', 'POST'])]
    public function new(Request $request, Article $article, CommentaireRepository $commentaireRepository): Response
    {
        $theUserRole="ROLE_USER";
        // if($this->getUser()){
        //    $theUserRole= $this->getUser()->getRoles()[0];
        // }

        $commentaire = new Commentaire();
        /** add the comment to the specific artical **/
        $commentaire->setArticle($article);
        /** return id  parameter of the artical from the route **/
        $id = $article->getId();
        
        $form = $this->createForm(CommentaireType::class, $commentaire);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $commentaireRepository->save($commentaire, true);
            return $this->redirectToRoute('app_article_show', ['id'=>$id], Response::HTTP_SEE_OTHER);
        }
        return $this->renderForm('commentaire/new.html.twig', [
            'commentaire' => $commentaire,
            'form' => $form,

        'roleunique' => $theUserRole
        ]);
    }

    //CRETING NEW ARTICAL API : 
    #[Route('/addCommentJSON/new', name: 'app_commentaire_newJJSON', methods: ['GET'])]
    public function newCommentJSON(Request $req, Article $article,NormalizerInterface $normalizer ): Response
    {
        $commentaire = new Commentaire();
        /** add the comment to the specific artical **/
        $commentaire->setArticle($article);
        $em = $this->getDoctrine()->getManager();
        $commentaire->setContenu($req->get('contenu'));

        $user = $em->getRepository(User::class)->find($req->get('user'));
        $commentaire->setUserId($user);
        $em->persist($commentaire);
        $em->flush();
        $commentaireNormalizer=$normalizer->normalize($commentaire,'json',['groups'=>"articles"]);
        $json= json_encode($commentaireNormalizer);
        return new Response($json);
    }
    
//edit the specific Comment :
    #[Route('/edit', name: 'app_commentaire_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Commentaire $commentaire, CommentaireRepository $commentaireRepository): Response
    {
        $theUserRole="ROLE_USER";
        // if($this->getUser()){
        //    $theUserRole= $this->getUser()->getRoles()[0];
        // }

        $form = $this->createForm(CommentaireType::class, $commentaire);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $commentaireRepository->save($commentaire, true);
            return $this->redirectToRoute('app_article_index',[], Response::HTTP_SEE_OTHER);
        }
        return $this->renderForm('commentaire/edit.html.twig', [
            'commentaire' => $commentaire,
            'form' => $form,

        'roleunique' => $theUserRole
        ]);
    }

// Delete the specific Comment : 
    #[Route('/delete', name: 'app_commentaire_delete', methods: ['POST'])]
    public function delete(Request $request, Commentaire $commentaire, CommentaireRepository $commentaireRepository): Response
    {
        $theUserRole="ROLE_USER";
        // if($this->getUser()){
        //    $theUserRole= $this->getUser()->getRoles()[0];
        // }

        if ($this->isCsrfTokenValid('delete'.$commentaire->getId(), $request->request->get('_token'))) {
            $commentaireRepository->remove($commentaire, true);
        }
        return $this->redirectToRoute('app_article_index', [

        'roleunique' => $theUserRole
        ], Response::HTTP_SEE_OTHER);
    }
}
