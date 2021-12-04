<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\Profile\EditPasswordType;
use App\Form\Profile\EditProfileType;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class ProfileController extends AbstractController
{
    #[Route('/profile', name: 'profile_edit')]
    #[IsGranted("ROLE_USER", message: 'Vous n\'avez pas le droit d\'acceder à cette page')]
    public function edit(RequestStack $requestStack, EntityManagerInterface $manager, UserPasswordHasherInterface $userPasswordHasherInterface): Response
    {
        $request = $requestStack->getCurrentRequest();
        /** @var User $user */
        $user = $this->getUser();

        $editAccountGeneralInformationForm = $this->createForm(EditProfileType::class, $user);
        $editAccountGeneralInformationForm->handleRequest($request);

        $editAccountPasswordForm = $this->createForm(EditPasswordType::class);
        $editAccountPasswordForm->handleRequest($request);

        if($editAccountGeneralInformationForm->isSubmitted() && $editAccountGeneralInformationForm->isValid())
        {
            $manager->flush();

            $this->addFlash('success', 'Vos modifications ont bien été prises en compte');

            return $this->redirectToRoute('profile_edit');
        }
//        dd($editAccountPasswordForm);
        if($editAccountPasswordForm->isSubmitted() && $editAccountPasswordForm->isValid())
        {
            $oldUserPassword = $user->getPassword();
            $oldUserPasswordSubmited = $editAccountPasswordForm['oldPassword']->getData();
            $newUserPasswordSubmited = $editAccountPasswordForm['plainPassword']->getData();

            if(password_verify($oldUserPasswordSubmited, $oldUserPassword))
            {
                $newPassword = $userPasswordHasherInterface->hashPassword($user, $newUserPasswordSubmited);
                $user->setPassword($newPassword);
                
                $manager->flush();

                $this->addFlash('success', 'Votre mot de passe a été modifié avec succes');

                return $this->redirectToRoute('profile_edit');
            }
            $this->addFlash('danger', 'Votre ancien mot de passe ne correspond pas');

            return $this->redirectToRoute('profile_edit');
        }

        return $this->renderForm('profile/profile.html.twig', compact('editAccountGeneralInformationForm', 'editAccountPasswordForm'));
    }
}
