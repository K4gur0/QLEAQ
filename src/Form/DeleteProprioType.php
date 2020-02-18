<?php

namespace App\Form;

use App\Entity\Proprietaire;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DeleteProprioType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

//        $builder
////            ->add('roles')
////            ->add('raison_social')
////            ->add('telephone')
////            ->add('adresse')
////            ->add('cp')
////            ->add('ville')
////            ->add('password')
////            ->add('email')
////            ->add('statut')
////            ->add('isConfirmed')
////            ->add('securityToken')
//            ->add('date_creation_compte',null,
//                ['widget' => 'single_text',
//                    'format' => true,
//                    'label' => false,
//                    'attr' => ['class' => ''],
//                    'html5' => false,
//                    'disabled' => true,
//                    ])
////            ->add('refus')
////            ->add('refusToken')
//        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Proprietaire::class,
        ]);
    }
}
