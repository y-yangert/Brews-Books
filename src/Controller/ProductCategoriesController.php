<?php

namespace App\Controller;

use App\Entity\ProductCategories;
use App\Form\ProductCategoriesType;
use App\Repository\ProductCategoriesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/product/categories')]
final class ProductCategoriesController extends AbstractController
{
    #[Route(name: 'app_product_categories_index', methods: ['GET'])]
    public function index(ProductCategoriesRepository $productCategoriesRepository): Response
    {
        return $this->render('product_categories/index.html.twig', [
            'product_categories' => $productCategoriesRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_product_categories_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $productCategory = new ProductCategories();
        $form = $this->createForm(ProductCategoriesType::class, $productCategory);
        $form->handleRequest($request);

        if ($form->isSubmitted() && !$form->isValid()) {
            return $this->render('product_categories/new.html.twig', [
                'product_category' => $productCategory,
                'form' => $form,
            ]);
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($productCategory);
            $entityManager->flush();

            return $this->redirectToRoute('app_product_categories_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('product_categories/new.html.twig', [
            'product_category' => $productCategory,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_product_categories_show', methods: ['GET'])]
    public function show(ProductCategories $productCategory): Response
    {
        return $this->render('product_categories/show.html.twig', [
            'product_category' => $productCategory,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_product_categories_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, ProductCategories $productCategory, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ProductCategoriesType::class, $productCategory);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_product_categories_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('product_categories/edit.html.twig', [
            'product_category' => $productCategory,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_product_categories_delete', methods: ['POST'])]
    public function delete(Request $request, ProductCategories $productCategory, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$productCategory->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($productCategory);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_product_categories_index', [], Response::HTTP_SEE_OTHER);
    }
}
