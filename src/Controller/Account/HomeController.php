<?php

namespace App\Controller\Account;

use App\Repository\OrderRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    //Route pour afficher le template du compte utilisateur sur la page /compte.html.twig. 
    #[Route('/compte', name: 'app_account')]
    public function index(OrderRepository $orderRepository): Response
    {
        // afficher les commandes pour l'utlisateur sur le page /compte.html.twig
        $orders = $orderRepository->findBy([
            'user' => $this->getUser(),
            'state' => [2, 3]
        ]);
        return $this->render('account/index.html.twig', [
            'orders' => $orders,
        ]);
    }
}
