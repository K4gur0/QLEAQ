<?php

namespace App\Form;

use App\Entity\AnnonceSearch;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AnnonceSearchFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('tarifMin', NumberType::class,[
                'required' => false,
                'label' => false,
                'error_bubbling' => true,
//                'invalid_message' => 'Valeur entrée non valide',
                'attr' => [
                    'class' => 'input',
                    'title' => 'Loyer minimum',
                    'placeholder' => 'ex : 350',
                ]
            ])
            ->add('tarifMax', NumberType::class,[
                'required' => false,
                'label' => false,
                'error_bubbling' => true,
//                'invalid_message' => 'Valeur entrée non valide',
                'attr' => [
                    'class' => 'input',
                    'title' => 'Loyer maximum',
                    'placeholder' => 'ex : 700',
                ]
            ])

            ->add('superficieMin', NumberType::class,[
                'required' => false,
                'label' => false,
                'error_bubbling' => true,
//                'invalid_message' => 'Valeur entrée non valide',
                'attr' => [
                    'class' => 'input',
                    'title' => 'Superficie minimum',
                    'placeholder' => 'ex : 20',
                ]
            ])
            ->add('superficieMax', NumberType::class,[
                'required' => false,
                'label' => false,
                'error_bubbling' => true,
//                'invalid_message' => 'Valeur entrée non valide',
                'attr' => [
                    'class' => 'input',
                    'title' => 'Superficie maximum',
                    'placeholder' => 'ex : 60',
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

    public function getBlockPrefix()
    {
        return '';
    }
}
