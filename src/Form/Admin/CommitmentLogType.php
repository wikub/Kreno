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

use App\Entity\User;
use App\Form\Model\AddCommitmentLogFormModel;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CommitmentLogType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('users', EntityType::class,
                [
                    'label' => 'Coopérateurs',
                    'class' => User::class,
                    'choice_label' => function (User $user) {
                        return $user->getName().' '.$user->getFirstname();
                    },
                    'query_builder' => function (EntityRepository $er) {
                        return $er->createQueryBuilder('u')
                            ->where('u.enabled = 1')
                            ->orderBy('u.name', 'ASC')
                            ->addOrderBy('u.firstname', 'ASC');
                    },
                    'multiple' => true,
                    'attr' => [
                        'class' => 'js-choice',
                    ],
                ])
            ->add('nbTimeslot', NumberType::class, [
                    'label' => 'Nombre de créneaux',
                    'scale' => 0,
                    'html5' => true,
            ])
            ->add('nbHour', NumberType::class, [
                    'label' => 'Nombre d\'heure',
                    'scale' => 0,
                    'html5' => true,
            ])
            ->add('comment', TextType::class, [
                    'label' => 'Commentaire',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => AddCommitmentLogFormModel::class,
        ]);
    }
}
