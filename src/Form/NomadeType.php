<?php

namespace App\Form;

use App\Entity\Nomade;

use Doctrine\ORM\Query\Expr\Select;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class NomadeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder

//            ->add('nom', TextType::class, [
////                'attr' => ['class' => 'input'],
////            ])

            ->add('nom',
                TextType::class,
                array('label' => false)
            )


            ->add('prenom', TextType::class,
                array('label' => false)
            )

            ->add('date_naissance', BirthdayType::class,
                array('label' => false, 'required' => false,)
            )

            ->add('email', EmailType::class,
                array('label' => false)
            )

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

//            ->add('photo_profile', FileType::class,
//                [
//                'attr' => ['class' => 'input'],
//
//                'label' => false,
//                // unmapped means that this field is not associated to any entity property
//                'mapped' => false,
//
//                // make it optional so you don't have to re-upload the PDF file
//                // everytime you edit the Product details
//                'required' => false,
//
//                // unmapped fields can't define their validation using annotations
//                // in the associated entity, so you can use the PHP constraint classes
//                'constraints' => [
//                    new File([
//                        'maxSize' => '2048k',
//                        'mimeTypes' => [
//                            'application/pdf',
//                            'application/pdf',
//                        ],
//                        'mimeTypesMessage' => 'Please upload a valid PDF document',
//                    ])
//                ],
//            ])

//            ->add('budget', NumberType::class, [
//                'attr' => ['class' => 'input'],
//            ])

            ->add('presentation', TextareaType::class,
                array('label' => false, 'required' => false,)
            )

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
