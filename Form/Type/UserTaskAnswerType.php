<?php

namespace Smirik\CourseBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class UserTaskAnswerType extends AbstractType
{

    private $name = 'UserTaskAnswer';

    public function __construct($name = 'UserTaskAnswer')
    {
        $this->name = $name;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('text')
            ->add('url')
            ->add('file', 'file', array('required' => false))
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
        return $this->name;
    }

}
