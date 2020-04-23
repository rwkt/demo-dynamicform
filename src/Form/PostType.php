<?php

namespace App\Form;

use App\Entity\Post;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PostType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title')
            ->add('category', ChoiceType::class, [
                'mapped' => false,
                'required' => true,
                'choices' => [
                    'Games' => 'Games',
                    'Movies' => 'Movies',
                ],
            ])
        ;

        $formModifier = function (FormInterface $form, $category = null) {
            $options = [
                'mapped' => false,
                'required' => true
            ];
            $choices = [
                'choices' => [
                    'None' => 'None'
                ]
            ];
            if ($category == 'Games') {
                $choices = [
                    'choices' => [
                        'Game1' => 'Game1',
                        'Game2' => 'Game2'
                    ]
                ];
            }
            $options = array_merge($options, $choices);
            $form->add('subcategory', ChoiceType::class, $options);
        };

        $builder->addEventListener(FormEvents::PRE_SET_DATA,
            function (FormEvent $event) use ($formModifier) {
                $post = $event->getData();
                $formModifier($event->getForm(), $post->getCategory());
            }
        );

        $builder->get('category')->addEventListener(FormEvents::POST_SUBMIT,
            function (FormEvent $event) use ($formModifier) {
                $category = $event->getData();
                $formModifier($event->getForm()->getParent(), $category);
            }
        );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Post::class,
        ]);
    }
}
