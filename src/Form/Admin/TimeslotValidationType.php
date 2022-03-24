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
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TimeslotValidationType extends AbstractType
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
            ->add('commentValidation', TextareaType::class, [
                'label' => 'Commentaire',
                'required' => false,
            ])
            ->add('jobs', CollectionType::class, [
                'entry_type' => JobValidationType::class,
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
