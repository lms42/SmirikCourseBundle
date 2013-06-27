<?php

namespace Smirik\CourseBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class LessonQuestionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('lesson', 'model', array(
                'class' => 'Smirik\CourseBundle\Model\Lesson',
            ))
            ->add('title')
            ->add('text', 'ckeditor')
            ->add('is_visible')
            ->add('is_answered')
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class' => 'Smirik\CourseBundle\Model\LessonQuestion'
            )
        );
    }

    public function getName()
    {
        return 'LessonQuestion';
    }

}

