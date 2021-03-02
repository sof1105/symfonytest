<?php

namespace App\Repository;

use App\Entity\CaseType;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method CaseType|null find($id, $lockMode = null, $lockVersion = null)
 * @method CaseType|null findOneBy(array $criteria, array $orderBy = null)
 * @method CaseType[]    findAll()
 * @method CaseType[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CaseTypeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CaseType::class);
    }

    // /**
    //  * @return CaseType[] Returns an array of CaseType objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?CaseType
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
