<?php

namespace App\Controller\Admin;

use App\Entity\Alternance;
use App\Entity\Candidature;
use App\Entity\Conversation;
use App\Entity\Event;
use App\Entity\Faq;
use App\Entity\Message;
use App\Entity\ProjetTER;
use App\Entity\Selection;
use App\Entity\Stage;
use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractDashboardController
{
    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        return $this->render('admin/index.html.twig');

        // Option 1. You can make your dashboard redirect to some common page of your backend
        //
        // $adminUrlGenerator = $this->container->get(AdminUrlGenerator::class);
        // return $this->redirect($adminUrlGenerator->setController(OneOfYourCrudController::class)->generateUrl());

        // Option 2. You can make your dashboard redirect to different pages depending on the user
        //
        // if ('jane' === $this->getUser()->getUsername()) {
        //     return $this->redirect('...');
        // }

        // Option 3. You can render some custom template to display a proper dashboard with widgets, etc.
        // (tip: it's easier if your template extends from @EasyAdmin/page/content.html.twig)
        //
        // return $this->render('some/path/my-dashboard.html.twig');
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Administration');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');
        yield MenuItem::linkToCrud('Alternance', 'fas fa-list', Alternance::class);
        yield MenuItem::linkToCrud('Candidature', 'fas fa-list', Candidature::class);
        yield MenuItem::linkToCrud('Conversation', 'fas fa-list', Conversation::class);
        yield MenuItem::linkToCrud('Event', 'fas fa-list', Event::class);
        yield MenuItem::linkToCrud('Faq', 'fas fa-list', Faq::class);
        yield MenuItem::linkToCrud('Message', 'fas fa-list', Message::class);
        yield MenuItem::linkToCrud('Projet TER', 'fas fa-list', ProjetTER::class);
        yield MenuItem::linkToCrud('Selection', 'fas fa-list', Selection::class);
        yield MenuItem::linkToCrud('Stage', 'fas fa-list', Stage::class);
        yield MenuItem::linkToCrud('User', 'fa fa-user', User::class);
    }
}
