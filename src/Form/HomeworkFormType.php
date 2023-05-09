<?php

namespace App\Form;

use App\Entity\Matiere;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Vich\UploaderBundle\Form\Type\VichFileType;

class HomeworkFormType extends AbstractType
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $users = $this->entityManager->getRepository(User::class)->findUsersByRole('ROLE_TEACHER');
        $userChoices = [];
        foreach ($users as $user) {
            $matiere = $this->entityManager->getRepository(Matiere::class)->findOneBy(['teacher' => $user]);
            $userChoices[$matiere->getMatiereName()." : ".$user->getUsername()] = $user;
        }
        $builder
            ->add('teacher', ChoiceType::class,[
                'choices' => $userChoices,
                'attr' => ['class' => 'form-select']
            ])
            ->add('commentaire', TextType::class, [
                'attr' => ['class' => 'form-control'],
            ])
            ->add('homeworkFile', VichFileType::class,[
                'label' => 'Course',
                'attr' => ['class' => 'form-control'],
            ])
        ;
    }

}