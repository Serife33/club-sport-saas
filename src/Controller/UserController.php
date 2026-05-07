<?php

namespace App\Controller;

use App\Entity\Club;
use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class UserController extends AbstractController
{
    #[Route('/users', name: 'app_user_index', methods:['GET', 'POST'])]
    public function index(UserRepository $userRepo): Response
    {
        /** @var \App\Entity\User $currentUser */
        $currentUser = $this->getUser();
        $club = $currentUser->getClub();
        $users = $userRepo->findBy(['club' => $club]);

        return $this->render('user/index.html.twig', [
            'users' => $users,
        ]);
    }

    #[Route('/users/{id}', name: 'app_user_show')]
    public function show(User $user) 
    {
        return $this->render('user/show.html.twig', [
            'user' => $user
        ]);
    }

    #[Route('/users/{id}/edit', name: 'app_user_edit', methods:['GET', 'POST'])]
    public function edit(User $user, Request $request, EntityManagerInterface $em)
    {
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            $this->addFlash('success', 'Membre modifié avec succès.');
            return $this->redirectToRoute('app_user_show', ['id' => $user->getId()]);
        }

        return $this->render('user/edit.html.twig', [
            'form' => $form,
            'user' => $user,
        ]);
    }
}
