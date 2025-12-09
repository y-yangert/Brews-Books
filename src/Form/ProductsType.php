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
use Symfony\Component\Validator\Constraints\File as FileConstraint;

class ProductsType extends AbstractType
{
    
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('description')
            ->add('cost_per_unit')
            ->add('price_per_unit')
            ->add('sku_code', TextType::class, [
                'disabled' => $options['data'] && $options['data']->getId() !== null,
            ])

            ->add('reorder_level')
            ->add('product_categories', EntityType::class, [
                'class' => ProductCategories::class,
                'choice_label' => 'name',
                'placeholder' => 'Select product category',
                'disabled' => $options['data'] && $options['data']->getId() !== null,
            ])

            ->add('supplier_id', EntityType::class, [
                'class' => Suppliers::class,
                'choice_label' => 'name',
                'placeholder' => 'Select supplier',
                'disabled' => $options['data'] && $options['data']->getId() !== null,
            ])

            ->add('is_active', ChoiceType::class, [
                'choices' => [
                    'Active' => 'Active',
                    'Inactive' => 'Inactive',
                ],
                'placeholder' => 'Choose status',
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
