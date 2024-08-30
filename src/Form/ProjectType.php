<?php

namespace App\Form;

use App\Entity\Task;
use App\Entity\User;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Image;

class ProjectType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder->add('project_name', TextType::class, array(
            'label' => 'Название проекта'
        ))
            ->add('description', TextareaType::class, array(
                'label' => 'Описание'
            ))
            ->add('submit', SubmitType::class, array(
                'label' => 'Сохранить'
            ));
    }


    public function configureOptions(OptionsResolver $resolver)
    {

        $resolver->setDefaults([
            'users' => User::class,
            'tasks' => Task::class
        ]);

    }

}