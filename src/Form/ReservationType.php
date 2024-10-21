<?php

namespace App\Form;

use App\Entity\Reservation;
use App\Entity\Room;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class ReservationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('startDate', DateTimeType::class, [
                'widget' => 'single_text',
            ])
            ->add('endDate', DateTimeType::class, [
                'widget' => 'single_text',
            ])
            ->add('room', EntityType::class, [
                'class' => Room::class,
                'choice_label' => 'name',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Reservation::class,
            'constraints' => [
                new Callback([$this, 'validateReservation']),
            ],
        ]);
    }

    public function validateReservation($data, ExecutionContextInterface $context)
    {
        // Check if the room is available for the given dates
        $room = $data->getRoom();
        $startDate = $data->getStartDate();
        $endDate = $data->getEndDate();

        // Implement the logic to check if the room is available
        // If not available, add a violation
        if (!$this->isRoomAvailable($room, $startDate, $endDate)) {
            $context->buildViolation('The room is not available for the selected dates.')
                ->atPath('room')
                ->addViolation();
        }
    }

    private function isRoomAvailable(Room $room, \DateTimeImmutable $startDate, \DateTimeImmutable $endDate): bool
    {
        // Implement the logic to check if the room is available
        // This could involve querying the database for existing reservations
        // and checking for overlaps
        return true; // Replace with actual logic
    }
}