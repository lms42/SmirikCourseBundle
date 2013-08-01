<?php

namespace Smirik\CourseBundle\Manager;

use Smirik\CourseBundle\Model\TaskQuery;
use Smirik\CourseBundle\Model\UserTaskQuery;
use Smirik\CourseBundle\Model\UserTask;

class TaskManager
{
    /**
     * @param \Smirik\CourseBundle\Model\Lesson $lesson
     * @param \Smirik\CourseBundle\Model\Task $task
     * @param $user
     * @return \Smirik\CourseBundle\Model\UserTask|UserTask
     */
    public function findOrCreate($lesson, $task, $user)
    {
        $user_task = UserTaskQuery::create()
            ->filterByLessonId($lesson->getId())
            ->filterByTaskId($task->getId())
            ->filterByUserId($user->getId())
            ->findOne()
        ;
            
        if ( ! $user_task) {
            $user_task = new UserTask();
            $user_task->setUserId($user->getId());
            $user_task->setTaskId($task->getId());
            $user_task->setLessonId($lesson->getId());
        }
        
        return $user_task;
    }

    /**
     * @param array $array
     * @return \Smirik\CourseBundle\Model\LessonQuestion
     */
    public function add($array)
    {
        $question = new LessonQuestion();
        $question->fromArray($array);
        $question->setIsVisible(false);
        $question->save();
        return $question;
    }

}
