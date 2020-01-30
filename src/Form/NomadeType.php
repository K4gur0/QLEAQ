<?php

namespace App\Form;

use App\Entity\Nomade;

use Doctrine\ORM\Query\Expr\Select;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class NomadeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder

            ->add('nom', TextType::class, [
                'attr' => ['class' => 'input'],
            ])

            ->add('prenom', TextType::class, [
                'attr' => ['class' => 'input'],
            ])

            ->add('date_naissance', BirthdayType::class, [
                            'attr' => ['class' => 'input'],
                    ])

            ->add('email', TextType::class, [
                'attr' => ['class' => 'input'],
            ])

//            ->add('password', PasswordType::class, [
//                'attr' => ['class' => 'input'],
//            ])

            ->add('plainPassword', RepeatedType::class, [
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

            ->add('telephone', TelType::class, [
                'attr' => ['class' => 'input'],
            ])

            ->add('adresse', TextType::class, [
                'attr' => ['class' => 'input'],
            ])

            ->add('cp', NumberType::class, [
                'label' => 'Code postal',
                'attr' => ['class' => 'input'],
            ])

            ->add('ville', TextType::class, [
                'attr' => ['class' => 'input'],
            ])

            ->add('photo_profile', TextType::class, [
                'attr' => ['class' => 'input'],
            ])

//            ->add('budget', NumberType::class, [
//                'attr' => ['class' => 'input'],
//            ])

            ->add('presentation', TextareaType::class, [
                'attr' => ['class' => 'textarea'],
            ])

//            ->add('statut')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Nomade::class,
        ]);
    }
}
