<?php

namespace App\Form;

use App\Entity\Nomade;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', EmailType::class, [
                'label' => false,
                'attr' => ['class' => 'input'],
                'constraints' => [
                    new NotBlank(['message' => 'Veuillez remplir ce champ.']),
                    new Email(['message' => 'Veuillez indiquer une adresse mail valide']),
                ]
            ])

            ->add('telephone', TelType::class,
                array('label' => false,
                    'required' => true,
                    'constraints' => [
                            new NotBlank(['message' => 'Veuillez remplir ce champ.']),
                        ]
                    )
            )


            ->add('nom',TextType::class, [
                'label' => false,
                'attr' => ['class' => 'input'],
                ])

            ->add('prenom', TextType::class,[
                'label' => false,
                'attr' => ['class' => 'input'],
            ])

            ->add('plainPassword', RepeatedType::class, [
                'first_options'  => array('label' => false),
                'second_options' => array('label' => false),
                'label' => false,
                'mapped' => false,
                'type' => PasswordType::class,
                'invalid_message' => 'Les mots de passe ne correspondent pas',
                'required' => true,
                'constraints' => [
                    new NotBlank(['message' => 'Veuillez remplir ce champ.']),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Votre mot de passe doit contenir au moins 6 caractères.'
                    ])
                ]
            ])

            ->add('statut', ChoiceType::class,
                array('label' => false,
                    'required' => true,
                    'choices' => [
                        'Etudiant' => 'Etudiant',
                        'Salarié' => 'Salarie',
                        'Profesionnel' => 'Profesionnel',
                        'Expatrié' => 'Expatrié',
                        'Intérimaire' => 'Intérimaire',
                        'Intérmitant' => 'Intérmitant',
                        'Autre' => 'Autre',
                    ]
                )
            )

            ->add('sexe', ChoiceType::class,
                array('label' => false,
                    'required' => true,
                    'placeholder' => 'Choisissez ...',
                    'choices' => [
                        'M.' => 'Monsieur',
                        'Mme.' => 'Madame',
                    ]
                )
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
