<?php

namespace Smirik\CourseBundle\Listener;

use Smirik\AdminBundle\Event\ConfigureMenuEvent;

class ConfigureMenuListener
{
    /**
     * @param \Smirik\AdminBundle\Event\ConfigureMenuEvent $event
     */
    public function onMenuConfigure(ConfigureMenuEvent $event)
    {
        $menu = $event->getMenu();
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
}