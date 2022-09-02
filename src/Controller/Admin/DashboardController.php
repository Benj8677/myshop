<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Entity\Produit;
use App\Entity\Commande;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Controller\Admin\CommandeCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;

class DashboardController extends AbstractDashboardController
{
    #[Route('/admin', name: 'app_admin')]
    public function index(): Response
    {
        //return parent::index();
        $adminUrlGenerator = $this->container->get(AdminUrlGenerator::class);

        // Option 1. Make your dashboard redirect to the same page for all users
        return $this->redirect($adminUrlGenerator->setController(CommandeCrudController::class)->generateUrl());

    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('MyShop');
    }

    public function configureMenuItems(): iterable
    {
        return [
            MenuItem::linkToRoute('retour au site', 'fa fa-home', 'app_boutique'),
            MenuItem::section('boutique'),
            MenuItem::linkToCrud('Produit', 'fas fa-socks', Produit::class),
            MenuItem::linkToCrud('Commande', 'fas fa-coins', Commande::class),
            MenuItem::linkToCrud('Utilisateur', 'fas fa-user', User::class),
        ];
    }

    // public function configureCrud(): Crud
    // {
    //     return Crud::new()
    //         ->overrideTemplates([
    //             'crud/index' => 'admin/index.html.twig',
    //         ])
    //     ;
    // }
}
