<?php

namespace Smirik\CourseBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class CourseType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $transformer = new \Smirik\PropelAdminBundle\Form\DataTransformer\FileToTextTransformer();
        $builder
            ->add('title')
            ->add('description', 'ckeditor')
            ->add('type', 'choice', array('choices' => array(0 => 'Randomly', 1 => 'Сoherently')))
            ->add($builder->create('file', 'file', array('required' => false))->addModelTransformer($transformer))
            ->add('is_public')
            ->add('is_active')
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class' => 'Smirik\CourseBundle\Model\Course'
            )
        );
    }

    public function getName()
    {
        return 'Course';
    }

}

