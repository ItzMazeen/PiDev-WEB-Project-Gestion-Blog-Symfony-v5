<?php

namespace App\Controller;
use App\Entity\Article;
use App\Entity\User;
use App\Repository\UserRepository;

use App\Form\ArticleType;
use App\Service\MyBadWordsFilter;
use App\Repository\ArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Dompdf\Dompdf;
use Dompdf\Options;
#[Route('/articles')]
class ArticleController extends AbstractController
{


    
//Articals Index : 
    #[Route('/', name: 'app_article_index', methods: ['GET'])]
    public function index(Request $request,ArticleRepository $articleRepository,PaginatorInterface $paginator,MyBadWordsFilter $badWordsFilter): Response
    {
        $theUserRole=null;
        if($this->getUser()){
           $theUserRole= $this->getUser()->getRoles()[0];
        }

     
        $em = $this->getDoctrine()->getManager();

        // getting all articals :
        $articles = $articleRepository->findAll();

        foreach ($articles as $article) {

            //Filter the subject artical BAD WORDs :
            $title = $article->getSujet();
            $filteredTitle = $badWordsFilter->filter($title);
            $article->setSujet($filteredTitle);

            //Filter the content artical BAD WORDs :
            $content = $article->getContenu();
            $filteredContent = $badWordsFilter->filter($content);
            $article->setContenu($filteredContent);
        }

        //pagination feature
        $articles = $paginator->paginate(
            $articles, /* query NOT result */
            $request->query->getInt('page', 1)/*page number*/,
            2/*limit per page*/
        );
        $articles->setCustomParameters([
            'align' => 'center', # center|right (for template: twitter_bootstrap_v4_pagination)
            'style' => 'bottom',
            'span_class' => 'whatever',
        ]);

        // Getting the best 3 articles from views :
        $Bestarticles = $em->getRepository(Article::class)->findBy([], ['views' => 'DESC'], 3);

        return $this->render('article/index.html.twig', [
            'articles' => $articles,
            'Bestarticles' => $Bestarticles,
             'roleunique' => $theUserRole

        ]);
    }

//ALL ARTICALS API :
    #[Route('/getAllArticals', name: 'app_article_getAll')]
    public function getAll(ArticleRepository $articleRepository,NormalizerInterface $normalizer,MyBadWordsFilter $badWordsFilter ): Response
    {
       
        $articles = $articleRepository->findAll();
        foreach ($articles as $article) {
            //Filter the subject artical BAD WORDs :
            $title = $article->getSujet();
            $filteredTitle = $badWordsFilter->filter($title);
            $article->setSujet($filteredTitle);

            //Filter the content artical BAD WORDs :
            $content = $article->getContenu();
            $filteredContent = $badWordsFilter->filter($content);
            $article->setContenu($filteredContent);
        }
        $articlesNormalizer = $normalizer->normalize($articles, 'json' , ['groups' => "articles"]);
        $json = json_encode($articlesNormalizer);
        return new Response($json);
    }
//DELETE ARTICAL BY ID API :
    #[Route('/removeArticleAPI/{id}', name: 'app_article_deleteJSON')]
    public function deleteAPI(Request $req,$id,NormalizerInterface $normalizer): Response
    {
        $em = $this->getDoctrine()->getManager();
        $article = $em ->getRepository(Article::class)->find($id);
        $em->remove($article);
        $em->flush();
        $articlesNormalizer = $normalizer->normalize($article, 'json' , ['groups' => "articles"]);
        $json = json_encode($articlesNormalizer);
        return new Response("Article deletet sucesseflly" . $json);
    }
//UPDATE ARTICAL BY ID API :
#[Route('/updateArticleAPI/{id}', name: 'app_article_updateJSON')]
public function updateAPI(Request $req,$id,NormalizerInterface $normalizer): Response
{
    $em = $this->getDoctrine()->getManager();
    $article = $em ->getRepository(Article::class)->find($id);
    $article->setSujet($req->get('sujet'));
    $article->setContenu($req->get('contenu'));
    $article->setImage($req->get('image'));
    $em->flush();
    $articlesNormalizer = $normalizer->normalize($article, 'json' , ['groups' => "articles"]);
    $json = json_encode($articlesNormalizer);
    return new Response("Article updated sucesseflly" . $json);
}
 //CRETING NEW ARTICAL API : 
    #[Route('/addArticleJSON/new', name: 'app_article_newJJSON', methods: ['GET'])]
    public function newJSON(Request $req,NormalizerInterface $normalizer ): Response
    {
        $em = $this->getDoctrine()->getManager();
        $article = new Article();
        $article->setSujet($req->get('sujet'));
        $article->setContenu($req->get('contenu'));
        $article->setImage($req->get('image'));

        $user = $em->getRepository(User::class)->find($req->get('user'));
        $article->setUserId($user);
        $em->persist($article);
        $em->flush();
        $articlesNormalizer=$normalizer->normalize($article,'json',['groups'=>"articles"]);
        $json= json_encode($articlesNormalizer);
        return new Response($json);
    }

