<?php

namespace App\Controller;

use App\Form\CompleteUserType;
use App\Form\PictureUserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

class UserController extends AbstractController
{
    #[Route('/utilisateur/{id}', name: 'app_user')]
    public function index(EntityManagerInterface $entityManager,
                          Request $request,
                          SluggerInterface $slugger): Response
    {
        $user = $this->getUser();

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
        ]);
    }
}
