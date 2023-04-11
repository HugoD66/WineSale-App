<?php

namespace App\Controller;

use App\Entity\Commande;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PanierController extends AbstractController
{
    #[Route('/panier/{id}', name: 'app_panier')]
    public function index(int $id,
                          ManagerRegistry $doctrine): Response
    {
        $user= $this->getUser();
        $commandes =  $doctrine->getRepository(Commande::class)->findBy(['user' => $user]);

        return $this->render('user/panier.html.twig', [
            'title'     => 'Win\'Export - Votre panier',
            'commandes' => $commandes,
            'user'      => $user,
        ]);
    }
}
