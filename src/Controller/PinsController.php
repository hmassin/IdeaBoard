<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

class PinsController extends AbstractController
{
    #[Route('/pins', name: 'app_pins')]
    public function index(Security $security): Response
    {
        $user = $security->getUser();

        return $this->render('pins/index.html.twig', [
            'controller_name' => 'PinsController',
            'user' => $user,
        ]);
    }
}
