<?php

namespace Smirik\CourseBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

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
                'type'         => new \Smirik\CourseBundle\Form\Type\TextContentType(),
                'allow_add'    => true,
                'allow_delete' => true,
                'by_reference' => false,
            ))
            ->add('url_contents', 'collection', array(
                'type'         => new \Smirik\CourseBundle\Form\Type\UrlContentType(),
                'allow_add'    => true,
                'allow_delete' => true,
                'by_reference' => false,
            ))
            ->add('slideshare_contents', 'collection', array(
                'type'         => new \Smirik\CourseBundle\Form\Type\SlideshareContentType(),
                'allow_add'    => true,
                'allow_delete' => true,
                'by_reference' => false,
            ))
    ;
  }

    public function getDefaultOptions(array $options)
    {
        return array(
            'data_class' => 'Smirik\CourseBundle\Model\Lesson',
        );
    }

    public function getName()
    {
        return 'Lesson';
    }

}
