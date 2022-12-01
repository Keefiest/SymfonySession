<?php

namespace App\Form;

use App\Entity\Session;
use App\Entity\Formateur;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;

class SessionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                "attr" => ["class" => "form-control"]
                ])
            ->add('startDate', DateTimeType::class, [
                "widget" => "single_text",
                "attr" => ["class" => "form-control"]
                ])
            ->add('endDate', DateTimeType::class, [
                "widget" => "single_text",
                "attr" => ["class" => "form-control"]
                ])
            ->add('slots', IntegerType::class, [
                "attr" => ["class" => "form-control"]
                ])
            ->add('formateur', EntityType::class, [
                "class" => Formateur::class,
                "choice_label" => "nom",
                "placeholder" => "Séléctionner un formateur",
                "attr" => ["class" => "form-control"]
                ])
            ->add('submit', SubmitType::class, [
                "label" => "Confirmer",
                "attr" => ["class" => "btn btn-primary"]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Session::class,
        ]);
    }
}
