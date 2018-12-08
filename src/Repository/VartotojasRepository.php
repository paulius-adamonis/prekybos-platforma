<?php

namespace App\Repository;

use App\Entity\Sandelis;
use App\Entity\Vartotojas;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Vartotojas|null find($id, $lockMode = null, $lockVersion = null)
 * @method Vartotojas|null findOneBy(array $criteria, array $orderBy = null)
 * @method Vartotojas[]    findAll()
 * @method Vartotojas[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VartotojasRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Vartotojas::class);
    }

    public function findValdytojasBySandelis(Sandelis $sandelis){
        try {
            return $this->createQueryBuilder('v')
                ->select('v')
                ->from('App:SandeliuPriklausymas', 'sp')
                ->where('sp.fkSandelis = :id')
                ->setParameter('id', $sandelis->getId())
                ->andWhere('sp.fkVartotojas = v.id')
                ->andWhere('v.roles LIKE :role')
                ->setParameter('role', '%"ROLE_VALDYTOJAS"%')
                ->andWhere('v.arAktyvus = 1')
                ->getQuery()->getSingleResult();
        } catch (NonUniqueResultException $e) {
            return null;
        } catch (NoResultException $e){
            return null;
        }
    }

    public function findByRoles(String $roles){
        return $this->createQueryBuilder('v')
            ->andWhere('v.roles LIKE :roles')
            ->setParameter('roles', '%'.$roles.'%')
            ->andWhere('v.arAktyvus = 1')
            ->getQuery()->getResult();
    }

    public function findByNotRoles(String $roles){
        return $this->createQueryBuilder('v')
            ->andWhere('v.roles NOT LIKE :roles')
            ->setParameter('roles', '%'.$roles.'%')
            ->andWhere('v.arAktyvus = 1')
            ->getQuery()->getResult();
    }

    // /**
    //  * @return Vartotojas[] Returns an array of Vartotojas objects
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
    public function findOneBySomeField($value): ?Vartotojas
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
