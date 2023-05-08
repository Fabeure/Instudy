<?php

namespace App\Form;

use PHPUnit\TextUI\XmlConfiguration\CodeCoverage\Report\Text;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;


class TicketFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('Object', TextType::class, [
                'attr' => [
                    'class' => 'form-control form-group',
                    'placeholder' => 'Object',
                    'required' => 'required',
                    'maxlength' => '100',
                ],
            ])
            ->add('Description', TextareaType::class, [
                'attr' => [
                    'class' => 'form-control form-group',
                    'placeholder' => 'Description',
                    'required' => 'required',
                ],
            ]);

    }
}
