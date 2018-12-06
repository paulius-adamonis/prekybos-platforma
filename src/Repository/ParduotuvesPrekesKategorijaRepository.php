<?php

namespace App\Repository;

use App\Entity\ParduotuvesPreke;
use App\Entity\ParduotuvesPrekesKategorija;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method ParduotuvesPrekesKategorija|null find($id, $lockMode = null, $lockVersion = null)
 * @method ParduotuvesPrekesKategorija|null findOneBy(array $criteria, array $orderBy = null)
 * @method ParduotuvesPrekesKategorija[]    findAll()
 * @method ParduotuvesPrekesKategorija[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ParduotuvesPrekesKategorijaRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, ParduotuvesPrekesKategorija::class);
    }

    public function findByPreke(ParduotuvesPreke $preke){
        return $this->createQueryBuilder('k')
            ->select('k')
            ->from('App:PardPrekiuKategorijuPriklausymas', 'ppkp')
            ->where('ppkp.fkParduotuvesPreke = :id')
            ->setParameter('id', $preke->getId())
            ->andWhere('ppkp.fkParduotuvesPrekesKategorija = k.id')
            ->getQuery()->execute();
    }

    public function findPavadinimaiByPreke(ParduotuvesPreke $preke){
        return array_column(
            $this->createQueryBuilder('k')
            ->select('k.pavadinimas')
            ->from('App:PardPrekiuKategorijuPriklausymas', 'ppkp')
            ->where('ppkp.fkParduotuvesPreke = :id')
            ->setParameter('id', $preke->getId())
            ->andWhere('ppkp.fkParduotuvesPrekesKategorija = k.id')
            ->getQuery()->getScalarResult(),
            'pavadinimas');
    }

    // /**
    //  * @return ParduotuvesPrekesKategorija[] Returns an array of ParduotuvesPrekesKategorija objects
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
    public function findOneBySomeField($value): ?ParduotuvesPrekesKategorija
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
