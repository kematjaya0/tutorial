<?php

namespace App\Repository;

use App\Entity\MNews;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method MNews|null find($id, $lockMode = null, $lockVersion = null)
 * @method MNews|null findOneBy(array $criteria, array $orderBy = null)
 * @method MNews[]    findAll()
 * @method MNews[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MNewsRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, MNews::class);
    }

//    /**
//     * @return MNews[] Returns an array of MNews objects
//     */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('m.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?MNews
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
