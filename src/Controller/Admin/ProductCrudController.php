<?php

namespace App\Controller\Admin;

use App\Entity\Product;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\SlugField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class ProductCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Product::class;
    }

      // Configurer les paramètres(personalisation d'affichage par exemple) EasyAdmin CRUD
    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Produit')
            ->setEntityLabelInPlural('Produits')
        ;
    }

    // Configurer les champs à afficher dans la liste et dans le formulaire d'édition EasyAdmin CRUD (pour produits   )
    public function configureFields(string $pageName): iterable
    {
        // Récupérer le statut de la page pour déterminer si on est en édition ou création d'un produit
        $required = true;
        if ($pageName == 'edit'){
            $required = false;
        }

        // Champs à afficher dans la liste et dans le formulaire d'édition Easy
        return [
            TextField::new('name')->setLabel('Nom')->setHelp('Nom du produit'), // TextField pour le nom du produit
            BooleanField::new('isHomepage')->setLabel('Produit à la une')->setHelp('Vous permet d\'afficher un produit sur la homepage'),
            SlugField::new('slug')->setTargetFieldName('name')->setHelp('URL unique pour le produit générée automatiquement'), // SlugField pour générer une URL unique pour le produit
            TextEditorField::new('description')->setLabel('Description')->setHelp('Description du produit'), // TextEditorField pour la description du produit
            ImageField::new('illustration')
            ->setLabel('Image')
            ->setHelp('Image du produit')
            ->setUploadedFileNamePattern('[year]-[month]-[day]-[contenthash].[extension]')
            ->setBasePath('/uploads')
            ->setUploadDir('/public/uploads')
            ->setRequired($required), // ImageField pour l'image du produit

            NumberField::new('price')->setLabel('Prix H.T')->setHelp('Prix du produit en H.T. (en euros)'), // NumberField pour le prix du produit
            ChoiceField::new('tva')->setLabel('Taux de TVA')->setChoices([
                '0%' => 0,
                '5%' => 5,
                '10%' => 10,
                '20%' => 20,
                '25%' => 25,
                '30%' => 30,
            ]), // ChoiceField pour le taux de TVA du produit
            AssociationField::new('category')->setLabel('Catégorie'), // AssociationField pour lier les produits à une catégorie
        ];
    }
}
