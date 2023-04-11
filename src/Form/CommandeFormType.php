<?php

namespace App\Form;

use App\Entity\Commande;
use App\Entity\User;
use App\Entity\Wine;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CommandeFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('number', IntegerType::class, [
                'label' => ' ',
                'attr' => [
                    'placeholder' => 'Combien ?',
                ]
            ])
            ->add('user', EntityType::class, [
                "class" =>  User::class,
                "choice_label"  =>  "id",
                'label' => ' ',
                'attr' => ['style' => 'display:none'],
            ])
            ->add('wine', EntityType::class, [
                'class' => Wine::class,
                'multiple' => true,
                'required' => false,
                'expanded' => false,
                'label' => ' ',
                'attr' => ['style' => 'display:none'],
                ])
            ->add('submit', SubmitType::class, [
                'attr' => ['class' => 'button-custom-one'],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Commande::class,
        ]);
    }
}
