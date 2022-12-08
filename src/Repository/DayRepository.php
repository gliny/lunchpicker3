<?php

namespace App\Repository;

use App\Entity\Day;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Day>
 *
 * @method Day|null find($id, $lockMode = null, $lockVersion = null)
 * @method Day|null findOneBy(array $criteria, array $orderBy = null)
 * @method Day[]    findAll()
 * @method Day[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DayRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Day::class);
    }

    public function save(Day $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Day $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findByDay(Day $day): ?Day
    {
        return $this->createQueryBuilder('byDay')
            ->andWhere('byDay.date = :val')
            ->setParameter('val', $day->getDate())
            ->getQuery()
            ->getOneOrNullResult()
            ;
    }

    public function findNewerThanYesterday(): array
    {
        $yesterday = new \DateTime();
        $yesterday->modify('-1day');

        return $this->createQueryBuilder('byDay')
            ->andWhere('byDay.date > :val')
            ->setParameter('val', $yesterday)
            ->orderBy('byDay.date')
            ->getQuery()
            ->getResult()
            ;
    }
}
