<?php

namespace Smirik\CourseBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class CourseType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title')
            ->add('description')
            ->add('type', 'choice', array('choices' => array(0 => 'Randomly', 1 => 'Сoherently')))
            ->add('file')
            ->add('is_public')
            ->add('is_active')
        ;
    }

    public function getDefaultOptions(array $options)
    {
        return array(
            'data_class' => 'Smirik\CourseBundle\Model\Course',
        );
    }

    public function getName()
    {
        return 'Course';
    }

}

