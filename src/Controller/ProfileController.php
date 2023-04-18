<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\InformationUser;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use http\Client\Curl\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class ProfileController extends AbstractController
{
    public function __construct(
        private UserRepository $repository,
        private EntityManagerInterface $em
    ) {
    }

    #[Route('/profile', name: 'app_profile')]
    public function index(): Response
    {
        return $this->render('profile/index.html.twig', [
            'controller_name' => 'ProfileController',
        ]);
    }

    #[Route('/profile/edit', name: 'app_profile_edit')]
    public function edit(): Response
    {
        return $this->render('profile/edit.html.twig');
    }

    #[Route('/profile/edit/user', name: 'app_profile_edit_user', methods: 'POST')]
    public function updateUser(Request $request): RedirectResponse
    {
        $submittedToken = $request->request->get('profile_update_user');

        if ($this->isCsrfTokenValid('profile_update_user', $submittedToken)) {
            $this->getAuthUser()->setName($request->get('name'));
            $this->getAuthUser()->setEmail($request->get('email'));

            $this->em->persist($this->getAuthUser());
            $this->em->flush();
        }

        $this->addFlash('success', 'Update User with success');
        return $this->redirectToRoute('app_profile_edit');
    }

    #[Route('/profile/edit/information', name: 'app_profile_edit_information', methods: 'POST')]
    public function UpdateUserInformation(Request $request): RedirectResponse
    {
        $submittedToken = $request->request->get('profile_update_information');
        $information = $this->getUser()->getInformationUser() ?? new InformationUser();

        if ($this->isCsrfTokenValid('profile_update_information', $submittedToken)) {
            if ($information->getId() === null) {
                $information->setUser($this->getUser());
            }

            $information->setPhone($request->get('phone'));
            $information->setCity($request->get('city'));
            $information->setPays($request->get('pays'));
            $information->setDescription($request->get('description'));

            $this->em->persist($information);
            $this->em->flush();
        }

        $this->addFlash('success', 'Update User with success');
        return $this->redirectToRoute('app_profile_edit');
    }

    #[Route('/profile/edit/password', name: 'app_profile_edit_password', methods: 'POST')]
    public function updatePassword(Request $request, UserPasswordHasherInterface $hasher): RedirectResponse
    {
        $submittedToken = $request->request->get('profile_update_password');

        if ($this->isCsrfTokenValid('profile_update_information', $submittedToken)) {
            $password = $request->get('password') === $request->get('confirmation');

            if ($request->get('password') === $request->get('confirm-password')) {
                $this->getAuthUser()->setPassword($hasher->hashPassword($this->getUser(), $password));

                $this->em->persist($this->getAuthUser());
                $this->em->flush();
            }
        }

        $this->addFlash('success', 'Update Password with success');
        return $this->redirectToRoute('app_profile_edit');
    }

    private function getAuthUser(): ?\App\Entity\User
    {
        /** @var User $userId */
        $userId = $this->getUser();
        $user = $this->repository->find($userId);

        return $user;
    }
}
