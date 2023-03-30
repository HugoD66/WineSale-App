<?php

namespace App\Form;

use App\Entity\ContactUs;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ContactUsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'attr' => [
                    'placeholder' => 'Contact@exemple.com'
                ]
            ])
            ->add('title', TextType::class, [
                'attr' => [
                    'placeholder' => 'J\'aime bien votre site.'
                ]
            ])
            ->add('content', TextareaType::class, [
                'attr' => [
                    'placeholder' => '...'
                ]
            ])
            ->add('submit', SubmitType::class, [
                'attr' => array(
                    'class' => 'button-custom-two-form'
                )
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ContactUs::class,
        ]);
    }
}
