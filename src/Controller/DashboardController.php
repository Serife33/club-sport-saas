<?php

namespace App\Controller;

use App\Repository\EventRepository;
use App\Repository\TeamRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class DashboardController extends AbstractController
{
    #[Route('/dashboard', name: 'app_dashboard')]
    public function index(
        UserRepository $userRepository,
        TeamRepository $teamRepository,
        EventRepository $eventRepository,
    ): Response {
        return $this->render('dashboard/index.html.twig', [
            'stats' => [
                'members' => $userRepository->count([]),
                'teams'   => $teamRepository->count([]),
                'events'  => $eventRepository->count([]),
            ],
            'recent_members' => $userRepository->findBy([], ['createdAt' => 'DESC'], 5),
        ]);
    }
}
