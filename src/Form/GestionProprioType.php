<?php

namespace App\Form;

use App\Entity\Proprietaire;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;

class GestionProprioType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
////            ->add('roles')
//            ->add('raison_social')
//            ->add('telephone')
//            ->add('adresse')
//            ->add('cp')
//            ->add('ville')
//            ->add('password')
//            ->add('email')
//            ->add('statut')
//            ->add('isConfirmed')
//            ->add('securityToken')
//            ->add('date_creation_compte')
//            ->add('refus')
//            ->add('refusToken')
//        ;
            ->add('raison_social',TextType::class,
                array('label' => false,
                )
            )

            ->add('telephone',TelType::class,
                array('label' => false,
                    'required' => false)
            )

            ->add('adresse',TextType::class, [
                'label' => false,
                'attr' => ['class' => 'input'],
            ])


            ->add('cp', TextType::class,[
                'label' => false,
                'attr' => ['class' => 'input'],
            ])


            ->add('ville', TextType::class,[
                'label' => false,
                'attr' => ['class' => 'input'],
            ])


            ->add('email',EmailType::class, [
                'label' => false,
                'error_bubbling' => true,
                'attr' => ['class' => 'input'],
                'constraints' => [
                    new NotBlank(['message' => 'Veuillez remplir ce champ.']),
                    new Email(['message' => 'Veuillez indiquer une adresse mail valide. Exemple : mon_adresse_mail@gmail.com']),
                ]
            ])

            ->add('statut',ChoiceType::class,
                array('label' => false,
                    'required' => true,
                    'choices' => [
                        'Propriétaire' => 'Proprietaire',
                        'Mandataire' => 'Mandataire',
                        'Agence Immo' => 'Agence_immo',
                        'Hôtel' => 'Hotel',
                        'Aparthôtel' => 'Aparthotel',
                        'Auberge de Jeunesse' => 'Aubrege_de_jeunesse',
                        'Résidence étudiante' => 'Residence_etudiante',
                        'Autre' => 'Autre',
                    ]
                )
            )

            ->add('date_creation_compte',null,
                ['widget' => 'single_text',
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
            'data_class' => Proprietaire::class,
        ]);
    }
}
