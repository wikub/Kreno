<?php

namespace App\Form;

use App\Entity\TimeslotTemplate;
use App\Entity\TimeslotType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Choice;

class TimeslotTemplateType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom'
            ])
            ->add('dayWeek', ChoiceType::class, [
                'label' => 'Jour',
                'choices' => array_flip(TimeslotTemplate::$dayWeekLabel)
            ])
            ->add('start', TimeType::class, [
                'label' => 'Début'
            ])
            ->add('finish', TimeType::class,[
                'label' => 'Fin'
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description'
            ])
            ->add('nbJob', ChoiceType::class, [
                'label' => 'Nombre de poste',
                'choices' => [1 => 1,2 => 2, 3 => 3, 4 => 4, 5 => 5, 6 => 6, 7 => 7, 8 => 8, 9 => 9]
            ])
            ->add('timeslotType', EntityType::class, [
                'label' => 'Type',
                'class' => TimeslotType::class,
                'choice_label' => 'name'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => TimeslotTemplate::class,
        ]);
    }
}
