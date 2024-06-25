<?php

namespace App\Form;

use App\Entity\Avatar;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Event\PostSubmitEvent;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class AvatarType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('avatar',FileType::class, [ 'attr'=>['class'=>'form-control mt-4'],
        'label'=>false,
        'multiple'=>false,
        'mapped'=>false,
        'required' =>false
    ])
        ->add('submit',SubmitType::class,['attr'=>['class'=>'btn btn-info mt-4']])
        ->addEventListener(FormEvents::POST_SUBMIT, $this->addDate(...));
        
    }

    public function addDate(PostSubmitEvent $event): void
    {
        $data  = $event->getData();
        if (!($data instanceof Avatar)) {
            return;
        }
        $data->setUpdatedAt(new \DateTimeImmutable());
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Avatar::class,
        ]);
    }
}
