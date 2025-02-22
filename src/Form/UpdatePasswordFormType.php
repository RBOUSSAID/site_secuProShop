<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;

class UpdatePasswordFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('plainPassword', RepeatedType::class, [
            'type' => PasswordType::class,
            'first_options'  => [
                'label' => 'Choisissez Votre nouveau mot de passe', 
                'constraints' => [
                    new Length (['min' => 8,'max' => 12])
                ],
                'attr'=>[
                    'placeholder' => 'Choisissez votre nouveau mot de passe'
                ],
                'hash_property_path' => 'password'
            ],

            'second_options' => [
                'label' => ' Confirmez votre mot de passe',
                'attr'=>[
                    'placeholder' => 'Confirmez votre mot de passe'
                ],
            ],
            'mapped' => false
        ])

        ->add('submit', SubmitType::class, [
            'label' => 'Mettre à jour mon mot de passe',
            'attr' => [
                'class' => 'btn btn-warning w-100 fw-bold py-2 hover-shadow',
                'style' => 'background-color: #ff8505;'
            ]
            ]);        
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
