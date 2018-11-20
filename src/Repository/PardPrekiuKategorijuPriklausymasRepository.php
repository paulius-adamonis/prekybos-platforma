<?php

namespace App\Repository;

use App\Entity\PardPrekiuKategorijuPriklausymas;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method PardPrekiuKategorijuPriklausymas|null find($id, $lockMode = null, $lockVersion = null)
 * @method PardPrekiuKategorijuPriklausymas|null findOneBy(array $criteria, array $orderBy = null)
 * @method PardPrekiuKategorijuPriklausymas[]    findAll()
 * @method PardPrekiuKategorijuPriklausymas[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PardPrekiuKategorijuPriklausymasRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, PardPrekiuKategorijuPriklausymas::class);
    }

    // /**
    //  * @return PardPrekiuKategorijuPriklausymas[] Returns an array of PardPrekiuKategorijuPriklausymas objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?PardPrekiuKategorijuPriklausymas
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
