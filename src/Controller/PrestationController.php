<?php

namespace App\Controller;

use App\Entity\Prestation;
use App\Entity\User;
use App\Form\PrestationType;
use App\Repository\PrestationRepository;
use App\Services\FileServiceInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PrestationController extends AbstractController
{

    public function __construct(
        private PrestationRepository $repository,
        private EntityManagerInterface $em,
        private FileServiceInterface $fileService,
        private string  $uploadDirectory
    )
    {
    }

    #[Route('/prestation/create', name: 'app_prestation_create')]
    public function create(Request $request): Response
    {
        $prestation = new Prestation();
        $form = $this->createForm(PrestationType::class, $prestation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var User $user */
            $user = $this->getUser();
            $dataImage = $form->get('image')->getData();

            $file = $this->fileService->upload($dataImage, $this->uploadDirectory);
            $prestation->setUser($user);
            $prestation->setImage($file);

            $this->em->persist($prestation);
            $this->em->flush();

            $this->addFlash('success', 'Votre prestation à bien été créer');
            return $this->redirectToRoute('app_home');
        }

        return $this->render('prestation/create.html.twig', [
            'form' => $form->createView()
        ]);
    }


    #[Route('/prestation/{id}', name: 'app_prestation_show')]
    public function show(int $id): Response
    {
        $prestation = $this->repository->find($id);

        return $this->render('prestation/show.html.twig', [
            'prestation' => $prestation
        ]);
    }
}
