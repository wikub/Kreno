<?php

/*
 * This file is part of the Kreno package.
 *
 * (c) Valentin Van Meeuwen <contact@wikub.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Form\Type;

use App\Form\DataTransformer\TagsTransformer;
use App\Repository\TagRepository;
use Symfony\Bridge\Doctrine\Form\DataTransformer\CollectionToArrayTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TagsType extends AbstractType
{
    private $transformer;
    private $tagRepository;
    private $dataTags;

    public function __construct(TagsTransformer $transformer, TagRepository $tagRepository)
    {
        $this->transformer = $transformer;
        $this->tagRepository = $tagRepository;

        $tags = $this->tagRepository->findAll();
        $this->dataTags = [];
        foreach ($tags as $tag) {
            $this->dataTags[] = $tag->getName();
        }
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->addModelTransformer(new CollectionToArrayTransformer(), true)
            ->addModelTransformer($this->transformer, true);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefault('attr', [
            'class' => 'user-tags',
            'data-tags' => json_encode($this->dataTags),
        ]);
        $resolver->setDefault('required', false);
        // $resolver->setDefaults([
        //     'attr' => [
        //         'class' => 'tag-input',
        //         'data-tags' => json_encode($this->dataTags),
        //     ],
        //     'required' => false,
        // ]);
    }

    public function getParent(): string
    {
        return TextType::class;
    }
}
