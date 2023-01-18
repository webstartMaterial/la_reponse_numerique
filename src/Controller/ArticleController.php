<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Article;
use App\Entity\Newsletter;
use App\Entity\Category;
use Knp\Component\Pager\PaginatorInterface;
use Doctrine\Persistence\ManagerRegistry as PersistenceManagerRegistry; 

class ArticleController extends AbstractController
{
    #[Route('/article/{id}', name: 'article')]
    public function index(Request $request, PersistenceManagerRegistry $doctrine, PaginatorInterface $paginator): Response
    {

        if (!empty($request->request->get('newsletter'))) {
            
            $email = strtolower($request->request->get('newsletter'));
            $frequence = $request->request->get('frequence');

            $entityManager = $doctrine->getManager();
            $newsletter = new Newsletter();
            $newsletter->setEmail($email);
            $newsletter->setFrequence($frequence);

            $entityManager->persist($newsletter);
            $entityManager->flush();

            $this->addFlash('secondary', 'Vous avez bien été inscrit à notre newsletter !');

        }


        $idArticle = $request->get("id");
        $article = $doctrine->getRepository(Article::class)->find($idArticle);
        $relatedArticles = $doctrine->getRepository(Article::class)->findRelatedArticles($article->getCategory(), $idArticle);
        
        $relatedArticles = $paginator->paginate(
            $relatedArticles, /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            3 /*limit per page*/
        );


        return $this->render('article/index.html.twig', [
            'article' => $article,
            'relatedArticles' => $relatedArticles,
            'categorySelected' => $article->getCategory()
        ]);
    }
}
