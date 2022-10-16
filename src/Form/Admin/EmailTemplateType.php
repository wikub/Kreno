<?php

namespace App\Form\Admin;

use App\Entity\EmailTemplate;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EmailTemplateType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('label', TextType::class, [
                'label' => 'Libelle'
            ])
            ->add('code', TextType::class, [
                'label' => 'Code'
            ])
            ->add('title', TextType::class, [
                'label' => 'Objet de l\'email'
            ])
            ->add('body', CKEditorType::class, [
                'label' => 'Contenu de l\'email'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => EmailTemplate::class,
        ]);
    }
}
