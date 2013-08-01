<?php

namespace Smirik\CourseBundle\Manager;

use Smirik\CourseBundle\Model\UserLessonQuery;
use Smirik\CourseBundle\Model\UserLesson;

class UserLessonManager
{

    protected $user_task_manager;

    public function setManagers($user_task_manager)
    {
        $this->user_task_manager = $user_task_manager;
    }
    
    public function getByLesson($user, $lesson)
    {
        return 
            UserLessonQuery::create()
                ->filterByUserId($user->getId())
                ->filterByLessonId($lesson->getId())
                ->findOne();
    }
    
    /**
     * Create user lesson (start button)
     * @param  integer                          $user_id
     * @param  \Smirik\CourseBundle\Model\Lesson $lesson
     * @return boolean
     */
    public function create($user, $lesson)
    {
        $user_lesson = new UserLesson();
        $user_lesson->setUserId($user->getId());
        $user_lesson->setLessonId($lesson->getId());
        $user_lesson->setCourseId($lesson->getCourseId());

        return $user_lesson->save();
    }
    
    public function action($user, $lesson, $action)
    {
        $user_lesson = $this->getByLesson($user, $lesson);

        if ($user_lesson && is_object($user_lesson)) {
            if ($action == 'finish') {
                /**
                 * @todo Check quizes
                 */
                $user_lesson->finish();
            } elseif ($action == 'close') {
                if ($lesson->canBeClosedByUser($user->getId())) {
                    $user_lesson->close();
                }
            }
        } else {
            if ($action == 'start' && $lesson->canBeStartedByUser($user->getId())) {
                $this->create($user, $lesson);
                /**
                 * Generate user tasks
                 */
                $this->user_task_manager->generate($user, $lesson);

            }
        }
    }

    /**
     * @param $user
     * @param \Smirik\CourseBundle\Model\Lesson $lesson
     * @return UserLesson
     */
    public function hasUser($user, $lesson)
    {
        return
            UserLessonQuery::create()
                ->filterByUserId($user->getId())
                ->filterByLessonId($lesson->getId())
                ->findOne()
            ;
    }
    
    /**
     * Unsubscribe $user from all lessons related to $course
     * @param \FOS\UserBundle\Propel\User $user
     * @param \Smirik\CourseBundle\Model\Course
     * @return void 
     */
    public function unsubscribe($user, $course)
    {
        $user_lessons = UserLessonQuery::create()
            ->filterByCourseId($course->getId())
            ->filterByUserId($user->getId())
            ->find();

        foreach($user_lessons as $user_lesson) {
            $this->user_task_manager->unsubscribe($user, $user_lesson);
            $user_lesson->delete();
        }
    }

}