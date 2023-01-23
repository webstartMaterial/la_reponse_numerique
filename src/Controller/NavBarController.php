<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry as PersistenceManagerRegistry; 
use App\Entity\Category;

class NavBarController extends AbstractController
{
    #[Route('/navbar', name: 'navbar')]
    public function index(PersistenceManagerRegistry $doctrine): Response
    {
        
        $categories = $doctrine->getRepository(Category::class)->findAll();

        return $this->render('navbar/nav.html.twig', [
            'listCategories' => $categories,
        ]);
    }
}
