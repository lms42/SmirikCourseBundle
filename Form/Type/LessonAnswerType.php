<?php

namespace Smirik\CourseBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class LessonAnswerType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
    	    ->add('lesson', 'model', array(
    	    	'class' => 'Smirik\CourseBundle\Model\Lesson',
    	    ))
    	    ->add('lesson_question', 'model', array(
    	    	'class' => 'Smirik\CourseBundle\Model\LessonQuestion',
    	    ))
            ->add('text', 'ckeditor')
            ->add('is_visible')
            ->add('is_accepted')
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class' => 'Smirik\CourseBundle\Model\LessonAnswer'
            )
        );
    }

    public function getName()
    {
        return 'LessonAnswer';
    }

}

