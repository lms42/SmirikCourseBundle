<?php

namespace Smirik\CourseBundle\Form\Type\Base;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class UserTaskType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('lesson')
            ->add('task')
            ->add('user')
            ->add('text')
            ->add('url')
            ->add('file')
            ->add('status')
            ->add('mark')
        ;
    }

    public function getDefaultOptions(array $options)
    {
        return array(
            'data_class' => 'Smirik\CourseBundle\Model\UserTask',
        );
    }

    public function getName()
    {
        return 'UserTask';
    }

}

