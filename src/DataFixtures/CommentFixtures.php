<?php

namespace App\DataFixtures;

use App\Entity\Comment;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CommentFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {

        for($i=0;$i<5;$i++) {

            $comment = new Comment();

            $comment->setContenu('Commentaire '.$i);
            $comment->setAuthor('Pseudo '.$i);
            $comment->setDateComment(new DateTime());
            $comment->setArticle($this->getReference('article-'.$i));
            
            for($j=0;$j<3;$j++) {
                $manager->persist($comment);
            }
            
        }
        
        $manager->flush();
        
    }

    // recuperer les fixtures necessaire avant load
    public function getDependencies() {
        return [
            ArticleFixtures::class
        ];
    }
}