 //ARTICAL By ID API : 
    #[Route('/getArtical/{id}', name: 'app_article_getById', methods: ['GET'])]
    public function getById(Article $article,$id,ArticleRepository $articleRepository,NormalizerInterface $normalizer,MyBadWordsFilter $badWordsFilter ): Response
    {
        $article->incrementViews();
                //Filter the SUBJECT artical BAD WORDs :
                $title = $article->getSujet();
                $filteredTitle = $badWordsFilter->filter($title);
                $article->setSujet($filteredTitle);
        
                //Filter the CONTENT artical BAD WORDs :
                $artical_content = $article->getContenu();
                $filteredArtContent = $badWordsFilter->filter($artical_content);
                $article->setContenu($filteredArtContent);

        $comments = $article->getComments();
           //Filter the comments artical BADWORDs :
           foreach ($comments as $comment) {
            $content = $comment->getContenu();
            $filteredContent = $badWordsFilter->filter($content);
            $comment->setContenu($filteredContent);
            }
        $articles = $articleRepository->find($id);
        $articlesNormalizer=$normalizer->normalize($articles,'json',['groups'=>"articles"]);
        $json= json_encode($articlesNormalizer);
        return new Response($json);
    }
// Creating new artical : 
    #[Route('/new', name: 'app_article_new', methods: ['GET', 'POST'])]
    public function new(UserRepository $userRepository,Request $request, ArticleRepository $articleRepository): Response
    {
        $theUserRole=null;
        if($this->getUser()){
           $theUserRole= $this->getUser()->getRoles()[0];
        }
        $id=null;
        if($this->getUser()){
            $user = $userRepository->findOneBy([ 
                 'email' => $this->getUser()->getUserIdentifier()
            ]);

           $id= $user;
        }
        $article = new Article();
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // upload the $file 
            $file = $form->get('image')->getData();
            // this is needed to safely include the file name as part of the URL
            $fileName = md5(uniqid()).'.'.$file->guessExtension();
            // Move the file to the directory where images are stored
            try {
                $file->move(
                    $this->getParameter('images_directory'),
                    $fileName
                );
            } catch (FileException $e) {
            // ... handle exception if something happens during file upload
            }
            $entityManager = $this->getDoctrine()->getManager();
            $article->setImage($fileName);
            // tell Doctrine you want to save the image
            $entityManager->persist($article);
            // actually executes the queries
            $entityManager->flush();
            $articleRepository->save($article, true);
            return $this->redirectToRoute('app_article_index', [
        'roleunique' => $theUserRole
    ], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('article/new.html.twig', [    
            'article' => $article,
            'form' => $form,
            'roleunique' => $theUserRole,
            'user' => $id->getId(),
        ]);
    }
// show a specific Artical details with ID :
    #[Route('/{id}', name: 'app_article_show', methods: ['GET'])]
    public function show($id,Article $article,MyBadWordsFilter $badWordsFilter,ArticleRepository $articleRepository): Response
    {
        $theUserRole=null;
        if($this->getUser()){
           $theUserRole= $this->getUser()->getRoles()[0];
        }
 
        // Getting the best articles from views :
        $em = $this->getDoctrine()->getManager();
        $Bestarticles = $em->getRepository(Article::class)->findBy([], ['views' => 'DESC'], 3);

        /** return all comments **/
        $comments = $article->getComments();

        //Filter the SUBJECT artical BAD WORDs :
        $title = $article->getSujet();
        $filteredTitle = $badWordsFilter->filter($title);
        $article->setSujet($filteredTitle);

        //Filter the CONTENT artical BAD WORDs :
        $artical_content = $article->getContenu();
        $filteredArtContent = $badWordsFilter->filter($artical_content);
        $article->setContenu($filteredArtContent);

        //Filter the comments artical BADWORDs :
        foreach ($comments as $comment) {
        $content = $comment->getContenu();
        $filteredContent = $badWordsFilter->filter($content);
        $comment->setContenu($filteredContent);
        }

        //Increment the views of articals :
        $article->incrementViews();
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->flush();

        return $this->render('article/show.html.twig', [
            'article' => $article,
            'comments' => $comments,
            'Bestarticles' => $Bestarticles,
        'roleunique' => $theUserRole
        ]);   
    }
// Download the specific Article in PDF Format:
    #[Route('/{id}/download', name: 'app_pdf_download')]
    public function pdf(Article $article)
    {

        /** return all comments **/
        $comments = $article->getComments();

        $article->incrementViews();
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->flush();
        // Configure Dompdf according to your needs
        $pdfOptions = new Options();
        $pdfOptions->set('defaultFont', 'Arial');
        
        // Instantiate Dompdf with our options
        $dompdf = new Dompdf($pdfOptions);
        
        // Retrieve the HTML generated in our twig file
        $html = $this->renderView('article/showPDF.html.twig', [
            'article' => $article,
            'comments' => $comments,
        ]);  
        
        // Load HTML to Dompdf
        $dompdf->loadHtml($html);
        
        // (Optional) Setup the paper size and orientation 'portrait' or 'portrait'
        $dompdf->setPaper('A4', 'portrait');

        // Render the HTML as PDF
        $dompdf->render();

        // Output the generated PDF to Browser (inline view)
        $dompdf->stream("Aidme Artical.pdf", [
            "Attachment" => true
        ]); 
        return new Response();
    }
// Edit a specific artical :
    #[Route('/{id}/edit', name: 'app_article_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Article $article, ArticleRepository $articleRepository): Response
    {
        $theUserRole=null;
        if($this->getUser()){
           $theUserRole= $this->getUser()->getRoles()[0];
        }

        
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $file = $form->get('image')->getData();
            $fileName = md5(uniqid()).'.'.$file->guessExtension();
            // // Move the file to the directory where brochures are stored
            try {
                $file->move(
                    $this->getParameter('images_directory'),
                    $fileName
                );
            } catch (FileException $e) {
                // ... handle exception if something happens during file upload
            }
            $entityManager = $this->getDoctrine()->getManager();
            $article->setImage($fileName);
            $entityManager->persist($article);
            $entityManager->flush();
            $articleRepository->save($article, true);
            return $this->redirectToRoute('app_article_show', ['id'=>$article->getId()], Response::HTTP_SEE_OTHER);
        }
        return $this->renderForm('article/edit.html.twig', [
            'article' => $article,
            'form' => $form,

        'roleunique' => $theUserRole
        ]);
    }
// Delete the specific Artical :
    #[Route('/{id}', name: 'app_article_delete', methods: ['POST'])]
    public function delete(Request $request, Article $article, ArticleRepository $articleRepository): Response
    {
        $theUserRole=null;
        if($this->getUser()){
           $theUserRole= $this->getUser()->getRoles()[0];
        }

        if ($this->isCsrfTokenValid('delete'.$article->getId(), $request->request->get('_token'))) {
            $articleRepository->remove($article, true);
        }

        return $this->redirectToRoute('app_article_index', [  

    'roleunique' => $theUserRole], Response::HTTP_SEE_OTHER);
    }
}