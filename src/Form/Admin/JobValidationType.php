<?php

namespace App\Form\Admin;

use App\Entity\Job;
use App\Entity\JobDoneType;
use App\Entity\User;

use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class JobValidationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('user', EntityType::class, [
            'label' => 'Membre',
            'required' => false,
            'class' => User::class,
            'choice_label' => 'name',
            'placeholder' => 'Libre',
            'query_builder' => function (EntityRepository $er) {
                return $er->createQueryBuilder('u')
                    ->orderBy('u.name', 'ASC');
            },
        ])
        ->add('manager', CheckboxType::class, [
            'label' => 'Référent',
            'required' => false
        ])
        ->add('jobDone', EntityType::class, [
            'label' => 'Validation',
            'required' => true,
            'class' => JobDoneType::class,
            'choice_label' => 'name',
            'query_builder' => function (EntityRepository $er) {
                return $er->createQueryBuilder('u')
                    ->orderBy('u.position', 'ASC');
            },
        ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Job::class,
            'by_reference' => false
        ]);
    }
}