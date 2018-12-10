<?php

namespace App\Repository;

use App\Entity\PrekiuPriklausymas;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method PrekiuPriklausymas|null find($id, $lockMode = null, $lockVersion = null)
 * @method PrekiuPriklausymas|null findOneBy(array $criteria, array $orderBy = null)
 * @method PrekiuPriklausymas[]    findAll()
 * @method PrekiuPriklausymas[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PrekiuPriklausymasRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, PrekiuPriklausymas::class);
    }

    public function findPrekesBySandelisNotDeleted($sandelioId){
        return $this->getEntityManager()
            ->createQuery('select p.id, p.kiekis, (p.fkSandelis), (p.fkKokybe), (p.fkParduotuvesPreke), pp.pavadinimas as pavadinimas
                                    from App\Entity\PrekiuPriklausymas p
                                    INNEr JOIN App\Entity\ParduotuvesPreke pp
                                    INNEr JOIN App\Entity\Sandelis s
                                    where pp.id=p.fkParduotuvesPreke
                                    and s.id = :sandelioId
                                    and p.fkSandelis = s.id
                                    and pp.arPasalinta = 0
                                    and p.kiekis < 5' )
            ->setParameter('sandelioId', $sandelioId)
            ->getArrayResult();
    }

    // /**
    //  * @return PrekiuPriklausymas[] Returns an array of PrekiuPriklausymas objects
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

    /*
    public function findOneBySomeField($value): ?PrekiuPriklausymas
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
