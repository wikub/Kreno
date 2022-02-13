<?php

namespace App\Filter\Form;


use App\Entity\Week;
use App\Filter\UserWeekScheduler;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserWeekSchedulerType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('week', EntityType::class, [
            'label' => 'Semaine',
            'required' => false,
            'class' => Week::class,
            'choice_label' => function($week) {
                return $week->getDisplayName();
            },
            'placeholder' => 'Semaine courante',
            'query_builder' => function (EntityRepository $er) {
                return $er->createQueryBuilder('w')
                    ->orderBy('w.startAt', 'ASC');
            },
        ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => UserWeekScheduler::class,
        ]);
    }
}