<?php

namespace App\Form;

use App\Entity\Nomade;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DeleteNomadeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
//        $builder
//            ->add('email')
//            ->add('roles')
//            ->add('password')
//            ->add('nom')
//            ->add('prenom')
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
//            ->add('sexe')
//            ->add('isConfirmed')
//            ->add('securityToken')
//            ->add('type_sejour')
//        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Nomade::class,
        ]);
    }
}
