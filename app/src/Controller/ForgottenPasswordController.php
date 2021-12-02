<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\Auth\RecoveryType;
use App\Form\ForgottenPasswordType;
use App\Form\ResetPasswordType;
use App\Repository\UserRepository;
use App\Service\EmailSender;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;

class ForgottenPasswordController extends AbstractController
{
    private EntityManagerInterface $entityManager;
    private RequestStack $requestStack;
    private UserRepository $userRepository;

    public function __construct(EntityManagerInterface $entityManager, UserRepository $userRepository, RequestStack $requestStack)
    {
        $this->entityManager = $entityManager;
        $this->userRepository = $userRepository;
        $this->requestStack = $requestStack;
    }

    #[Route('/forgottenPassword', name: 'forgotten_request', methods: ["GET", "POST"])]
    public function forgottenRequest(Request $request, TokenGeneratorInterface $tokenGenerator, EmailSender $emailSender): Response
    {
        $form = $this->createForm(ForgottenPasswordType::class);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $user = $this->userRepository->findOneBy(['username' => $form->get('username')->getData()]);

            if(!$user or !$user instanceof User)
            {
                $this->addFlash('success', 'Un email vous a été envoyé avec un lien pour redefinir votre mot de passe.');
                return $this->redirectToRoute('security_login');
            }

            $user->setForgotPasswordToken($tokenGenerator->generateToken())
                ->setForgotPasswordTokenRequestedAt(new \DateTime('now'))
                ->setForgotPasswordTokenMustBeVerifiedBefore(new \DateTime('+15 minutes'));

            $this->entityManager->flush();

            $emailSender->send([
                'recipient_email' => $user->getEmail(),
                'subject' => 'Modification de votre mot de passe',
                'html_template' => 'forgotten_password/forgotten_password_email.html.twig',
                'context' => [
                    'userId' => $user->getId(),
//                    'userToken' => $user->getForgotPasswordToken()
                ]
            ]);

            $this->addFlash('success', 'Un email vous a été envoyé avec un lien pour redefinir votre mot de passe.');

            return $this->redirectToRoute('security_login');
        }

        return $this->renderForm('forgotten_password/forgotten_password.html.twig', compact('form'));
    }

    #[Route('/forgottenPassword/{id<\d+>}/{token}', name: 'forgotten_get_credentials', methods: ['GET'])]
    public function getCredentialsFromEmailLink(User $user, string $token): Response
    {
        $session = $this->requestStack->getSession();
        $session->set('Reset-Password-Token-URL', $token);
        $session->set('Reset-Password-Username', $user->getUserIdentifier());

        return $this->redirectToRoute('forgotten_reset_password');
    }

    #[Route('/reset-password', name: "forgotten_reset_password", methods: ['GET', 'POST'])]
    public function resetPassword(Request $request, UserPasswordHasherInterface $passwordHasher)
    {
        [
            'token' => $token,
            'username' => $username
        ] = $this->getCredentialsFromSession();

        $user = $this->userRepository->findOneBy(['username' => $username]);

        if(!$user)
        {
            return $this->redirectToRoute('forgotten_request');
        }
        dd($user);
//        $forgotPasswordTokenMustBeVerifiedBefore = $user->getForgotPasswordTokenMustBeVerifiedBefore();
//        || ($this->isRequestedInTime($forgotPasswordTokenMustBeVerifiedBefore))
        if(($user->getForgotPasswordToken() === null) || ($user->getForgotPasswordToken() !== $token))
        {
            return $this->redirectToRoute('forgotten_request');
        }

        $form = $this->createForm(ResetPasswordType::class, $user);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $user->setPassword($passwordHasher->hashPassword($user, $form['newPassword']->getData()));
            $user->setForgotPasswordToken(null);

            $this->entityManager->flush();

            $this->removeCredentialsFromSession();

            $this->addFlash('success', 'Votre mot de passe a été modifié, vous pouvez à present vous connecter.');

            return $this->redirectToRoute('security_login');
        }

        return $this->renderForm('forgotten_password/reset_password.html.twig', compact('form'));
    }

    private function getCredentialsFromSession(): array
    {
        /** @var Session $session */
        $session = $this->requestStack->getSession();

        return [
            'token' => $session->get('Reset-Password-Token-URL'),
            'username' => $session->get('Reset-Password-Username'),
        ];
    }

    private function isRequestedInTime(\DateTime $token): bool
    {
        return (new \DateTime('now') > $token);
    }

    private function removeCredentialsFromSession(): void
    {
        $this->requestStack->getSession()->remove('Reset-Password-Token-URL');
        $this->requestStack->getSession()->remove('Reset-Password-Username');
    }

    private function passwordMustBeModifiedBefore(User $user): string
    {
        $passwordMustBeModifiedBefore = $user->getForgotPasswordTokenMustBeVerifiedBefore();
        return $passwordMustBeModifiedBefore->format('H\hi');
    }
}
