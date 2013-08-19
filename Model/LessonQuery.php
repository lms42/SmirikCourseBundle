<?php

namespace Smirik\CourseBundle\Model;

use Smirik\CourseBundle\Model\om\BaseLessonQuery;

class LessonQuery extends BaseLessonQuery
{

    public function orderByCourse($order)
    {
        return $this
            ->useCourseQuery()
            ->orderByTitle($order)
            ->endUse();
    }

    public function filterByCourse($course, $comparison = null)
    {
        return $this
            ->useCourseQuery()
            ->filterByTitle($course, $comparison)
            ->endUse();
    }
    
    /**
     * Filtering opened lessons list. All or by specific Course for specified $user
     * @param  \FOS\UserBundle\Propel\User|int    $user
     * @param  Course|int  [$course=null]
     * @return CourseQuery
     */
    public function openForUser($user, $course = null)
    {
        return $this
                ->_if($course)
                    ->filterById(is_object($course) ? $course->getId() : $course)
                ->_endIf()
                ->useUserLessonQuery()
                    ->filterByUserId(is_object($user) ? $user->getId() : $user)
                    ->open()
                ->endUse()
            ;
    }
    
}
