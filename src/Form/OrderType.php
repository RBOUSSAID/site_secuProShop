<?php

namespace App\Form;

use App\Entity\Address;
use App\Entity\Carrier;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class OrderType extends AbstractType
{
    // fonction pour générer le formulaire OrderType en utilisant le builder Symfony Forms.
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('addresses', EntityType::class, [
                'label' => 'Choisissez votre adresse de livraison',
                'required' => true,
                'class' => Address::class,
                'expanded' => true,
                'choices' => $options['addresses'],
                'label_html' => true
                ])

                ->add('carriers', EntityType::class, [
                    'label' => 'Choisissez votre transporteur',
                    'required' => true,
                    'class' => Carrier::class,
                    'expanded' => true,
                    'label_html' => true
                    ])

                ->add('submit', SubmitType::class, [
                    
                    'label' => 'Valider la commande',
                    'attr'=>[
                        'class' => 'w-100 btn btn-success'
                    ]
                    ])    
        ;
    }

    // fonction pour spécifier les options du formulaire OrderType.
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'addresses' => null, // Option personnalisée pour les adresses
            'csrf_protection' => true, // Activation de la protection CSRF
            'csrf_field_name' => '_token', // Nom du champ CSRF
            'csrf_token_id' => 'order_form' // Identifiant unique du token CSR
        ]);
    }
}
