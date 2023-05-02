<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\PrestationSearch;
use App\Form\PrestationSearchType;
use App\Repository\PrestationRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    public function __construct(
        private PrestationRepository $repository,
        private PaginatorInterface $paginator
    ) {
    }

    #[Route('/', name: 'app_home')]
    public function index(Request $request): Response
    {
        $search = new PrestationSearch();
        $form = $this->createForm(PrestationSearchType::class, $search);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $search->setPage($request->query->getInt('page', 1));
            $prestations = $this->repository->findBySearch($search);

            return $this->render('home/index.html.twig', [
                'prestations' => $prestations,
                'form' => $form->createView()
            ]);
        }

        return $this->render('home/index.html.twig', [
            'prestations' => $this->paginator->paginate($this->repository->findAll(), $request->query->getInt('page', 1), 12),
            'form' => $form->createView()
        ]);
    }
}
