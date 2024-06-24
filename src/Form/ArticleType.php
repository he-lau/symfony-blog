<?php

namespace App\Form;

use App\Entity\Article;
use App\Entity\Category;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\SubmitButton;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ArticleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titre',null,[
                'attr' => [
                    'placeholder' => 'Ajoutez un titre'
                ]
            ])
            ->add('contenu')
            // le type d'input est défini automatiquement par Symfony grâce à l'Entity
            ->add('dateCreation',null,[
                'label'=>'Date',
                'widget'=>'single_text',
                'input'=>'datetime'
            ])
            ->add('categories',EntityType::class,[
                'class' => Category::class,
                'multiple' => true,
                // IMPORTANT : table de relation
                'by_reference'=>false
            ])
            ->add('brouillon',SubmitType::class,[
                'label'=>'Enregistrer un brouillon'
            ])
            ->add('publier',SubmitType::class,[
                'label'=>'Publier'
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
