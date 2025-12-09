<?php

namespace App\Controller;


use App\Entity\Products;
use App\Entity\Stocks;

use App\Form\StocksType;

use App\Repository\StocksRepository;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/stocks')]
final class StocksController extends AbstractController
{
    #[Route(name: 'app_stocks_index', methods: ['GET'])]
    public function index(StocksRepository $stocksRepository): Response
    {
        $totalBooks = $stocksRepository->sumStockByCategory(1);
        $totalCoffee = $stocksRepository->sumStockByCategory(2);
        $totalBundleComp = $stocksRepository->sumStockByCategory(3);

        return $this->render('stocks/index.html.twig', [
            'stocks' => $stocksRepository->findAll(),
            'totalBooks' => $totalBooks,
            'totalCoffee' => $totalCoffee,
            'totalBundleComp' => $totalBundleComp,
            ]);
    }

    #[Route('/new', name: 'app_stocks_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $stock = new Stocks();
        $form = $this->createForm(StocksType::class, $stock);
        $form->handleRequest($request);

        if ($form->isSubmitted() && !$form->isValid()) {
            return $this->render('stocks/new.html.twig', [
                'stock' => $stock,
                'form' => $form,
            ]);
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($stock);
            $entityManager->flush();

            return $this->redirectToRoute('app_stocks_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('stocks/new.html.twig', [
            'stock' => $stock,
            'form' => $form,
        ]);
    }

    #[Route('/stocks/new/{productId}', name: 'app_stocks_new_redirect', methods: ['GET', 'POST'])]
    public function newRedirect(Request $request, EntityManagerInterface $entityManager, #[MapEntity(mapping: ['productId' => 'id'])] Products $product): Response {
        {  
            $stocks = new Stocks();
            $stocks->setProduct($product);

            $form = $this->createForm(StocksType::class, $stocks);
            $form->handleRequest($request);
            if (!$product) {
                return $this->redirectToRoute('app_products_index');
            }
            if ($form->isSubmitted() && $form->isValid()) {
                $entityManager->persist($stocks);
                $entityManager->flush();

                return $this->redirectToRoute('app_products_index'); // Or wherever you want to go next
            }
            
            return $this->render('stocks/new.html.twig', [
                'form' => $form->createView(),
            ]);
        }
    }

    #[Route('/{id}', name: 'app_stocks_show', methods: ['GET'])]
    public function show(Stocks $stock): Response
    {
        return $this->render('stocks/show.html.twig', [
            'stock' => $stock,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_stocks_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Stocks $stock, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(StocksType::class, $stock);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_stocks_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('stocks/edit.html.twig', [
            'stock' => $stock,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_stocks_delete', methods: ['POST'])]
    public function delete(Request $request, Stocks $stock, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$stock->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($stock);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_stocks_index', [], Response::HTTP_SEE_OTHER);
    }
}
