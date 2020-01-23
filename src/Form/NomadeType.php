<?php

namespace App\Form;

use App\Entity\Nomade;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class NomadeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom')
            ->add('prenom')
            ->add('date_naissance')
            ->add('email')
            ->add('password')
            ->add('telephone')
            ->add('adresse')
            ->add('cp')
            ->add('ville')
            ->add('photo_profile')
            ->add('budget')
            ->add('presentation')
            ->add('statut')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Nomade::class,
        ]);
    }
}
