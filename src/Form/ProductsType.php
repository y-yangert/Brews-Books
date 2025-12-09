<?php

namespace App\Form;

use App\Entity\ProductCategories;
use App\Entity\Products;
use App\Entity\Suppliers;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\File as FileConstraint;

class ProductsType extends AbstractType
{
    
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', null, [
                'constraints' => [
                    new Assert\NotBlank(['message' => 'Please enter a product name.']),
                ],
            ])
            ->add('description')
            ->add('cost_per_unit', null, [
                'constraints' => [
                    new Assert\NotNull(['message' => 'Please enter the cost per unit.']),
                    new Assert\PositiveOrZero(['message' => 'Cost per unit cannot be negative.']),
                ],
            ])
            ->add('price_per_unit', null, [
                'constraints' => [
                    new Assert\NotNull(['message' => 'Please enter the price per unit.']),
                    new Assert\PositiveOrZero(['message' => 'Price per unit cannot be negative.']),
                ],
            ])
            ->add('sku_code', TextType::class, [
                'disabled' => $options['data'] && $options['data']->getId() !== null,
                'constraints' => [
                    new Assert\NotBlank(['message' => 'Please enter an SKU code.']),
                ],
            ])

            ->add('reorder_level', null, [
                'constraints' => [
                    new Assert\NotNull(['message' => 'Please enter a reorder level.']),
                    new Assert\PositiveOrZero(['message' => 'Reorder level cannot be negative.']),
                ],
            ])
            ->add('product_categories', EntityType::class, [
                'class' => ProductCategories::class,
                'choice_label' => 'name',
                'placeholder' => 'Select product category',
                'disabled' => $options['data'] && $options['data']->getId() !== null,
                'constraints' => [
                    new Assert\NotNull(['message' => 'Please select a product category.']),
                ],
            ])

            

            ->add('supplier_id', EntityType::class, [
                'class' => Suppliers::class,
                'choice_label' => 'name',
                'placeholder' => 'Select supplier',
                'disabled' => $options['data'] && $options['data']->getId() !== null,
                'constraints' => [
                    new Assert\NotNull(['message' => 'Please select a supplier.']),
                ],
            ])

            ->add('is_active', ChoiceType::class, [
                'choices' => [
                    'Active' => true,
                    'Inactive' => false,
                ],
                'placeholder' => 'Choose status',
                'constraints' => [
                    new Assert\NotNull(['message' => 'Please choose a status.']),
                ],
            ])

            ->add('image', FileType::class, [
                'label' => 'Product Image (Image file)',
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new FileConstraint([
                        'maxSize' => '2M',
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/png',
                        ],
                        'mimeTypesMessage' => 'Please upload a valid image (JPEG or PNG).',
                    ])
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Products::class,
        ]);
    }
}
