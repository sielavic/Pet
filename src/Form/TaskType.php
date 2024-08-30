<?php

namespace App\Form;

use App\Entity\Hours;
use App\Entity\Task;
use App\Entity\User;
use Cassandra\Collection;
use Doctrine\Persistence\ManagerRegistry;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Image;

class TaskType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder->add('assignedUser', ChoiceType::class, array(
            'label' => 'Назначено',
            'choices' => $options['users'],
            'choice_value' => 'id',
            'choice_label' => function ($user) {
                return $user->getSurname() . ' ' . $user->getName();
            }

        ))
            ->add('parentId', ChoiceType::class, array(
                'label' => 'Главная задача',
                'choices' => $options['tasks'],
                'choice_value' => 'id',
                'choice_label' => function ($task) {
                    /** @var Task $task */
                    return $task->getTitle();
                },
                'required' => false,
            ))
            ->add('title', TextType::class, array(
                'label' => 'Заголовок'
            ))
            ->add('content', CKEditorType::class, array(
                'config' => array('toolbar' => 'full'),
                'label' => 'Контент',
            ))
            ->add('priority', ChoiceType::class, array(
                'label' => 'Приоритет',
                'choices' => array(
                    'Высокий' => 'high',
                    'Средний' => 'medium',
                    'Низкий' => 'low'
                )

            ))
            ->add('state', ChoiceType::class, array(
                'label' => 'Состояние',
                'choices' => array(
                    'Бэклог' => 'backlog',
                    'В работе' => 'inprogress',
                    'Тестирование' => 'test',
                    'Ready for dev' => 'dev',
                    'Ready for deploy' => 'deploy',
                    'Готово' => 'finished',
                )

            ))
            ->add('submit', SubmitType::class, array(
                'label' => 'Сохранить'
            ));
    }


    public function configureOptions(OptionsResolver $resolver)
    {

        $resolver->setDefaults([
            'users' => User::class,
            'tasks' => !is_string(Task::class) ?: null
        ]);

    }

}