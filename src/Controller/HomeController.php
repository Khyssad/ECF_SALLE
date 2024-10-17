<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{
    #[Route('/home', name: 'app_home')]
    public function index(): Response
    {
        // Vérifier si l'utilisateur a le role admin
        $isAdmin = $this->isGranted('ROLE_ADMIN');

        // Sinon, renvoyer la vue avec la variable isAdmin
        return $this->render('home/index.html.twig', [
            'is_granted' => $isAdmin,
        ]);
    }
}
