<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Category;
use App\Entity\Newsletter;
use App\Entity\Article;
use Doctrine\Persistence\ManagerRegistry as PersistenceManagerRegistry; 
use Knp\Component\Pager\PaginatorInterface;



class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(PersistenceManagerRegistry $doctrine, PaginatorInterface $paginator, Request $request): Response
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

        $categories = $doctrine->getRepository(Category::class)->findAll();
        $articles = $doctrine->getRepository(Article::class)->findAll();

        $articles = $paginator->paginate(
            $articles, /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            5 /*limit per page*/
        );

        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
            'listCategories' => $categories,
            'listArticles' => $articles,
        ]);
    }
}
