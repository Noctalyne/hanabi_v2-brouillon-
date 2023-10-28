<?php

namespace App\Controller;

use App\Entity\Vendeurs;
use App\Form\VendeursType;
use App\Repository\UserRepository;
use App\Repository\VendeursRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/vendeurs')]
class VendeursController extends AbstractController
{
    #[Route('/', name: 'app_vendeurs_index', methods: ['GET'])]
    public function index(VendeursRepository $vendeursRepository): Response
    {
        return $this->render('vendeurs/index.html.twig', [
            'vendeurs' => $vendeursRepository->findAll(),
        ]);
    }

    #[Route('/{user_id}/createVendeur', name: 'app_vendeurs_create', methods: ['GET', 'POST'])]
    public function createVendeur(int $user_id,Request $request, EntityManagerInterface $entityManager, UserRepository $userRepository, Vendeurs $vendeur): Response
    {
        
        $form = $this->createForm(VendeursType::class, $vendeur);
        $form->handleRequest($request);

        $user = $userRepository->find($user_id);

        if ($form->isSubmitted() && $form->isValid()) {

            // $user->setRoles(['ROLE_ADMIN']);

            $newVendeur = new Vendeurs();

            $newVendeur->setUserVendeur($user);

            $newVendeur->setNom($form->get('nom')->getData());
            $newVendeur->setPrenom($form->get('prenom')->getData());



            $entityManager->persist($newVendeur);
            $entityManager->flush();

            return $this->redirectToRoute('app_vendeurs_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('vendeurs/new.html.twig', [
            // 'user' => $user,
            'vendeur' => $vendeur,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_vendeurs_show', methods: ['GET'])]
    public function show(Vendeurs $vendeur): Response
    {
        return $this->render('vendeurs/show.html.twig', [
            'vendeur' => $vendeur,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_vendeurs_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Vendeurs $vendeur, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(VendeursType::class, $vendeur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_vendeurs_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('vendeurs/edit.html.twig', [
            'vendeur' => $vendeur,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_vendeurs_delete', methods: ['POST'])]
    public function delete(Request $request, Vendeurs $vendeur, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$vendeur->getId(), $request->request->get('_token'))) {
            $entityManager->remove($vendeur);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_vendeurs_index', [], Response::HTTP_SEE_OTHER);
    }
}
