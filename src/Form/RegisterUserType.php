<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;

class RegisterUserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'label' => 'Votre adresse email',
                'attr'=>[
                    'placeholder' => 'Indiquez votre adresse email'
                ]
                ])

                ->add('plainPassword', RepeatedType::class, [
                    'type' => PasswordType::class,
                    'first_options'  => [
                        'label' => 'Choisissez Votre mot de passe', 
                        'constraints' => [
                            new Length (['min' => 8,'max' => 12])
                        ],
                        'attr'=>[
                            'placeholder' => 'Choisissez votre mot de passe'
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

            ->add('firstname', TextType::class, [
                'label' => 'Votre prénom',
                'constraints' => [
                            new Length (['min' => 2,'max' => 30])
                        ],
                'attr'=>[
                    'placeholder' => 'Indiquez votre prénom'
                ]
                ])

            ->add('lastname', TextType::class, [
            'label' => 'Votre nom',
            'constraints' => [
                            new Length (['min' => 2,'max' => 30])
                        ],
            'attr'=>[
                    'placeholder' => 'Indiquez votre nom'
                ]
            ])

            ->add('submit', SubmitType::class, [
                'label' => 'S\'inscrire',
                'attr'=>[
                    'class' => 'btn btn-success'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'constraints' => [
                new UniqueEntity([
                    'entityClass' => User::class,
                    'fields' => 'email',
                ])
            ],
            'data_class' => User::class,
        ]);
    }
}