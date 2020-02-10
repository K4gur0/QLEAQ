<?php

namespace App\Form;

use App\Entity\Nomade;
use App\Entity\Proprietaire;
use function Couchbase\defaultEncoder;
use Doctrine\DBAL\Types\ArrayType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\ChoiceList\ArrayChoiceList;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class ProprioRegistrationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
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
                array('label' => false,
                    'required' => true,
                    'constraints' => [
                        new NotBlank(['message' => 'Veuillez remplir ce champ.']),
                    ]
                )
            )

            ->add('plainPassword', RepeatedType::class, [
                'first_options'  => array('label' => false),
                'second_options' => array('label' => false),
                'label' => false,
                'mapped' => false,
                'type' => PasswordType::class,
                'invalid_message' => 'Les mots de passe ne correspondent pas.',
                'required' => true,
                'error_bubbling' => true,
                'constraints' => [
                    new NotBlank(['message' => 'Veuillez remplir ce champ.']),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Votre mot de passe doit contenir au moins 6 caractères.'
                    ])
                ]
            ])

// PRENOM ICI = RAISON SOCIAL / NOM D'ENTREPRISE :
            ->add('raison_social', TextType::class,[
                'label' => false,
                'attr' => ['class' => 'input'],
            ])



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


//            ->add('presentation', TextareaType::class,
//                array('label' => false, 'required' => false,)
//            )


            ->add('statut', ChoiceType::class,
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





        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Proprietaire::class,
        ]);
    }
}
