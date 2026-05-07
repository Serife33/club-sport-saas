<?php

namespace App\Controller;

use App\Entity\Invitation;
use App\Entity\User;
use App\Form\ActivateAccountType;
use App\Form\InvitationType;
use App\Repository\InvitationRepository;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

final class InvitationController extends AbstractController
{
    #[Route('/invitation', name: 'app_invitation_index')]
    public function index(InvitationRepository $ir): Response
    {
        /** @var \App\Entity\User $currentUser */
        $currentUser = $this->getUser();
        $club = $currentUser->getClub();
        $invitations = $ir->findByClub($club);

        return $this->render('invitation/index.html.twig', [
            'invitations' => $invitations,
        ]);
    }

    #[IsGranted( 'ROLE_ADMIN')]
    #[Route('/invitation/new', name: 'app_invitation_new', methods:['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $em): Response
    {
        $invitation = new Invitation();
        $form = $this->createForm(InvitationType::class, $invitation);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $invitation->setToken(bin2hex(random_bytes(32)));  // randombytes génére 32 octets aléatoires illisibles. bin2hex convertit ce bruit en une chaine hexadecimal lisible. ça servira de clé dans l'url d'activation. 

            $invitation->setSentAt(new \DateTimeImmutable());

            $invitation->setExpiresAt(new \DateTimeImmutable()->modify('+7 days'));

            $currentUser = $this->getUser();

            $invitation->setInvitedBy($currentUser);

            $em->persist($invitation);
            $em->flush();

            return $this->redirectToRoute('app_invitation_index');

        }

        return $this->render('invitation/new.html.twig', [
            'form' => $form
        ]);
    }

    #[Route('/invitation/activate/{token}', name: 'app_invitation_activate', methods: ['GET', 'POST'])]
    public function activate(string $token, InvitationRepository $ir, EntityManagerInterface $em, Request $request, UserPasswordHasherInterface $passwordHasher): Response
    {
        $invitation = $ir->findOneBy(['token' => $token]);

        // 3 vérifications dans activate()

        // Si le token n'existe pas ou a été modifié alors 
        if (!$invitation) {
            $this->addFlash('danger', 'Ce lien d\'invitation est invalide.');
            return $this->redirectToRoute('app_login');
        }

        // Si le token a déja été utilisée
        if ($invitation->getUsedAt()) {
            $this->addFlash('danger', 'Ce lien d\'invitation a déja été utilisée.');
            return $this->redirectToRoute('app_login');
        }

        // Si le token a deja expiré
        if ($invitation->getExpiresAt() < new \DateTimeImmutable()) {
            $this->addFlash('danger', 'Ce lien d\'invitation a expiré.');
            return $this->redirectToRoute('app_login');
        }

        $user= new User();
        $form = $this->createForm(ActivateAccountType::class, $user);
        $form->handleRequest($request);


        if($form->isSubmitted() && $form->isValid()) {
            $user->setEmail($invitation->getEmail());
            $user->setRole($invitation->getRole());
            $user->setStatus('ACTIVE');
            $user->setPassword($passwordHasher->hashPassword($user, $form->get('plainPassword')->getData()));
            $invitation->setUsedAt(new \DateTimeImmutable());
            
            $em->persist($user);
            $em->flush();

            $this->addFlash('success', 'Votre compte a été activé ! Connectez-vous.');
            return $this->redirectToRoute('app_login');
        }

        return $this->render('invitation/activate.html.twig', [
            'form' => $form,
        ]);
    }
}
