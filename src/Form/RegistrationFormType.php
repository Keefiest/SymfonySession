<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'attr' => ['class' => 'form-control', 'placeholder' => 'email valide'],
                'constraints' => [
                    new NotBlank(),
                ]
            ])
            ->add('nom', TextType::class, [
                'attr' => ['class' => 'form-control', 'placeholder' => 'nom valide'],
            ])
            ->add('prenom', TextType::class, [
                'attr' => ['class' => 'form-control', 'placeholder' => 'prenom valide'],
            ])
            ->add('sexe', TextType::class, [
                'attr' => ['class' => 'form-control', 'placeholder' => 'M ou F'],
            ])
            ->add('dateNaissance', DateType::class, [
                "widget" => "single_text",
                "attr" => ["class" => "form-control"]
            ])
            ->add('adresse', TextType::class, [
                'attr' => ['class' => 'form-control', 'placeholder' => 'Adresse'],
            ])
            ->add('codePostale', TextType::class, [
                'attr' => ['class' => 'form-control', 'placeholder' => 'Code Postale'],
            ])
            ->add('ville', TextType::class, [
                'attr' => ['class' => 'form-control', 'placeholder' => 'Ville'],
            ])
            ->add('telephone', TextType::class, [
                'attr' => ['class' => 'form-control', 'placeholder' => '0651...'],
            ])
            ->add('agreeTerms', CheckboxType::class, [
                'mapped' => false,
                'constraints' => [
                    new IsTrue([
                        'message' => 'You should agree to our terms.',
                    ]),
                ],
            ])
            ->add('plainPassword', PasswordType::class, [
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                'mapped' => false,
                'attr' => ['autocomplete' => 'new-password', 'class' => 'form-control'],
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
