<?php

namespace App\Form;

use App\Entity\Article;
use App\Entity\Category;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichImageType;

class ArticleForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', null, [
                'attr' => ['class' => 'form-control'],
                'label_attr' => ['class' => 'form-label'],
            ])
            ->add('content', null, [
                'attr' => ['class' => 'form-control'],
                'label_attr' => ['class' => 'form-label'],
            ])

            ->add('imageFile', VichImageType::class, [    
                'required' => false,
                'allow_delete' => false,
                'download_uri' => false,
                'image_uri' => false, 
                'attr' => ['class' => 'form-control'],
                'label' => 'Changer l’image',
                'label_attr' => ['class' => 'form-label'],
            ])

            ->add('categories', EntityType::class, [
                'class' => Category::class,
                'choice_label' => 'name',
                'multiple' => true,
                'attr' => ['class' => 'form-select'],
                'label_attr' => ['class' => 'form-label'],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Article::class,
        ]);
    }
}
