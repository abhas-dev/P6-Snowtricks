<?php

namespace App\Form\Profile;

use App\Entity\User;
use App\Form\FormExtension\RepeatedPasswordType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class EditPasswordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('oldPassword', PasswordType::class, [
                'label' => 'Ancien mot de passe',
                'mapped' => false,
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
                ]
            ])
            ->add('plainPassword', RepeatedPasswordType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'csrf_protection' => false,
            // the name of the hidden HTML field that stores the token
//            'csrf_field_name' => '_token',
//            // an arbitrary string used to generate the value of the token
//            // using a different string for each form improves its security
//            'csrf_token_id'   => 'change_password',
            'attr' => [
                'novalidate' => true
            ]
//            'allow_extra_fields' => true,
        ]);
    }

    public function getBlockPrefix()
    {
        return 'profile_change_password';
    }
}
