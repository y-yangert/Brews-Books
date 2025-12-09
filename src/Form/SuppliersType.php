<?php

namespace App\Form;

use App\Entity\Suppliers;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints as Assert;

class SuppliersType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', null, [
                'constraints' => [
                    new Assert\NotBlank(['message' => 'Please enter a supplier name.']),
                ],
            ])
            ->add('contact_info', TextType::class, [
                'constraints' => [
                    new Assert\NotBlank([
                        'message' => 'Please enter contact information.',
                    ]),
                    new Assert\Length([
                        'min' => 11,
                        'max' => 50,
                        'minMessage' => 'Contact info must be at least {{ limit }} characters.',
                        'maxMessage' => 'Contact info cannot be longer than {{ limit }} characters.',
                    ]),
                ],
            ])
            ->add('address', null, [
                'constraints' => [
                    new Assert\NotBlank(['message' => 'Please enter an address.']),
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Suppliers::class,
        ]);
    }
}
