<?php

namespace App\Controller;

use App\Entity\Categorie;
use App\Form\CategorieType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

class CategorieController extends AbstractController
{

    /**
     * @Route("/categorie/create", name="app_categoriecreate")
     * @isGranted("ROLE_ADMIN")
     */
    public function create(Request $request){
        $categorie = new Categorie(); 

        $form = $this->createForm(CategorieType::class, $categorie);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $manager = $this->getDoctrine()->getManager(); 
            $manager->persist($categorie); 
            $manager->flush(); 

            return $this->redirectToRoute('app_categories'); 
        }

        return $this->render('categorie/create.html.twig', [
            'form' => $form->createView()
        ]);

    }

    /**
     * @Route("/categorie/update/{id<\d+>}", name="app_categorieupdate")
     * @isGranted("ROLE_ADMIN")
     */
    public function update(Request $request, $id){
        $manager = $this->getDoctrine()->getManager(); 

        $categorie = $manager->getRepository(Categorie::class)->find($id);

        $form = $this->createForm(CategorieType::class, $categorie);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $manager->persist($categorie); 
            $manager->flush(); 

            return $this->redirectToRoute('app_categories'); 
        }

        return $this->render('categorie/create.html.twig', [
            'form' => $form->createView(), 
            'categorie' => $categorie
        ]);

    }

    /**
     * @Route("/categories", name="app_categories")
     * @isGranted("ROLE_ADMIN")
     */
    public function showAll(){
        $categories = $this->getDoctrine()->getRepository(Categorie::class)->findAll(); 

        return $this->render('categorie/showall.html.twig', [
            'categories' => $categories
        ]);
    }

    /**
     * @Route("/categorie/{id<\d+>}", name="app_categorie")
     *
     */
    public function show($id){
        $categorie = $this->getDoctrine()->getRepository(Categorie::class)->find($id);
        $categories = $this->getDoctrine()->getRepository(Categorie::class)->findAll(); 
        // dd($categories);
        return $this->render('categorie/show.html.twig', [
            'categorie' => $categorie, 
            'categories' => $categories
        ]); 
    }

    /**
     * @Route("/categorie/delete/{id<\d+>}", name="app_categoriedelete")
     * @isGranted("ROLE_ADMIN")
     */
    public function delete($id){
        $manager = $this->getDoctrine()->getManager(); 
        $categorie = $manager->getRepository(Categorie::class)->find($id); 
        $manager->remove($categorie); 
        $manager->flush();

        return $this->redirectToRoute('app_categories');
    }
}
