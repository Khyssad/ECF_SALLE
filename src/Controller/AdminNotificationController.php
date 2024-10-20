<?php

// Controller/AdminNotificationController.php

namespace App\Controller;

use App\Repository\ReservationRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminNotificationController extends AbstractController
{
    private $reservationRepository;
    private $userRepository;

    public function __construct(ReservationRepository $reservationRepository, UserRepository $userRepository)
    {
        $this->reservationRepository = $reservationRepository;
        $this->userRepository = $userRepository;
    }

    #[Route('/admin/notifications', name: 'admin_notifications')]
    public function index(): Response
    {
        $pendingReservations = $this->reservationRepository->findPendingReservations();

        return $this->render('admin/notifications.html.twig', [
            'pending_reservations' => $pendingReservations,
        ]);
    }
}