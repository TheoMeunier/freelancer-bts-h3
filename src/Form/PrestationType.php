<?php

declare(strict_types=1);

namespace App\Form;

use App\Entity\Category;
use App\Entity\Prestation;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PrestationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class)
            ->add('price', IntegerType::class)
            ->add('image', FileType::class, [
                'mapped' => false,
                'required' => false,
                ])
            ->add('categories', EntityType::class, [
                'class' => Category::class,
                'placeholder' => 'Choisir une catégories',
                'multiple' => true,
                'attr' => [
                    'id' => 'input_tom_select'
                ]
            ])
            ->add('description', TextareaType::class)
            ->add('content', TextareaType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Prestation::class,
        ]);
    }
}
