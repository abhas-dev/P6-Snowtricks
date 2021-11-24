<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\Auth\RegistrationFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class RegistrationController extends AbstractController
{
    #[Route('/register', name: 'security_register')]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasherInterface): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setPassword(
            $userPasswordHasherInterface->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );
            $user->setIsVerified('false');
            $user->setCreatedAt(new \DateTime('now'));

            if($form['agreeTerms']->getData()){
                $user->agreeTermes();
            }

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();
            // do anything else you need here, like send an email
            $this->addFlash(
                'success',
                "Votre compte a bien été enregistré. Veuillez confirmez votre adresse en cliquant sur lien dans le mail reçu."
            );

            return $this->redirectToRoute('homepage');
        }

        return $this->renderForm('registration/register.html.twig', ['registrationForm' => $form]);
    }
}
