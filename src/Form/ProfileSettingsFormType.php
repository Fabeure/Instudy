<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Vich\UploaderBundle\Form\Type\VichImageType;
class ProfileSettingsFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('username', TextType::class, [
                'attr' => ['class' => 'form-control'],
            ])
            ->add('name', TextType::class, [
                'attr' => ['class' => 'form-control'],
            ])
            ->add('surname', TextType::class, [
                'attr' => ['class' => 'form-control'],
            ])
            ->add('phone', NumberType::class, [
                'attr' => ['class' => 'form-control'],
            ])
            ->add('bio', TextType::class, [
                'attr' => ['class' => 'form-control'],
            ])
            ->add('personalEmail', TextType::class, [
                'attr' => ['class' => 'form-control'],
            ])
            ->add('imageFile', VichImageType::class,[
                'label' => 'profile picture'
            ])
        ;
    }


}
