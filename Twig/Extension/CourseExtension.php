<?php
    
namespace Smirik\CourseBundle\Twig\Extension;

class CourseExtension extends \Twig_Extension
{
 
    protected $course_manager;
    protected $context;
    
    public function getFunctions()
    {
        return array(
            'courses_list' => new \Twig_Function_Method($this, 'getCoursesList')
        );
    }
    
    public function getUser()
    {
        return $this->context->getToken()->getUser();
    }
    
    public function getCoursesList()
    {
        if (is_object($this->getUser()))
        {
            return $this->course_manager->my($this->getUser());
        } else
        {
            $courses = $this->course_manager->getAll();
            return $courses;
        }
    }
    
    public function setCourseManager($course_manager)
    {
        $this->course_manager = $course_manager;
    }
    
    public function setContext($context)
    {
        $this->context = $context;
    }
    
    public function getName()
    {
        return 'course';
    }
    
}