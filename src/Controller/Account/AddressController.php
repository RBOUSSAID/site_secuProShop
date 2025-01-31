<?php

namespace App\Controller\Account;

use App\Classe\Cart;
use App\Entity\Address;
use App\Form\AddressUserType;
use App\Repository\AddressRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AddressController extends AbstractController
{
    private $entityManager; // injection du EntityManager pour utiliser Doctrine.

    // fonction pour injecter le EntityManager lors de l'instanciation de la classe. 
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    // Route pour afficher les adresses du compte utilisateur sur la page /compte/adresses.html.twig.
    #[Route('/compte/adresses', name: 'app_account_addresses')]
    public function index(): Response
    {
        return $this->render('account/address/index.html.twig');
    }

    // route pour supprimer une adresse du compte utilisateur sur la page /compte/adresses/delete.html.twig.
    #[Route('/compte/adresses/delete/{id}', name: 'app_account_address_delete')]
    public function delete($id, AddressRepository $addressRepository): Response
    {
        $address = $addressRepository->findOneById($id);
        // si l'adresse n'existe pas, renvoyer vers la page des adresses du compte utilisateur
        if (!$address or $address->getUser() != $this->getUser( )){
            return $this->redirectToRoute('app_account_addresses');
        }  
        $this->addFlash(
            'success',
            'Votre adresse a bien été supprimée.'
        );

        $this->entityManager->remove($address); // supprimer l'adresse de la base de données
        $this->entityManager->flush(); // sauvegarder les modifications dans la base de données

        return $this->redirectToRoute('app_account_addresses');
    }

    //route pour ajouter les adresses du compte utilisateur sur la page /compte/adresse/modifier/{id}.html.twig.
    #[Route('/compte/adresse/ajouter/{id}', name: 'app_account_address_form', defaults: ['id' => null] )]
    public function form(Request $request, $id, AddressRepository $addressRepository, Cart $cart): Response
    {
        // si l'id est fourni, cela signifie que nous voulons modifier une adresse existante
        if ($id){
            $address = $addressRepository->findOneById($id); // récupérer l'adresse correspondant à l'id
            // si l'adresse n'existe pas, renvoyer vers la page des adresses du compte utilisateur
            if (!$address or $address->getUser() != $this->getUser( )){
                return $this->redirectToRoute('app_account_addresses');
            }    
        } else { // sinon, cela signifie que nous voulons ajouter une nouvelle adresse
            $address = new Address(); 
            $address->setUser($this->getUser()); // lier l'adresse à l'utilisateur connecté 
        }
        // création d'une nouvelle adresse si aucun id n'est fourni ou si l'id est nul
        

        $form = $this->createForm(AddressUserType::class, $address); // form du compte utilisateur pour ajputer des adresses

        $form->handleRequest($request); // ecouter la requete pour le formulaire

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($address); // persister (quand si la premiere fois on persiste) l'adresse dans la base de données
            $this->entityManager->flush(); // sauvegarder les modifications dans la base de données

            $this->addFlash(
                'success',
                'Votre adresse a bien été sauvegardée.'
            );

        if ($cart->fullQuantity() > 0){
            return $this->redirectToRoute('app_order');
        }

            return $this->redirectToRoute('app_account_addresses'); // rediriger vers la page des adresses du compte utilisateur
        }

        // renvoyer le template avec le formulaire pour ajouter une nouvelle adresse au compte utilisateur
        return $this->render('account/address/form.html.twig', [
            'addressForm' => $form // afficher le formulaire à l'utilisateur
        ]);
    }
}
?>