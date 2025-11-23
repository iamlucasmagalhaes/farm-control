<?php

namespace App\Repository;

use App\Entity\Cow;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\QueryBuilder;

/**
 * @extends ServiceEntityRepository<Cow>
 */
class CowRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Cow::class);
    }

    

//    /**
//     * @return Cow[] Returns an array of Cow objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('c.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Cow
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }

    public function createSlaughteredCowsQuery(): QueryBuilder
    {
        return $this->createQueryBuilder('c')
            ->select('c', 'f') // Seleciona Cow (c) e Farm (f) para evitar lazy loading
            ->leftJoin('c.farm', 'f') // Faz o Join com a Farm
            ->where('c.isslaughtered = :isSlaughtered') // Filtra por animais abatidos
            ->setParameter('isSlaughtered', true)
            ->orderBy('c.slaughterdate', 'DESC'); // Ordena do mais recente para o mais antigo
    } 
    
    public function getTotalMilkProducedPerWeek(): float
    {
        return $this->createQueryBuilder('c')
            ->select('SUM(c.milkperweek) as total_milk')
            ->where('c.isalive = :isAlive')
            ->setParameter('isAlive', true)
            ->getQuery()
            ->getSingleScalarResult() ?? 0.0;
    }

    public function getTotalFeedConsumedPerWeek(): float
    {
        return $this->createQueryBuilder('c')
            ->select('SUM(c.foodperweek) as total_food')
            ->where('c.isalive = :isAlive')
            ->setParameter('isAlive', true)
            ->getQuery()
            ->getSingleScalarResult() ?? 0.0;
    }

    public function countYoungHighFeedConsumers(): int
    {
        // Calcula a data limite para idade <= 1 ano (1 ano atrás)
        $dateLimit1Year = new \DateTime('-1 year');

        return $this->createQueryBuilder('c')
            ->select('COUNT(c.id)')
            ->where('c.isalive = :isAlive')
            ->andWhere('c.birthdate >= :dateLimit1Year') // Nascidos APÓS esta data
            ->andWhere('c.foodperweek > :feedLimit')     // Consumo maior que 500Kg
            ->setParameter('isAlive', true)
            ->setParameter('dateLimit1Year', $dateLimit1Year)
            ->setParameter('feedLimit', 500.0)
            ->getQuery()
            ->getSingleScalarResult();
    }
}
