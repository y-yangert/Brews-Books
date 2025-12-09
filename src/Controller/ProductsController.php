<?php

namespace App\Controller;

use App\Entity\Products;
use App\Form\ProductsType;

use App\Repository\ProductsRepository;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

#[Route('/products')]
final class ProductsController extends AbstractController
{

    #[Route(name: 'app_products_index', methods: ['GET'])]
    public function index(ProductsRepository $productsRepository): Response
    {
        $totalProducts = $productsRepository->count([]);
        $totalBooks = $productsRepository->count(['product_categories' => 1]);
        $totalCoffee = $productsRepository->count(['product_categories' => 2]);
        $totalBundleComp = $productsRepository->count(['product_categories' => 3]);
        
        return $this->render('products/index.html.twig', [
            'products' => $productsRepository->findAll(),
            'totalProducts' => $totalProducts,
            'totalBooks' => $totalBooks,
            'totalCoffee' => $totalCoffee,
            'totalBundleComp' => $totalBundleComp,
        ]);
    }

    #[Route('/new', name: 'app_products_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response
    {
        $product = new Products();
        $form = $this->createForm(ProductsType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $image = $form->get('image')->getData();
            if ($image) {
                $originalFilename = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $image->guessExtension();

                $image->move($this->getParameter('products_images_directory'), $newFilename);
                $product->setImage($newFilename);
            }

            $entityManager->persist($product);
            $entityManager->flush();

            // Check if product category
            if ($product->getProductCategories() && $product->getProductCategories()->getId() === 1) {
                return $this->redirectToRoute('app_book_details_new', [
                    'productId' => $product->getId()
                ]);
            }
            
            else if ($product->getProductCategories() && $product->getProductCategories()->getId() === 2) {
                return $this->redirectToRoute('app_coffee_details_new', [
                    'productId' => $product->getId()
                ]);
            }
            $this->addFlash('success', 'Product successfully created.');
            return $this->redirectToRoute('app_products_index');
        }

        return $this->render('products/new.html.twig', [
            'product' => $product,
            'form' => $form->createView(),
        ]);
    }


    #[Route('/{id}', name: 'app_products_show', methods: ['GET'])]
    public function show(Products $product): Response
    {
        return $this->render('products/show.html.twig', [
            'product' => $product,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_products_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Products $product, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response
    {
        $form = $this->createForm(ProductsType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $image = $form->get('image')->getData();
            if ($image) {
                $originalFilename = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $image->guessExtension();

                $image->move($this->getParameter('products_images_directory'), $newFilename);
                $product->setImage($newFilename);
            }

            $entityManager->flush();

            return $this->redirectToRoute('app_products_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('products/edit.html.twig', [
            'product' => $product,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_products_delete', methods: ['POST'])]
    public function delete(Request $request, Products $product, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$product->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($product);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_products_index', [], Response::HTTP_SEE_OTHER);
    }
}
