<?php

namespace App\Form;

use App\Entity\AnnonceSearch;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AnnonceSearchFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('datemin', DateType::class,[
                'required' => false,
                'label' => false,
                'attr' => [
                    'placeholder' => 'Date min'
                ]
            ])
            ->add('datemax', DateType::class,[
                'required' => false,
                'label' => false,
                'attr' => [
                    'placeholder' => 'Date max'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => AnnonceSearch::class,
            'method' => 'get',
            'csrf_protection' => false,
        ]);
    }
}
