<?php

namespace App\Controller;

use App\Entity\Clients;
use App\Entity\User;
use App\Form\ClientsType;
use App\Form\UserType;
use App\Repository\ClientsRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/user')]
class UserController extends AbstractController
{
    #[Route('/', name: 'app_user_index', methods: ['GET'])]
    public function index(UserRepository $userRepository): Response
    {
        return $this->render('user/index.html.twig', [
            'users' => $userRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_user_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('user/new.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_user_show', methods: ['GET'])]
    public function show(User $user): Response
    {
        return $this->render('user/show.html.twig', [
            'user' => $user,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_user_edit', methods: ['GET', 'POST'])]
    public function edit(int $id, Request $request, User $user,  ClientsRepository $clientsRepository, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        $client = $clientsRepository->findClient($id);

        if ($form->isSubmitted() && $form->isValid()) {

            $client = $form ->getData(); // recup les info du form

            $entityManager->persist($client); // persist client en bdd

            $entityManager->flush();

            // dd($client);
            return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('user/edit.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    // permet de crée un client à partir d'un user tous en laissant au user d etre modifier
    #[Route('/{user_id}/create', name: 'app_user_create_client', methods: ['GET', 'POST'])]
    public function createClient(int $user_id, Request $request, UserRepository $userRepository, Clients $client, EntityManagerInterface $entityManager): Response
    {
        $formClient = $this->createForm(ClientsType::class, $client);
        $formClient->handleRequest($request);

        $user = $userRepository->find($user_id);

        if ($formClient->isSubmitted() && $formClient->isValid()) {

            $newClient = new Clients();

            $newClient->setUser($user);
            $newClient->setNom($formClient->get('nom')->getData());
            $newClient->setPrenom($formClient->get('prenom')->getData());
            $newClient->setTelephone($formClient->get('telephone')->getData());

            $entityManager->persist($newClient);

            // dd($client);
            $entityManager->flush();

            return $this->redirectToRoute('app_accueil', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('clients/edit.html.twig', [
            'user' => $user,
            'client' => $client,
            'form' => $formClient,
        ]);
    }


    #[Route('/{id}', name: 'app_user_delete', methods: ['POST'])]
    public function delete(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {
            $entityManager->remove($user);
            $entityManager->flush();
        }

        // return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
        return $this->redirectToRoute('app_accueil', [], Response::HTTP_SEE_OTHER);
    }
}
