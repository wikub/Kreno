<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('username', TextType::class, [
                'label' => 'Identifiant (login)'
            ])
            ->add('name', TextType::class, [
                'label' => 'Nom'
            ])
            ->add('firstname', TextType::class, [
                'label' => 'Prénom'
            ])
            ->add('email', EmailType::class, [
                'label' => 'Courriel',
                'required' => false
            ])
            ->add('phonenumber', TextType::class, [
                'label' => 'Téléphone',
                'required' => false
            ])
            ->add('category', ChoiceType::class, [
                'label' => 'Catégorie',
                'choices' => array_flip(User::getCategoryLabels())
            ])
            ->add('subscriptionType', ChoiceType::class, [
                'label' => 'Inscription',
                'choices' => array_flip(User::getSubscriptionTypeLabels())
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
