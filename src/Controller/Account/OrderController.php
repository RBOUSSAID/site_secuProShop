<?php

namespace App\Controller\Account;

use App\Repository\OrderRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class OrderController extends AbstractController
{
    #[Route('/compte/commande/{id_order}', name: 'app_account_order')]
    public function index($id_order, OrderRepository $orderRepository): Response
    {
        // Récupérer la commande avec l'ID fourni
        $order = $orderRepository->findOneBy([
            'id' => $id_order,
            'user' => $this->getUser(), // remplir le champ user et permettre de sécuriser le site
        ]);

        // Si la commande ne correspond pas à l'utilisateur, renvoyer a la page d'accueil
        if (!$order) {
            return $this->redirectToRoute('app_home');
        }
        
        // renvoyer le template avec la commande et les détails
        return $this->render('account/order/index.html.twig', [
            'order' => $order,
        ]);
    }
}
