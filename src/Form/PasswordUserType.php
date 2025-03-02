<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormError;

class PasswordUserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('actualPassword', PasswordType::class, [
            'label' => '🔒 Votre mot de passe actuel',
            'attr' => [
                'placeholder' => 'Entrez votre mot de passe actuel',
                'class' => 'form-control bg-secondary text-white border-2 rounded-3 p-2 shadow-sm hover-effect'
            ],
            'mapped' => false
        ])
        
        ->add('plainPassword', RepeatedType::class, [
            'type' => PasswordType::class,
            'first_options'  => [
                'label' => '🔑 Choisissez Votre nouveau mot de passe',
                'constraints' => [
                    new Length (['min' => 8, 'max' => 12])
                ],
                'attr' => [
                    'placeholder' => 'Choisissez votre nouveau mot de passe',
                    'class' => 'form-control bg-secondary text-white border-2 rounded-3 p-2 shadow-sm hover-effect'
                ],
                'hash_property_path' => 'password'
            ],
            'second_options' => [
                'label' => '🔄 Confirmez votre mot de passe',
                'attr' => [
                    'placeholder' => 'Confirmez votre mot de passe',
                    'class' => 'form-control bg-secondary text-white border-2 rounded-3 p-2 shadow-sm hover-effect'
                ]
            ],
            'mapped' => false
        ])
        
        ->add('submit', SubmitType::class, [
            'label' => 'Mettre à jour mon mot de passe',
            'attr' => [
                'class' => 'btn btn-warning w-100 fw-bold py-2 hover-shadow',
                'style' => 'background-color: #ff8505;'
            ]
        ])
        
        ->addEventListener(FormEvents::SUBMIT, function (FormEvent $event) {
            $form = $event->getForm();
            $user = $form->getConfig()->getOptions()['data'];
            $passwordHasher = $form->getConfig()->getOptions()['passwordHasher'];
        
            // Récupérer le mot de passe saisi par l'utilisateur et le comparer avec le mot de passe actuel dans la base de données
            $actualPwd = $form->get('actualPassword')->getData();
            $isValid = $passwordHasher->isPasswordValid(
                $user,
                $form->get('actualPassword')->getData()
            );
        
            // Si non, afficher un message d'erreur
            if (!$isValid) {
                $form->get('actualPassword')->addError(new FormError('Le mot de passe actuel est incorrect.'));
            }
        });
    }           

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'passwordHasher' => null,
            'csrf_protection' => true,
            'csrf_field_name' => '_token',
            'csrf_token_id' => 'password_form'

        ]);
    }
}
