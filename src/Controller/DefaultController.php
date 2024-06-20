<?php

namespace App\Controller;

use App\Entity\Article;
use App\Repository\ArticleRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;


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
    public function ajouter(EntityManagerInterface $manager) {

        //dump($manager);die();

        // creation de l'objet
        $article = new Article(); 
        $article->setTitre("Titre article");
        $article->setContenu("Contenu de l'article.");
        $article->setDateCreation(new DateTime('now'));

        // injection bdd
        $manager->persist($article);
        $manager->flush();

        //dump($article);
        die();
    }
}
