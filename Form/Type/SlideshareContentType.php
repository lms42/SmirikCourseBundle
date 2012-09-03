<?php

namespace Smirik\CourseBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class SlideshareContentType extends AbstractType
{
  
  public function buildForm(FormBuilderInterface $builder, array $options)
  {
    $builder
      ->add('title')
			->add('url')
      ->add('description', 'textarea', array('required' => false))
    ;
  }

  public function getDefaultOptions(array $options)
  {
    return array(
      'data_class' => 'Smirik\CourseBundle\Model\SlideshareContent',
    );
  }

  public function getName()
  {
    return 'SlideshareContent';
  }
  
}
