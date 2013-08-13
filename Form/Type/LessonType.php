<?php

namespace Smirik\CourseBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class LessonType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('course', 'model', array(
                'class' => 'Smirik\CourseBundle\Model\Course',
            ))
            ->add('title')
            ->add('text_contents', 'collection', array(
                'type'         => new TextContentType(),
                'allow_add'    => true,
                'allow_delete' => true,
                'by_reference' => false,
            ))
            ->add('url_contents', 'collection', array(
                'type'         => new UrlContentType(),
                'allow_add'    => true,
                'allow_delete' => true,
                'by_reference' => false,
            ))
            ->add('slideshare_contents', 'collection', array(
                'type'         => new SlideshareContentType(),
                'allow_add'    => true,
                'allow_delete' => true,
                'by_reference' => false,
            ))
            ->add('tasks', 'collection', array(
                'type'         => new AdminTaskType(),
                'allow_add'    => true,
                'allow_delete' => true,
                'by_reference' => false,
            ))
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class' => 'Smirik\CourseBundle\Model\Lesson'
            )
        );
    }

    public function getName()
    {
        return 'Lesson';
    }

}
