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

use App\Form\Model\EmailParamsFormModel;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EmailParamsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('EMAIL_NOTIF_START_CYCLE_ENABLE', CheckboxType::class, [
                'required' => false,
            ])
            ->add('EMAIL_NOTIF_START_CYCLE_NB_DAYS_BEFORE', NumberType::class, [
                'required' => false,
                'html5' => true,
                'scale' => 0,
            ])
            ->add('EMAIL_NOTIF_REMINDER_TIMESLOT_ENABLE', CheckboxType::class, [
                'required' => false,
            ])
            ->add('EMAIL_NOTIF_REMINDER_TIMESLOT_NB_HOURS_BEFORE', NumberType::class, [
                'required' => false,
                'html5' => true,
                'scale' => 0,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => EmailParamsFormModel::class,
        ]);
    }
}
