<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Vich\UploaderBundle\Form\Type\VichFileType;


class CourseFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder

            ->add('nom', TextType::class, [
                'attr' => ['class' => 'form-control'],
            ])
            ->add('courseFile', VichFileType::class,[
                'label' => 'Course',
                'attr' => ['class' => 'form-control']
            ])
        ;
    }
}