<?php

namespace App\Controller;

use App\Entity\Messageries;
use App\Entity\Messages;
use App\Form\MessagesType;
use App\Repository\MessagesRepository;
use Doctrine\ORM\EntityManagerInterface;
use http\Message;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MessagerieController extends AbstractController
{

    public function __construct(
        private readonly EntityManagerInterface $em,
    )
    {
    }

    #[Route('/profile/messagerie', name: 'app_profile_messagerie')]
    public function index(): Response
    {
        return $this->render('profile/messageries/index.html.twig', [
            'controller_name' => 'MessagerieController',
        ]);
    }

    #[Route('/profile/messagerie/{id}', name: 'app_profile_messagerie_show')]
    public function show(Messageries $messageries, Request $request): Response
    {
        $message = new Messages();
        $form = $this->createForm(MessagesType::class, $message);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->persist($message);
            $this->em->flush();

            $this->addFlash('success', 'Message envoyé');
            return $this->redirectToRoute('app_profile_messagerie_show', ['id', $messageries->getId()]);
        }

        return $this->render('profile/messageries/show.html.twig', [
            'messagerie' => $messageries,
            'form' => $form->createView()
        ]);
    }
}
