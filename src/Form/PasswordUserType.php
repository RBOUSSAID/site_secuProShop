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
                'label' => 'Votre mot de passe actuel',
                'attr'=>[
                    'placeholder' => 'Entrez votre mot de passe actuel'
                ],
                'mapped' => false
            ])

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
                'attr'=>[
                    'class' => 'btn btn-success'
                ]
            ])
            ->addEventListener(FormEvents::SUBMIT, function (FormEvent $event) {
                $form = $event->getForm();
                $user = $form->getConfig()->getOptions()['data'];
                $passwordHasher = $form->getConfig()->getOptions()['passwordHasher'];

                //Récuperer le mot de passe saisi par l'utilisateur et le comparer avec le mot de passe actuel dans la base de données
                $actualPwd = $form->get('actualPassword')->getData();
                $isValid = $passwordHasher->isPasswordValid(
                    $user,
                    $form->get('actualPassword')->getData()
                );

                //Si non, afficher un message d'erreur
                if (!$isValid) {
                    $form->get('actualPassword')->addError(new FormError('Le mot de passe actuel est incorrect.'));
                }
            } )
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'passwordHasher' => null
        ]);
    }
}
