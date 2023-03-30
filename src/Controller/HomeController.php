<?php

namespace App\Controller;

use App\Entity\ContactUs;
use App\Entity\User;
use App\Entity\Wine;
use App\Form\ContactUsType;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(ManagerRegistry $doctrine,
                          EntityManagerInterface $entityManager,
                          Request $request): Response
    {
        $wines = $doctrine->getRepository(Wine::class)->findAll();
        $user = $this->getUser();

        $contactUs = new ContactUs();
        $form = $this->createForm(ContactUsType::class, $contactUs);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $contactUs->setSentAt(new \DateTimeImmutable());
            $contactUs = $form->getData();
            $entityManager->persist($contactUs);
            $entityManager->flush();
        }


        return $this->render('home.html.twig', [
            'title'     => 'Win\'Export, vente de vin',
            'wines'     => $wines,
            'form'      => $form,
            'user'      => $user,
        ]);
    }
}
