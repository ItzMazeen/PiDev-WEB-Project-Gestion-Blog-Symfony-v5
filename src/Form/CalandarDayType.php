<?php

namespace App\Form;

use DateTime;
use App\Entity\User;
use App\Entity\CalandarDay;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Validator\Constraints\GreaterThanOrEqual;

class CalandarDayType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $id = $options['id']; // argument passed by the controller as argument 
        $builder
            ->add(
                'date',
                DateType::class,
                [
                    'constraints' => [
                        new NotBlank(),
                        new GreaterThanOrEqual([
                            'value' => 'today',
                            'message' => 'The date cannot be before today.'
                        ]),
                    ],
                    'data' => new DateTime()
                ]
            )
            ->add(
                'dayStart',
                TimeType::class,
                [
                    'data' => new \DateTime("09:00")
                ]
            )
            ->add(
                'dayEnd',
                TimeType::class,
                [
                    'data' => new \DateTime("17:00")
                ]
            )
            ->add('sessionDuration')
            ->add(
                'lunchBreakStart',
                TimeType::class,
                [
                    'data' => new \DateTime("13:00")
                ]
            )
            ->add(
                'lunchBreakEnd',
                TimeType::class,
                [
                    'data' => new \DateTime("14:00")
                ]
            )
            ->add('totalTimeSlots', HiddenType::class, [])
            ->add('doctor', EntityType::class, [
                'class' => User::class,                      // use (id) , is a way to tell the anonymous function to use variables from the outside the scope
                'query_builder' => function (EntityRepository $er) use ($id) { // "closure" a type of anonymous function in php
                    // search for a particular user
                    dump($id);

                    return $er->createQueryBuilder('u')
                        ->where('u.id = :userId')
                        ->setParameter('userId', $id);
                },
                'choice_label' => 'firstName',
            ]);;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => CalandarDay::class,
            'id' => null // this what we will catch from controller to pass as the id for search when we passed the attribute to the template
        ]);
    }

   
}
