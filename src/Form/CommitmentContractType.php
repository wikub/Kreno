<?php

/*
 * This file is part of the Kreno package.
 *
 * (c) Valentin Van Meeuwen <contact@wikub.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Form;

use App\Entity\CommitmentContract;
use App\Entity\CommitmentType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CommitmentContractType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('start', DateType::class, [
                'label' => 'Début',
                'html5' => true,
                'widget' => 'single_text',
            ])
            ->add('finish', DateType::class, [
                'label' => 'Fin',
                'required' => false,
                'html5' => true,
                'widget' => 'single_text',
            ])
            ->add('type', EntityType::class, [
                'label' => 'Type',
                'class' => CommitmentType::class,
                'choice_label' => 'name',
            ])
            ->add('regularTimeslots', CollectionType::class, [
                'entry_type' => CommitmentContractRegularTimeslotType::class,
                'entry_options' => ['label' => false],
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
                'attr' => [
                    'data-entry-label' => 'RegularTimeslots',
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => CommitmentContract::class,
        ]);
    }
}
