<?php

namespace App\Controller;

use App\Classe\Mail;
use App\Entity\User;
use App\Form\RegisterUserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class RegisterController extends AbstractController
{
    // Page d'inscription du site. Affiche le formulaire de création de compte et gère le traitement du formulaire.
    #[Route('/inscription', name: 'app_register')]
    public function index(Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = new User(); // Crée une nouvelle instance de l'entité User

        // Crée le formulaire de création de compte en utilisant la classe RegisterUserType
        // Le formulaire est lié à l'objet $user pour automatiquement mapper les données du formulaire à l'entité
        $form = $this->createForm(RegisterUserType::class, $user); 

        // Lie les données de la requête HTTP (POST) au formulaire
        // Cela permet de remplir le formulaire avec les données soumises par l'utilisateu
        $form->handleRequest($request); 

        // Vérifie si le formulaire a été soumis et si les données sont valides
        if ($form->isSubmitted() && $form->isValid()) {
            // Persiste l'objet $user dans la base de données (le prépare pour l'insertion)
            $entityManager->persist($user);
            $entityManager->flush(); // Exécute la requête SQL pour insérer l'utilisateur dans la base de don
            $this->addFlash(
                'success',
                'Votre compte a bien été créée. Vous pouvez vous connecter maintenant.'
            );

        // Envoie d'un email de confirmation d'inscription à l'utilisateur
        $mail = new Mail();
        
        // Remplit les variables du template avec les données de l'utilisateur
        $vars = [
            'firstname' => $user->getFirstName(),
            'lastname' => $user->getLastname(),
            'email' => $user->getEmail(),
        ];
        $content = "Bonjour<br>mon mail test !";
        $mail->send($user->getEmail(), $user->getFirstName() . ' ' . $user->getLastname(), 'Bienvenue sur la boutique SecuProShop', 'welcome.html', $vars); 

             //si la création du compte est réussie, on redirige vers la page de login
            return $this->redirectToRoute('app_login'); 
            
        }
        
        return $this->render('register/index.html.twig', [
        'registerForm' => $form->createView()
        ]);
    }
}
