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

    public function add(CurrencyExchangeRate $entity, bool $flush = true): void
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

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

    /**
     * @return CurrencyExchangeRate[]
     */
    public function findCurrencyExchangeRate(string $from, string $to, \DateTime $date): array
    {
        return $this->findBy([
            'base' => $from,
            'second' => $to,
            'date' => $date,
        ]);
    }

}
