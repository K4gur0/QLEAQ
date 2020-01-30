<?php

namespace App\Form;

use App\Entity\Nomade;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class NomadeRegistrationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom')
            ->add('prenom')
            ->add('email')
            ->add('confirm_email')
//            ->add('roles')
            ->add('password')
            ->add('confirm_password')


//            ->add('date_naissance')
//            ->add('telephone')
//            ->add('adresse')
//            ->add('cp')
//            ->add('ville')
//            ->add('photo_profile')
//            ->add('budget')
//            ->add('presentation')
//            ->add('statut')
//            ->add('date_creation_compte')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Nomade::class,
        ]);
    }
}
