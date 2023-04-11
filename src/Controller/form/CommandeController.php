<?php

namespace App\Controller\form;

use App\Entity\Commande;
use App\Form\CommandeFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CommandeController extends AbstractController
{
    #[Route('/commande', name: 'app_commande')]
    public function index(Request $request,
                          EntityManagerInterface $entityManager): Response
    {
        $commande = new Commande();
        $form = $this->createForm(CommandeFormType::class, $commande);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            $commande = $form->getData();
            $entityManager->persist($commande);
            $entityManager->flush();

        }

        return $this->render('commande/index.html.twig', [
            'controller_name' => 'CommandeController',
            'form' => $form->createView(),
        ]);
    }
}
