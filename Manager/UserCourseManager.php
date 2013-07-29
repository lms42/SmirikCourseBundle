<?php

namespace Smirik\CourseBundle\Manager;

use Smirik\CourseBundle\Model\UserCourseQuery;

class UserCourseManager
{
    protected $user_lesson_manager;

    public function setManagers($user_lesson_manager)
    {
        $this->user_lesson_manager = $user_lesson_manager;
    }
    
    /**
     * Get UserCourse instance for $user & $course
     * @param \FOS\UserBundle\Propel\User $user
     * @param \Smirik\CourseBundle\Model\Course
     * @return \Smirik\CourseBundle\Model\UserCourse|nu;;
     */
    public function get($user, $course)
    {
        $user_course = UserCourseQuery::create()
            ->filterByCourse($course)
            ->filterByUser($user)
            ->findOne();
        return $user_course;
    }
    
    /**
     * Unsubscribe $user from $course
     * @param \FOS\UserBundle\Propel\User $user
     * @param \Smirik\CourseBundle\Model\Course
     * @return boolean
     */
    public function unsubscribe($user, $course)
    {
        $user_course = $this->get($user, $course);
        if (!$user_course) {
            return false;
        }
        
        $this->user_lesson_manager->unsubscribe($user, $course);
        $user_course->delete();
        return true;
    }
    
}
