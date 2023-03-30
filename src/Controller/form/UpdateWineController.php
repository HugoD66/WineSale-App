<?php

namespace App\Controller\form;

use App\Entity\Wine;
use App\Form\UpdateWineType;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

class UpdateWineController extends AbstractController
{
    #[Route('/update-wine/{id}', name: 'app_update_wine')]
    public function index(Request $request,
                          int $id,
                          ManagerRegistry $doctrine,
                          EntityManagerInterface $entityManager,
                          SluggerInterface $slugger): Response
    {
        $wine = $doctrine->getRepository(Wine::class)->find($id);
        $form= $this->createForm(UpdateWineType::class, $wine);
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

        return $this->render('form/update_wine.html.twig', [
            'title' => 'Win\'Export - Mise à jour d\'un vin',
            'form'  => $form,
        ]);
    }
}
