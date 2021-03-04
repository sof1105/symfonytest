<?php

namespace App\Repository;

use App\Entity\Cases;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Cases|null find($id, $lockMode = null, $lockVersion = null)
 * @method Cases|null findOneBy(array $criteria, array $orderBy = null)
 * @method Cases[]    findAll()
 * @method Cases[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CasesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Cases::class);
    }

     /**
      * @return Cases[] Returns an array of Cases objects
      */

    public function findAllCases($searchValue = null)
    {
        $qb = $this->createQueryBuilder('c')
            ->select('c.id,c.reference,c.name,c.limitDate,status.slug as case_status,cases_type.slug as type')
            ->leftJoin('c.status','status')
            ->leftJoin('c.casesType','cases_type');

        if($searchValue != null){

            $qb->where($qb->expr()->like('c.reference',':name'))
                ->orWhere($qb->expr()->like('c.reference',':name'))
                ->orWhere($qb->expr()->like('c.name',':name'))
                ->orWhere($qb->expr()->like('c.limitDate',':name'))
                ->orWhere($qb->expr()->like('status.slug',':name'))
                ->orWhere($qb->expr()->like('cases_type.slug',':name'))
                ->setParameter('name', '%'.$searchValue.'%')

                ;
        }

        $data = $qb->getQuery()
            ->getArrayResult();

        return $data;
        ;
    }


    /*
    public function findOneBySomeField($value): ?Cases
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
