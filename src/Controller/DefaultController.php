<?php

namespace App\Controller;

use App\Entity\Article;
use App\Repository\ArticleRepository;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;


class DefaultController extends AbstractController
{
    // constructeur
    public function __construct(UrlGeneratorInterface $urlGenerator)
    {
    }

    #[Route(
        '/',
        'liste_articles',
        methods:['GET']
    )]
    public function listeArticles(ArticleRepository $repo):Response {
        //dump($this->url);die();
        //return new Response(content:"Hello default route !");

        //$articles = $repo->findAll();

        $articles = $repo->findBy(
            [],[
                'dateCreation' => "DESC"
            ]
            
        );

        
        
        $response = $this->render(view:'default/index.html.twig',parameters:[
            'controller_name'=>"liste_articles",
            'articles'=>$articles
        ]);

        return $response;
    }    

    #[Route(
        '/{id}',
        name:'vue_article',
        requirements:['id'=>"\d+"],
        methods:["GET"]
    )]
    
    //public function vueArticle(ArticleRepository $repo, $id) {
    public function vueArticle(Article $article) {
        
        //dump($article);die();

        // Récuperer l'article depuis la bdd
        //$article = $repo->find($id);

        //dump($article);die();

        $response = $this->render(view:"default/vue.html.twig",parameters:[
            'article'=>$article
        ]);

        return $response;
    }

    #[Route('/article/ajouter',name:"ajout_article")]
    public function ajouter(EntityManagerInterface $entityManager, Request $request, CategoryRepository $categoryRepository) {
        // https://symfony.com/doc/current/forms.html
        $form = $this->createFormBuilder()
        ->add("titre",TextType::class,[
            'label'=>"Titre de l'article"
        ])
        ->add('contenu',TextareaType::class,[
            'label'=> 'Contenu'
        ])
        ->add('date', DateType::class,[
            'label'=>'Date',
            'widget'=>'single_text',
            'input'=>'datetime'
        ])
       ->getForm();

       // soumission du form
       $form->handleRequest($request);
    
       // form valid + token
       if($form->isSubmitted() && $form->isValid()) {

            $titre = $form->get('titre')->getData();
            $contenu = $form->get('contenu')->getData();
            $date = $form->get('date')->getData();


            $article = new Article();

            $category = $categoryRepository->findOneBy([
                    'name'=>'Sport'
                ]);
                
            //dump($article);dump($category);die();   
            
            $article
            ->setTitre($titre)
            ->setContenu($contenu)
            ->setDateCreation($date)
            ->addCategory($category);

            $entityManager->persist($article);

            $entityManager->flush();

            return $this->redirectToRoute('liste_articles');


       }

        return $this->render('default/ajout.html.twig',[
            'form'=>$form->createView()
        ]);
    }
}
