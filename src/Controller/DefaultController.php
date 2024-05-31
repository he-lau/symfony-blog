<?php

namespace App\Controller;

use App\Entity\Article;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;


class DefaultController extends AbstractController
{

    private $articles;

    // constructeur
    public function __construct(UrlGeneratorInterface $urlGenerator)
    {
        $this->articles = [
            [
                'nom' => "AAAA",
                //'url' => $urlGenerator->generate('vue_article', ['id' => "564"])
                'id' => 564
            ],
            [
                'nom' => "LSNJNS",
                //'url' => $urlGenerator->generate('vue_article', ['id' => "564"])
                'id' => 42
            ],
            [
                'nom' => "PKLM",
                //'url' => $urlGenerator->generate('vue_article', ['id' => "564"])
                'id' => 4242
            ]            

        ];
    }

    #[Route(
        '/',
        'liste_articles',
        methods:['GET']
    )]
    public function listeArticles():Response {
        //dump($this->url);die();
        //return new Response(content:"Hello default route !");
        $response = $this->render(view:'default/index.html.twig',parameters:[
            'controller_name'=>"liste_articles",
            'articles'=>$this->articles
        ]);
        return $response;
    }    

    #[Route(
        '/{id}',
        name:'vue_article',
        requirements:['id'=>"\d+"],
        methods:["GET"]
    )]
    
    public function vueArticle($id) {
        $response = $this->render(view:"default/vue.html.twig",parameters:[
            'id'=>$id
        ]);

        return $response;
    }

    #[Route('/article/ajouter',name:"ajout_article")]
    public function ajouter(EntityManagerInterface $manager) {

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
