<?php

namespace App\Form;

use App\Entity\Matiere;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\RangeType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Vich\UploaderBundle\Form\Type\VichFileType;

class AssesHomeworkFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('commentaire', TextType::class, [
                'attr' => ['class' => 'form-control'],
            ])
            ->add('grade', RangeType::class, [
                'attr' => ['class' => 'form-range'],
            ])
            ->add('submit', SubmitType::class, [
                'attr' => ['class' => 'btn btn-secondary btn-sm'],
            ]);
    }

}