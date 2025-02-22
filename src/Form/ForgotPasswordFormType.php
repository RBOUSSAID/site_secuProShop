<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class ForgotPasswordFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('email', EmailType::class, [
            'label' => '📧 Votre adresse email',
            'label_attr' => ['class' => 'fw-bold', 'style' => 'color: #ff8505;',],
            'attr' => [
                'class' => 'form-control bg-secondary text-white border-0 rounded-3 p-2',
                'placeholder' => 'Entrez votre adresse email'
            ],
        ])
        ->add('submit', SubmitType::class, [
            'label' => 'Réinitialiser',
            'attr' => [
                'class' => 'btn btn-warning w-100 fw-bold py-2',
                'style' => 'background-color: #ff8505;'
            ],
        ]);
    }        

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
