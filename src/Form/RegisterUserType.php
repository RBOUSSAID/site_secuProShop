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
    // fonction pour générer le formulaire d'inscription
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('email', EmailType::class, [
            'label' => '📧 Votre adresse email',
            'attr' => [
                'class' => 'form-control bg-secondary text-white border-2 rounded-3 p-2',
                'placeholder' => 'Indiquez votre adresse email'
            ]
        ])
        
        ->add('plainPassword', RepeatedType::class, [
            'type' => PasswordType::class,
            'first_options' => [
                'label' => '🔒 Choisissez votre mot de passe',
                'constraints' => [
                    new Length(['min' => 12, 'max' => 24])
                ],
                'attr' => [
                    'class' => 'form-control bg-secondary text-white border-2 rounded-3 p-2',
                    'placeholder' => 'Choisissez votre mot de passe'
                ],
                'hash_property_path' => 'password'
            ],
            'second_options' => [
                'label' => '🔄 Confirmez votre mot de passe',
                'attr' => [
                    'class' => 'form-control bg-secondary text-white border-2 rounded-3 p-2',
                    'placeholder' => 'Confirmez votre mot de passe'
                ],
            ],
            'mapped' => false
        ])

        ->add('firstname', TextType::class, [
            'label' => '👤 Votre prénom',
            'constraints' => [
                new Length(['min' => 2, 'max' => 30])
            ],
            'attr' => [
                'class' => 'form-control bg-secondary text-white border-2 rounded-3 p-2',
                'placeholder' => 'Indiquez votre prénom'
            ]
        ])

        ->add('lastname', TextType::class, [
            'label' => '📝 Votre nom',
            'constraints' => [
                new Length(['min' => 2, 'max' => 30])
            ],
            'attr' => [
                'class' => 'form-control bg-secondary text-white border-2 rounded-3 p-2',
                'placeholder' => 'Indiquez votre nom'
            ]
        ])

        ->add('submit', SubmitType::class, [
            'label' => 'S\'inscrire',
            'attr' => [
                'class' => 'btn btn-warning w-100 fw-bold py-2',
                'style' => 'background-color: #ff8505;'
            ]
        ]);
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
        'csrf_protection' => true, // Activation de la protection CSRF
        'csrf_field_name' => '_token', // Nom du champ CSRF
        'csrf_token_id' => 'register_form' // Identifiant unique du token CSR
    ]);
}
}
