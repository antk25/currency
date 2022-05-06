<?php

namespace App\Repository;

use App\Entity\CurrencyExchangeRate;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<CurrencyExchangeRate>
 *
 * @method CurrencyExchangeRate|null find($id, $lockMode = null, $lockVersion = null)
 * @method CurrencyExchangeRate|null findOneBy(array $criteria, array $orderBy = null)
 * @method CurrencyExchangeRate[]    findAll()
 * @method CurrencyExchangeRate[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CurrencyExchangeRateRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CurrencyExchangeRate::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(CurrencyExchangeRate $entity, bool $flush = true): void
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function remove(CurrencyExchangeRate $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }


    /**
     * @return CurrencyExchangeRate[]
     */
    public function findAllBetweenDates(string $base, string $second, \DateTime $datePeriodFrom, \DateTime $datePeriodTo): array
    {

        $qb = $this->createQueryBuilder("e");
        $qb
            ->andWhere('e.base = :base')
            ->andWhere('e.second = :second')
            ->andWhere('e.date BETWEEN :from AND :to')
            ->setParameter('base', $base)
            ->setParameter('second', $second)
            ->setParameter('from', $datePeriodFrom )
            ->setParameter('to', $datePeriodTo)
        ;

        return $qb->getQuery()->getResult();

    }

    // /**
    //  * @return CurrencyExchangeRate[] Returns an array of CurrencyExchangeRate objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */


//    public function findOneBySomeField($value): ?CurrencyExchangeRate
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }

//    public function findExchangeRate(array $filter)
//    {
//        return $this->findOneBy($filter);
//    }

}
