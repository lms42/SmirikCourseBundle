<?php

namespace Smirik\CourseBundle\Manager;

use Smirik\CourseBundle\Model\Course;
use Smirik\CourseBundle\Model\UserCourseQuery;
use Smirik\CourseBundle\Model\CourseQuery;
use Smirik\CourseBundle\Model\UserCourse;

class CourseManager
{
    protected $lesson_manager;
    protected $user_quiz_manager;
    protected $user_task_manager;

    public function setManagers($lesson_manager, $user_quiz_manager, $user_task_manager)
    {
        $this->lesson_manager      = $lesson_manager;
        $this->user_quiz_manager   = $user_quiz_manager;
        $this->user_task_manager   = $user_task_manager;
    }

    /**
     * @param  \FOS\UserBundle\Propel\User|int  $user
     * @return Course[]|\PropelObjectCollection
     */
    public function my($user)
    {
        $user_id = is_object($user) ? $user->getId() : $user;

        return
            CourseQuery::create()
                ->useUserCourseQuery()
                    ->filterByUserId($user_id)
                ->endUse()
                ->joinWith('UserCourse')
                ->find();
    }
    
    /**
     * @return PropelObjectCollection
     */
    public function getAll()
    {
        return CourseQuery::create()
            ->limit(100)
            ->find()
        ;
    }

    /**
     * @param  $except_ids
     * @return \PropelObjectCollection|Course[]
     */
    public function available($except_ids)
    {
        return
            CourseQuery::create()
                ->filterByIsPublic(true)
                ->filterByIsActive(true)
                ->filterByPid(null)
                ->_if(count($except_ids) > 0)
                    ->filterById($except_ids, \Criteria::NOT_IN)
                ->_endIf()
                ->find()
            ;
    }

    /**
     * Get published courses which available for study (without already studied)
     *
     * @param  \FOS\UserBundle\Propel\User|int  $user
     * @return \PropelObjectCollection|Course[]
     */
    public function getToStudy($user)
    {
        $ids = $this->my($user)->toKeyValue('PrimaryKey', 'Id');
        return $this->available($ids);
    }

    /**
     * @param $user_id
     * @param $course_id
     * @param bool $is_passed
     * @return bool
     */
    public function hasUserCourse($user_id, $course_id, $is_passed)
    {
        $uc = UserCourseQuery::create()
            ->filterByUserId($user_id)
            ->filterByCourseId($course_id)
            ->filterByIsPassed($is_passed)
            ->findOne();

        if ($uc && is_object($uc)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * @param $user_id
     * @param $course_id
     * @return bool
     */
    public function hasUserFinishedCourse($user_id, $course_id)
    {
        return $this->hasUserCourse($user_id, $course_id, true);
    }

    /**
     * @param $user_id
     * @param $course_id
     * @return bool
     */
    public function hasUserStartedCourse($user_id, $course_id)
    {
        return $this->hasUserCourse($user_id, $course_id, false);
    }

    /**
     * @param $course_id
     * @param $user_id
     */
    public function startCourse($course_id, $user_id)
    {
        $uc = new UserCourse();
        $uc->setUserId($user_id);
        $uc->setCourseId($course_id);
        $uc->save();
    }
    
    public function progress($courses, $user)
    {
        $count = $this->lesson_manager->count($courses);
        $count_completed = $this->lesson_manager->countCompleted($courses, $user);
        
        $progress = array();
        foreach ($count_completed as $course_id => $num)
        {
            $progress[$course_id] = round($num*1.0 / $count[$course_id] * 100);
        }
        
        return $progress;
    }

    /**
     * Get results of course: lessons, quizes, tasks e.t.c.
     * @param $user
     * @return array
     */
    public function getResults($user)
    {
        $users_courses = UserCourseQuery::create('uc')
            ->filterByUserId($user->getId())
            ->leftJoin('uc.Course')
            ->find();

        $courses_lessons = array();
        $tasks_data      = array();
        $questions_data  = array();
        foreach ($users_courses as $user_course) {
            $lessons = $this->lesson_manager->getByUserCourse($user_course);
            $lessons_ids = $lessons->getPrimaryKeys();
            $tasks_data[$user_course->getCourseId()]      = $this->user_task_manager->getByLessons($lessons_ids, $user);
            $questions_data[$user_course->getCourseId()]  = $this->lesson_manager->getUserQuestions($lessons_ids, $user);
            $courses_lessons[$user_course->getCourseId()] = $lessons;
        }

        $user_quiz = $this->user_quiz_manager->get($user);

        return array(
            'user' => $user,
            'courses_lessons' => $courses_lessons,
            'users_courses' => $users_courses,
            'tasks_data' => $tasks_data,
            'questions_data' => $questions_data,
            'user_quizes' => $user_quiz,
        );
    }
    
}
