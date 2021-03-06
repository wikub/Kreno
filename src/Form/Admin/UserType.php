<?php

/*
 * This file is part of the Kreno package.
 *
 * (c) Valentin Van Meeuwen <contact@wikub.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Form\Admin;

use App\Entity\User;
use App\Form\Type\TagsType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('username', TextType::class, [
                'label' => 'Identifiant (login) *',
            ])
            ->add('name', TextType::class, [
                'label' => 'Nom *',
            ])
            ->add('firstname', TextType::class, [
                'label' => 'Prénom *',
            ])
            ->add('email', EmailType::class, [
                'label' => 'Courriel',
                'required' => false,
            ])
            ->add('phonenumber', TextType::class, [
                'label' => 'Téléphone',
                'required' => false,
            ])
            ->add('odooId', TextType::class, [
                'label' => 'Identifiant Odoo',
                'required' => false,
            ])
            ->add('comment', TextareaType::class, [
                'label' => 'Commentaire',
                'required' => false,
            ])
            ->add('tags', TagsType::class, [
                'label' => 'Mots clés',
                'help' => '',
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
