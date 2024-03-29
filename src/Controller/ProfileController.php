<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\InformationUser;
use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class ProfileController extends AbstractController
{
    public function __construct(
        private readonly UserRepository $repository,
        private readonly EntityManagerInterface $em
    ) {
    }

    #[Route('/profile', name: 'app_profile')]
    public function index(): Response
    {
        return $this->render('profile/show/index.html.twig', [
            'controller_name' => 'ProfileController',
        ]);
    }

    #[Route('/profile/prestation', name: 'app_profile_prestation')]
    public function prestation(): Response
    {
        return $this->render('profile/show/prestation.html.twig', [
            'controller_name' => 'ProfileController',
        ]);
    }

    #[Route('/profile/commande', name: 'app_profile_commande')]
    public function commande(): Response
    {
        return $this->render('profile/show/commande.html.twig', [
            'controller_name' => 'ProfileController',
        ]);
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
        return $this->redirectToRoute('app_profile');
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
        return $this->redirectToRoute('app_profile');
    }

    #[Route('/profile/edit/password', name: 'app_profile_edit_password', methods: 'POST')]
    public function updatePassword(Request $request, UserPasswordHasherInterface $hasher): RedirectResponse
    {
        $submittedToken = $request->request->get('profile_update_password');

        if ($this->isCsrfTokenValid('profile_update_information', $submittedToken)) {
            $password = $request->get('password') === $request->get('confirmation');

            if ($request->get('password') === $request->get('confirm-password')) {
                $this->getAuthUser()->setPassword($hasher->hashPassword($this->getAuthUser(), $password));

                $this->em->persist($this->getAuthUser());
                $this->em->flush();
            }
        }

        $this->addFlash('success', 'Update Password with success');
        return $this->redirectToRoute('app_profile');
    }

    private function getAuthUser(): User
    {
        /** @var User $user */
        $user = $this->getUser();
        return $this->repository->find($user);
    }
}
