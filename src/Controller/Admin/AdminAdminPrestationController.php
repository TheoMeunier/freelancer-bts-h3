<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Entity\Prestation;
use App\Form\PrestationType;
use App\Repository\PrestationRepository;
use App\Services\FileServiceInterface;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/prestation', name: 'admin_prestation_')]
class AdminAdminPrestationController extends AbstractController
{
    public function __construct(
        private readonly PrestationRepository $repository,
        private readonly PaginatorInterface $paginator,
        private readonly EntityManagerInterface $em,
        private readonly FileServiceInterface $fileService,
        private readonly string $uploadDirectory
    ) {
    }

    #[Route('/', name: 'index')]
    public function index(Request $request): Response
    {
        $prestations = $this->paginator->paginate(
            $this->repository->findAll(),
            $request->query->getInt('page', 1),
            9,
        );

        return $this->render('admin/prestations/index.html.twig', [
            'prestations' => $prestations,
        ]);
    }

    #[Route('/edit/{id}', name: 'edit', methods: ['GET', 'POST'])]
    public function update(Request $request, Prestation $prestation): Response
    {
        $form = $this->createForm(PrestationType::class, $prestation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $dataImage = $form->get('image')->getData();

            if ($dataImage) {
                $file = $this->fileService->upload($dataImage, $this->uploadDirectory);
                $prestation->setImage($file);
            }

            $this->em->flush();
            $this->addFlash('success', 'Prestation modifiée avec succès');

            return $this->redirectToRoute('admin_prestation_index');
        }

        return $this->render('admin/prestations/edit.html.twig', [
            'prestation' => $prestation,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/delete/{id}', name: 'delete', methods: ['POST', 'DELETE'])]
    public function delete(Request $request, Prestation $prestation): Response
    {
        if ($this->isCsrfTokenValid('delete' . $prestation->getId(), $request->get('_token'))) {
            $this->em->remove($prestation);
            $this->em->flush();
            $this->addFlash('success', 'Prestation supprimée avec succès');
        }

        return $this->redirectToRoute('admin_prestation_index');
    }
}
