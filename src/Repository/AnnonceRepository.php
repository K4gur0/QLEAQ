<?php

namespace App\Repository;

use App\Entity\Annonce;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Annonce|null find($id, $lockMode = null, $lockVersion = null)
 * @method Annonce|null findOneBy(array $criteria, array $orderBy = null)
 * @method Annonce[]    findAll()
 * @method Annonce[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AnnonceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Annonce::class);
    }

    public function  findById($value){
        return $this->createQueryBuilder('a')
            ->andWhere('a.proprio = :val')
            ->setParameter('val', $value)
            ->orderBy('a.date_creation', 'DESC')
            ->getQuery()
            ->getResult()
            ;
    }

    public function  findById2($value){
        return $this->createQueryBuilder('a')
            ->andWhere('a.id = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getResult()
            ;
    }

    public function  findByPublication($value){
        return $this->createQueryBuilder('a')
            ->andWhere('a.publicationAuth = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getResult()
            ;
    }

    public function orderByDate()
    {
        return $this->createQueryBuilder('a')
            ->orderBy('a.date_creation', 'DESC')
            ->getQuery()
            ->getResult();
    }

}
