<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('username', TextType::class, [
                'label' => 'Username',
                'required' => false,
                'attr' => [
                    'placeholder' => 'Username'
                ]
            ])
            ->add('fname', TextType::class, [
                'label' => 'First Name',
                'required' => false,
                'attr' => [
                    'placeholder' => 'First Name'
                ]
            ])
            ->add('lname', TextType::class, [
                'label' => 'Last Name',
                'required' => false,
                'attr' => [
                    'placeholder' => 'Last Name'
                ]
            ])
            ->add('email', EmailType::class, [
                'label' => 'Email',
                'required' => false,
                'attr' => [
                    'placeholder' => 'Email'
                ]
            ])
            ->add('phone_number', TextType::class, [
                'label' => 'Phone Number',
                'required' => false,
                'attr' => [
                    'placeholder' => 'Phone number'
                ],
                'constraints' => [
                    new Length([
                        'min' => 10,
                        'minMessage' => 'Your phone number should be at least {{ limit }} characters',
                        // max length allowed by Symfony for security reasons
                        'max' => 15,
                    ])
                ]
            ])
            ->add('address', TextType::class, [
                'label' => 'Address',
                'required' => false,
                'attr' => [
                    'placeholder' => 'Address'
                ]
            ])
            ->add('plainPassword', PasswordType::class, [
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                'mapped' => false,
                'label' => 'Confirm Password',
                'attr' => [
                    'autocomplete' => 'new-password',
                    'placeholder' => 'Password',
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter a password',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Your password should be at least {{ limit }} characters',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ]),
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
