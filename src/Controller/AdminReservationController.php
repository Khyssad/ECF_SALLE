<?php

namespace App\Controller;

use App\Entity\Reservation;
use App\Form\ReservationType;
use App\Repository\ReservationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

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
        $reservation->setStatus(Reservation::STATUS_CONFIRMED);
        $entityManager->flush();

        $this->addFlash('success', 'La réservation a été confirmée.');

        return $this->redirectToRoute('admin_pending_reservations');
    }

    #[Route('/{id}/reject', name: 'admin_reject_reservation', methods: ['POST'])]
    public function rejectReservation(Reservation $reservation, EntityManagerInterface $entityManager): Response
    {
        $reservation->setStatus(Reservation::STATUS_CANCELLED);
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

    #[Route('/', name: 'admin_reservations')]
    public function index(ReservationRepository $reservationRepository): Response
    {
        $reservations = $reservationRepository->findAll();

        return $this->render('admin/reservations.html.twig', [
            'reservations' => $reservations,
        ]);
    }

    #[Route('/{id}/edit', name: 'admin_reservation_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Reservation $reservation, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ReservationType::class, $reservation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            $this->addFlash('success', 'La réservation a été mise à jour.');

            return $this->redirectToRoute('admin_reservations');
        }

        return $this->render('admin/reservation_edit.html.twig', [
            'reservation' => $reservation,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}/delete', name: 'admin_reservation_delete', methods: ['POST'])]
    public function delete(Request $request, Reservation $reservation, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $reservation->getId(), $request->request->get('_token'))) {
            $entityManager->remove($reservation);
            $entityManager->flush();

            $this->addFlash('success', 'La réservation a été supprimée.');
        }

        return $this->redirectToRoute('admin_reservations');
    }
}