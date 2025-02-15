<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\Address;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;

class AddressUserType extends AbstractType
{
    // une fonction pour générer le formulaire d'adresse
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('firstname', TextType::class, [
            'label' => 'Votre prénom',
            'attr' => [
                'placeholder' => 'Entrez votre prénom',
                'class' => 'form-control bg-secondary text-white border-0 rounded-3 p-2 shadow-sm hover-effect'
            ]
        ])
        
        ->add('lastname', TextType::class, [
            'label' => 'Votre nom',
            'attr' => [
                'placeholder' => 'Entrez votre nom',
                'class' => 'form-control bg-secondary text-white border-0 rounded-3 p-2 shadow-sm hover-effect'
            ]
        ])
        
        ->add('address', TextType::class, [
            'label' => 'Votre adresse',
            'attr' => [
                'placeholder' => 'Entrez votre adresse',
                'class' => 'form-control bg-secondary text-white border-0 rounded-3 p-2 shadow-sm hover-effect'
            ]
        ])
        
        ->add('postal', TextType::class, [
            'label' => 'Votre code postal',
            'attr' => [
                'placeholder' => 'Entrez votre code postal',
                'class' => 'form-control bg-secondary text-white border-0 rounded-3 p-2 shadow-sm hover-effect'
            ]
        ])
        
        ->add('city', TextType::class, [
            'label' => 'Votre ville',
            'attr' => [
                'placeholder' => 'Entrez votre ville',
                'class' => 'form-control bg-secondary text-white border-0 rounded-3 p-2 shadow-sm hover-effect'
            ]
        ])
        
        ->add('country', CountryType::class, [
            'label' => 'Votre pays',
            'placeholder' => 'Choisissez votre pays',
            'attr' => [
                'class' => 'form-control bg-secondary text-white border-0 rounded-3 p-2 shadow-sm hover-effect'
            ]
        ])
        
        ->add('phone', TextType::class, [
            'label' => 'Votre téléphone',
            'attr' => [
                'placeholder' => 'Entrez votre numéro de téléphone',
                'class' => 'form-control bg-secondary text-white border-0 rounded-3 p-2 shadow-sm hover-effect'
            ]
        ])
        
        ->add('submit', SubmitType::class, [
            'label' => 'Sauvegarder',
            'attr' => [
                'class' => 'btn btn-warning btn-lg w-100 fw-bold py-2 hover-shadow'
            ]
        ]);
    }        

    // une fonction pour spécifier les options du formulaire
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Address::class,
        ]);
    }
}
