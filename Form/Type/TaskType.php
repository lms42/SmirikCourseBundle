<?php

namespace Smirik\CourseBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class TaskType extends AbstractType
{
  
  public function buildForm(FormBuilderInterface $builder, array $options)
  {
    $builder
	    ->add('lesson', 'model', array(
	    	'class' => 'Smirik\CourseBundle\Model\Lesson',
	    ))
      ->add('title')
      ->add('text')
      ->add('file', 'file', array('required' => false))
    ;
  }

  public function getDefaultOptions(array $options)
  {
    return array(
      'data_class' => 'Smirik\CourseBundle\Model\Task',
    );
  }

  public function getName()
  {
    return 'Task';
  }
  
}

