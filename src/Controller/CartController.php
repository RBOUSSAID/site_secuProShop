<?php

namespace App\Controller;

use App\Classe\Cart;
use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CartController extends AbstractController
{
    #[Route('/mon-panier/{motif}', name: 'app_cart', defaults: ['motif' => null])]
    public function index(Cart $cart, $motif): Response
    {

        // ajouter un message flash pour notifier l'utilisateur si il y a un motif de la commande
        if ($motif == 'annulation') {
            $this->addFlash(
                'info',
                "Votre paiement est annulé, bous pouvez mettre à jour votre panier et passer la commande"
            );
        }

        return $this->render('cart/index.html.twig', [
            'cart' => $cart->getCart(), // passer le panier à la vue
            'totalWt' => $cart->getTotalWt() // calculer le total de la commande
        ]);
    }

    // creation d'une route qui nous permet d'ajouter un article dans le panier
    #[Route('/cart/add/{id}', name: 'app_cart_add')]
    public function add($id, Cart $cart, ProductRepository $productRepository, Request $request): Response
    {
        $product = $productRepository->findOneById($id); // récupérer l'article correspondant à l'id
        $cart->add($product); // ajouter l'article au panier et l'afficher dans le panier
    
        // ajouter un message flash pour notifier l'utilisateur que l'article a bien été ajouté
        $this->addFlash(
            'success',
            'Votre produit a bien etait ajouté dans votre panier.'
        );

        return $this->redirect($request->headers->get('referer')); // rediriger vers la page du produit
    }

    // creation d'une route qui nous permet de supprimer la quantité d'un article dans le panier
    #[Route('/cart/decrease/{id}', name: 'app_cart_decrease')]
    public function decrease($id, Cart $cart): Response
    {
        $cart->decrease($id); 
    
        // ajouter un message flash pour notifier l'utilisateur que l'article a bien été supprimé
        $this->addFlash(
            'success',
            'Votre produit a bien etait supprimé dans votre panier.'
        );

        return $this->redirectToRoute('app_cart'); // rediriger vers la page du panier
    }

    // creation d'une route qui nous permet de supprimer un article du panier
    #[Route('/cart/remove', name: 'app_cart_remove')]
    public function remove(Cart $cart): Response
    {
        $cart->remove();

        return $this->redirectToRoute('app_home');
    }
}
