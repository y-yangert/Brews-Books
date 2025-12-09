<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\ActivityLogRepository;
use App\Repository\ProductsRepository;

final class AdminDashboardController extends AbstractController
{
    #[Route('/admin/dashboard', name: 'app_admin_dashboard')]
    public function index(ActivityLogRepository $activityLogRepository, ProductsRepository $productsRepository): Response
    {
        $recentLogs = $activityLogRepository->findLatest(20);
        $totalProducts = $productsRepository->count([]);
        $totalBooks = $productsRepository->count(['product_categories' => 1]);
        $totalCoffee = $productsRepository->count(['product_categories' => 2]);
        $totalBundleComp = $productsRepository->count(['product_categories' => 3]);

        return $this->render('admin_dashboard/index.html.twig', [
            'recentLogs' => $recentLogs,
            'totalProducts' => $totalProducts,
            'totalBooks' => $totalBooks,
            'totalCoffee' => $totalCoffee,
            'totalBundleComp' => $totalBundleComp,
        ]);
    }
}


