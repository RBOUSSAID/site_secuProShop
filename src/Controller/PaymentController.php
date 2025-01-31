<?php

namespace App\Controller;

use Stripe\Stripe;
use App\Classe\Cart;
use Stripe\Checkout\Session;
use App\Repository\OrderRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PaymentController extends AbstractController
{
    // route pour l'affichage du formulaire de paiement
    #[Route('/commande/paiment/{id_order}', name: 'app_payment')]
    public function index($id_order, OrderRepository $orderRepository, EntityManagerInterface $entityManager): Response
    {
        // initalisation de stripe avec la clé privé
        Stripe::setApikey($_ENV['STRIPE_SECRET_KEY']);
        
        // créer une session de paiement avec les informations du panier 
        $order = $orderRepository->findOneBy([
            'id' => $id_order,
            'user' => $this->getUser(), // remplir le champ user dans stripe et permer de sécurier nitre site 
        ]);

        // si la commande ne correspond pas l'utlisateur on renvoie a la page d'accueil
        if (!$order) {
            return $this->redirectToRoute('app_home');
        }

        $products_for_stripe = []; // tableau pour les products dans stripe
        
        foreach ($order->getOrderDetails() as $product)
        {
            $products_for_stripe[] = [
                'price_data' => [
                    'currency' => 'eur',
                    // afficher les prix sans séparateur en decimal, rien en séparateur en centaimes 
                    'unit_amount' => number_format($product->getProductPriceWt() * 100, 0, '', ''),
                    'product_data' => [
                        'name' => $product->getProductName(),
                        'images' => [
                            $_ENV['DOMAIN'].'/uploads/'.$product->getProductIllustration()
                        ],
                    ],
                ],
                'quantity' => $product->getProductQuantity(),
            ];
        }

        // ajouter le info de transporteur au panier stripe
        $products_for_stripe[] = [
            'price_data' => [
                'currency' => 'eur',
                // afficher les prix sans séparateur en decimal, rien en séparateur en centaimes 
                'unit_amount' => number_format($order->getCarrierPrice() * 100, 0, '', ''),
                'product_data' => [
                    'name' => 'Transporteur : ' . $order->getCarrierName(),
                ],
            ],
            'quantity' => 1,
        ];

        // créer une session de paiement avec stripe, avec les informations de prix, le nom du produit, etc.
        $checkout_session = Session::create([
            'payment_method_types' => ['card'], // choisir la méthode de paiement
            'customer_email' => $this->getUser()->getEmail(), // remplir le cahamp email dans stripe
            'line_items' => [[
                $products_for_stripe
            ]],
            'mode' => 'payment',
            'success_url' => $_ENV['DOMAIN'] . '/commande/valider/{CHECKOUT_SESSION_ID}',
            'cancel_url' => $_ENV['DOMAIN'] . '/mon-panier/annulation',
        ]);

        $order->setStripeSessionId($checkout_session->id); // enrigitrer l'id du stripe dans la BDD order
        $entityManager->flush(); 

        return $this->redirect($checkout_session->url);
    }    

    // route de redirection vers la page de succès après le paiement
    #[Route('/commande/valider/{stripe_session_id}', name: 'app_payment_success')]
    public function success($stripe_session_id, OrderRepository $orderRepository, EntityManagerInterface $entityManager, Cart $cart): Response
    {
        $order = $orderRepository->findOneBy([
            'stripe_session_id' => $stripe_session_id,
            'user' => $this->getUser(),
        ]);

         // si la commande ne correspond pas l'utlisateur on renvoie a la page d'accueil
        if (!$order) {
            return $this->redirectToRoute('app_home');
        }

        if ($order->getState() == 1){
            $order->setState(2);
            $cart->remove(); // vider le panier après la validation de la commande
            $entityManager->flush(); //enregistrer dans la BDD
        }

        // si la commande est payée, on marque le statut à "payée"
        return $this->render('payment/success.html.twig', [
            'order' => $order,
            ]);
    }
}
