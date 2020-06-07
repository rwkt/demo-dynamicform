<?php

namespace App\Form;

use App\Entity\Post;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
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
                'choices' => [
                    'Games' => 'Games',
                    'Movies' => 'Movies',
                ],
                'placeholder' => 'Choose category',
            ])
        ;

        $builder->addEventListener(FormEvents::PRE_SET_DATA, [$this, 'onPreSetData']);
        $builder->get('category')->addEventListener(FormEvents::POST_SUBMIT, [$this, 'onPostSubmitCategory']);
    }

    public function onPreSetData(FormEvent $event)
    {
        $form = $event->getForm();
        $post = $event->getData();

        $this->addSubcategoryField($form, $post);
        $this->addRatingField($form, $post);
    }

    public function onPostSubmitCategory(FormEvent $event)
    {
        $this->addSubcategoryField($event->getForm()->getParent(), null, $event->getForm()->getData());
    }

    private function addSubcategoryField(FormInterface $form, $post, $category = null)
    {
        $options = [
            'required' => true,
            'placeholder' => 'Choose subcategory',
            'auto_initialize' => false,
        ];

        if (is_null($category) && !is_null($post)) {
            $category = $post->getCategory();
        }

        $choices = [];
        switch ($category) {
            case 'Games':
                $choices = [
                    'choices' => [
                        'Game1' => 'Game1',
                        'Game2' => 'Game2'
                    ]
                ];
                break;
            case 'Movies':
                $choices = [
                    'choices' => [
                        'Movie1' => 'Movie1',
                        'Movie2' => 'Movie2'
                    ]
                ];
                break;
        }

        $options = array_merge($options, $choices);

        // add subcategory field with listener
        $builder = $form->getConfig()->getFormFactory()->createNamedBuilder('subcategory', ChoiceType::class, null, $options);
        $builder->addEventListener(FormEvents::POST_SUBMIT, [$this, 'onPostSubmitSubcategory']);
        $form->add($builder->getForm());
    }

    public function onPostSubmitSubcategory(FormEvent $event)
    {
        $this->addRatingField($event->getForm()->getParent(), null, $event->getForm()->getData());
    }

    private function addRatingField(FormInterface $form, $post, $subcategory = null)
    {
        $options = [
            'required' => true,
        ];

        if (is_null($subcategory) && !is_null($post)) {
            $subcategory = $post->getSubcategory();
        }

        switch ($subcategory) {
            case 'Game1':
                $options['data'] = 1;
                break;
            case 'Game2':
                $options['data'] = 2;
                break;
            case 'Movie1':
                $options['data'] = 3;
                break;
            case 'Movie2':
                $options['data'] = 4;
                break;
        }

        $form->add('rating', TextType::class, $options);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Post::class,
        ]);
    }
}
