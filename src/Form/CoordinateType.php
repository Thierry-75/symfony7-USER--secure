<?php

namespace App\Form;

use App\Entity\Coordinate;
use App\Entity\Avatar;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Event\PostSubmitEvent;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\Sequentially;

class CoordinateType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom',TextType::class,['attr'=>['class'=>'form-control'],'label'=>'Lastname','label_attr'=> ['class'=>'form-label mt-4'],
            'constraints'=> [
                new Sequentially([
                    new NotBlank(message: 'Please enter your lastname'),
                    new Length(['min'=>2,'max'=>30,'minMessage'=>'min 2 characters','maxMessage'=>'max 30 characters']),
                    new Regex(
                        pattern: '/^[a-zA-Z-\' éèàïç]{2,30}$/i',
                        htmlPattern: '^[a-zA-Z-\' éèàïç]{2,30}$'
                    )
                ])
            ]])
            ->add('prenom',TextType::class,['attr'=>['class'=>'form-control'],'label'=>'Firstname','label_attr'=> ['class'=>'form-label mt-2'],
            'constraints'=> [
                new Sequentially([
                    new NotBlank(message: 'Please enter your firstname'),
                    new Length(['min'=>2,'max'=>30,'minMessage'=>'min 2 characters','maxMessage'=>'max 30 characters']),
                    new Regex(
                        pattern: '/^[a-zA-Z-\' éèàïç]{2,30}$/i',
                        htmlPattern: '^[a-zA-Z-\' éèàïç]{2,30}$'
                    )
                ])
            ]])
            ->add('pseudo',TextType::class, ['attr'=>['class'=>'form-control'],'label'=>'Pseudo','label_attr'=>['class'=>'form-label mt-2'],
            'constraints'=> [
                new Sequentially([
                    new NotBlank(message: 'Please enter your pseudo'),
                    new Length(['min'=>5,'max'=>30,'minMessage'=>'min 5 characters','maxMessage'=>'max 30 characters']),
                    new Regex(
                        pattern: '/^[a-zA-Z0-9-\' éèàïçôê]{5,30}$/i',
                        htmlPattern: '^[a-zA-Z0-9-\' éèàïçôê]{5,30}$'
                    )
                ])
            ]
            ])
            ->add('address',TextType::class,['attr'=>['class'=>'form-control'],'label'=>'Address','label_attr'=>['class'=>'form-label mt-2'],
            'constraints'=> [
                new Sequentially([
                    new NotBlank(message: 'Please enter your address'),
                    new Length(['min'=>5,'max'=>50,'minMessage'=>'min 5 characters','maxMessage'=>'max 50 characters']),
                    new Regex(
                        pattern: '/^[a-zA-Z0-9-\' éèàïç]{5,50}$/i',
                        htmlPattern: '^[a-zA-Z0-9-\' éèàïç]{5,50}$'
                    )
                ])
            ]
            ])
            ->add('zip',TextType::class,['attr'=>['class'=>'form-control'],'label'=>'Zip','label_attr'=>['class'=>'form-label mt-2'],
            'constraints'=> [
                new Sequentially([
                    new NotBlank(message: 'Please enter your zip code'),
                    new Length(['min'=>5,'max'=>5,'minMessage'=>'min 5 characters','maxMessage'=>'max 5 characters']),
                    new Regex(
                        pattern: '/^((2[A|B])|[0-9]{2})[0-9]{3}$/i',
                        htmlPattern: '^((2[A|B])|[0-9]{2})[0-9]{3}$'
                    )
                ])
            ]
            ])
            ->add('city',TextType::class,['attr'=>['class'=>'form-control'],'label'=>'City','label_attr'=>['class'=>'form-label mt-2'],
            'constraints'=> [
                new Sequentially([
                    new NotBlank(message: 'Please enter your city'),
                    new Length(['min'=>2,'max'=>25,'minMessage'=>'min 2 characters','maxMessage'=>'max 25 characters']),
                    new Regex(
                        pattern: '/^[a-zA-Z-\' éèàïç]{2,25}$/i',
                        htmlPattern: '^[a-zA-Z-\' éèàïç]{2,25}$'
                    )
                ])
            ]
            ])
            ->add('phone', TelType::class,['attr'=>['class'=>'form-control'],'label'=>'Phone','label_attr'=>['class'=>'form-label mt-2 mb-2'],
            'constraints'=> [
                new Sequentially([
                    new NotBlank(message: 'Please enter your phone number'),
                    new Regex(
                        pattern:'/^(?:(?:\+|00)33|0)\s*[1-9](?:[\s.-]*\d{2}){4}$/i',
                        htmlPattern: '^(?:(?:\+|00)33|0)\s*[1-9](?:[\s.-]*\d{2}){4}$'
                    )
                ])
            ]
            ])
            ->add('avatar',FileType::class, [ 'attr'=>['class'=>'form-control mt-4'],
                'label'=>false,
                'multiple'=>false,
                'mapped'=>false,
                'required' =>false
            ])
            
            ->addEventListener(FormEvents::POST_SUBMIT, $this->addDate(...));
        ;
    }

    public function addDate(PostSubmitEvent $event): void
    {
        $data  = $event->getData();
        if (!($data instanceof Coordinate)){
            return;
        }
        $data->setUpdatedAt(new \DateTimeImmutable());
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Coordinate::class,
        ]);
    }
}
