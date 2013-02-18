<?php

namespace Smirik\CourseBundle\Form\Type\Base;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class LessonQuestionType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
              ->add('lesson')
            ->add('title')
            ->add('text')
            ->add('is_visible')
            ->add('is_answered')
        ;
    }

    public function getDefaultOptions(array $options)
    {
        return array(
            'data_class' => 'Smirik\CourseBundle\Model\LessonQuestion',
        );
    }

    public function getName()
    {
        return 'LessonQuestion';
    }

}

