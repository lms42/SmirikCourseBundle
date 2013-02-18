<?php

namespace Smirik\CourseBundle\Form\Type\Base;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class LessonAnswerType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
              ->add('lesson')
            ->add('lesson_question')
            ->add('text')
            ->add('is_visible')
            ->add('is_accepted')
        ;
    }

    public function getDefaultOptions(array $options)
    {
        return array(
            'data_class' => 'Smirik\CourseBundle\Model\LessonAnswer',
        );
    }

    public function getName()
    {
        return 'LessonAnswer';
    }

}

