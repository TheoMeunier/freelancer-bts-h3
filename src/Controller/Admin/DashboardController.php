<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Entity\Category;
use App\Entity\Prestation;
use App\Entity\PrestationComments;
use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractDashboardController
{
    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        return $this->render('admin/index.html.twig');
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Administation');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');

        yield MenuItem::section('Users');
        yield MenuItem::linkToCrud('Utilisateurs', 'fa-solid fa-user', User::class);

        yield MenuItem::section('Prestations');
        yield MenuItem::linkToCrud('Prestations', 'fa-sharp fa-solid fa-laptop', Prestation::class);
        yield MenuItem::linkToCrud('Categories', 'fa-solid fa-swatchbook', Category::class);
        yield MenuItem::linkToCrud('Commentaires', 'fa-regular fa-message', PrestationComments::class);
    }
}
