<?php
// src/Form/InvoiceLineType.php

namespace App\Form;

use App\Entity\InvoiceLine;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType; // Ajout de EntityType
use App\Entity\Invoice; // Import de l'entité Invoice

class InvoiceLineType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('invoice', EntityType::class, [ // Champ invoice de type EntityType
                'class' => Invoice::class, // Classe de l'entité Invoice
                'choice_label' => 'invoiceNumber', // Propriété de l'entité à afficher dans le choix
                'required' => true,
            ])
            ->add('description', TextareaType::class)
            ->add('quantity', IntegerType::class)
            ->add('amount', NumberType::class, [
                'scale' => 2,
                'required' => true,
            ])
            ->add('vatAmount', NumberType::class, [
                'scale' => 2,
                'required' => true,
            ])
            ->add('totalWithVat', NumberType::class, [
                'scale' => 2,
                'required' => true,
            ])
            ->add('save', SubmitType::class, ['label' => 'Create'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => InvoiceLine::class,
        ]);
    }
}
