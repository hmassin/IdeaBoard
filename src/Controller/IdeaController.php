<?php

// src/Controller/IdeaController.php

namespace App\Controller;

use App\Entity\Idea;
use App\Form\IdeaFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class IdeaController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/ideas', name: 'ideas')]
    public function index(): Response
    {
        // Récupérez toutes les idées depuis la base de données
        $ideas = $this->entityManager->getRepository(Idea::class)->findAll();

        return $this->render('idea/index.html.twig', [
            'controller_name' => 'IdeaController',
            'ideas' => $ideas,
        ]);
    }


    #[Route('/idea/create', name: 'idea_create')]
    public function create(Request $request): Response
    {
        // Vérifiez si l'utilisateur est connecté
        if (!$this->getUser()) {
            // Redirigez l'utilisateur vers la page de connexion
            return $this->redirectToRoute('app_login');
        }

        // Créez une nouvelle idée
        $idea = new Idea();
        $idea->setTitle('Nouvelle Idée');
        $idea->setDescription('Description de la nouvelle idée');
        // ...

        // Associez l'idée à l'utilisateur actuellement connecté
        $idea->setUser($this->getUser());

        // Créez le formulaire
        $form = $this->createForm(IdeaFormType::class, $idea);

        // Gérez la soumission du formulaire
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // Enregistrez l'idée dans la base de données
            $this->entityManager->persist($idea);
            $this->entityManager->flush();

            return $this->redirectToRoute('ideas'); // Redirigez vers la liste des idées
        }

        // Affichez le formulaire
        return $this->render('idea/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
