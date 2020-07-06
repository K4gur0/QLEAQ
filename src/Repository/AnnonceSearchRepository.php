<?php

namespace App\Repository;

use App\Entity\AnnonceSearch;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method AnnonceSearch|null find($id, $lockMode = null, $lockVersion = null)
 * @method AnnonceSearch|null findOneBy(array $criteria, array $orderBy = null)
 * @method AnnonceSearch[]    findAll()
 * @method AnnonceSearch[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AnnonceSearchRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AnnonceSearch::class);
    }

    // /**
    //  * @return AnnonceSearch[] Returns an array of AnnonceSearch objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?AnnonceSearch
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
