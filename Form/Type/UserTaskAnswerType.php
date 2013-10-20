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
            ->add('text', 'ckeditor', array(
                            'transformers'                 => array('strip_js', 'strip_css', 'strip_comments'),
                            'toolbar'                      => array('basicstyles', 'paragraph', 'links'),
                            'toolbar_groups'               => array(
                                'basicstyles' => array('Bold','Italic','Underline','Strike','Subscript','Superscript','-','RemoveFormat', 'mathedit'),
                                'paragraph' => array('NumberedList','BulletedList','-','Outdent','Indent','-','JustifyLeft', 'JustifyCenter','JustifyRight','JustifyBlock'),
                                'links' => array('Link','Unlink','Anchor'),
                            ),
                            'ui_color'                     => '#ffffff',
                            'startup_outline_blocks'       => false,
                            'width'                        => '100%',
                            'height'                       => '320',
                            'language'                     => 'ru-ru',
                            // 'filebrowser_image_browse_url' => array(
                            //     'url' => 'relative-url.php?type=file',
                            // ),
                            // 'filebrowser_image_browse_url' => array(
                            //     'route'            => 'route_name',
                            //     'route_parameters' => array(
                            //         'type' => 'image',
                            //     ),
                            // ),
                        ))
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
