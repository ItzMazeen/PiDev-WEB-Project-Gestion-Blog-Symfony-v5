<?php

namespace App\Controller;

use App\Entity\Article;
use App\Form\ArticleType;
use App\Repository\ArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;
use App\Service\MyBadWordsFilter;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

#[Route('/adminArticles')]
class ArticleAdminController extends AbstractController
{
    
//Articals Index :
    #[Route('/', name: 'app_article_index1', methods: ['GET'])]
    public function index(Request $request,ArticleRepository $articleRepository,PaginatorInterface $paginator,MyBadWordsFilter $badWordsFilter): Response
    {
        $articles = $articleRepository->findAll();
        // Get the number of articles
        $numArticles = count($articles);
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
        // Number of users : 
        $query = "SELECT COUNT(DISTINCT c.userId) FROM App\Entity\Article c";
        $countUsers = $entityManager->createQuery($query)->getSingleScalarResult();
        // Get the count of articles created this week
        $query = "SELECT COUNT(*) FROM article WHERE created_at >= '{$weekStart->format('Y-m-d H:i:s')}'";
        $weekCount = $entityManager->getConnection()->query($query)->fetchOne();
        // Get the count of articles created today
        $query = "SELECT COUNT(*) FROM article WHERE created_at BETWEEN '{$todayStart->format('Y-m-d H:i:s')}' AND '{$todayEnd->format('Y-m-d H:i:s')}'";
        $todayCount = $entityManager->getConnection()->query($query)->fetchOne();
        
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
        
        return $this->render('article_admin/index.html.twig', [
            'articles' => $articles,
            'numArticles' => $numArticles,
            'todayCount' => $todayCount,
            'weekCount' => $weekCount,
            'countUsers' => $countUsers
        ]);
    }

    #[Route('/adminAPI', name: 'app_article_adminAPI', methods: ['GET'])]
    public function adminAPI(NormalizerInterface $normalizer,Request $request,ArticleRepository $articleRepository,MyBadWordsFilter $badWordsFilter): Response
    {
        $articles = $articleRepository->findAll();
        // Get the number of articles
        $numArticles = count($articles);
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
        // Number of users : 
        $query = "SELECT COUNT(DISTINCT c.userId) FROM App\Entity\Article c";
        $countUsers = $entityManager->createQuery($query)->getSingleScalarResult();
        // Get the count of articles created this week
        $query = "SELECT COUNT(*) FROM article WHERE created_at >= '{$weekStart->format('Y-m-d H:i:s')}'";
        $weekCount = $entityManager->getConnection()->query($query)->fetchOne();
        // Get the count of articles created today
        $query = "SELECT COUNT(*) FROM article WHERE created_at BETWEEN '{$todayStart->format('Y-m-d H:i:s')}' AND '{$todayEnd->format('Y-m-d H:i:s')}'";
        $todayCount = $entityManager->getConnection()->query($query)->fetchOne();      
        $data = [
            'numArticles' => $numArticles,
            'countUsers' => $countUsers,
            'weekCount' => $weekCount,
            'todayCount' => $todayCount
        ];
        
        $articlesNormalizer = $normalizer->normalize($data, 'json' , []);
        $json = json_encode($articlesNormalizer);
        return new Response($json);
        
    }

// create new artical :
    #[Route('/new', name: 'app_article_new1', methods: ['GET', 'POST'])]
    public function new(Request $request, ArticleRepository $articleRepository): Response
    {
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
            return $this->redirectToRoute('app_article_index1', [], Response::HTTP_SEE_OTHER);
        }
        return $this->renderForm('article_admin/new.html.twig', [
            'article' => $article,
            'form' => $form,
        ]);
    }
// show specific article details :
    #[Route('/{id}', name: 'app_article_show1', methods: ['GET'])]
    public function show(Article $article): Response
    {
        return $this->render('article_admin/show.html.twig', [
            'article' => $article,
        ]);
    }
// delete specific article :
    #[Route('/{id}', name: 'app_article_delete1', methods: ['POST'])]
    public function delete(Request $request, Article $article, ArticleRepository $articleRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$article->getId(), $request->request->get('_token'))) {
            $articleRepository->remove($article, true);
        }
        return $this->redirectToRoute('app_article_index1', [], Response::HTTP_SEE_OTHER);
    }
}
