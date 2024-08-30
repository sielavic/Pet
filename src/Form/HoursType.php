<?php

namespace App\Form;

use App\Entity\Task;
use App\Entity\User;
use Doctrine\Persistence\ManagerRegistry;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Validator\Constraints\Date;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Image;

class HoursType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder->add('title', ChoiceType::class, array(
            'label' => 'Деятельность',
            'choices' => array(
                'Тестирование' => 'test',
                'Разработка' => 'dev',
                'Постановка задачи' => 'post',
                'Администрирование' => 'admin',
                'Совещание' => 'mit',
                'Ревью кода' => 'review',
                'Работа с документацией' => 'doc',
            )

        ))
            ->add('hours', TextType::class, array(
                'label' => 'Часы'
            ))
            ->add('date', DateType::class, array(
                'label' => 'Дата',
            ))
            ->add('submit', SubmitType::class, array(
                'label' => 'Сохранить'
            ));
    }


    public function configureOptions(OptionsResolver $resolver)
    {
    }

}