<?php

namespace App\Form;

use App\Entity\Note;
use App\Entity\User;
use App\Entity\Wine;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class NoteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('note', ChoiceType::class, [
                'choices' => [
                    '★' => 1,
                    '★★' => 2,
                    '★★★' => 3,
                    '★★★★' => 4,
                    '★★★★★' => 5,
                ],
                'expanded' => true,
                'multiple' => false,

            ])
            ->add('user', EntityType::class, [
                "class" =>  User::class,
                "choice_label"  =>  "id",
                'label' => ' ',
                'attr' => ['style' => 'display:none'], // champ caché via CSS
            ])
            ->add('wine', EntityType::class, [
                "class" =>  Wine::class,
                "choice_label"  =>  "id",
                'label' => ' ',
                'attr' => ['style' => 'display:none'], // champ caché via CSS
            ])
            ->add('submit', SubmitType::class, [
                'attr' => ['class' => 'submit-note'], // champ caché via CSS

            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Note::class,
        ]);
    }
}
