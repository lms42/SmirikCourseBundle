<?php

namespace Smirik\CourseBundle\Listener;

use Smirik\AdminBundle\Event\ConfigureMenuEvent;
use Smirik\CourseBundle\Model\CourseQuery;

class ConfigureMenuListener
{
    
    protected $security_context;
    
    public function __construct($security_context)
    {
        $this->security_context = $security_context;
    }
    
    /**
     * @param \Smirik\AdminBundle\Event\ConfigureMenuEvent $event
     */
    public function onMenuConfigure(ConfigureMenuEvent $event)
    {
        $menu = $event->getMenu();
        $menu->addChild('admin.courses.menu');
        $menu['admin.courses.menu']->addChild('admin.courses.menu', array('route' => 'admin_courses_index'));
        $menu['admin.courses.menu']->addChild('admin.lessons.menu', array('route' => 'admin_lessons_index'));
        $menu['admin.courses.menu']->addChild('admin.lessons_questions.menu', array('route' => 'admin_lessons_questions_index'));
        $menu['admin.courses.menu']->addChild('admin.lessons_answers.menu', array('route' => 'admin_lessons_answers_index'));
        $menu['admin.courses.menu']->addChild('admin.tasks.menu', array('route' => 'admin_tasks_index'));
        $menu['admin.courses.menu']->addChild('admin.users_tasks.menu', array('route' => 'admin_users_tasks_index'));
        $menu['admin.courses.menu']->addChild('admin.users_tasks_reviews.menu', array('route' => 'admin_users_tasks_reviews_index'));
    }
    
    public function onMainMenuConfigure(ConfigureMenuEvent $event)
    {
        $menu = $event->getMenu();
        
        $user = $this->security_context->getToken()->getUser();

        if ($this->security_context->isGranted('ROLE_USER'))
        {
            $key = 'Results';

            $id = $user->getId();
            $menu->addChild($key, array('route' => 'account_my'));
            $menu[$key]->addChild('Courses results', array('route' => 'course_results'));
            
            $courses = CourseQuery::create()
    			->useUserCourseQuery()
    			    ->filterByUserId($id)
    			->endUse()
    			->orderByCreatedAt()
    			->find();
    		
    		$node = 'Courses';
        } else
        {
            $courses = CourseQuery::create()
                ->filterByIsPublic(true)
                ->find();
            $node = 'Public courses';
        }

        $menu->addChild($node);


		foreach ($courses as $course)
		{
		    $menu[$node]->addChild($course->getTitle(), array('route' => 'course_show', 'routeParameters' => array('id' => $course->getId())));
		}

        if ($this->security_context->isGranted('ROLE_USER')){
            $menu[$node]->addChild('All courses', array('route' => 'course_index'));
        }
    }
    
}