<?php
    
namespace Smirik\CourseBundle\Twig\Extension;

use Smirik\CourseBundle\SmirikCourseBundle;

class CourseExtension extends \Twig_Extension
{

    /**
     * @var \Smirik\CourseBundle\Manager\CourseManager
     */
    protected $course_manager;
    protected $context;
    
    public function getFunctions()
    {
        return array(
            'courses_list'     => new \Twig_Function_Method($this, 'getCoursesList'),
            'my_courses_list'  => new \Twig_Function_Method($this, 'getMyCoursesList'),
            'courses_to_study' => new \Twig_Function_Method($this, 'getCoursesToStudy')
        );
    }
    
    public function getUser()
    {
        return $this->context->getToken()->getUser();
    }
    
    public function getMyCoursesList()
    {
        if ( is_object($this->getUser()) ) {
            return $this->course_manager->my($this->getUser());
        }

        return false;
    }

    public function getCoursesList()
    {
        $courses = $this->course_manager->getAll();
        return $courses;
    }

    public function getCoursesToStudy()
    {
        $courses = $this->course_manager->getToStudy($this->getUser());
        return $courses;
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