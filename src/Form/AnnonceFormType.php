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

            ->add('nombre_max_residents', NumberType::class,
                array('label' => false,
                    'required' => true,
                    )
            )
            ->add('description', TextType::class,
                array('label' => false,
                    'required' => true,
                    )
            )
//            ->add('superficie', NumberType::class,
//                array('label' => false,
//                    'required' => true,
//                    )
//            )
//            ->add('tarif', NumberType::class,
//                array('label' => false,
//                    'required' => true,
//                    )
//            )
//            ->add('date_disponible', DateType::class,
//                array('label' => false,
//                    'required' => true,
//                    )
//            )
//            ->add('adresse', TextType::class,
//                array('label' => false,
//                    'required' => true,
//                    )
//            )
//            ->add('cp', NumberType::class,
//                array('label' => false,
//                    'required' => true,
//                    )
//            )
//            ->add('ville', TextType::class,
//                array('label' => false,
//                    'required' => true,
//                    )
//            )
//            ->add('auteur', TextType::class,
//                array('label' => false,
//                    'required' => true,
//                    )
//            )
//        ;
//    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
//            'data_class' => Annonce::class,
        ]);
    }
}
