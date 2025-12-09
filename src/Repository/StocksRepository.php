<?php

namespace App\Repository;

use App\Entity\Stocks;
use App\Entity\Products;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Stocks>
 */
class StocksRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Stocks::class);
    }

    public function sumStockByCategory(int $categoryId): int
{
    return (int) $this->createQueryBuilder('s')
        ->select('SUM(s.quantity_in_stock)')
        ->join('s.product', 'p')
        ->andWhere('p.product_categories = :categoryId')
        ->setParameter('categoryId', $categoryId)
        ->getQuery()
        ->getSingleScalarResult();
}

//    /**
//     * @return Stocks[] Returns an array of Stocks objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('i')
//            ->andWhere('i.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('i.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Stocks
//    {
//        return $this->createQueryBuilder('i')
//            ->andWhere('i.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
