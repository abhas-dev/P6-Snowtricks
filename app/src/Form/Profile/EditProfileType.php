<?php

namespace App\Form\Profile;

use App\Entity\User;
use App\Form\FormExtension\RepeatedPasswordType;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;

class EditProfileType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'label' => 'Email',
                'empty_data' => '',
                'attr' => ['placeholder' => 'me@myemail.com'],
                'constraints' => [
                    new NotBlank([
                        'message' => "Veuillez saisir un email",
                    ]),
                    new Email(['message' => 'Veuillez saisir un email valide'])
                ]
            ])
//            ->add('password', RepeatedPasswordType::class)
            ->add('fullname', TextType::class, [
                'label' => 'Nom Complet',
                'attr' => ['placeholder' => 'Nom Prenom...'],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'attr' => ['novalidate' => true],
            'constraints' => [
                new UniqueEntity(['fields' => ['email'], 'message' => 'La valeur {{ value }} a deja été enregistrée'])
            ],
        ]);
    }

    public function getBlockPrefix()
    {
        return 'profile_informations_edit';
    }
}
