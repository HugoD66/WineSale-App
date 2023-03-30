<?php

namespace App\Form;

use App\Entity\Wine;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AddWineType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class)
            ->add('year', IntegerType::class)
            ->add('description', TextareaType::class, [
                'label' => false,
                'attr' =>  array(
                    'class' => 'picture-textarea',
                    'placeholder' => 'Description ...'
                )
            ])
            ->add('picture',FileType::class, [
                'label' => false,
                'data_class' => null,
                'attr' =>  array(
                    'class' => 'picture-custom'
                )
            ])
            ->add('submit', SubmitType::class, [
                'attr' => array(
                    'class' => 'button-custom-two'
                )
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Wine::class,
        ]);
    }
}
