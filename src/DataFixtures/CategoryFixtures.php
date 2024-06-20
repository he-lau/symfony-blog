<?php

namespace App\DataFixtures;

use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CategoryFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $categories = ['Sport','Maison'];

        foreach($categories as $category) {
            $categoryEntity = new Category();

            $categoryEntity->setName($category);
            $categoryEntity->addArticle($this->getReference(name:'article-1'));

            if($category==='Sport') {
                $categoryEntity->addArticle($this->getReference(name:'article-2'));
                $categoryEntity->addArticle($this->getReference(name:'article-3'));
            }

            $manager->persist($categoryEntity);
        }

        $manager->flush();
    }

    public function getDependencies() {
        return array(
            ArticleFixtures::class
        );
    }
}
