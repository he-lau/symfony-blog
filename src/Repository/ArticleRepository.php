<?php

namespace App\Repository;

use App\Entity\Article;
use DateTime;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Article>
 *
 * @method Article|null find($id, $lockMode = null, $lockVersion = null)
 * @method Article|null findOneBy(array $criteria, array $orderBy = null)
 * @method Article[]    findAll()
 * @method Article[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ArticleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Article::class);
    }

    public function findByDateCreation(DateTime $date) 
    {
        // query
        $qb = $this->createQueryBuilder('a');

        $qb->select('a')
           ->where('a.dateCreation = :date')
           ->setParameter('date', $date->format('Y-m-d'));

        return $qb->getQuery()->getResult();
    }

    public function findByTitre($titre)
    {
        // query
        $qb = $this->createQueryBuilder('a');

        $qb->select('a');
        $qb->where('a.titre = :titre');
        $qb->setParameter('titre', $titre);

        return $qb->getQuery()->getOneOrNullResult();        
    }
}
