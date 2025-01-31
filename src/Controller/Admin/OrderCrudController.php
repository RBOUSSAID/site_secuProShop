<?php

namespace App\Controller\Admin;

use App\Entity\Order;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class OrderCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Order::class;
    }

    // Modification du label des entités dans EasyAdmin CRUD pour le(s) commande(s)
    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Commande')
            ->setEntityLabelInPlural('Commandes')
        ;
    }

    // Suppression des actions par défaut sur la page d'index des commandes EasyAdmin CRUD
    public function configureActions(Actions $actions): Actions
    {
        $show = Action::new('Voir détail')->linkToCrudAction('show'); // Création d'une action pour afficher le détail d'une commande
        return $actions
            ->add(Crud::PAGE_INDEX, $show) // Ajout d'une action pour afficher le détail d'une commande
            ->remove(Crud::PAGE_INDEX, Action::DELETE)
            ->remove(Crud::PAGE_INDEX, Action::NEW)
            ->remove(Crud::PAGE_INDEX, Action::EDIT)
        ;
    }

    // Création d'une vue spécifique pour afficher le détail d'une commande EasyAdmin CRUD
    public function show(AdminContext $context)
    {
        $order = $context->getEntity()->getInstance();
        return $this->render('admin/order.html.twig', [
        'order' => $order
        ]);  // Passage de la commande à la vue);
    }

    // Configuration des champs à afficher dans la liste et dans le formulaire d'édition EasyAdmin CRUD pour les commandes
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            DateField::new('createdAt')->setLabel('Date de création'),
            NumberField::new('state')->setLabel('Status de la commande')->setTemplatePath('admin/state.html.twig'),
            AssociationField::new('user')->setLabel('Client'),
            TextField::new('carrierName')->setLabel('Nom du transporteur'),
            NumberField::new('totalTva')->setLabel('Total TVA'),
            NumberField::new('totalWt')->setLabel('Total TTC')
        ];
    }
}
