<?php

namespace App\Form;

use App\Entity\CommitmentContract;
use App\Entity\CommitmentType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CommitmentContractType extends AbstractType
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
                'required' => false,
                'html5' => true,
                'widget' => 'single_text'
            ])
            ->add('type', EntityType::class, [
                'label' => 'Type',
                'class' => CommitmentType::class,
                'choice_label' => 'name'
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
