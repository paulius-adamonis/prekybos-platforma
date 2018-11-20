<?php

namespace App\Repository;

use App\Entity\PardavimoTipas;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method PardavimoTipas|null find($id, $lockMode = null, $lockVersion = null)
 * @method PardavimoTipas|null findOneBy(array $criteria, array $orderBy = null)
 * @method PardavimoTipas[]    findAll()
 * @method PardavimoTipas[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PardavimoTipasRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, PardavimoTipas::class);
    }

    // /**
    //  * @return PardavimoTipas[] Returns an array of PardavimoTipas objects
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
    public function findOneBySomeField($value): ?PardavimoTipas
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
