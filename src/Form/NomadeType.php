<?php

namespace App\Form;

use App\Entity\Nomade;

use Doctrine\ORM\Query\Expr\Select;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

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

            ->add('password', PasswordType::class, [
                'attr' => ['class' => 'input'],
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

            ->add('budget', NumberType::class, [
                'attr' => ['class' => 'input'],
            ])

            ->add('presentation', TextareaType::class, [
                'attr' => ['class' => 'input'],
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
