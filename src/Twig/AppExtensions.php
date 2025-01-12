<?php

namespace App\Twig;

use App\Classe\Cart;
use Twig\TwigFilter;
use Twig\Extension\GlobalsInterface;
use Twig\Extension\AbstractExtension;
use App\Repository\CategoryRepository;

class AppExtensions extends AbstractExtension implements GlobalsInterface
{

    // Injection du repository CategoryRepository pour récupérer les catégories du repository d'entités Category
    private $categoryRepository;
    private $cart; // Injection du panier
    public function __construct(CategoryRepository $categoryRepository, Cart $cart)
    {
        $this->categoryRepository = $categoryRepository; // Injection du repository
        $this->cart = $cart; // Injection du panier
    }

    // Créer la fonction formatPrice et getPrice pour gérer le formattage du prix
    public function getFilters()
    {
        return [
            new TwigFilter('price', [$this, 'formatPrice']), // Ajout du filtre 'price' pour formater le prix
        ];
    }

    // Fonction pour formater un nombre en euros avec deux décimales et la virgule comme séparateur de milliers
    public function formatPrice($number)
    {
        return number_format($number, '2', ',') . ' €'; // Formatage du prix avec deux décimales et la virgule comme séparateur de milliers
    }

    //la fonction getGlobals pour ajouter des variables globales à Twig
    public function getGlobals(): Array
    {
        return [
            'allCategories' => $this->categoryRepository->findAll(), // Récupérer toutes les catégories
            'fullCartQuantity' => $this->cart->fullQuantity(), //   
        ];
    }


}