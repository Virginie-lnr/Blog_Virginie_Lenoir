<?php

namespace App\Controller;

use App\Entity\Article;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(): Response
    {
        $dernierArticle = $this->getDoctrine()->getRepository(Article::class)->findOneBy([], ['id' => 'DESC']); 

        return $this->render('home/home.html.twig', [
            'dernierArticle' => $dernierArticle
        ]);
    }
}
