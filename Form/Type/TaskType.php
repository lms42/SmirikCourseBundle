<?php

namespace Smirik\CourseBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class TaskType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('lesson', 'model', array(
                'class' => 'Smirik\CourseBundle\Model\Lesson',
            ))
            ->add('title')
            ->add('text', 'ckeditor')
            ->add('solution', 'ckeditor')
            ->add('file', 'file', array('required' => false))
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class' => 'Smirik\CourseBundle\Model\Task'
            )
        );
    }

    public function getName()
    {
        return 'Task';
    }

}

