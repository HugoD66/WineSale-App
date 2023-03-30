<?php

namespace App\Controller;

use App\Entity\Wine;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
    public function wineId(ManagerRegistry $doctrine,
                           int $id): Response
    {
        $wine = $doctrine->getRepository(Wine::class)->find($id);

        return $this->render('wine/unit-wine.html.twig', [
            'wine' => $wine,
        ]);
    }
}
