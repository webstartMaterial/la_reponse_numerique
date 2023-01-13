<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Article;
use App\Entity\Category;

use Doctrine\Persistence\ManagerRegistry as PersistenceManagerRegistry; 


class ArticlesController extends AbstractController
{
    #[Route('/articles/{id}', name: 'articles')]
    public function index(Request $request, PersistenceManagerRegistry $doctrine): Response
    {

        $idCategory = $request->get("id");
        $articles = $doctrine->getRepository(Article::class)->findByCategory($idCategory);
        $category = $doctrine->getRepository(Category::class)->find($idCategory);
        
        return $this->render('articles/index.html.twig', [
            'listArticles' => $articles,
            'categorySelected' => $category
        ]);
    }

    #[Route('/articles/{subject}', name: 'articles_by_subjects')]
    public function articlesBySubject(Request $request, PersistenceManagerRegistry $doctrine): Response
    {

        $idCategory = $request->get("id");
        $articles = $doctrine->getRepository(Article::class)->findByCategory($idCategory);
        $category = $doctrine->getRepository(Category::class)->find($idCategory);
        
        return $this->render('articles/index.html.twig', [
            'listArticles' => $articles,
            'categorySelected' => $category
        ]);
    }
}
