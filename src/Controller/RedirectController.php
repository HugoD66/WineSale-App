<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class RedirectController extends AbstractController
{
    #[Route('/deja-inscrit', name: 'app_deja_inscrit')]
    public function index(): Response
    {
        return $this->render('redirect/deja_inscrit.html.twig', [
            'title'     => ' Win\'Export, Déjà inscrit',
        ]);
    }

    #[Route('/non-inscrit', name: 'app_non_inscrit')]
    public function isRegister(): Response
    {
        return $this->render('redirect/non_inscrit.html.twig', [
            'title'     => ' Win\'Export, Non inscrit',
        ]);
    }

    #[Route('/changer-mot-de-passe/success/{id}', name: 'app_success_change_password')]
    public function changePasswordSuccess(int $id,
                                          ManagerRegistry $doctrine): Response
    {
        $user=$doctrine->getRepository(User::class)->find($id);

        return $this->render('redirect/change_password.html.twig', [
            'title'     => ' Win\'Export, Succès changement mot de passe',
            'user'     => $user,
        ]);
    }
    #[Route('/changer-photo/success/{id}', name: 'app_success_change_picture')]
    public function changePictureSuccess(int $id,
                                          ManagerRegistry $doctrine): Response
    {
        $user=$doctrine->getRepository(User::class)->find($id);

        return $this->render('redirect/change_picture.html.twig', [
            'title'     => ' Win\'Export, Succès changement image',
            'user'     => $user,
        ]);
    }
    #[Route('/success-enregistrement', name: 'app_success_register')]
    public function successRegister(): Response
    {
        return $this->render('redirect/success_register.html.twig', [
            'title'     => ' Win\'Export, Succès Enregistrement',

        ]);
    }
}
