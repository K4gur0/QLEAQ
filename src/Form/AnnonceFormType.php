<?php

namespace App\Form;

use App\Entity\Annonce;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AnnonceFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('titre', TextType::class,
                array('label' => false,
                    'required' => true,
                    )
            )

            ->add('type_logement', ChoiceType::class,
                array('label' => false,
                    'required' => true,
                    'choices' => [
//                        'Propriétaire' => 'Proprietaire',
//                        'Mandataire' => 'Mandataire',
//                        'Agence Immo' => 'Agence_immo',
//                        'Hôtel' => 'Hotel',
//                        'Aparthôtel' => 'Aparthotel',
//                        'Auberge de Jeunesse' => 'Aubrege_de_jeunesse',
//                        'Résidence étudiante' => 'Residence_etudiante',
                        'Autre' => 'Autre',
                    ]
                )
            )

            ->add('nombre_max_residents', ChoiceType::class,
                array('label' => false,
                    'required' => true,
                    'choices' => [
                        '1' => '1',
                        '2' => '2',
                        '3' => '3',
                        '4' => '4',
                        '5' => '5',
                        ]
                    )
            )
            ->add('description', TextType::class,
                array('label' => false,
                    'required' => false,
                    )
            )
            ->add('superficie', NumberType::class,
                array('label' => false,
                    'required' => true,
                    )
            )
            ->add('tarif', NumberType::class,
                array('label' => false,
                    'required' => true,
                    )
            )
            ->add('date_disponible', DateType::class,
                array('label' => false,
                    'required' => true,
                    'years' => range(date('Y'), date('Y')+100),
                    'months' => range(date('m'), 12),
//                    'days' => range(date('d'), 31),
                    )
            )
            ->add('adresse', TextType::class,
                array('label' => false,
                    'required' => false,
                    )
            )
            ->add('cp', NumberType::class,
                array('label' => false,
                    'required' => true,
                    )
            )
            ->add('ville', TextType::class,
                array('label' => false,
                    'required' => true,
                    )
            )

        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
//            'data_class' => Annonce::class,
        ]);
    }
}
