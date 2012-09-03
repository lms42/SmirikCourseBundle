<?php

namespace Smirik\CourseBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class TextContentType extends AbstractType
{
  
  public function buildForm(FormBuilderInterface $builder, array $options)
  {
    $builder
      ->add('title')
      ->add('description', 'textarea', array('required' => false))
			->add('text', 'textarea')
    ;
  }

  public function getDefaultOptions(array $options)
  {
    return array(
      'data_class' => 'Smirik\CourseBundle\Model\TextContent',
    );
  }

  public function getName()
  {
    return 'TextContent';
  }
  
}
