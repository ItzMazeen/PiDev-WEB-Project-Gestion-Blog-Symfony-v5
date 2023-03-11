<?php

namespace App\Form;

use App\Entity\TimeSlot;
use App\Entity\CalandarDay;

use Symfony\Component\Form\AbstractType;
use function PHPUnit\Framework\stringContains;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;

use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class TimeSlotType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('id', null, [
                'attr' => [
                    'disabled' => true,
                ],
            ])
            ->add('startTime', TimeType::class, [
                'required' => true,
                'disabled' => true,
                'widget' => 'single_text',
            ])
            ->add('endTime', null, [
                'required' => true,
                'widget' => 'single_text',
                'disabled' => true

            ])
            ->add('status', ChoiceType::class, [
                'required' => true,
                'choices' => [
                    'available' => 'available',
                    'not-available' => 'not-available',
                ]
            ])
            ->add('reason', TextType::class, [
                'required' => false,
                'attr' => [
                    'placeholder' => '.,;:\'\\"()_-] are available to use',
                ],
            ])
            ->add('note', TextareaType::class, [
                'label' => 'Message',
                'required' => false,
                'attr' => [
                    'class' => 'my-custom-class',
                    'rows' => 5,
                    'placeholder' => '.,;:\'\\"()_-] are available to use , max 600 character',
                    'maxlength' => 600
                ],
            ])
            // ->add('indexSlot')
            ->add('appointment', null, ['attr' => [
                'disabled' => true,
                'required' => false,
            ]])
            // ->add(
            //     'calandarDay',
            //     EntityType::class, // use to string to generate the list which i dont want 
            //     ['class' => CalandarDay::class],
            // )
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => TimeSlot::class,
        ]);
    }
}
