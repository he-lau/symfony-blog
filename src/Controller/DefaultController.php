<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Comment;
use App\Form\ArticleType;
use App\Form\CommentType;
use App\Repository\ArticleRepository;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\Entity;
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

    /**
     * Afficher la liste des articles 
     * 
     */
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

    
    /**
     * Afficher un article 
     * 
     */
    #[Route(
        '/{id}',
        name:'vue_article',
        requirements:['id'=>"\d+"],
        methods:["GET","POST"]
    )]
    //public function vueArticle(ArticleRepository $repo, $id) {
    public function vueArticle(Article $article,Request $request, EntityManagerInterface $entityManager) {

        // instance de Comment pour ensuite mapper avec le form
        $comment = new Comment();
        $comment->setArticle($article);

        // génerer un form pour l'afficher dans la vue
        $form = $this->createForm(CommentType::class,$comment);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            // 
            $entityManager->persist($comment);
            $entityManager->flush();

            $this->redirectToRoute("vue_article",['id'=>$article->getId()]);
        }

        $response = $this->render(view:"default/vue.html.twig",parameters:[
            'article'=>$article,
            'form'=>$form->createView()
        ]);

        return $response;
    }

    /**
     * 
     * Afficher le formulaire pour ajouter un article
     */
    #[Route('/article/ajouter',name:"ajout_article")]
    public function ajouter(EntityManagerInterface $entityManager, Request $request, CategoryRepository $categoryRepository) {
        
        /*
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
       */

       $article = new Article();

       // formulaire lié à l'entity "Article"
       $form = $this->createForm(ArticleType::class,$article);

       // soumission du form
       $form->handleRequest($request);
    
       // form valid + token
       if($form->isSubmitted() && $form->isValid()) {

        /*

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

        */   
        
        $entityManager->persist($article);
        $entityManager->flush();
        return $this->redirectToRoute('liste_articles');


       }

        return $this->render('default/ajout.html.twig',[
            'form'=>$form->createView()
        ]);
    }
}
