<?php

namespace App\Controller;

use App\Repository\PrestationRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{

    public function __construct(
        private PrestationRepository $repository,
        private PaginatorInterface $paginator
    )
    {
    }

    #[Route('/', name: 'app_home')]
    public function index(): Response
    {
        $prestations = $this->paginator->paginate(
            $this->repository->findAll(),
            1,
            9
        );

        return $this->render('home/index.html.twig', [
            'prestations' => $prestations
        ]);
    }

}
