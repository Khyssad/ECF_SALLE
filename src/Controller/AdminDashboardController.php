<?php

namespace App\Controller;

use App\Entity\Reservation;
use App\Repository\ReservationRepository;
use App\Repository\RoomRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminDashboardController extends AbstractController
{
    #[Route('/admin', name: 'admin_dashboard')]
    public function index(ReservationRepository $reservationRepo, RoomRepository $roomRepo, UserRepository $userRepo): Response
    {
        return $this->render('admin/dashboard.html.twig', [
            'pre_reservations_count' => $reservationRepo->countByStatus(Reservation::STATUS_PRE_RESERVED),
            'confirmed_reservations_count' => $reservationRepo->countByStatus(Reservation::STATUS_CONFIRMED),
            'rooms_count' => $roomRepo->count([]),
            'users_count' => $userRepo->count([]),
        ]);
    }
}