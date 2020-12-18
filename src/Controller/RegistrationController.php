<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Swift_Mailer;
use Swift_Message;

class RegistrationController extends AbstractController
{
    /**
     * @Route("/register", name="app_register")
     */
    public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder, \Swift_Mailer $mailer): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setPassword(
                $passwordEncoder->encodePassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();
            

            // do anything else you need here, like send an email
            $message = (new \Swift_Message('Hello', $user->getNom))
            ->setFrom('virginielenoir.pro@gmail.com')
            ->setTo($user->getEmail())
            ->setBody('Bienvenue sur le site MediaTimes');

            // // you can remove the following code if you don't define a text version for your emails
            // ->addPart(
            //     $this->renderView(
            //         // templates/emails/registration.txt.twig
            //         'emails/registration.txt.twig',
            //         ['name' => $name]
            //     ),
            //     'text/plain'
            // )
            // ;
            $mailer->send($message);

            return $this->redirectToRoute('home');
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }
}
