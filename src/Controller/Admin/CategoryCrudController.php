<?php

namespace App\Controller\Admin;

use App\Entity\Category;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\SlugField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class CategoryCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Category::class;
    }

    // Configurer les paramètres(personalisation d'affichage par exemple) EasyAdmin CRUD
    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Catégorie')
            ->setEntityLabelInPlural('Catégories')
        ;
    }
    
    // Configurer les champs à afficher dans la liste et dans le formulaire d'édition EasyAdmin CRUD (pout catégories   )
    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('name')->setLabel('Titre')->setHelp('Titre de la catégorie'),
            SlugField::new('slug')->setLabel('URL')->setTargetFieldName('name')->setHelp('URL unique pour la catégorie générée automatiquement'),

        ];
    }
}
