<?php

namespace App\Repository;

use App\Entity\VartotojoAtsiliepimas;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method VartotojoAtsiliepimas|null find($id, $lockMode = null, $lockVersion = null)
 * @method VartotojoAtsiliepimas|null findOneBy(array $criteria, array $orderBy = null)
 * @method VartotojoAtsiliepimas[]    findAll()
 * @method VartotojoAtsiliepimas[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VartotojoAtsiliepimasRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, VartotojoAtsiliepimas::class);
    }

    /**
     * @param $userId
     * @return double
     */
    public function getAverageRating($userId)
    {
        // automatically knows to select Products
        // the "p" is an alias you'll use in the rest of the query
        $qb = $this->createQueryBuilder('v')
            ->select("avg(v.reitingas) as rating_avg")
            ->where('v.fkGavejas = :userId')
            ->setParameter('userId', $userId)
            ->getQuery()
            ->getSingleScalarResult();

        return $qb;

        // to get just one result:
        // $product = $qb->setMaxResults(1)->getOneOrNullResult();
    }

    // /**
    //  * @return VartotojoAtsiliepimas[] Returns an array of VartotojoAtsiliepimas objects
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
    public function findOneBySomeField($value): ?VartotojoAtsiliepimas
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
