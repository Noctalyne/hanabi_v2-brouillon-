<?php

namespace App\Controller;

use App\Entity\Clients;
use App\Form\ClientsType;
use App\Repository\ClientsRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/clients')]
class ClientsController extends AbstractController
{
    #[Route('/', name: 'app_clients_index', methods: ['GET'])]
    public function index(ClientsRepository $clientsRepository): Response
    {
        return $this->render('clients/index.html.twig', [
            'clients' => $clientsRepository->findAllWithUser(),
        ]);
    }

    #[Route('/new', name: 'app_clients_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $client = new Clients();
        $form = $this->createForm(ClientsType::class, $client);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($client);
            $entityManager->flush();

            return $this->redirectToRoute('app_clients_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('clients/new.html.twig', [
            'client' => $client,
            'form' => $form,
        ]);
    }

    #[Route('/{user_id}', name: 'app_clients_show', methods: ['GET'])]
    public function show(int $user_id, Clients $client, ClientsRepository $clientsRepository, UserRepository $userRepository): Response
    {
        $user = $userRepository->find($user_id);
        $client = $clientsRepository->findClient($user_id);
        // dd($client);
        return $this->render('clients/show.html.twig', [
            'client' => $client,
            'user' => $user,
        ]);
    }

    #[Route('/{user_id}/edit', name: 'app_clients_edit', methods: ['GET', 'POST'])]
    public function edit(int $user_id, Request $request, Clients $client,UserRepository $userRepository, ClientsRepository $clientsRepository, EntityManagerInterface $entityManager): Response
    {
        $formClient = $this->createForm(ClientsType::class, $client);
        $formClient->handleRequest($request);

        // $client = $clientsRepository->findClient($user_id);

        // $user= $userRepository->findUser($user_id);
        // $user_id = $user->getId();
        // dd($user);

        // if ( ) {

        

        echo ('<pre>'),var_dump($client);echo ('</pre>');
        // }
        

        if ($formClient->isSubmitted() && $formClient->isValid()) {
            // $client->setUser($user);
            $client->setUser($this->getUser());
            
            $entityManager->persist($client);
            $entityManager->flush();

            return $this->redirectToRoute('app_clients_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('clients/edit.html.twig', [
            // 'user' => $user,
            'client' => $client,
            'form' => $formClient,
        ]);
    }

    #[Route('/{id}', name: 'app_clients_delete', methods: ['POST'])]
    public function delete(Request $request, Clients $client, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$client->getId(), $request->request->get('_token'))) {
            $entityManager->remove($client);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_clients_index', [], Response::HTTP_SEE_OTHER);
    }
}
