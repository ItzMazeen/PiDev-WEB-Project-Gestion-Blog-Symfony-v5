<?php

namespace App\Form;

use App\Entity\User;
use DateTimeInterface;
use App\Entity\TimeSlot;
use App\Entity\Appointment;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\Date;
use Symfony\Component\Validator\Constraints\Choice;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class AppointmentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {

        $userId = $options['user_id']; // argument passed by the controller as argument 
        $doctorId = $options['doctor_id']; // argument passed by the controller as argument 
        $slotId = $options['time_slot_id']; // argument passed by the controller as argument 
        $date = $options['date_app'];
        $time = $options['time_app'];


        $builder
            ->add('date', null, [
                'disabled' => true,
                'required' => true,
            ])
            ->add('reason', ChoiceType::class, [
                'choices' => [
                    'appointment' => 'appointment',
                    'check-up' => 'check-up',
                ],
                'required' => true,
            ])
            ->add('hour', null, [
                'disabled' => true,
                'required' => true,
            ])
            ->add('bookingState', ChoiceType::class, [
                'choices' => [
                    'confirmed' => 'confirmed',
                    'not-confirmed' => 'not-confirmed',
                ]
            ])
            ->add('doctor', EntityType::class, [
                'class' => User::class,
                'query_builder' => function (EntityRepository $er) use ($doctorId) { // "closure" a type of anonymous function in php
                    // search for a particular doctor
                    dump($doctorId);

                    return $er->createQueryBuilder('u')
                        ->where('u.id = :doctorId')
                        ->setParameter('doctorId', $doctorId);
                },
                'disabled' => true,
                'required' => true,
            ])
            ->add('patient', EntityType::class, [
                'class' => User::class,
                'query_builder' => function (EntityRepository $er) use ($userId) { // "closure" a type of anonymous function in php
                    // the connected user normally
                    dump($userId);

                    return $er->createQueryBuilder('u')
                        ->where('u.id = :userId')
                        ->setParameter('userId', $userId);
                },
                'disabled' => true,
                'required' => true,
            ])
            ->add('timeSlot', EntityType::class, [
                'class' => TimeSlot::class,
                'query_builder' => function (EntityRepository $er) use ($slotId) { // "closure" a type of anonymous function in php
                    // search for a particular slot
                    dump($slotId);

                    return $er->createQueryBuilder('s')
                        ->where('s.id = :timeSlotId')
                        ->setParameter('timeSlotId', $slotId);
                },
                'disabled' => true,
                'required' => true,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Appointment::class,
            'user_id' => User::class,
            'doctor_id' => User::class,
            'time_slot_id' => TimeSlot::class,
            'date_app' => DateTimeInterface::class,
            'time_app' => \DateTimeInterface::class,

        ]);
    }
}
