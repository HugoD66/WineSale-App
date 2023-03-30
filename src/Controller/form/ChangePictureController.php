<?php

namespace App\Controller\form;

use App\Entity\User;
use App\Form\ChangePasswordType;
use App\Form\ChangePictureType;
use App\Form\PictureUserType;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

class ChangePictureController extends AbstractController
{
    #[Route('/changer-photo/{id}', name: 'app_change_picture')]
    public function index(ManagerRegistry $doctrine,
                          Request $request,
                          EntityManagerInterface $entityManager,
                          int $id,
                          SluggerInterface $slugger): Response
    {
        $user=$doctrine->getRepository(User::class)->find($id);

        $form= $this->createForm(ChangePictureType::class, $user);
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
            return $this->redirectToRoute('app_success_change_picture', ['id' => $id]);

        }
        return $this->render('form/change_picture.html.twig', [
            'form' => $form->createView(),
            'title' => 'Win\'Export - Changer Photo'
        ]);
    }
}
