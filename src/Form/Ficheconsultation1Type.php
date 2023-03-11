<?php

namespace App\Form;

use App\Entity\Ficheconsultation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class Ficheconsultation1Type extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('date_consultation')
            ->add('firstName')
            ->add('lastName')
            ->add('specialite')
            ->add('traitement')
            ->add('reccomendation')
            ->add('doctor')
            ->add('dossierMedical')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Ficheconsultation::class,
        ]);
    }
}
