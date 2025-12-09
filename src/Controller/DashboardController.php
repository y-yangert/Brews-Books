<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\ActivityLogRepository;
use App\Repository\ProductsRepository;

final class DashboardController extends AbstractController
{
    #[Route('/dashboard', name: 'app_dashboard')]
    public function index(ActivityLogRepository $activityLogRepository, ProductsRepository $productsRepository): Response
    {
        $recentLogs = $activityLogRepository->findLatest(20);
        $activeProducts = $productsRepository->createQueryBuilder('p')
            ->select('COUNT(p.id)')
            ->where('p.is_active = :active')
            ->setParameter('active', true)
            ->getQuery()
            ->getSingleScalarResult();
        $totalBooks = $productsRepository->count(['product_categories' => 1]);
        $totalCoffee = $productsRepository->count(['product_categories' => 2]);
        $totalBundleComp = $productsRepository->count(['product_categories' => 3]);

        // Get low stock products (stock <= reorder level)
        $lowStockProducts = $productsRepository->createQueryBuilder('p')
            ->leftJoin('p.stocks', 's')
            ->where('(s.quantity_in_stock <= p.reorder_level OR s.quantity_in_stock IS NULL)')
            ->andWhere('p.is_active = :active')
            ->setParameter('active', true)
            ->getQuery()
            ->getResult();

        return $this->render('dashboard/index.html.twig', [
            'recentLogs' => $recentLogs,
            'activeProducts' => $activeProducts,
            'totalBooks' => $totalBooks,
            'totalCoffee' => $totalCoffee,
            'totalBundleComp' => $totalBundleComp,
            'lowStockProducts' => $lowStockProducts,
        ]);
    }
}


