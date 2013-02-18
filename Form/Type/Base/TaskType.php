<?php

namespace Smirik\CourseBundle\Form\Type\Base;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class TaskType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
              ->add('lesson')
            ->add('title')
            ->add('text')
            ->add('file')
        ;
    }

    public function getDefaultOptions(array $options)
    {
        return array(
            'data_class' => 'Smirik\CourseBundle\Model\Task',
        );
    }

    public function getName()
    {
        return 'Task';
    }

}

