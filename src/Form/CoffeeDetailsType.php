<?php

namespace App\Form;

use App\Entity\CoffeeDetails;
use App\Entity\Products;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class CoffeeDetailsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('roast_level', ChoiceType::class, [
                'choices' => [
                    'Light' => 'Light',
                    'Medium' => 'Medium',
                    'Dark' => 'Dark',
                ],
                'placeholder' => 'Choose roast level',
            ])

            ->add('weight_per_package')
            ->add('flavor_description')
            ->add('product', EntityType::class, [
                'class' => Products::class,
                'choice_label' => 'name',
                'disabled' => $options['data'] && $options['data']->getId() !== null,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => CoffeeDetails::class,
        ]);
    }
}
