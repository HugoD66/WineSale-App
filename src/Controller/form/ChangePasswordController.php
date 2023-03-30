<?php

namespace App\Controller\form;

use App\Entity\User;
use App\Form\ChangePasswordType;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class ChangePasswordController extends AbstractController
{
    #[Route('/changer-mot-de-passe/{id}', name: 'app_change_password')]
    public function index(ManagerRegistry $doctrine,
                          Request $request,
                          UserPasswordHasherInterface $userPasswordHasher,
                          EntityManagerInterface $entityManager,
                          int $id): Response
    {
        $user=$doctrine->getRepository(User::class)->find($id);
        $form = $this->createForm(ChangePasswordType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );
            $entityManager->persist($user);
            $entityManager->flush();
            return $this->redirectToRoute('app_success_change_password', ['id' => $id]);

        }
        return $this->render('form/change_password.html.twig', [
            'form' => $form->createView(),
            'title' => 'Win\'Export - Changement mot de passer',
        ]);
    }
}
