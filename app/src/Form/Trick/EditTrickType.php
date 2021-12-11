<?php

namespace App\Form\Trick;

use App\Entity\TrickCategory;
use App\Form\Model\EditTrickFormModel;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EditTrickType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom du trick',
                'attr' => [
                    'placeholder' => 'Nom du trick'
                ]
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description du trick',
                'attr' => [
                    'placeholder' => 'Description du trick'
                ]
            ])
            ->add('trickCategory', EntityType::class, [
                'label' => 'Catégorie',
                'class' => TrickCategory::class,
                'choice_label' => 'name'
            ])
            ->add('newTrickImages', CollectionType::class, [
                'label' => false,
                'mapped' => false,
                'entry_options' => ['label' => false],
                'entry_type' => TrickImageType::class,
                'by_reference' => false,
                'allow_add' => true,
                'allow_delete' => true,
                'error_bubbling' => false
            ])
            ->add('newTrickVideos', CollectionType::class, [
                'label' => false,
                'entry_options' => ['label' => false],
                'entry_type' => TrickVideoType::class,
                'by_reference' => false,
                'allow_add' => true,
                'allow_delete' => true,
                'error_bubbling' => false
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => EditTrickFormModel::class,
            'attr' => [
                'novalidate' => true
            ]
        ]);
    }
}
