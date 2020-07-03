<?php

namespace App\Repository;

use App\Entity\Nomade;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @method Nomade|null find($id, $lockMode = null, $lockVersion = null)
 * @method Nomade|null findOneBy(array $criteria, array $orderBy = null)
 * @method Nomade[]    findAll()
 * @method Nomade[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class NomadeRepository extends ServiceEntityRepository implements PasswordUpgraderInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Nomade::class);
    }

    /**
     * Used to upgrade (rehash) the user's password automatically over time.
     */
    public function upgradePassword(UserInterface $user, string $newEncodedPassword): void
    {
        if (!$user instanceof Nomade) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', \get_class($user)));
        }

        $user->setPassword($newEncodedPassword);
        $this->_em->persist($user);
        $this->_em->flush();
    }


     /**
      * @return Nomade[] Returns an array of Nomade objects
      */

//    public function findByFavorie($value)
//    {
//        return $this->createQueryBuilder('n')
//            ->andWhere('n.favorie = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getResult()
//        ;
//    }


    /*
    public function findOneBySomeField($value): ?Nomade
    {
        return $this->createQueryBuilder('n')
            ->andWhere('n.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
