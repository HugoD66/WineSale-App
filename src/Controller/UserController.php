<?php

namespace App\Controller;

use App\Entity\Commande;
use App\Entity\ContactUs;
use App\Entity\Message;
use App\Entity\Post;
use App\Entity\User;
use App\Entity\Wine;
use App\Form\CompleteUserType;
use App\Form\PictureUserType;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

class UserController extends AbstractController
{
    #[Route('/utilisateur/{id}', name: 'app_user')]
    public function index(ManagerRegistry $doctrine,
                          EntityManagerInterface $entityManager,
                          Request $request,
                          SluggerInterface $slugger): Response
    {
        $user = $this->getUser();

        $totalCommande = $doctrine->getRepository(Commande::class)->getTotalCommandeByUser($user);




        $messages = $doctrine->getRepository(ContactUs::class)->findAll();
        $wines = $doctrine->getRepository(Wine::class)->findAll();
        $commandes =  $doctrine->getRepository(Commande::class)->findBy(['user' => $user]);

        $form= $this->createForm(PictureUserType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $picture = $form->get('picture')->getData();
            if ($picture) {
                $originalFilename = pathinfo($picture->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $picture->guessExtension();
                try {
                    $picture->move(
                        $this->getParameter('recipe-picture'),
                        $newFilename
                    );
                } catch (FileException $e) {
                }
                $user->setPicture($newFilename);
                $user =  $form->getData();
            }
            $entityManager->persist($user);
            $entityManager->flush();
        }
        return $this->render('user/user.html.twig', [
            'user'          => $user,
            'form'          => $form->createView(),
            'messages'      => $messages,
            'wines'         => $wines,
            'title'         => 'Win\'Export - Gestion comptes',
            'commandes'     =>  $commandes,
            'totalCommande' => $totalCommande,
        ]);
    }

    #[Route('/utilisateur/remove-contact-us/{id}', name: 'delete_form')]
    public function removeContactUs(ManagerRegistry $doctrine,
                           int $id): Response
    {
        $entityManager = $doctrine->getManager();
        $contact = $entityManager->getRepository(ContactUs::class)->findOneBy(['id' => $id]);
        $entityManager->remove($contact);
        $entityManager->flush();

        $this->addFlash('success', 'Message supprimé.');

        
        return $this->redirectToRoute('app_user', ['id' => $id]);
    }
    #[Route('/utilisateur/remove-wine/{id}', name: 'delete_wine')]
    public function removeWine(ManagerRegistry $doctrine,
                           int $id): Response
    {
        $entityManager = $doctrine->getManager();
        $wine = $entityManager->getRepository(Wine::class)->findOneBy(['id' => $id]);
        $entityManager->remove($wine);
        $entityManager->flush();

        $this->addFlash('success', 'Vin supprimé.');


        return $this->redirectToRoute('app_user', ['id' => $id]);
    }

    #[Route('/utilisateur/{user_id}/remove-commande/{commande_id}', name: 'delete_wine_list')]
    public function removeWineList(ManagerRegistry $doctrine,
                                    int $user_id,
                                    int $commande_id): Response
    {

        $user = $doctrine->getRepository(User::class)->find($user_id);
        $commande = $doctrine->getRepository(Commande::class)->find($commande_id);

        $entityManager = $doctrine->getManager();
        $entityManager->remove($commande);
        $entityManager->flush();

        $this->addFlash('success', 'Commande retirée.');


        return $this->redirectToRoute('app_user', ['id' => $user]);
    }
}
