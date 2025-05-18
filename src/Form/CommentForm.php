<?php

namespace App\Form;

use App\Entity\Article;
use App\Entity\Comment;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CommentForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('Author', TextType::class, [
                'label' => 'Votre nom',
                'attr' => ['class' => 'form-control'] ] )
            ->add('Content', TextareaType::class, [
                'label' => 'Votre commentaire',
                'attr' => ['class' => 'form-control', 'rows' => 4]
            ])

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Comment::class,
        ]);
    }
}
