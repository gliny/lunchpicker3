<?php

namespace App\Repository;

use App\Entity\Lunch;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Lunch>
 *
 * @method Lunch|null find($id, $lockMode = null, $lockVersion = null)
 * @method Lunch|null findOneBy(array $criteria, array $orderBy = null)
 * @method Lunch[]    findAll()
 * @method Lunch[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LunchRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Lunch::class);
    }

    public function save(Lunch $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Lunch $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findOneByLunch(Lunch $lunch): array
    {
        return $this->createQueryBuilder('lunchesByDateAndText')
            ->andWhere('lunchesByDateAndText.day = :day')
            ->setParameter('day', $lunch->getDay())
            ->andWhere('lunchesByDateAndText.text = :text')
            ->setParameter('text', $lunch->getText())
            ->getQuery()
            ->getResult()
            ;
    }
}
