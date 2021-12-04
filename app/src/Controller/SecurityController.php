<?php

namespace App\Controller;

use App\Form\Auth\LoginType;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

#[Route(name: "security_")]
class SecurityController extends AbstractController
{
    protected UserRepository $repository;

    public function __construct(UserRepository $repository)
    {
        $this->repository = $repository;
    }

    #[Route('/login', name: "login")]
    public function login(AuthenticationUtils $authenticationUtils, FormFactoryInterface $formFactory): Response
    {
        $user = $this->getUser();
         if ($user) {
             return $this->redirectToRoute('homepage');
         }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        // Pour pouvoir modifier le prefixe du formulaire (login['username'])
        $form = $formFactory->createNamed('',LoginType::class, ['username' => $lastUsername]);

        return $this->renderForm('security/login.html.twig', compact('lastUsername', 'error', 'form'));
    }


    #[Route('/logout', name: "logout")]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

}
