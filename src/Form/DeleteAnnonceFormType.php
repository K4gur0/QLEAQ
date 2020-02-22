<?php

namespace App\Form;

use App\Entity\Annonce;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DeleteAnnonceFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
//        $builder
//            ->add('titre')
//            ->add('type_logement')
//            ->add('nombre_max_residents')
//            ->add('description')
//            ->add('superficie')
//            ->add('tarif')
//            ->add('date_disponible')
//            ->add('adresse')
//            ->add('cp')
//            ->add('ville')
//            ->add('date_creation')
//            ->add('proprio')
//        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Annonce::class,
        ]);
    }
}
