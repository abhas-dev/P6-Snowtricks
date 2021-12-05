<?php

namespace App\Form\Auth;

use App\Entity\User;
use App\Form\FormExtension\RepeatedPasswordType;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class RegistrationFormType extends AbstractType
{
    /**
     * @param FormBuilderInterface<callable> $builder
     * @param array<mixed> $options
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'label' => 'Email',
                'attr' => [
                    'placeholder' => 'johdoe@email.fr',
                    'autofocus' => true
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => "Veuillez saisir un email",
                    ]),
                    new Email(['message' => 'Veuillez saisir un email valide'])
                ]
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
                ]
            ])
            ->add('fullname', TextType::class, [
                'label' => "Nom complet",
                'attr' => [
                    'placeholder' => "John Doe"
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => "Veuillez saisir un nom",
                    ])
                ]
            ])
            ->add('plainPassword', RepeatedPasswordType::class)

            ->add('agreeTerms', CheckboxType::class, [
                'label' => 'Accepter nos conditions',
                'mapped' => false,
                'constraints' => [
                    new IsTrue([
                        'message' => 'Vous devez accepter nos conditions.',
                    ]),
                ]
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
            ],
            'attr' => ['novalidate' => true]
        ]);
    }
}
