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
        
        return $this->render('products/index.html.twig', [
            'products' => $productsRepository->findAll(),
            'activeProducts' => $activeProducts,
            'totalBooks' => $totalBooks,
            'totalCoffee' => $totalCoffee,
            'totalBundleComp' => $totalBundleComp,
            'lowStockProducts' => $lowStockProducts,
        ]);
    }

    #[Route('/new', name: 'app_products_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response
    {
        $product = new Products();
        $form = $this->createForm(ProductsType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && !$form->isValid()) {
            return $this->render('products/new.html.twig', [
            'product' => $product,
            'form' => $form,
        ]);
        }

        if ($form->isSubmitted() && $form->isValid()) {
            
            $category = $product->getProductCategories();
            $categoryId = $category ? $category->getId() : null;

            if (in_array($categoryId, [1, 2], true)) {

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

                if ($categoryId === 1) {
                    return $this->redirectToRoute('app_book_details_new', ['productId' => $product->getId()]);
                }

                if ($categoryId === 2) {
                    return $this->redirectToRoute('app_coffee_details_new', ['productId' => $product->getId()]);
                }
            }

            // For non-book/coffee categories, proceed with the original persist flow
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

        // Handle book/coffee details forms
        $bookDetailsForm = null;
        $coffeeDetailsForm = null;
        $bookDetails = null;
        $coffeeDetails = null;
        
        if ($product->getProductCategories() && $product->getProductCategories()->getId() === 1) {
            // Book product
            $bookDetails = $product->getBookDetails();
            if (!$bookDetails) {
                $bookDetails = new \App\Entity\BookDetails();
                $bookDetails->setProduct($product);
            }
            $bookDetailsForm = $this->createForm(\App\Form\BookDetailsType::class, $bookDetails);
            $bookDetailsForm->handleRequest($request);
        } elseif ($product->getProductCategories() && $product->getProductCategories()->getId() === 2) {
            // Coffee product
            $coffeeDetails = $product->getCoffeeDetails();
            if (!$coffeeDetails) {
                $coffeeDetails = new \App\Entity\CoffeeDetails();
                $coffeeDetails->setProduct($product);
            }
            $coffeeDetailsForm = $this->createForm(\App\Form\CoffeeDetailsType::class, $coffeeDetails);
            $coffeeDetailsForm->handleRequest($request);
        }

        if ($request->isMethod('POST')) {
            // Handle product form
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
            }

            // Handle book/coffee details forms
            if ($bookDetailsForm && $bookDetailsForm->isSubmitted() && $bookDetailsForm->isValid()) {
                $entityManager->persist($bookDetailsForm->getData());
                $entityManager->flush();
                $this->addFlash('success', 'Product details successfully updated.');
                return $this->redirectToRoute('app_products_edit', ['id' => $product->getId()], Response::HTTP_SEE_OTHER);
            }
            
            if ($coffeeDetailsForm && $coffeeDetailsForm->isSubmitted() && $coffeeDetailsForm->isValid()) {
                $entityManager->persist($coffeeDetailsForm->getData());
                $entityManager->flush();
                $this->addFlash('success', 'Product successfully updated.');
                return $this->redirectToRoute('app_products_edit', ['id' => $product->getId()], Response::HTTP_SEE_OTHER);
            }

            if ($form->isSubmitted() && $form->isValid()) {
                $this->addFlash('success', 'Product successfully updated.');
                return $this->redirectToRoute('app_products_edit', ['id' => $product->getId()], Response::HTTP_SEE_OTHER);
            }
        }

        return $this->render('products/edit.html.twig', [
            'product' => $product,
            'form' => $form->createView(),
            'bookDetailsForm' => $bookDetailsForm ? $bookDetailsForm->createView() : null,
            'coffeeDetailsForm' => $coffeeDetailsForm ? $coffeeDetailsForm->createView() : null,
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
