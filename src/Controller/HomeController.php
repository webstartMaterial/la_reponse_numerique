<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Category;
use App\Entity\Article;
use Doctrine\Persistence\ManagerRegistry as PersistenceManagerRegistry; 


class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(PersistenceManagerRegistry $doctrine): Response
    {

        $categories = $doctrine->getRepository(Category::class)->findAll();
        $articles = $doctrine->getRepository(Article::class)->findAll();

        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
            'listCategories' => $categories,
            'listArticles' => $articles,
        ]);
    }
}
