<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\Auth\RegistrationFormType;
use App\Service\EmailSender;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;

class RegistrationController extends AbstractController
{
    private UserPasswordHasherInterface $userPasswordHasherInterface;
    private EmailSender $emailVerifier;
    private TokenGeneratorInterface $tokenGenerator;
    private EntityManagerInterface $entityManager;

    public function __construct(
        UserPasswordHasherInterface $userPasswordHasherInterface,
        EmailSender                 $emailVerifier,
        TokenGeneratorInterface     $tokenGenerator,
        EntityManagerInterface      $entityManager
    )
    {

        $this->userPasswordHasherInterface = $userPasswordHasherInterface;
        $this->emailVerifier = $emailVerifier;
        $this->tokenGenerator = $tokenGenerator;
        $this->entityManager = $entityManager;
    }

    #[Route('/register', name: 'security_register')]
    public function register(Request $request): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $registrationToken = $this->tokenGenerator->generateToken();
//            $user->setAccountMustBeVerifiedBefore(new \DateInterval())
            $user->setRegistrationToken($registrationToken);
            // encode the plain password
            $user->setPassword(
            $this->userPasswordHasherInterface->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );
            $user->setIsVerified(false);
            $user->setAvatarFilename('avatar-default.png');
            $user->setCreatedAt(new \DateTime('now'));

            if($form['agreeTerms']->getData()){
                $user->agreeTermes();
            }

//            $entityManager = $this->getDoctrine()->getManager();
            $this->entityManager->persist($user);
            $this->entityManager->flush();
            // do anything else you need here, like send an email

            // generate a signed url and email it to the user
            $this->emailVerifier->send([
                'recipient_email' => $user->getEmail(),
                'subject' => 'Merci de confirmer votre adresse mail pour activer votre compte utilisateur',
                'html_template' => 'registration/confirmation_email.html.twig',
                'context' => [
                    'userId' => $user->getId(),
                    'registrationToken' => $registrationToken,
//                    'tokenLifeTime' => $user->getAccountMustBeVerifiedBefore()->format('d/m/Y à H:i')
                ]
            ]);

            $this->addFlash(
                'success',
                "Votre compte a bien été enregistré. Veuillez confirmer votre adresse en cliquant sur lien dans le mail reçu."
            );


            return $this->redirectToRoute('homepage');
        }

        return $this->renderForm('registration/register.html.twig', ['registrationForm' => $form]);
    }

    #[Route('/registration-verify/{id<\d+>?0}/{token}', name: 'registration_verify_account')]
    public function verifyAccount(User $user, string $token): Response
    {
        // || ($this->isNotRequestedInTime($user->getAccountMustBeVerifiedBefore()))
        if(($user->getRegistrationToken() === null) || ($user->getRegistrationToken() !== $token)){
            throw $this->createAccessDeniedException();
        }

        $user->setIsVerified(true);
        $user->setRegistrationToken(null);

        $this->entityManager->flush();
        $this->addFlash('success', 'Felicitations, votre compte a été validé avec succès. Vous pouvez maintenant vous connecter.');

        return $this->redirectToRoute('security_login');
    }
}
