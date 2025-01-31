<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ProductController extends AbstractController
{
    // Affichage d'un produit selon son slug (titre URL)
    #[Route('/produit/{slug}', name: 'app_product')]
    public function index($slug, ProductRepository $productRepository): Response
    {
        
        $product = $productRepository->findOneBySlug($slug);

        // si le produit n'existe pas, on redirige vers la page d'accueil
        if (!$product) {
            return $this->redirectToRoute('app_home');
        }

        return $this->render('product/index.html.twig', [
            'product' => $product,
        ]);
    }
}
