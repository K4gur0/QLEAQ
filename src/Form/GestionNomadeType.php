<?php

namespace App\Form;

use App\Entity\Nomade;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\DataTransformer\BooleanToStringTransformer;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RadioType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class GestionNomadeType extends AbstractType
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


            ->add('date_creation_compte',null,
                [
                    'widget' => 'single_text',
                    'format' => true,
                    'label' => false,
                    'html5' => false,
                    'disabled' => true,
                ])


            ->add('isconfirmed', CheckboxType::class,
                array('disabled' => true,
                    'label' => false,)
            )
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Nomade::class,
        ]);
    }
}
