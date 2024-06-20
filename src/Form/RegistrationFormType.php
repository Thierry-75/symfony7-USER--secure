<?php

namespace App\Form;

use App\Entity\User;
use Doctrine\Inflector\Rules\Pattern;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Event\PostSubmitEvent;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Validator\Constraints\Blank;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\Sequentially;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email',EmailType::class, ['attr'=>['class'=>'form-floating','autofocus'=>true],'label'=>'Email',
             'constraints'=>[
                new Sequentially([
                    new Length(['max'=>180,'maxMessage'=>'Your email should be at least {{ limit }} characters']),
                    new NotBlank(message: "Please enter your email"),
                    new Email(message: 'The email {{ value }} is not a valid email.')
                ])
             ]])
            ->add('agreeTerms', CheckboxType::class, [
                'mapped' => false,
                'constraints' => [
                    new IsTrue([
                        'message' => 'You should agree to our terms.',
                    ]),
                ],
            ])
            ->add('plainPassword', PasswordType::class, [
                'mapped' => false,
                'attr' => ['autocomplete' => 'new-password','placeholder'=>'between 10 and 12 characters', 
                'data-toggle'=>'tooltip', 'data-placement'=>'top', 'title'=>'AZaz09#?!@$ %^&*- authorized'],
                'constraints' => [
                    new Sequentially([
                        new NotBlank(['message'=> 'Please enter your password']),
                        new Length(['min'=>10,'max'=>12,'minMessage'=>'Your password should be at least {{ limit }} characters',
                        'maxMessage'=>'Your password should be max {{ limit }} characters']),
                        new Regex(
                            pattern: '/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$ %^&*-]).{10,12}$/i',
                            htmlPattern: '^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$ %^&*-]).{10,12}$'
                        )
                    ])
                ],
            ])
            ->add('register', SubmitType::class,['attr'=>['class'=>'btn btn-warning w-100 py-2']])
            ->addEventListener(FormEvents::POST_SUBMIT,$this->addDate(...))
            ;
    }

    public function addDate(PostSubmitEvent $event): void
    {
        $data  = $event->getData();
        if (!($data instanceof User)){
            return;
        }
        $data->setCreatedAt(new \DateTimeImmutable());
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
