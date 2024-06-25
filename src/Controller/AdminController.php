<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Article;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Form\ArticleType;
use App\Repository\CategoryRepository;
use Symfony\Component\HttpFoundation\Request;


#[Route("/admin")]
class AdminController extends AbstractController
{

    /**
     * 
     * Afficher le formulaire pour ajouter un article
     */
    #[
        Route('/article/ajouter',name:"ajout_article"),
        Route('/article/{id}/edition',name:"edition_article",requirements:['id'=>"\d+"],methods:['GET','POST'])
    ]
    public function ajouter(Article $article=null, EntityManagerInterface $entityManager, Request $request, CategoryRepository $categoryRepository) {

        // si pas d'article recuperer dans l'url
        if($article===null) {
            $article = new Article();
        }

       // formulaire lié à l'entity "Article"
       $form = $this->createForm(ArticleType::class,$article,[
        'method'=>'GET',
        'csrf_protection'=>false
       ]);

       // soumission du form
       $form->handleRequest($request);
    
       // form valid + token
       if($form->isSubmitted() && $form->isValid()) {

        //die();
        
        // creation d'un brouillon
        if ($form->get('brouillon')->isClicked()) {
            $article->setState('brouillon');
        } else {
            $article->setState('publie');
        }

        // ajout de l'article si pas dans la bdd
        if($article->getId()===null) {
            $entityManager->persist($article);
            $entityManager->flush();
        }

        return $this->redirectToRoute('liste_articles');


       }

        return $this->render('default/ajout.html.twig',[
            'form'=>$form->createView()
        ]);
    }


    /**
     * Afficher la liste des articles brouillons
     */
    #[
        Route('/article/brouillon',name:'brouillon_article')
    ]
    public function brouillon(ArticleRepository $articleRepository) {

        $state = 'brouillon';
        $articles = $articleRepository->findBy(
            [
                'state'=>$state
            ],[
                'dateCreation'=>'DESC'
            ]
        );

        $response = $this->render(view:'default/index.html.twig',parameters:[
            'controller_name'=>"liste_articles",
            'articles'=>$articles,
            'state'=>$state
        ]);

        return $response;

    }
}
