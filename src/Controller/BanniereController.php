<?php

namespace App\Controller;

use App\Entity\Banniere;
use App\Form\BanniereType;
use App\Repository\BanniereRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

#[Route('/banniere')]
class BanniereController extends AbstractController
{
    #[Route('/', name: 'app_banniere_index', methods: ['GET'])]
    public function index(BanniereRepository $banniereRepository): Response
    {
        return $this->render('banniere/index.html.twig', [
            'bannieres' => $banniereRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_banniere_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response
    {
        $banniere = new Banniere();
        $form = $this->createForm(BanniereType::class, $banniere);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $premiereImage = $form->get('premiereImage')->getData(); // On récupère les données qui composent l’image

            if ($premiereImage) { // Si une image a bien été insérée 
                $originalFilename = pathinfo($premiereImage->getClientOriginalName(), PATHINFO_FILENAME); // On prend le nom de base du fichier
                $safeFilename = $slugger->slug($originalFilename);// this is needed to safely include the file name as part of the URL
                $newFilename = $safeFilename.'-'.uniqid().'.'.$premiereImage->guessExtension(); // Tente de déplacer le fichier vers le répertoire définit plus tôt
                try {
                    $premiereImage->move(
                    $this->getParameter('banniere_directory'),
                    $newFilename
                    );
                } catch (FileException $e) {
                    // Gérer le cas en cas d’exception levée (droits insuffisants, stockage insuffisant, ...)
                }
                $banniere->setPremiereImage($newFilename); // On redéfinit l’image de notre objet pour permettre l’enregistrement du bon nom d’image en BDD
            }

            $deuxiemeImage = $form->get('deuxiemeImage')->getData(); // On récupère les données qui composent l’image
            if ($deuxiemeImage) { // Si une image a bien été insérée 
                $originalFilename = pathinfo($deuxiemeImage->getClientOriginalName(), PATHINFO_FILENAME); // On prend le nom de base du fichier
                $safeFilename = $slugger->slug($originalFilename);// this is needed to safely include the file name as part of the URL
                $newFilename = $safeFilename.'-'.uniqid().'.'.$deuxiemeImage->guessExtension(); // Tente de déplacer le fichier vers le répertoire définit plus tôt
                try {
                    $deuxiemeImage->move(
                    $this->getParameter('banniere_directory'),
                    $newFilename
                    );
                } catch (FileException $e) {
                    // Gérer le cas en cas d’exception levée (droits insuffisants, stockage insuffisant, ...)
                }
                $banniere->setDeuxiemeImage($newFilename); // On redéfinit l’image de notre objet pour permettre l’enregistrement du bon nom d’image en BDD
            }

            $troisiemeImage = $form->get('troisiemeImage')->getData(); // On récupère les données qui composent l’image
            if ($troisiemeImage) { // Si une image a bien été insérée 
                $originalFilename = pathinfo($troisiemeImage->getClientOriginalName(), PATHINFO_FILENAME); // On prend le nom de base du fichier
                $safeFilename = $slugger->slug($originalFilename);// this is needed to safely include the file name as part of the URL
                $newFilename = $safeFilename.'-'.uniqid().'.'.$troisiemeImage->guessExtension(); // Tente de déplacer le fichier vers le répertoire définit plus tôt
                try {
                    $troisiemeImage->move(
                    $this->getParameter('banniere_directory'),
                    $newFilename
                    );
                } catch (FileException $e) {
                    // Gérer le cas en cas d’exception levée (droits insuffisants, stockage insuffisant, ...)
                }
                $banniere->setTroisiemeImage($newFilename); // On redéfinit l’image de notre objet pour permettre l’enregistrement du bon nom d’image en BDD
            }

            $entityManager->persist($banniere);
            $entityManager->flush();

            return $this->redirectToRoute('app_banniere_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('banniere/new.html.twig', [
            'banniere' => $banniere,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_banniere_show', methods: ['GET'])]
    public function show(Banniere $banniere): Response
    {
        return $this->render('banniere/show.html.twig', [
            'banniere' => $banniere,
        ]);
    }


    // permet de modifier les images indépendaments des autres 
    #[Route('/{id}/edit', name: 'app_banniere_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Banniere $banniere, EntityManagerInterface $entityManager, BanniereRepository $banniereRepository, SluggerInterface $slugger): Response
    {
        $form = $this->createForm(BanniereType::class, $banniere);
        $form->handleRequest($request);

        $banniere = $banniereRepository->find(id: $banniere->getId());

        if ($form->isSubmitted() && $form->isValid()) {

            $premiereImage = $form->get('premiereImage')->getData(); // On récupère les données qui composent l’image

            if ($premiereImage) { // Si une image a bien été insérée 
                $originalFilename = pathinfo($premiereImage->getClientOriginalName(), PATHINFO_FILENAME); // On prend le nom de base du fichier
                $safeFilename = $slugger->slug($originalFilename);// this is needed to safely include the file name as part of the URL
                $newFilename = $safeFilename.'-'.uniqid().'.'.$premiereImage->guessExtension(); // Tente de déplacer le fichier vers le répertoire définit plus tôt
                try {
                    $premiereImage->move(
                    $this->getParameter('banniere_directory'),
                    $newFilename
                    );
                } catch (FileException $e) {
                    // Gérer le cas en cas d’exception levée (droits insuffisants, stockage insuffisant, ...)
                }
                $banniere->setPremiereImage($newFilename); // On redéfinit l’image de notre objet pour permettre l’enregistrement du bon nom d’image en BDD
            }

            $deuxiemeImage = $form->get('deuxiemeImage')->getData(); // On récupère les données qui composent l’image
            if ($deuxiemeImage) { // Si une image a bien été insérée 
                $originalFilename = pathinfo($deuxiemeImage->getClientOriginalName(), PATHINFO_FILENAME); // On prend le nom de base du fichier
                $safeFilename = $slugger->slug($originalFilename);// this is needed to safely include the file name as part of the URL
                $newFilename = $safeFilename.'-'.uniqid().'.'.$deuxiemeImage->guessExtension(); // Tente de déplacer le fichier vers le répertoire définit plus tôt
                try {
                    $deuxiemeImage->move(
                    $this->getParameter('banniere_directory'),
                    $newFilename
                    );
                } catch (FileException $e) {
                    // Gérer le cas en cas d’exception levée (droits insuffisants, stockage insuffisant, ...)
                }
                $banniere->setDeuxiemeImage($newFilename); // On redéfinit l’image de notre objet pour permettre l’enregistrement du bon nom d’image en BDD
            }

            $troisiemeImage = $form->get('troisiemeImage')->getData(); // On récupère les données qui composent l’image
            if ($troisiemeImage) { // Si une image a bien été insérée 
                $originalFilename = pathinfo($troisiemeImage->getClientOriginalName(), PATHINFO_FILENAME); // On prend le nom de base du fichier
                $safeFilename = $slugger->slug($originalFilename);// this is needed to safely include the file name as part of the URL
                $newFilename = $safeFilename.'-'.uniqid().'.'.$troisiemeImage->guessExtension(); // Tente de déplacer le fichier vers le répertoire définit plus tôt
                try {
                    $troisiemeImage->move(
                    $this->getParameter('banniere_directory'),
                    $newFilename
                    );
                } catch (FileException $e) {
                    // Gérer le cas en cas d’exception levée (droits insuffisants, stockage insuffisant, ...)
                }
                $banniere->setTroisiemeImage($newFilename); // On redéfinit l’image de notre objet pour permettre l’enregistrement du bon nom d’image en BDD
            }

            $entityManager->persist($banniere);
            $entityManager->flush();

            return $this->redirectToRoute('app_banniere_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('banniere/edit.html.twig', [
            'banniere' => $banniere,
            'form' => $form,
        ]);
    }


    // #[Route('/{id}/edit', name: 'app_banniere_edit_premiere_image', methods: ['GET', 'POST'])]
    // public function editPremiereImage(Request $request, Banniere $banniere, EntityManagerInterface $entityManager, BanniereRepository $banniereRepository, SluggerInterface $slugger): Response
    // {
    //     $form = $this->createForm(BanniereType::class, $banniere);
    //     $form->handleRequest($request);

    //     $banniere = $banniereRepository->find(id: $banniere->getId());

    //     if ($form->isSubmitted() && $form->isValid()) {

    //         $premiereImage = $form->get('premiereImage')->getData(); // On récupère les données qui composent l’image

    //         if ($premiereImage) { // Si une image a bien été insérée 
    //             $originalFilename = pathinfo($premiereImage->getClientOriginalName(), PATHINFO_FILENAME); // On prend le nom de base du fichier
    //             $safeFilename = $slugger->slug($originalFilename);// this is needed to safely include the file name as part of the URL
    //             $newFilename = $safeFilename.'-'.uniqid().'.'.$premiereImage->guessExtension(); // Tente de déplacer le fichier vers le répertoire définit plus tôt
    //             try {
    //                 $premiereImage->move(
    //                 $this->getParameter('banniere_directory'),
    //                 $newFilename
    //                 );
    //             } catch (FileException $e) {
    //                 // Gérer le cas en cas d’exception levée (droits insuffisants, stockage insuffisant, ...)
    //             }
    //             $banniere->setPremiereImage($newFilename); // On redéfinit l’image de notre objet pour permettre l’enregistrement du bon nom d’image en BDD
    //         }

    //         $entityManager->persist($banniere);
    //         $entityManager->flush();

    //         return $this->redirectToRoute('app_banniere_index', [], Response::HTTP_SEE_OTHER);
    //     }

    //     return $this->render('banniere/editPremiereImage.html.twig', [
    //         'banniere' => $banniere,
    //         'form' => $form,
    //     ]);
    // }





    #[Route('/{id}', name: 'app_banniere_delete', methods: ['POST'])]
    public function delete(Request $request, Banniere $banniere, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$banniere->getId(), $request->request->get('_token'))) {
            $entityManager->remove($banniere);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_banniere_index', [], Response::HTTP_SEE_OTHER);
    }
}
