<?php

namespace App\Form;

use App\Entity\EventTicket;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class EventTicketType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('matricule_event',)
            ->add('image')
            ->add('date_ticket')
            ->add('valide_ticket',ChoiceType::class , [
                'choices'=>[
                    'Valide'=>'Valide',
                    'Non Valide'=>'Non Valide'],
                'attr' => ['class' => 'form-select'],
                'label_attr' => ['class' => 'form-label'],
            ])
            ->add('prix_ticket',ChoiceType::class , [
                'choices'=>[
                    'Normal: 2 DT'=>'Normal: 2 DT',
                    'VIP: 4 DT'=>'VIP: 4 DT'],
                'attr' => ['class' => 'form-select'],
                'label_attr' => ['class' => 'form-label'],
            ])
            
            
            ->add('userID')
            ->add('eventID')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => EventTicket::class,
        ]);
    }
}
