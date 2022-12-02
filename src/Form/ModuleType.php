<?php

namespace App\Form;

use App\Entity\Module;
use App\Entity\Category;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class ModuleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('moduleName', TextType::class, [
                'attr' => ['class' => 'form-control', 'placeholder' => 'Nom du module']
            ])
            ->add('category', EntityType::class, [
                "class" => Category::class,
                "choice_label" => "categoryName",
                "placeholder" => "Séléctionner une catégorie",
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
            'data_class' => Module::class,
        ]);
    }
}
