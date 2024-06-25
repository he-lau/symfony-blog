<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Comment;
use App\Form\ArticleType;
use App\Form\CommentType;
use App\Repository\ArticleRepository;
use App\Repository\CategoryRepository;
use App\Service\VerificationComment;
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
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;


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

        $state = 'publie';

        $articles = $repo->findBy(
            [
                'state'=>$state
            ],[
                'dateCreation' => "DESC"
                
            ]
            
        );

        
        
        $response = $this->render(view:'default/index.html.twig',parameters:[
            'controller_name'=>"liste_articles",
            'articles'=>$articles,
            'state'=>$state
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
    public function vueArticle(Article $article,Request $request, EntityManagerInterface $entityManager, VerificationComment $verificationComment) {


        // instance de Comment pour ensuite mapper avec le form
        $comment = new Comment();
        $comment->setArticle($article);

        // génerer un form pour l'afficher dans la vue
        $form = $this->createForm(CommentType::class,$comment);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {

            //dump($comment->getContenu());
            //dump($verificationComment->commentaireNonAutorise($comment));
            //die();

            if($verificationComment->commentaireNonAutorise($comment)) {
                //die;
                //$session->getFlashBag()->add("error", "Commentaire interdit. Veuillez modifier votre commentaire.");
                $this->addFlash("danger", "Commentaire interdit. Veuillez modifier votre commentaire.");
            } else {
                // 
                $entityManager->persist($comment);
                $entityManager->flush();

            }

            $this->redirectToRoute("vue_article",['id'=>$article->getId()]);
        }

        $response = $this->render(view:"default/vue.html.twig",parameters:[
            'article'=>$article,
            'form'=>$form->createView()
        ]);

        return $response;
    }



}
