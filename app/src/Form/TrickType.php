<?php

namespace App\Form;

use App\Entity\Trick;
use App\Entity\TrickCategory;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class TrickType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class,[
                'label' => 'Nom du trick',
                'attr' => [
                    'placeholder' => 'Nom du trick'
                ],
                'required' => false
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description du trick',
                'attr' => [
                    'placeholder' => 'Description du trick'
                ],
                'required' => false
            ])
            ->add('trickCategory', EntityType::class, [
                'label' => 'Catégorie',
                'placeholder' => '-- Choisir une catégorie --',
                'class' => TrickCategory::class,
                'choice_label' => 'name',
                'required' => false

            ])
//            ->add('trickImages', FileType::class, [
//                'label' => 'Image',
//                'mapped' => false,
//                'multiple' => true,
//                'required' => false,
////                'constraints' => [
////                    new File([
////                        'mimeTypes' => [
////                            'image/jpeg',
////                            'image/jpg',
////                            'image/png'
////                        ],
////                        'mimeTypesMessage' => 'Veuillez entrer un fichier valide',
////                    ])
////                ]
//
//            ])
            ->add('trickImages', CollectionType::class, [
                'label' => false,
                'entry_options' => ['label' => false],
                'entry_type' => TrickImageType::class,
                'by_reference' => false,
                'allow_add' => true,
                'allow_delete' => true,
                'required' => false,
                'error_bubbling' => false
            ])
            ->add('trickVideos', CollectionType::class, [
                'label' => false,
                'entry_options' => ['label' => false],
                'entry_type' => TrickVideoType::class,
                'by_reference' => false,
                'allow_add' => true,
                'allow_delete' => true,
                'required' => false,
                'error_bubbling' => false
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Trick::class,
            'csrf_protection' => false,
//            // the name of the hidden HTML field that stores the token
//            'csrf_field_name' => '_token',
//            // an arbitrary string used to generate the value of the token
//            // using a different string for each form improves its security
//            'csrf_token_id'   => 'edit_trick',
        ]);
    }
}
