<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class LoginController extends AbstractController
{
    #[Route('/connexion', name: 'app_login')]
    public function index(AuthenticationUtils $authenticationUtils): Response
    {

        //gérer les erreurs d'authentification
        $error = $authenticationUtils->getLastAuthenticationError();

        //récupérer le username (email) entré par l'utilisateur
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('login/index.html.twig', [
            'error' => $error,
            'last_username' => $lastUsername,
        ]);
    }

    // route pour déconnecter l'utilisateur
    #[Route('/deconnexion', name: 'app_logout', methods: ['GET'])]
    public function logout():never
    {
    throw new \Exception('Don\'t forget to activate logout in security.yaml');
    
    }
}
