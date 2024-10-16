<?php

namespace App\Controller;

use App\Entity\Reservation;
use App\Repository\ReservationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin/reservation')]
class AdminReservationController extends AbstractController
{
    #[Route('/pending', name: 'admin_pending_reservations')]
    public function pendingReservations(ReservationRepository $reservationRepository): Response
    {
        $pendingReservations = $reservationRepository->findPendingReservations();

        return $this->render('admin/pending_reservations.html.twig', [
            'pending_reservations' => $pendingReservations,
        ]);
    }

    #[Route('/{id}/confirm', name: 'admin_confirm_reservation', methods: ['POST'])]
    public function confirmReservation(Reservation $reservation, EntityManagerInterface $entityManager): Response
    {
        $reservation->confirm();
        $entityManager->flush();

        $this->addFlash('success', 'La réservation a été confirmée.');

        return $this->redirectToRoute('admin_pending_reservations');
    }

    #[Route('/{id}/reject', name: 'admin_reject_reservation', methods: ['POST'])]
    public function rejectReservation(Reservation $reservation, EntityManagerInterface $entityManager): Response
    {
        $reservation->cancel(); // Nous utilisons cancel() comme rejet
        $entityManager->flush();

        $this->addFlash('success', 'La réservation a été rejetée.');

        return $this->redirectToRoute('admin_pending_reservations');
    }

    #[Route('/notifications', name: 'admin_reservation_notifications')]
    public function notifications(ReservationRepository $reservationRepository): Response
    {
        $threshold = new \DateTimeImmutable('-2 days');
        $oldPendingReservations = $reservationRepository->findOldPendingReservations($threshold);

        return $this->render('admin/reservation_notifications.html.twig', [
            'old_pending_reservations' => $oldPendingReservations,
        ]);
    }
}