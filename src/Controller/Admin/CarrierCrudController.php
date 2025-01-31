<?php

namespace App\Controller\Admin;

use App\Entity\Carrier;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class CarrierCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Carrier::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            // Personaliser le label des entités en français pour les actions CRUD
            ->setEntityLabelInSingular('Transporteur')
            ->setEntityLabelInPlural('Transporteurs')
        ;
    }

   // Configurer les champs à afficher dans la liste et dans le formulaire d'édition EasyAdmin CRUD (pour transporteurs   )
    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('name')->setLabel("Nom du transporteur"),
            TextareaField::new('description')->setLabel("Description du transporteur"),
            NumberField::new('price')->setLabel('Prix T.T.C')->setHelp('Prix du transporteur en H.T.C (en euros)'),
        ];
    }
    
}
