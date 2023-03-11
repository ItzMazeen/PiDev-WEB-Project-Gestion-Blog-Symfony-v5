<?php

namespace App\Form;

use App\Entity\DossierMedical;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DossierMedical1Type extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstName')
            ->add('lastName')
            ->add('email')
            ->add('date_naissance')
            ->add('vaccins')
            ->add('maladies')
            ->add('allergies')
            ->add('analyses')
            ->add('intervention_chirurgicale')
            ->add('userId')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => DossierMedical::class,
        ]);
    }
}
