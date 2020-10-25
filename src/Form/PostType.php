<?php

namespace App\Form;

use App\Entity\Post;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
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
            ->add('hasRating', CheckboxType::class)
        ;

        $builder->addEventListener(FormEvents::PRE_SET_DATA, [$this, 'onPreSetData']);
        $builder->get('hasRating')->addEventListener(FormEvents::POST_SUBMIT, [$this, 'onPostSubmitHasRating']);
    }

    public function onPreSetData(FormEvent $event)
    {
        $form = $event->getForm();
        $post = $event->getData();

        if ($post === null) {
            return;
        }

        $this->addRatingField($form, $post->getHasRating());
    }

    public function onPostSubmitHasRating(FormEvent $event)
    {
        $this->addRatingField($event->getForm()->getParent(), $event->getForm()->getData());
    }

    private function addRatingField(FormInterface $form, $hasRating = null)
    {
        $options = [
            'required' => true,
            'html5' => true,
            'attr' => [
                'readonly' => true,
            ],
        ];

        if ($hasRating) {
            $options['attr']['readonly'] = false;
        }

        $form->add('rating', NumberType::class, $options);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Post::class,
        ]);
    }
}
