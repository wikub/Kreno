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

use App\Entity\CommitmentContractRegularTimeslot;
use App\Entity\TimeslotTemplate;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CommitmentContractRegularTimeslotType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('timeslotTemplate', EntityType::class, [
            'label' => 'créneau',
            'required' => true,
            'class' => TimeslotTemplate::class,
            'choice_label' => function ($timeslot) {
                return 'Semaine '.$timeslot->getWeekTemplate()->getWeekTypeLabel().' - '.$timeslot->getDayWeekLabel().' de '.$timeslot->getStart()->format('H:i').' à '.$timeslot->getFinish()->format('H:i').' - '.$timeslot->getTimeslotType()->getName();
            },
            'query_builder' => function (EntityRepository $er) {
                return $er->createQueryBuilder('tt')
                    ->innerJoin('tt.weekTemplate', 'wt')
                    //->innerJoin('tt.timeslotType', 'type')
                    ->orderBy('wt.weekType', 'ASC', 'tt.dayWeek', 'ASC', 'tt.start', 'ASC');
            },
            'group_by' => function ($timeslot, $key, $value) {
                return 'Semaine '.$timeslot->getWeekTemplate()->getWeekTypeLabel();
            },
        ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => CommitmentContractRegularTimeslot::class,
            //'by_reference' => false
        ]);
    }
}
