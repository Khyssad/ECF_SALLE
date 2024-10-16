<?php

namespace App\Controller;

use App\Entity\Room;
use App\Entity\Reservation;
use App\Entity\User;
use App\Repository\ReservationRepository;
use App\Repository\RoomRepository;
use App\Repository\UserRepository;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminDashboardController extends AbstractDashboardController
{
    private $reservationRepository;
    private $roomRepository;
    private $userRepository;

    public function __construct(
        ReservationRepository $reservationRepository,
        RoomRepository $roomRepository,
        UserRepository $userRepository
    ) {
        $this->reservationRepository = $reservationRepository;
        $this->roomRepository = $roomRepository;
        $this->userRepository = $userRepository;
    }

    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        $pendingReservationsCount = $this->reservationRepository->countPendingReservations();
        $totalRooms = $this->roomRepository->count([]);
        $totalUsers = $this->userRepository->count([]);

        return $this->render('admin_dashboard/dashboard.html.twig', [
            'pending_reservations_count' => $pendingReservationsCount,
            'total_rooms' => $totalRooms,
            'total_users' => $totalUsers,
        ]);
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Système de Réservation de Salles');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');
        yield MenuItem::linkToCrud('Salles', 'fa fa-door-open', Room::class);
        yield MenuItem::linkToCrud('Réservations', 'fa fa-calendar', Reservation::class);
        yield MenuItem::linkToCrud('Utilisateurs', 'fa fa-users', User::class);
        yield MenuItem::linkToRoute('Notifications', 'fa fa-bell', 'admin_notifications');
    }
}