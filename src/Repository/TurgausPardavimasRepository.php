<?php

namespace App\Repository;

use App\Entity\TurgausPardavimas;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method TurgausPardavimas|null find($id, $lockMode = null, $lockVersion = null)
 * @method TurgausPardavimas|null findOneBy(array $criteria, array $orderBy = null)
 * @method TurgausPardavimas[]    findAll()
 * @method TurgausPardavimas[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TurgausPardavimasRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, TurgausPardavimas::class);
    }

    // /**
    //  * @return TurgausPardavimas[] Returns an array of TurgausPardavimas objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?TurgausPardavimas
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
