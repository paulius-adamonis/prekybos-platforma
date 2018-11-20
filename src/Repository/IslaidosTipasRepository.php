<?php

namespace App\Repository;

use App\Entity\IslaidosTipas;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method IslaidosTipas|null find($id, $lockMode = null, $lockVersion = null)
 * @method IslaidosTipas|null findOneBy(array $criteria, array $orderBy = null)
 * @method IslaidosTipas[]    findAll()
 * @method IslaidosTipas[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class IslaidosTipasRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, IslaidosTipas::class);
    }

    // /**
    //  * @return IslaidosTipas[] Returns an array of IslaidosTipas objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('i.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?IslaidosTipas
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
