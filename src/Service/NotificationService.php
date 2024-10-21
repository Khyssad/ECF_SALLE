<?php

namespace App\Service;

use App\Entity\Reservation;
use App\Repository\ReservationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class NotificationService
{
    private $mailer;
    private $reservationRepository;
    private $entityManager;

    public function __construct(
        MailerInterface $mailer,
        ReservationRepository $reservationRepository,
        EntityManagerInterface $entityManager
    ) {
        $this->mailer = $mailer;
        $this->reservationRepository = $reservationRepository;
        $this->entityManager = $entityManager;
    }

    public function notifyAdminsOfPendingReservations()
    {
        $pendingReservations = $this->reservationRepository->findPendingReservations();

        if (count($pendingReservations) > 0) {
            $this->sendEmailNotification($pendingReservations);
            $this->createInAppNotification($pendingReservations);
        }
    }

    private function sendEmailNotification(array $pendingReservations)
    {
        $emailContent = $this->generateEmailContent($pendingReservations);

        $email = (new Email())
            ->from('noreply@roomreservation.com')
            ->to('admin@example.com')
            ->subject('Pending Reservations Notification')
            ->html($emailContent);

        $this->mailer->send($email);
    }

    private function createInAppNotification(array $pendingReservations)
    {
        $notification = new AdminNotification();
        $notification->setType('pending_reservations');
        $notification->setContent('There are ' . count($pendingReservations) . ' pending reservations that need attention.');
        $notification->setCreatedAt(new \DateTime());

        $this->entityManager->persist($notification);
        $this->entityManager->flush();
    }

    private function generateEmailContent(array $pendingReservations): string
    {
        $content = "<h1>Pending Reservations</h1>";
        $content .= "<p>The following reservations need your attention:</p>";
        $content .= "<ul>";

        foreach ($pendingReservations as $reservation) {
            $content .= "<li>Reservation ID: " . $reservation->getId() .
                        ", Start Date: " . $reservation->getStartDate()->format('Y-m-d H:i') .
                        ", Room: " . $reservation->getRoom()->getName() . "</li>";
        }

        $content .= "</ul>";
        return $content;
    }
}