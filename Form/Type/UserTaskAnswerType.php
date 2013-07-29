<?php

namespace Smirik\CourseBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class UserTaskAnswerType extends AbstractType
{

    private $name = 'UserTaskAnswer';

    public function __construct($name = 'UserTaskAnswer')
    {
        $this->name = $name;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $transformer = new \Smirik\PropelAdminBundle\Form\DataTransformer\FileToTextTransformer();
        $builder
            ->add('text')
            ->add('url')
            ->add($builder->create('file', 'file', array('required' => false))->addModelTransformer($transformer))
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class' => 'Smirik\CourseBundle\Model\UserTask'
            )
        );
    }

    public function getName()
    {
        return $this->name;
    }

}
