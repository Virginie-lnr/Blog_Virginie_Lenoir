<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\PromoteAdminType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    /**
     * @Route("/login", name="app_login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // if ($this->getUser()) {
        //     return $this->redirectToRoute('target_path');
        // }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    /**
     * @Route("/logout", name="app_logout")
     */
    public function logout()
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

    /**
     * @Route("/users", name="app_users")
     * @IsGranted("ROLE_ADMIN")
     */
    public function showall(){
        $allUsers = $this->getDoctrine()->getRepository(User::class)->findAll(); 
        return $this->render('security/showall.html.twig', [
            'users' => $allUsers
        ]); 
    }

    /**
     * @Route("/user/delete/{id<\d+>}", name="app_userdelete")
     * @IsGranted("ROLE_ADMIN")
     */
    public function delete($id){
        $manager = $this->getDoctrine()->getManager(); 

        $user = $manager->getRepository(User::class)->find($id);
        $manager->remove($user); 
        $manager->flush(); 

        return $this->redirectToRoute('app_users'); 
    }

    /**
     * @Route("/admin/promouvoir/{id<\d+>}", name="app_promoteadmin")
     * @IsGranted("ROLE_ADMIN")
     */
    public function promoteAdmin(Request $request, $id){
        $secret = 'abracadabra'; 

        $form = $this->createForm(PromoteAdminType::class); 
        $form->handleRequest($request);

        $manager = $this->getDoctrine()->getManager(); 
        $user = $manager->getRepository(User::class)->find($id); 

        if(!$user){
            throw $this->createNotFoundException("Impossible de trouver l'utilisateur avec l'id : $id"); 
        };

        if($form->isSubmitted() && $form->isValid()){
            if($form->get("secret")->getData() != $secret){
                throw $this->createNotFoundException("Vous n'avez pas le bon mot de passe pour être administrateur"); 
            }
            $user->setRoles(["ROLE_ADMIN"]); 
            
            $manager->persist($user); 
            $manager->flush(); 

            return $this->redirectToRoute('app_users'); 
        }
        return $this->render('security/promoteadmin.html.twig', [
            'form' => $form->createView(), 
            'user' => $user
        ]);
    }

    /**
     * @Route("/admin/retirer-role-admin/{id<\d+>}", name="app_removeadmin")
     * @IsGranted("ROLE_ADMIN")
     */
    public function removeAdmin(Request $request, $id){
        $secret = 'abracadabra'; 

        $form = $this->createForm(PromoteAdminType::class); 
        $form->handleRequest($request);

        $manager = $this->getDoctrine()->getManager(); 
        $user = $manager->getRepository(User::class)->find($id); 

        if(!$user){
            throw $this->createNotFoundException("Impossible de trouver l'utilisateur avec l'id : $id"); 
        };

        if($form->isSubmitted() && $form->isValid()){
            if($form->get("secret")->getData() != $secret){
                throw $this->createNotFoundException("Vous n'avez pas le bon mot de passe pour être administrateur"); 
            }
            $user->setRoles([]); 
            
            $manager->persist($user); 
            $manager->flush(); 

            return $this->redirectToRoute('app_users'); 
        }
        return $this->render('security/promoteadmin.html.twig', [
            'form' => $form->createView(), 
            'user' => $user
        ]);
    }

}
