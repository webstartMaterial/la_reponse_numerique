<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Article;
use App\Entity\Category;
use Knp\Component\Pager\PaginatorInterface;

use Doctrine\Persistence\ManagerRegistry as PersistenceManagerRegistry; 


class ArticlesController extends AbstractController
{
    #[Route('/articles/{id}', name: 'articles')]
    public function index(Request $request, PersistenceManagerRegistry $doctrine, PaginatorInterface $paginator): Response
    {

        $idCategory = $request->get("id");
        $articles = $doctrine->getRepository(Article::class)->findByCategory($idCategory);
        $category = $doctrine->getRepository(Category::class)->find($idCategory);

        $articles = $paginator->paginate(
            $articles, /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            5 /*limit per page*/
        );

        
        return $this->render('articles/index.html.twig', [
            'listArticles' => $articles,
            'categorySelected' => $category,
            'searchSelect' => null
        ]);
    }

    #[Route('/articles/search/{subject}', name: 'articles_by_subjects')]
    public function articlesBySubject(Request $request, PersistenceManagerRegistry $doctrine, PaginatorInterface $paginator): Response
    {

        $subject = strtolower($request->get("subject"));
        $articles = $doctrine->getRepository(Article::class)->findBySearch($subject);
        
        $articles = $paginator->paginate(
            $articles, /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            5 /*limit per page*/
        );

        return $this->render('articles/index.html.twig', [
            'listArticles' => $articles,
            'searchSelect' => $subject,
            'categorySelected' => null

        ]);
    }

    #[Route('/articles', name: 'all_articles')]
    public function allArticles(Request $request, PersistenceManagerRegistry $doctrine, PaginatorInterface $paginator): Response
    {

        $articles = $doctrine->getRepository(Article::class)->findAll();

        $articles = $paginator->paginate(
            $articles, /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            5 /*limit per page*/
        );

        
        return $this->render('articles/index.html.twig', [
            'listArticles' => $articles,
            'categorySelected' => null,
            'searchSelect' => null
        ]);
    }

}
