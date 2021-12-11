<?php

namespace App\Form\Trick;

use App\Entity\TrickImage;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\Validator\Constraints\NotBlank;

class TrickImageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom',
                'attr' => ['placeholder' => "Nom de l'image"],
                'constraints' => [
                    new NotBlank(['message' => 'Le nom doit etre renseigné'])
                ]
            ])
            ->add('file', FileType::class, [
                'label' => false,
                'constraints' => [
                    new Image([
                        'maxSize' => 1024000,
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/jpg',
                            'image/png'
                        ],
                        'mimeTypesMessage' => 'Veuillez entrer un fichier valide',
                        'allowPortrait' => false,
                        'allowPortraitMessage' => "L'image doit etre au format paysage"
                    ]),
                    new NotBlank(['message' => 'Veuillez saisir une image ou supprimer le champ'])
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => TrickImage::class,
        ]);
    }
}
