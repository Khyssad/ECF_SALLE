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
        $upcomingReservations = $this->reservationRepository->findUpcomingReservations();

        if (count($pendingReservations) > 0) {
            $this->sendEmailNotification($pendingReservations, 'Réservations en attente');
            $this->createInAppNotification($pendingReservations, 'pending_reservations');
        }

        if (count($upcomingReservations) > 0) {
            $this->sendEmailNotification($upcomingReservations, 'Réservations à venir non traitées');
            $this->createInAppNotification($upcomingReservations, 'upcoming_reservations');
        }
    }

    private function sendEmailNotification(array $reservations, string $subject)
    {
        $emailContent = $this->generateEmailContent($reservations, $subject);

        $email = (new Email())
            ->from('noreply@roomreservation.com')
            ->to('admin@example.com')
            ->subject($subject)
            ->html($emailContent);

        $this->mailer->send($email);
    }
    private function createInAppNotification(array $reservations, string $type)
    {
        $notification = new AdminNotification();
        $notification->setType($type);
        $notification->setContent('Il y a ' . count($reservations) . ' réservations qui nécessitent votre attention.');
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