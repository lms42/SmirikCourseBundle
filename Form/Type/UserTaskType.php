<?php

namespace Smirik\CourseBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class UserTaskType extends AbstractType
{
  
  public function buildForm(FormBuilderInterface $builder, array $options)
  {
    $builder
	    ->add('lesson', 'model', array(
	    	'class' => 'Smirik\CourseBundle\Model\Lesson',
	    ))
	    ->add('task', 'model', array(
	    	'class' => 'Smirik\CourseBundle\Model\Task',
	    ))
	    ->add('user', 'model', array(
	    	'class' => 'FOS\UserBundle\Propel\User',
	    ))
      ->add('text')
      ->add('url')
      ->add('file')
      ->add('status', 'choice', array(
      	'choices' => array(0 => 'Draft', 1 => 'Sent', 2 => 'Rejected', 3 => 'Accepted')
      ))
      ->add('mark')
    ;
  }

  public function getDefaultOptions(array $options)
  {
    return array(
      'data_class' => 'Smirik\CourseBundle\Model\UserTask',
    );
  }

  public function getName()
  {
    return 'UserTask';
  }
  
}

