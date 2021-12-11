<?php

namespace App\Form\Trick;

use App\Entity\TrickVideo;
use App\Entity\VideoProvider;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TrickVideoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom',
                'attr' => ['placeholder' => 'Nom de la video']
            ])
            ->add('url', TextType::class, [
                'label' => 'Lien',
                'attr' => ['placeholder' => 'Lien vers la video']
            ])
            ->add('provider', EntityType::class,[
                'label' => 'Provider',
                'placeholder' => '-- Choisir un provider --',
                'class' => VideoProvider::class,
                'choice_label' => 'name'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => TrickVideo::class,
        ]);
    }
}
