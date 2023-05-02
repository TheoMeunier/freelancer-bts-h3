<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\PrestationComments;
use App\Entity\Contact;
use App\Entity\Prestation;
use App\Entity\User;
use App\Form\PrestationCommentsType;
use App\Form\ContactType;
use App\Form\PrestationType;
use App\Repository\PrestationRepository;
use App\Services\FileServiceInterface;
use App\Services\MailerServiceInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use DateTime;

class PrestationController extends AbstractController
{
    public function __construct(
        private PrestationRepository $repository,
        private EntityManagerInterface $em,
        private FileServiceInterface $fileService,
        private MailerServiceInterface $mailerService,
        private string $uploadDirectory
    ) {
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
    public function show(int $id, Request $request): Response
    {
        $prestation = $this->repository->find($id);

        $contact = new Contact();
        $form = $this->createForm(ContactType::class, $contact);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->mailerService->send(
                $this->getParameter('email_noreply'),
                $this->getParameter('email'),
                'Contact Prestation',
                'email/contact/contact.html.twig',
                'email/contact/contact.txt.twig',
                ['contact' => $contact],
            );

            $this->addFlash('success', 'Send Mail');

            return $this->redirectToRoute('app_prestation_show', ['id' => $prestation->getId()]);
        }

        //Commentaire
        $comment = new PrestationComments;
        $commentForm = $this->createForm(PrestationCommentsType::class, $comment);

        $commentForm -> handleRequest($request);
        if($commentForm->isSubmitted() && $commentForm->isValid()){
            $comment->setPrestation($prestation);
            $comment->setUser($this->getUser());

            $this->em->persist($comment);
            $this->em->flush();

            $this->addFlash('success', 'Votre commentaire a bien été envoyé!');
            return $this->redirectToRoute('app_prestation_show',['id' => $prestation->getId()]);
        }

        return $this->render('prestation/show.html.twig', [
            'prestation' => $prestation,
            'form' => $form->createView(),
            'commentForm' => $commentForm->createView()
        ]);
    }

    #[Route('/prestation/edit/{id}', name: 'app_prestation_edit', methods: ['GET', 'POST'])]
    public function edit(Prestation $prestation, Request $request): RedirectResponse|Response
    {
        $form = $this->createForm(PrestationType::class, $prestation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $dataImage = $form->get('image')->getData();

            if ($dataImage) {
                $fileImage = $this->fileService->upload($dataImage, $this->uploadDirectory);
                $prestation->setImage($fileImage);
            }

            $this->em->flush();
            $this->addFlash('success', " La prestation à bien été modifier avec succès");

            return $this->redirectToRoute('app_profile_prestation');
        }

        return $this->render('prestation/edit.html.twig', [
            'form' => $form->createView(),
            'prestation' => $prestation
        ]);
    }

    #[Route('/prestation/delete/{id}', name: 'app_prestation_delete', methods: ['POST', 'DELETE'])]
    public function delete(Prestation $prestation, Request $request): RedirectResponse
    {
        if ($this->isCsrfTokenValid('delete' . $prestation->getId(), $request->get('_token'))) {

            $this->em->remove($prestation);
            $this->em->flush();
            $this->addFlash('success', " La prestation à bien été supprimer avec succès");
        }

        return $this->redirectToRoute('app_profile_prestation');
    }
}
