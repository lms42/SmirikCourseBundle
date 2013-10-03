<?php

namespace Smirik\CourseBundle\Form\Type;

use Smirik\PropelAdminBundle\Form\DataTransformer\FileToTextTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class AdminTaskType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title')
            ->add('text', 'ckeditor')
            ->add('solution', 'ckeditor')
            ->add(
                $builder->create('file', 'file', array('required' => false))->addViewTransformer(new FileToTextTransformer())
            )
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

