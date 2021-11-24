<?php

namespace App\Form\Auth;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;

class LoginType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
//            ->add('email', EmailType::class, [
//                'label' => 'Adresse email',
//                'attr' => ['placeholder' => 'Adresse email de connexion'],
//                'required' => false
//            ])
            ->add('username', TextType::class, [
                'label' => 'Nom d\'utilisateur',
                "attr" => [
                    'placeholder' => 'Nom d\'utilisateur',
                    'class' => 'form-control',
                    'autofocus' => true
                ],
                'required' => false
                ])
            ->add('password', PasswordType::class, [
                'label' => 'Mot de passe',
                'attr' => [
                    'placeholder' => 'Mot de passe',
                    'class' => 'form-control'
                ],
                'required' => false,
                'constraints' => [
                    new Length(['min' => 8, 'minMessage' => 'La longueur minimum du mot de passe est {{ min }} caracteres'])
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => null,
        ]);
    }
}
