<?php

namespace App\Controller;

use Amp\Http\Client\Request;
use App\Entity\FormulaireDemandeProduit;
use App\Form\FormulaireDemandeProduitType;
use App\Repository\FormulaireDemandeProduitRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ContactController extends AbstractController
{
    #[Route('/contact', name: 'app_contact')]
    public function index(): Response
    {
        return $this->render('contact/contact.html.twig', [
            'controller_name' => 'ContactController',
        ]);
    }



    //Route accessibile si connecter 
    #[Route('/contact/Formulaire', name: 'app_contact_log')]
    public function contactAcces(FormulaireDemandeProduitRepository $formulaireDemandeProduitRepository): Response
    {

        return $this->render('contact/contact_log.html.twig', [
            'formulaire_demande_produits' => $formulaireDemandeProduitRepository->findAll(),
        ]);
    }







    // Route qui renvoie au formulaire de demande
    #[Route('/contact/Formulaire/Creer', name: 'app_contact_log_cree_form')]
    public function creerDemande(Request $request, EntityManagerInterface $entityManager): Response
    {
        $formulaireDemandeProduit = new FormulaireDemandeProduit();
        $form = $this->createForm(FormulaireDemandeProduitType::class, $formulaireDemandeProduit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // Permet d enregistrer la date et l heure de l envoie (format gmt a changer)
            $dateEnvoiForm = new \DateTime();
            $formulaireDemandeProduit->setDateEnvoieForm($dateEnvoiForm);

            // Définie la reponse du form en 'attente'
            $attenteReponse = 'attente';
            $formulaireDemandeProduit->setReponseDemande($attenteReponse);

            $entityManager->persist($formulaireDemandeProduit);
            $entityManager->flush();

            return $this->redirectToRoute('app_contact_log', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('contact/formulaireDemande.html.twig', [
            'formulaireDemande' => $formulaireDemandeProduit,
            'form' => $form,
        ]);;
    }
    

    // Afficher le formulaire celon son id 
    #[Route('/contact/Formulaire/Voir:{id}', name: 'app_formulaire_show', methods: ['GET'])]
    public function show(FormulaireDemandeProduit $formulaireDemandeProduit): Response
    {
        return $this->render('contact/formulaireShow.html.twig', [
            'formulaireDemande' => $formulaireDemandeProduit,
        ]);
    }



}
