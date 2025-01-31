<?php

namespace App\Controller;

use App\Classe\Cart;
use App\Entity\Order;
use App\Form\OrderType;
use App\Entity\OrderDetail;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class OrderController extends AbstractController
{
    /*
    * 1ére étape du tunnel d'achat
    * Choix de l'adresse de livraison et du transporteur.
    */
    #[Route('/commande/livraison', name: 'app_order')]
    public function index(): Response
    {
        $addresses = $this->getUser()->getAddresses(); // récupère les adresses du compte utilisateur
        
        if (count($addresses) == 0){
            return $this->redirectToRoute('app_account_address_form'); // renvoyer vers la page d'ajout d'adresses si aucune adresse n'est disponible
        }

        // création du formulaire de commande avec les adresses disponibles sur la page /commande/livraison.html.twig.
        $form = $this->createForm(OrderType::class, null, [
            'addresses' => $addresses, // récupère les adresses du compte utilisateur
            'action' => $this->generateUrl('app_order_summary') // renvoie vers la page de récapitulatif de la commande après validation du formulaire
        ]);

        // renvoie vers la page de commande de livraison et de transporteur sur la page /commande/livraison.html.twig.
        return $this->render('order/index.html.twig', [
            'deliveryForm' => $form->createView()
        ]);
    }

    /*
    * 2ème étape du tunnel d'achat
    * Récapitulatif de la commande et confirmation de l'achat.
    * Insertion dans la base de données de la commande.
    * Prépartion de payment.
    */
    #[Route('/commande/recapitulatif', name: 'app_order_summary')]
    public function add(Request $request, Cart $cart, EntityManagerInterface $entityManager): Response
    {

        if ($request->getMethod() != 'POST') {
            return $this->redirectToRoute('app_cart');
        }    
        $form = $this->createForm(OrderType::class, null, [
            'addresses' => $this->getUser()->getAddresses(), // récupère les adresses du compte utilisateur
        ]);

        $products = $cart->getCart(); // récupérer les produits du panier
        $form->handleRequest($request); // ça nous permet de récupérer les données du formulaire

        //stocker les données du formulaire dans BDD si les données sont valides et que le formulaire est soumis
        if ($form->isSubmitted() && $form->isValid()) {
            // stocker les données du formulaire dans la base de données ici...

            //création de la chaîne adresse
            $addressObject = $form->get('addresses')->getData(); // récupère les données dans la base de données 

            $address = $addressObject->getFirstname().''. $addressObject->getLastName(). '</br>';
            $address .= $addressObject->getAddress(). '</br>';
            $address .= $addressObject->getPostal().' '.$addressObject->getCity(). '</br>';
            $address .= $addressObject->getCountry(). '</br>';
            $address .= $addressObject->getPhone();

            $order = new Order();
            $order->setUser($this->getUser()); // lier la commande à l'utilisateur courant
            $order->setCreatedAt(new \DateTime());
            $order->setState(1); // initialiser le statut de la commande à 1 (en attente de payement)
            $order->setCarrierName($form->get('carriers')->getData()->getName());
            $order->setCarrierPrice($form->get('carriers')->getData()->getPrice());
            $order->setDelivery($addressObject);

            foreach ($products as $product) {
                $orderDetail = new OrderDetail(); // création d'un objet OrderDetail
                $orderDetail->setProductName($product['object']->getName());
                $orderDetail->setProductIllustration($product['object']->getIllustration());
                $orderDetail->setProductPrice($product['object']->getPrice());
                $orderDetail->setProductTva($product['object']->getTva());
                $orderDetail->setProductQuantity($product['quantity']);
                $order->addOrderDetail($orderDetail); // ajouter le OrderDetail à la commande
            }

            $entityManager->persist($order); // persister la commande dans la base de données
            $entityManager->flush(); // sauvegarder les modifications dans la base de données
        }

        return $this->render('order/summary.html.twig', [
        'choices' => $form->getData(),
        'cart' => $products,
        'order' => $order, // accder au liste des commandes (pour srtipe)
        'totalWt' => $cart->getTotalWt() // calculer le total de la commande
        ]);
    }
}
