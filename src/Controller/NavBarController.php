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

class NavBarController extends AbstractController
{
    #[Route('/navbar', name: 'navbar')]
    public function index(PersistenceManagerRegistry $doctrine, Request $request, PaginatorInterface $paginator): Response
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
            $articles = $doctrine->getRepository(Article::class)->findAll();

            $articles = $paginator->paginate(
                $articles, /* query NOT result */
                $request->query->getInt('page', 1), /*page number*/
                5 /*limit per page*/
            );
            
            return $this->render('articles/index.html.twig', [
                'listArticles' => $articles,
                'searchSelect' => null,
                'categorySelected' => null
            ]);

        }

        $search = null;
        // dd($request->request->get('search'));
        if (!empty($request->request->get('search'))) {

            $search = strtolower($request->request->get('search'));
            $articles = $doctrine->getRepository(Article::class)->findBySearch($search);

            $articles = $paginator->paginate(
                $articles, /* query NOT result */
                $request->query->getInt('page', 1), /*page number*/
                5 /*limit per page*/
            );

            return $this->render('articles/index.html.twig', [
                'listArticles' => $articles,
                'searchSelect' => $search,
                'categorySelected' => null
            ]);

        }

        $categories = $doctrine->getRepository(Category::class)->findAll();

        return $this->render('navbar/nav.html.twig', [
            'listCategories' => $categories,
        ]);
    }
}
