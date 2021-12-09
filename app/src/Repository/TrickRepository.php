<?php

declare(strict_types = 1);

namespace App\Repository;

use App\Entity\Trick;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Trick|null find($id, $lockMode = null, $lockVersion = null)
 * @method Trick|null findOneBy(array $criteria, array $orderBy = null)
 * @method Trick[]    findAll()
 * @method Trick[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TrickRepository extends ServiceEntityRepository
{
    private EntityManagerInterface $manager;

    public function __construct(ManagerRegistry $registry, EntityManagerInterface $manager)
    {
        parent::__construct($registry, Trick::class);
        $this->manager = $manager;
    }

    public function getCountTricks()
    {
        $queryBuilder = $this->createQueryBuilder('trick');
        $queryBuilder
            ->select($queryBuilder->expr()->count('trick.id'));

        return $queryBuilder->getQuery()->getSingleScalarResult();
    }

    public function getTricks(?int $offset = 0, int $limit = 10)
    {
        $queryBuilder = $this->createQueryBuilder('trick');
        $queryBuilder
            ->select()
            ->orderBy('trick.createdAt', 'ASC')
            ->setFirstResult($offset)
            ->setMaxResults($limit)
        ;

        return $queryBuilder->getQuery()->getResult();
    }

    public function findTotal()
    {
        $dql = 'SELECT COUNT(trick) FROM App\Entity\Trick trick';
        $query = $this->manager->createQuery($dql);

        return $query->getSingleScalarResult();
    }

    public function getArrayTricks(?int $offset = 0, int $limit = 10)
    {
        $queryBuilder = $this->createQueryBuilder('trick');
        $queryBuilder
            ->select()
            ->addSelect('mainTrickImage')
            ->leftJoin('trick.mainTrickImage', 'mainTrickImage')
            ->orderBy('trick.createdAt', 'ASC')
            ->setFirstResult($offset)
            ->setMaxResults($limit)
        ;

        return $queryBuilder->getQuery()->getArrayResult();
    }

}
