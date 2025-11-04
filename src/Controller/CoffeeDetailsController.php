<?php

namespace App\Controller;

use App\Entity\Products;
use App\Entity\CoffeeDetails;

use App\Form\CoffeeDetailsType;

use App\Repository\CoffeeDetailsRepository;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/coffee/details')]
final class CoffeeDetailsController extends AbstractController
{
    #[Route(name: 'app_coffee_details_index', methods: ['GET'])]
    public function index(CoffeeDetailsRepository $coffeeDetailsRepository): Response
    {
        return $this->render('coffee_details/index.html.twig', [
            'coffee_details' => $coffeeDetailsRepository->findAll(),
        ]);
    }

    #[Route('/new/{productId}', name: 'app_coffee_details_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, #[MapEntity(mapping: ['productId' => 'id'])] Products $product): Response
    {
        $coffeeDetail = new CoffeeDetails();
        $coffeeDetail->setProduct($product);

        $form = $this->createForm(CoffeeDetailsType::class, $coffeeDetail);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($coffeeDetail);
            $entityManager->flush();

            // Redirect to the stocks "add new" page after saving book details
            return $this->redirectToRoute('app_stocks_new_redirect', [
                    'productId' => $product->getId()]);
        }

        return $this->render('coffee_details/new.html.twig', [
            'coffee_detail' => $coffeeDetail,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'app_coffee_details_show', methods: ['GET'])]
    public function show(CoffeeDetails $coffeeDetail): Response
    {
        return $this->render('coffee_details/show.html.twig', [
            'coffee_detail' => $coffeeDetail,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_coffee_details_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, CoffeeDetails $coffeeDetail, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CoffeeDetailsType::class, $coffeeDetail);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_coffee_details_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('coffee_details/edit.html.twig', [
            'coffee_detail' => $coffeeDetail,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'app_coffee_details_delete', methods: ['POST'])]
    public function delete(Request $request, CoffeeDetails $coffeeDetail, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$coffeeDetail->getId(), $request->request->get('_token'))) {
            $entityManager->remove($coffeeDetail);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_coffee_details_index', [], Response::HTTP_SEE_OTHER);
    }
}
