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

use App\Entity\CommitmentContract;
use App\Entity\CommitmentType;
use App\Entity\Cycle;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CommitmentContractType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('startCycle', EntityType::class, [
                'label' => 'Premier cycle',
                'class' => Cycle::class,
                'choice_label' => function ($cycle) {
                    return $cycle->getName();
                },
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('c')
                        ->orderBy('c.start', 'ASC');
                },
            ])
            ->add('finishCycle', EntityType::class, [
                'label' => 'Premier cycle',
                'class' => Cycle::class,
                'choice_label' => function ($cycle) {
                    return $cycle->getName();
                },
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('c')
                        ->orderBy('c.start', 'ASC');
                },
                'required' => false,
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
                'label' => false,
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
