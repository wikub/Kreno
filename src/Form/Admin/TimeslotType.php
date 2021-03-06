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

use App\Entity\Timeslot;
use App\Entity\TimeslotType as EntityTimeslotType;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TimeslotType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        // $week = $builder->getData()->getWeek();
        // foreach($week->getDays() as $day) {
        //     $days[] = $day['date']->format('d');
        //     $month[] = $day['date']->format('m');
        //     $years[] = $day['date']->format('Y');
        // }

        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom',
            ])
            ->add('start', DateTimeType::class, [
                'label' => 'Début',
                'html5' => true,
                'date_widget' => 'single_text',
                'minutes' => [0, 15, 30, 45],
                'hours' => range(6, 23),
                'input' => 'datetime_immutable',
            ])
            ->add('finish', DateTimeType::class, [
                'label' => 'Fin',
                'html5' => true,
                'date_widget' => 'single_text',
                'minutes' => [0, 15, 30, 45],
                'hours' => range(6, 23),
                'input' => 'datetime_immutable',
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description',
                'required' => false,
            ])
            ->add('timeslotType', EntityType::class, [
                'label' => 'Type',
                'class' => EntityTimeslotType::class,
                'choice_label' => 'name',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('tt')
                        ->orderBy('tt.name', 'ASC');
                },
            ])
            ->add('jobs', CollectionType::class, [
                'entry_type' => JobType::class,
                'entry_options' => ['label' => false],
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
                'attr' => [
                    'data-entry-label' => 'Job',
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Timeslot::class,
        ]);
    }
}
