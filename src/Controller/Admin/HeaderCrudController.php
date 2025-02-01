<?php

namespace App\Controller\Admin;

use App\Entity\Header;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class HeaderCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Header::class;
    }

    public function configureFields(string $pageName): iterable
    {
        // Récupérer le statut de la page pour déterminer si on est en édition ou création d'un produit
        $required = true;
        if ($pageName == 'edit'){
            $required = false;
        }
        
        // Champs à afficher dans le Header de notre site dans la page homepage
        return [
            TextField::new('title', 'Titre'),
            TextareaField::new('content', 'Contenu'),
            TextareaField::new('buttonTitle', 'Titre du button'),
            TextareaField::new('buttonLink', 'URL du button'),
            ImageField::new('illustration')
            ->setLabel('Image de fond du Header')
            ->setHelp('Image de fond du header')
            ->setUploadedFileNamePattern('[year]-[month]-[day]-[contenthash].[extension]')
            ->setBasePath('/uploads')
            ->setUploadDir('/public/uploads')
            ->setRequired($required),
        ];
    }
}
