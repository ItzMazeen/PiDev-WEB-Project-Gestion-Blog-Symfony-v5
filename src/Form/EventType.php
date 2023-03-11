<?php

namespace App\Form;

use App\Entity\Event;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class EventType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom_event')
            ->add('discription_event')
            ->add('image_event')
            ->add('date_debut_event')
            ->add('date_fin_event')
            ->add('adresse_event')
            ->add('status',ChoiceType::class , [
                'choices'=>[
                    'Confirmé'=>'Confirmé',
                    'En attente'=>'En attente',
                    'Annulé'=>'Annulé' ],
                'attr' => ['class' => 'form-select'],
                'label_attr' => ['class' => 'form-label'],
            ])
            
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Event::class,
        ]);
    }
}
