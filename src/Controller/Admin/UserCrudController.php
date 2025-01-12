<?php

namespace App\Controller\Admin;

use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class UserCrudController extends AbstractCrudController
{
    // Retourner le nom de la classe de l'entité associée à EasyAdmin CRUD
    public static function getEntityFqcn(): string
    {
        return User::class;
    }

    // Configurer les paramètres(personalisation d'affichage par exemple) EasyAdmin CRUD
    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Utilisateur')
            ->setEntityLabelInPlural('Utilisateurs')
        ;
    }

    // Configurer les champs utilisés dans EasyAdmin CRUD pour la page d'édition
    public function configureFields(string $pageName): iterable
    {
        return [
            //modifier les champs en fonction de la page (nom, prénom...)
            TextField::new('firstname')->setLabel('Nom'),
            TextField::new('lastname')->setLabel('Prénom'),
            TextField::new('email')->setLabel('Email')->onlyOnIndex()
        ];
    }
    
}
