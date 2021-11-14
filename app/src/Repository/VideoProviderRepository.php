<?php

namespace App\Repository;

use App\Entity\VideoProvider;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method VideoProvider|null find($id, $lockMode = null, $lockVersion = null)
 * @method VideoProvider|null findOneBy(array $criteria, array $orderBy = null)
 * @method VideoProvider[]    findAll()
 * @method VideoProvider[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VideoProviderRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, VideoProvider::class);
    }

    // /**
    //  * @return VideoProvider[] Returns an array of VideoProvider objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('v')
            ->andWhere('v.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('v.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?VideoProvider
    {
        return $this->createQueryBuilder('v')
            ->andWhere('v.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
