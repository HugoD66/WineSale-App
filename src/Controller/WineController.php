<?php

namespace App\Controller;

use App\Entity\Commande;
use App\Entity\Note;
use App\Entity\User;
use App\Entity\Wine;
use App\Form\CommandeFormType;
use App\Form\NoteType;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class WineController extends AbstractController
{
    #[Route('/wines', name: 'app_list_wines')]
    public function index(): Response
    {
        $user = $this->getUser();

        return $this->render('wine/list_wine.html.twig', [
            'title' => 'Win\'Export - Liste vins',
            'user'  => $user,
        ]);
    }

    #[Route('/wine/{id}', name: 'app_wine')]
    public function wineId(Request $request,
                           ManagerRegistry $doctrine,
                           EntityManagerInterface $entityManager,
                           int $id): Response
    {
        $wine = $doctrine->getRepository(Wine::class)->find($id);
        $notes = $wine->getNote();
        $user = $this->getUser();


        $note = new Note();
        $form= $this->createForm(NoteType::class, $note);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if (!$user) {
                $this->addFlash('success', 'Connexion obligatoire.');

            } else {
                $existingNote = $doctrine->getRepository(Note::class)->findOneBy(['user' => $this->getUser(), 'wine' => $wine]);
                if ($existingNote) {
                    $existingNote->setNote($note->getNote());
                    $entityManager->flush();
                } else {
                    $note->setUser($this->getUser());
                    $note->setWine($wine);
                    $note = $form->getData();
                    $entityManager->persist($note);
                    $entityManager->flush();
                }
                $this->addFlash('success', 'Note envoyée.');
            }
        }

        $commande = new commande();
        $formCommande = $this->createForm(CommandeFormType::class, $commande);
        $formCommande->handleRequest($request);
        if ($formCommande->isSubmitted() && $formCommande->isValid()) {
            $commande->setUser($this->getUser());
            $commande->addWine($wine);
            $commande = $formCommande->getData();
            $entityManager->persist($commande);
            $entityManager->flush();
            $this->addFlash('success', 'Ajouté au panier.');
        }

        return $this->render('wine/unit-wine.html.twig', [
            'wine' => $wine,
            'form' => $form->createView(),
            'formCommande' => $formCommande,
            'notes' => $notes,
            'title' => 'Win\'Export - ' . $wine->getTitle(),
        ]);
    }




}
