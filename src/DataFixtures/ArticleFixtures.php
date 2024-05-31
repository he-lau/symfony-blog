<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Article;
use DateTime;

class ArticleFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        for($i=0;$i<10;$i++) {
            // creation de l'objet
            $article = new Article(); 
            $article->setTitre("Titre article $i");
            $article->setContenu("Contenu de l'article $i.");

            $date = new DateTime('now');
            $date->modify(modifier:'-'.$i.' days');

            $article->setDateCreation($date);
            $manager->persist($article);

            $manager->flush();
        }

    }
}
