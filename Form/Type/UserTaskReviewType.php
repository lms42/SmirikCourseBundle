<?php

namespace Smirik\CourseBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class UserTaskReviewType extends AbstractType
{
  
  public function buildForm(FormBuilderInterface $builder, array $options)
  {
    $builder
	    ->add('user_task', 'model', array(
	    	'class' => 'Smirik\CourseBundle\Model\UserTask',
	    ))
      ->add('text')
    ;
  }

  public function getDefaultOptions(array $options)
  {
    return array(
      'data_class' => 'Smirik\CourseBundle\Model\UserTaskReview',
    );
  }

  public function getName()
  {
    return 'UserTaskReview';
  }
  
}

