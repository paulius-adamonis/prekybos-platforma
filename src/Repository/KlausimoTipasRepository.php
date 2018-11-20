<?php

namespace App\Repository;

use App\Entity\KlausimoTipas;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method KlausimoTipas|null find($id, $lockMode = null, $lockVersion = null)
 * @method KlausimoTipas|null findOneBy(array $criteria, array $orderBy = null)
 * @method KlausimoTipas[]    findAll()
 * @method KlausimoTipas[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class KlausimoTipasRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, KlausimoTipas::class);
    }

    // /**
    //  * @return KlausimoTipas[] Returns an array of KlausimoTipas objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('k')
            ->andWhere('k.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('k.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?KlausimoTipas
    {
        return $this->createQueryBuilder('k')
            ->andWhere('k.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
