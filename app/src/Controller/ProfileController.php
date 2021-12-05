<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\Profile\EditPasswordType;
use App\Form\Profile\EditProfileType;
use App\Service\ImageService;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class ProfileController extends AbstractController
{
    private RequestStack $requestStack;
    private EntityManagerInterface $manager;
    private UserPasswordHasherInterface $userPasswordHasherInterface;
    private ImageService $imageService;

    public function __construct(RequestStack $requestStack, EntityManagerInterface $manager, UserPasswordHasherInterface $userPasswordHasherInterface, ImageService $imageService)
    {
        $this->requestStack = $requestStack;
        $this->manager = $manager;
        $this->userPasswordHasherInterface = $userPasswordHasherInterface;
        $this->imageService = $imageService;
    }

    #[Route('/profile', name: 'profile_edit')]
    #[IsGranted("ROLE_USER", message: 'Vous n\'avez pas le droit d\'acceder à cette page')]
    public function edit(): Response
    {
        $request = $this->requestStack->getCurrentRequest();
        /** @var User $user */
        $user = $this->getUser();

        $editAccountGeneralInformationForm = $this->createForm(EditProfileType::class, $user);
        $editAccountGeneralInformationForm->handleRequest($request);

        $editAccountPasswordForm = $this->createForm(EditPasswordType::class);
        $editAccountPasswordForm->handleRequest($request);

        if($editAccountGeneralInformationForm->isSubmitted() && $editAccountGeneralInformationForm->isValid())
        {
            /** @var UploadedFile $uploadedFile */
            $uploadedFile = $editAccountGeneralInformationForm['avatar']->getData();

            if($uploadedFile)
            {
                $newAvatarFilename = $this->imageService->uploadAvatar($uploadedFile);
                $user->setAvatarFilename($newAvatarFilename);
            }

            $this->manager->flush();

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
                $newPassword = $this->userPasswordHasherInterface->hashPassword($user, $newUserPasswordSubmited);
                $user->setPassword($newPassword);

                $this->manager->flush();

                $this->addFlash('success', 'Votre mot de passe a été modifié avec succes');

                return $this->redirectToRoute('profile_edit');
            }
            $this->addFlash('danger', 'Votre ancien mot de passe ne correspond pas');

            return $this->redirectToRoute('profile_edit');
        }

        return $this->renderForm('profile/profile.html.twig', compact('editAccountGeneralInformationForm', 'editAccountPasswordForm'));
    }
}
