<?php

namespace App\Form;

use App\Entity\Comment;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Security;

class CommentType extends AbstractType
{

    private $user;

    public function __construct(Security $security)
    {
        $this->user = $security->getUser();
    }

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
            /*
            ->add('checkbox',CheckboxType::class,[
                'mapped'=>false,
                'label'=> "test",
                'required' => true
            ])
            */    
        ;

        
        $builder->addEventListener(FormEvents::PRE_SET_DATA ,function(FormEvent $event){


        });
        $builder->addEventListener(FormEvents::POST_SET_DATA ,function(FormEvent $event){
           // $data = $event->getData();
            $form = $event->getForm();
            if($this->user !== null) {
                $form->get('author')->setData($this->user->getUserName());
            }
            
        });
        /*
        $builder->addEventListener(FormEvents::PRE_SUBMIT ,function(FormEvent $event){
            dump("PRE_SUBMIT");
            $data = $event->getData();
            dump($data);
        });
        $builder->addEventListener(FormEvents::SUBMIT ,function(FormEvent $event){
            dump("SUBMIT");
            $data = $event->getData();
            dump($data);
        });
        $builder->addEventListener(FormEvents::POST_SUBMIT ,function(FormEvent $event){
            dump("POST_SUBMIT");
            $data = $event->getData();
            dump($data);
        });
        */

    }

    /**
     * 
     * En indiquant quelle classe est attendu dans ce formulaire, 
     * Symfony relie automatiquement les données d'une entité au champs correspondant du formulaire
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Comment::class,
        ]);
    }
}
