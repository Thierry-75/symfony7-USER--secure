<?php

namespace App\Form;

use App\Entity\Coordinate;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Event\PostSubmitEvent;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CoordinateType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('address',TextType::class)
            ->add('zip',TextType::class)
            ->add('city',TextType::class)
            ->add('phone', TelType::class)
            ->add('submit', SubmitType::class,['attr'=>['class'=>'btn btn-warning']])
            ->addEventListener(FormEvents::POST_SUBMIT, $this->addDate(...));
        ;
    }

    public function addDate(PostSubmitEvent $event): void
    {
        $data = $event->getData();
        if(!($data instanceof Coordinate))return;
        $data->setUpdatedAt(new \DateTimeImmutable());
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Coordinate::class,
        ]);
    }
}
