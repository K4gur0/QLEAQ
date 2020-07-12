<?php

namespace App\Repository;

use App\Entity\Annonce;
use App\Entity\AnnonceSearch;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\Query;

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
            ->orderBy('a.datePublication','DESC')
            ->getQuery()
            ->getResult()
            ;
    }

    public function  findByFiltre(AnnonceSearch $search)
    {
        $query = $this->createQueryBuilder('a')
            ->andWhere('a.publicationAuth = :val')
            ->setParameter('val', true)
        ;

        if ($search->getTarifMax()){
            $query->andWhere('a.tarif <= :tmax')
                ->setParameter('tmax', $search->getTarifMax())
                ;
        }

        if ($search->getTarifMin()){
            $query->andWhere('a.tarif >= :tmin')
                ->setParameter('tmin', $search->getTarifMin())
                ;
        }

        if ($search->getSuperficieMax()){
            $query->andWhere('a.superficie <= :smax')
                ->setParameter('smax', $search->getSuperficieMax())
            ;
        }

        if ($search->getSuperficieMin()){
            $query->andWhere('a.superficie >= :smin')
                ->setParameter('smin', $search->getSuperficieMin())
            ;
        }

        $query
            ->orderBy('a.datePublication','DESC')
            ->getQuery()
            ->getResult()
            ;

        return $query;
    }



    public function orderByDate()
    {
        return $this->createQueryBuilder('a')
            ->orderBy('a.date_creation', 'DESC')
            ->getQuery()
            ->getResult();
    }


}
