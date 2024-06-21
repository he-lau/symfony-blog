<?php

namespace App\Form;

use App\Entity\Comment;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CommentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('author',null,[
                'label' => "Auteur",
                'attr' => [
                    'placeholder'=> "..."
                ]
            ])
            ->add('contenu',null,[
                'label' => "Commentaire",
                'attr' => [
                    'placeholder'=> "..."
                ]
                
            ])
            //->add('dateComment')
            //->add('article')
            ->add('checkbox',CheckboxType::class,[
                'mapped'=>false,
                'label'=> "test",
                'required' => true
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
