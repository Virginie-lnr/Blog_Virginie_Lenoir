<?php

namespace App\Controller;

use App\Entity\Article;
use App\Form\ArticleType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ArticleController extends AbstractController
{
    /**
     * @Route("/articles", name="app_articles")
     */
    public function showAll(): Response
    {
        $allArticles = $this->getDoctrine()->getRepository(Article::class)->findAll(); 

        return $this->render('article/showall.html.twig', [
            'articles' => $allArticles,
        ]);
    }

    /**
     * @Route("/article/{id<\d+>}", name="app_article")
     */
    public function show($id){
        $article = $this->getDoctrine()->getRepository(Article::class)->find($id); 

        return $this->render('article/show.html.twig', [
            'article' => $article
        ]);
    }

    /**
     * @Route("article/create", name="app_createarticle")
     */
    public function create(Request $request){

        $article = new Article();


        $form = $this->createForm(ArticleType::class, $article); 
        $form->handleRequest($request);   

        if($form->isSubmitted() && $form->isValid()){
            $article->setDateCreation(new \DateTime('now'));
            $manager = $this->getDoctrine()->getManager(); 
            $manager->persist($article); 
            $manager->flush(); 

            return $this->redirectToRoute('app_articles'); 
        }
        return $this->render('article/create.html.twig', [
            'form' => $form->createView(), 
        ]);
    }

    /**
     * @Route("article/update/{id<\d+>}", name="app_articleupdate")
     */
    public function update(Request $request, $id){
        $manager = $this->getDoctrine()->getManager(); 

        $article = $manager->getRepository(Article::class)->find($id); 

        $form = $this->createForm(ArticleType::class, $article); 
        $form->handleRequest($request); 

        if($form->isSubmitted() && $form->isValid()){
            $article->setDateCreation(new \DateTime('now')); 
            $manager->persist($article); 
            $manager->flush(); 
            
            return $this->redirectToRoute('app_articles');
        }

        return $this->render('article/create.html.twig', [
            'form' => $form->createView(), 
            'article' => $article
        ]);
    }

    /**
     * @Route("article/delete/{id<\d+>}", name="app_articledelete")
     */
    public function delete($id){
        $manager = $this->getDoctrine()->getManager(); 

        $article = $manager->getRepository(Article::class)->find($id); 

        $manager->remove($article); 
        $manager->flush();

        return $this->redirectToRoute('app_articles');
    }
}
