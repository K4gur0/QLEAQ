<?php

namespace App\Form;

use App\Entity\Nomade;


use function Sodium\add;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\NotBlank;

class NomadeType extends AbstractType
{


    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder

            ->add('nom',
                TextType::class,
                array('label' => false
                )
            )


            ->add('prenom', TextType::class,
                array('label' => false
                    )
            )

            ->add('date_naissance', BirthdayType::class,
                array('label' => false,
                    'required' => false,
                    'attr' => ['class' => 'buttons'],
                    'placeholder' => [
                            'year' => 'Année', 'month' => 'Mois', 'day' => 'Jour',
                            ],
                    'format' => 'dd MM yyyy',
                    'invalid_message' => 'Veuillez selectionnez une date valide.',
                    'error_bubbling' => true,
//                    'mapped' => false,
                    )
            )

            ->add('email', EmailType::class, [
                'label' => false,
                'error_bubbling' => true,
                'attr' => ['class' => 'input'],
                'constraints' => [
                    new NotBlank(['message' => 'Veuillez remplir ce champ.']),
                    new Email(['message' => 'Veuillez indiquer une adresse mail valide. Exemple : mon_adresse_mail@gmail.com']),
                ]
            ])

//            ->add('password', PasswordType::class, [
//                'attr' => ['class' => 'input'],
//            ])

            ->add('telephone', TelType::class,
                array('label' => false,  'required' => false)
            )

            ->add('adresse', TextType::class,
                array('label' => false, 'required' => false,)
            )

            ->add('cp', NumberType::class,
                array('label' => false, 'required' => false)
            )

            ->add('ville', TextType::class,
                array('label' => false, 'required' => false,)
            )


//            ->add('budget', NumberType::class, [
//                'attr' => ['class' => 'input'],
//            ])

            ->add('presentation', TextareaType::class,
                array('label' => false, 'required' => false,)
            )

            ->add('statut', ChoiceType::class,
                array('label' => false,
                        'required' => true,
                        'choices' => [
                            'Etudiant(e)' => 'Etudiant',
                            'Salarié(e)' => 'Salarie',
                            'Profesionnel(le)' => 'Profesionnel',
                            'Expatrié(e)' => 'Expatrié',
                            'Intérimaire' => 'Intérimaire',
                            'Intérmitent(e)' => 'Intérmitent',
                            'Autre' => 'Autre',
                        ]
                    )
            )

            ->add('type_sejour', ChoiceType::class,
                array('label' => false,
                    'required' => true,
                    'choices' => [
                        'Études' => 'etudes',
                        'Stage' => 'stage',
                        'Formation' => 'formation',
                        'Mission' => 'mission',
                        'Salon' => 'salon',
                        'Autre' => 'Autre',
                    ]
                )
            )

            ->add('sexe', ChoiceType::class,
                array('label' => false,
                    'required' => true,
                    'choices' => [
                        'M.' => 'Masculin',
                        'Mme.' => 'Féminin',
                    ]
                )
            )

            ->add('photo_nomade', FileType::class, [
                'required' => 'false',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            //'data_class' => Nomade::class,
        ]);


    }
}
