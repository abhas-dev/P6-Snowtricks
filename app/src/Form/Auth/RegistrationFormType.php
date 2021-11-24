<?php

namespace App\Form\Auth;

use App\Entity\User;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'label' => 'Email',
                'attr' => [
                    'placeholder' => 'johdoe@email.fr'
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => "Veuillez saisir un email",
                    ]),
                    new Email(['message' => 'Veuillez saisir un email valide'])
                ],
                'required' => false
            ])
            ->add('username', TextType::class, [
                'label' => "Nom d'utilisateur",
                'attr' => [
                    'placeholder' => "johndoe"
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => "Veuillez saisir un nom d'utilisateur",
                    ]),
                    new Length([
                        'min' => 4,
                        'minMessage' => "Veuillez saisir un nom d'utilisateur avec plus de {{ limit }} caracteres"
                    ])
                ],
                'required' => false
            ])
            ->add('plainPassword', RepeatedType::class, [
                'label' => 'Confirmation',
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                'mapped' => false,
                'type' => PasswordType::class,
                'attr' => [
                    'autocomplete' => 'new-password',
                    'class' => 'password-field'
                ],
                'first_options' => ['label' => 'Mot de passe'],
                'second_options' => ['label' => 'Confirmation'],
                'invalid_message' => 'Vous avez saisi deux mots de passe differents.',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez saisir le mot de passe',
                    ]),
                    new Length([
                        'min' => 8,
                        'minMessage' => 'Votre mot de passe doit avoir un minimum de {{ limit }} caracteres',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ]),
                ],
                'required' => false
            ])

            ->add('agreeTerms', CheckboxType::class, [
                'label' => 'Accepter nos conditions',
                'mapped' => false,
                'constraints' => [
                    new IsTrue([
                        'message' => 'Vous devez accepter nos conditions.',
                    ]),
                ],
                'required' => false
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'csrf_protection' => false,
            'constraints' => [
            new UniqueEntity(['fields' => ['email'], 'message' => 'La valeur {{ value }} a deja été enregistrée']),
            new UniqueEntity('username', 'La valeur {{ value }} a deja été enregistrée')
            ]
        ]);
    }
}
