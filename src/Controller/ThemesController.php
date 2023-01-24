<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry as PersistenceManagerRegistry; 
use App\Entity\Category;
use App\Entity\Article;
use Knp\Component\Pager\PaginatorInterface;
use App\Entity\Newsletter;

class ThemesController extends AbstractController
{
    #[Route('/themes', name: 'themes')]
    public function index(PersistenceManagerRegistry $doctrine, Request $request, PaginatorInterface $paginator): Response
    {

        $categories = $doctrine->getRepository(Category::class)->findAll();

        return $this->render('themes/themes.html.twig', [
            'listCategories' => $categories,
        ]);
    }
}
