<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Article;
use App\Entity\Category;

use Doctrine\Persistence\ManagerRegistry as PersistenceManagerRegistry; 

class ArticleController extends AbstractController
{
    #[Route('/article/{id}', name: 'article')]
    public function index(Request $request, PersistenceManagerRegistry $doctrine): Response
    {

        $idArticle = $request->get("id");
        $article = $doctrine->getRepository(Article::class)->find($idArticle);
        $relatedArticles = $doctrine->getRepository(Article::class)->findRelatedArticles($article->getCategory(), $idArticle);

        return $this->render('article/index.html.twig', [
            'article' => $article,
            'relatedArticles' => $relatedArticles,
        ]);
    }
}
