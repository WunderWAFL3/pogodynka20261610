<?php

namespace App\Repository;

use App\Entity\Place;
use App\Entity\WeatherRecord;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<WeatherRecord>
 */
class WeatherRecordRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, WeatherRecord::class);
    }
    public function findByLocation(Place $place)
    {
        $qb = $this->createQueryBuilder('m');
        $qb->where('m.place = :place')
            ->setParameter('place', $place)
            ->andWhere('m.date > :now')
            ->setParameter('now', date('Y-m-d'));


        $query = $qb->getQuery();
        $result = $query->getResult();
        return $result;
    }


//    /**
//     * @return WeatherRecord[] Returns an array of WeatherRecord objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('w')
//            ->andWhere('w.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('w.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?WeatherRecord
//    {
//        return $this->createQueryBuilder('w')
//            ->andWhere('w.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
