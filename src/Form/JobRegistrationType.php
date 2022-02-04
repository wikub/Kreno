<?php

namespace App\Form;

use App\Entity\Job;
use App\Entity\User;
use App\Entity\UserCategory;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class JobRegistrationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('job', EntityType::class, [
            'label' => 'Créneau',
            'class' => Job::class,
            'choice_label' => function($job) {
                return $job->getTimeslot()->getDisplayName().' '.$job->getUserCategory()->getName();
            },
            'query_builder' => function (EntityRepository $er) {
                return $er->createQueryBuilder('j')
                    ->join('j.timeslot', 'ts')
                    ->where('j.user IS NULL')
                    ->andWhere('ts.start > :now')
                    ->setParameter('now', new \DateTime('now'))
                    ->orderBy('ts.start', 'ASC');
            },
        ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'by_reference' => false
        ]);
    }
}