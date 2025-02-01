<?php

namespace App\Controller;

use DateTime;
use App\Classe\Mail;
use App\Repository\UserRepository;
use App\Form\ForgotPasswordFormType;
use App\Form\UpdatePasswordFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ForgotPasswordController extends AbstractController
{
    // Déclaration d'une propriété privée pour stocker l'EntityManager
    private $em;

    // Le constructeur de la classe est utilisé pour injecter des dépendances
    public function __construct(EntityManagerInterface $em)
    {
        // On assigne l'EntityManager passé en paramètre à la propriété $em de la classe
        // Cela permet de réutiliser l'EntityManager dans d'autres méthodes de la classe
        $this->em = $em;
    }

    #[Route('/mot-de-passe-oublier', name: 'app_password')]
    public function index(Request $request, UserRepository $userRepository): Response
    {
        // Formulaires
        $form = $this->createForm(ForgotPasswordFormType::class);

        $form->handleRequest($request);

        // Traitemnt du formulaire
        if ($form->isSubmitted() && $form->isValid()){
            // si l'émail renseigné est dans la BDD
            $email = $form->get('email')->getData(); 
            
            $user = $userRepository->findOneByEmail($email);

            //envoyer une notification pour l'utilisateur
            $this->addFlash('success', 'Si votre adresse mail est existe, vous recevrez un mail pour réinitialiser votre mot de passe.');
        
            // si l'utilisateur existe, on peut générer un nouveau mot de passe et le renvoyer à l'utilisateur
            if($user){
                // Créer un token qu'on vas stocker dans la BDD
                $randomBytes = random_bytes(15);
                $token = bin2hex($randomBytes);
                
                // Mettre à jour le token dans la BDD
                $user->setToken($token);

                $date = new DateTime(); // Crée un objet DateTime avec la date et l'heure actuelles
                $date->modify('+15 minutes'); // Ajoute 15 minutes à la date/heure actuelle

                $user->setTokenExpireAt($date); // Ajouter la date d'expiration de l'utilisateur à la date calculée dans la BDD

                $this->em->flush();

                // Envoie d'un email à l'utilisateur
                $mail = new Mail();
                
                // Remplit les variables du template avec les données de l'utilisateur
                // Créer un lien pour le lien du mail
                $vars = [
                    'link' => $this->generateUrl('app_password_update', ['token'=>$token], UrlGeneratorInterface::ABSOLUTE_URL),
                ];
                $content = "Bonjour<br>mon mail test !";
                $mail->send($user->getEmail(), $user->getFirstName() . ' ' . $user->getLastname(), 'Modification de votre mot de passe', 'forgotpassword.html', $vars); 
            }
        }

        return $this->render('password/index.html.twig', [
            'ForgotPasswordForm' => $form->createView(),
        ]);
    }

    #[Route('/mot-de-passe-update/{token}', name: 'app_password_update')]
    public function update(Request $request, UserRepository $userRepository, $token): Response
    {
        // Redirige vers la route 'app_password' (mot de passe oublié) si le token est null ou invalide
        if (!$token)
        {
            return $this->redirectToRoute('app_password');
        }

        // Recherche un utilisateur dans le repository en fonction du token fourni dans la BDD
        $user = $userRepository->findOneByToken($token);

        // Vérifie si l'utilisateur n'existe pas ou si le token a expiré
        // et Redirige vers la route 'app_password' (mot de passe oublié)
        if (!$user || $user->getTokenExpireAt() < new DateTime())
        {
            return $this->redirectToRoute('app_password');
        }


        // Formulaires
        $form = $this->createForm(UpdatePasswordFormType::class, $user);

        $form->handleRequest($request);

        // Traitemnt du formulaire
        if ($form->isSubmitted() && $form->isValid()){
            // Enregistre les modifications du mot de passe dans la BDD et supprime le token
            $user->setToken(null);
            $user->setTokenExpireAt(null);

            $this->em->flush();
            $this->addFlash(
                'success',
                'Votre mot de passe a bien été mis à jour.'
            );
            return $this->redirectToRoute('app_login'); // rediriger vers la page de connexion si tout est ok
        }

        return $this->render('password/update.html.twig',[
            'form' => $form->createView(),
        ]);
    }
}
