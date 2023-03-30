<?php

namespace App\Controller\form;

use App\Entity\Wine;
use App\Form\AddWineType;
use App\Form\CompleteUserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

class AddWineController extends AbstractController
{
    #[Route('/ajout-vin', name: 'app_add_wine')]
    public function index(Request $request,
                          EntityManagerInterface $entityManager,
                          SluggerInterface $slugger): Response
    {
        $wine = new Wine();
        $form= $this->createForm(AddWineType::class, $wine);
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
                $wine->setPicture($newFilename);
                $wine =  $form->getData();

            }
            $entityManager->persist($wine);
            $entityManager->flush();
        }

        return $this->render('form/add_wine.html.twig', [
            'title' => 'Win\'Export - Ajout d\'un vin',
            'form'  => $form,
        ]);
    }
}
