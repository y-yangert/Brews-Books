<?php

namespace App\Controller;

use App\Entity\Products;
use App\Entity\BookDetails;

use App\Form\BookDetailsType;

use App\Repository\BookDetailsRepository;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/book/details')]
final class BookDetailsController extends AbstractController
{
    #[Route(name: 'app_book_details_index', methods: ['GET'])]
    public function index(BookDetailsRepository $bookDetailsRepository): Response
    {
        return $this->render('book_details/index.html.twig', [
            'book_details' => $bookDetailsRepository->findAll(),
        ]);
    }

    #[Route('/new/{productId}', name: 'app_book_details_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, #[MapEntity(mapping: ['productId' => 'id'])] Products $product): Response
    {
        $bookDetail = new BookDetails();
        $bookDetail->setProduct($product);

        $form = $this->createForm(BookDetailsType::class, $bookDetail);
        $form->handleRequest($request);

        if ($form->isSubmitted() && !$form->isValid()) {
            return $this->render('book_details/new.html.twig', [
                'book_detail' => $bookDetail,
                'form' => $form,
            ]);
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($bookDetail);
            $entityManager->flush();

            // Redirect to the stocks "add new" page after saving book details
            return $this->redirectToRoute('app_stocks_new_redirect', [
                    'productId' => $product->getId()]);
        }

        return $this->render('book_details/new.html.twig', [
            'book_detail' => $bookDetail,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'app_book_details_show', methods: ['GET'])]
    public function show(BookDetails $bookDetail): Response
    {
        return $this->render('book_details/show.html.twig', [
            'book_detail' => $bookDetail,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_book_details_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, BookDetails $bookDetail, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(BookDetailsType::class, $bookDetail);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_book_details_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('book_details/edit.html.twig', [
            'book_detail' => $bookDetail,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'app_book_details_delete', methods: ['POST'])]
    public function delete(Request $request, BookDetails $bookDetail, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$bookDetail->getId(), $request->request->get('_token'))) {
            $entityManager->remove($bookDetail);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_book_details_index', [], Response::HTTP_SEE_OTHER);
    }
}
