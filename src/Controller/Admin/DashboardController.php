<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Entity\Order;
use App\Entity\Header;
use App\Entity\Carrier;
use App\Entity\Product;
use App\Entity\Category;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Config\UserMenu;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;

class DashboardController extends AbstractDashboardController
{
    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {

        //  redirigez dashboard vers une page commune de votre backend (UserCrudController)
        $adminUrlGenerator = $this->container->get(AdminUrlGenerator::class);
        return $this->redirect($adminUrlGenerator->setController(UserCrudController::class)->generateUrl());

    }

    // Configure les éléments du menu utilisateur
    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('SecuProShop');
    }

    //Génère les éléments du menu de l'admin
    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');
        yield MenuItem::linkToCrud('Utilisateurs', 'fas fa-list', User::class); // cette liste du dashboard vers une page commun  user
        yield MenuItem::linkToCrud('Catégories', 'fas fa-list', Category::class); // cette liste du dashboard vers une page commun  category
        yield MenuItem::linkToCrud('Produits', 'fas fa-list', Product::class);  // cette liste du dashboard vers une page commun product
        yield MenuItem::linkToCrud('Transporteur', 'fas fa-list', Carrier::class);  // cette liste du dashboard vers une page commun  carrier
        yield MenuItem::linkToCrud('Commandes', 'fas fa-list', Order::class);
        yield MenuItem::linkToCrud('Header', 'fas fa-list', Header::class);
    }
}
