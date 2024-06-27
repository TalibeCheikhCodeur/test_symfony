<?php

namespace App\Controller;

use App\Entity\InvoiceLine;
use App\Form\InvoiceLineType;
use App\Repository\InvoiceLineRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/invoice-line')]
class InvoiceLineController extends AbstractController
{
    #[Route('/', name: 'invoice_line_index', methods: ['GET'])]
    public function index(InvoiceLineRepository $invoiceLineRepository): Response
    {
        return $this->render('invoice_line/index.html.twig', [
            'invoice_lines' => $invoiceLineRepository->findAll(),
        ]);
    }

    #[Route('/invoice-line/new', name: 'invoice_line_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $invoiceLine = new InvoiceLine();
        $form = $this->createForm(InvoiceLineType::class, $invoiceLine);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // Récupérer l'instance Invoice ou créer une nouvelle instance si nécessaire
            $invoice = $invoiceLine->getInvoice();
            if (!$invoice) {
                // Gérer la création de l'invoice si besoin
                // Exemple: $invoice = $this->getInvoiceById($request->request->get('invoice_id'));
                // Assurez-vous de bien gérer cette logique selon votre application
                throw new \RuntimeException('Invoice cannot be null.');
            }

            $entityManager->persist($invoiceLine);
            $entityManager->flush();

            return $this->redirectToRoute('invoice_line_index');
        }

        return $this->render('invoice_line/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    
    #[Route('/{id}', name: 'invoice_line_show', methods: ['GET'])]
    public function show(InvoiceLine $invoiceLine): Response
    {
        return $this->render('invoice_line/show.html.twig', [
            'invoice_line' => $invoiceLine,
        ]);
    }

    #[Route('/{id}/edit', name: 'invoice_line_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, InvoiceLine $invoiceLine, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(InvoiceLineType::class, $invoiceLine);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('invoice_line_index');
        }

        return $this->render('invoice_line/edit.html.twig', [
            'invoice_line' => $invoiceLine,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'invoice_line_delete', methods: ['POST'])]
    public function delete(Request $request, InvoiceLine $invoiceLine, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$invoiceLine->getId(), $request->request->get('_token'))) {
            $entityManager->remove($invoiceLine);
            $entityManager->flush();
        }

        return $this->redirectToRoute('invoice_line_index');
    }
}
