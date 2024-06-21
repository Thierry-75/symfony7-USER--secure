<?php

namespace App\Form;

use App\Entity\Coordinate;
use PhpParser\Node\Stmt\Label;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Event\PostSubmitEvent;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
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
                        pattern: '/^[a-zA-Z-\' éèàïç].{2,30}$/i',
                        htmlPattern: '^[a-zA-Z-\' éèàïç].{2,30}$'
                    )
                ])
            ]])
            ->add('prenom',TextType::class,['attr'=>['class'=>'form-control'],'label'=>'Firstname','label_attr'=> ['class'=>'form-label mt-4']])
            ->add('pseudo',TextType::class, ['attr'=>['class'=>'form-control'],'label'=>'Pseudo','label_attr'=>['class'=>'form-label mt-4']])
            ->add('address',TextType::class,['attr'=>['class'=>'form-control'],'label'=>'Address','label_attr'=>['class'=>'form-label mt-4']])
            ->add('zip',TextType::class,['attr'=>['class'=>'form-control'],'label'=>'Zip','label_attr'=>['class'=>'form-label mt-4']])
            ->add('city',TextType::class,['attr'=>['class'=>'form-control'],'label'=>'City','label_attr'=>['class'=>'form-label mt-4']])
            ->add('phone', TelType::class,['attr'=>['class'=>'form-control'],'label'=>'Phone','label_attr'=>['class'=>'form-label mt-4']])
            ->add('submit', SubmitType::class,['attr'=>['class'=>'btn btn-warning'],'label'=>'Register'])
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
