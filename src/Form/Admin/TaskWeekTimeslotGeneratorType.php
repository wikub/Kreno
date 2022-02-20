<?php

namespace App\Form\Admin;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Choice;

class TaskWeekTimeslotGeneratorType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('start', DateType::class, [
                'label' => 'Début',
                'html5' => true,
                'widget' => 'single_text'
            ])
            ->add('finish', DateType::class, [
                'label' => 'Fin',
                'html5' => true,
                'widget' => 'single_text'
            ])
            ->add('ifWeekExist', ChoiceType::class, [
                'label' => 'Si semaine existe ?',
                'choices' => [
                    'Ignorer' => 1,
                    'Replacer' => 2
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            
        ]);
    }
}
