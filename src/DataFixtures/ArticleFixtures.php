<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Article;
use DateTime;

class ArticleFixtures extends Fixture
{
    private $state = ['brouillon','publie'];

    public function load(ObjectManager $manager): void
    {
        for($i=0;$i<10;$i++) {
            // creation de l'objet
            $article = new Article(); 
            $article->setTitre("Titre article $i");
            $article->setContenu("Contenu de l'article $i.");
            $article->setState($this->state[array_rand($this->state)]);

            $date = new DateTime('now');
            $date->modify(modifier:'-'.$i.' days');

            $article->setDateCreation($date);

            $this->addReference('article-'.$i,$article);

            $manager->persist($article);
            
        }
        $manager->flush();
    }


}
