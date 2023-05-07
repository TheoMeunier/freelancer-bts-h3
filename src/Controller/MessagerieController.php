<?php

namespace App\Controller;

use App\Entity\Message;
use App\Entity\Messagerie;
use App\Form\MessagesType;
use App\Repository\MessagerieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MessagerieController extends AbstractController
{

    public function __construct(
        private readonly EntityManagerInterface $em,
        private readonly MessagerieRepository $repository
    )
    {
    }

    #[Route('/profile/messagerie', name: 'app_profile_messagerie')]
    public function index(): Response
    {
        $messageries = $this->repository->getMessagerisUser($this->getUser());

        return $this->render('profile/messageries/index.html.twig', [
            'messageries' => $messageries,
        ]);
    }

    #[Route('/profile/messagerie/{id}', name: 'app_profile_messagerie_show')]
    public function show(Messagerie $messagerie, Request $request): Response
    {
        $message = new Message();
        $form = $this->createForm(MessagesType::class, $message);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $message->setUser($this->getUser());
            $message->setMessagerie($messagerie);

            $this->em->persist($message);
            $this->em->flush();

            $this->addFlash('success', 'Message envoyé');
            return $this->redirectToRoute('app_profile_messagerie_show', ['id' => $messagerie->getId()]);
        }

        return $this->render('profile/messageries/show.html.twig', [
            'messagerie' => $messagerie,
            'form' => $form->createView()
        ]);
    }
}
