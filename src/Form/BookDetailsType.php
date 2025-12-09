<?php

namespace App\Form;

use App\Entity\BookDetails;
use App\Entity\Products;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BookDetailsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('ISBN')
            ->add('pages')
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
            'data_class' => BookDetails::class,
        ]);
    }
}
