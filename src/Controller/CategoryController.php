<?php

namespace App\Controller;

use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CategoryController extends AbstractController
{
    #[Route('/categorie/{slug}', name: 'app_category')]
    public function index($slug, CategoryRepository $categoryRepository): Response
    {
    //(Repository a quoi sert =)j'ouvre la catégorie correspondante dans la base de données
    //connectez-vous à la base de données
    //fait une action pour récupérer la catégorie par son slug dans la base de données
    //retourne la catégorie à la vue

    $category = $categoryRepository->findOneBySlug($slug);

    // Si la catégorie n'existe pas, renvoyer une page d'acceuil
    if (!$category) {
        return $this->redirectToRoute('app_home');
    }
        return $this->render('category/index.html.twig', [
            'category' => $category,
        ]);
    }
}
