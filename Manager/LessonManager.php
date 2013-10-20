<?php

namespace Smirik\CourseBundle\Manager;

use Smirik\CourseBundle\Model\CourseQuery;
use Smirik\QuizBundle\Model\UserQuizQuery;
use Smirik\CourseBundle\Model\LessonQuestionQuery;
use Smirik\QuizBundle\Model\QuizQuery;
use Smirik\CourseBundle\Model\UserLessonQuery;
use Smirik\CourseBundle\Model\TaskQuery;
use Smirik\CourseBundle\Model\UserTaskQuery;
use Smirik\CourseBundle\Model\LessonQuery;
use Smirik\CourseBundle\Model\LessonQuizQuery;
use Smirik\CourseBundle\Model\LessonQuiz;

class LessonManager
{

    /**
     * Get text content
     * @param \Smirik\CourseBundle\Model\Lesson $lesson
     * @return array
     */
    private function getText($lesson)
    {
        $main_text = $lesson->getMainText();
        $additional_texts = false;
        if ($main_text) {
            $additional_texts = $lesson->getTextExcept(array($main_text->getId()));
        }
        return array(
            'main_text' => $main_text,
            'additional_texts' => $additional_texts,
        );
    }

    /**
     * Get main slideshare keynote.
     * @param \Smirik\CourseBundle\Model\Lesson $lesson
     * @return array
     */
    private function getSlideshare($lesson)
    {
        $main_slideshare = $lesson->getMainSlideshare();
        return array(
            'main_slideshare' => $main_slideshare,
        );
    }
    
    /**
     * Get youtube objects.
     * @param \Smirik\CourseBundle\Model\Lesson $lesson
     * @return array
     */
    private function getYoutube($lesson)
    {
        $youtube = $lesson->getYoutubeContents();
        return array(
            'youtube' => $youtube,
        );
    }

    /**
     * Get list of questions to the lesson
     * @param \Smirik\CourseBundle\Model\Lesson $lesson
     * @return array
     */
    private function getQuestions($lesson)
    {
        $questions = LessonQuestionQuery::create()
            ->filterByLessonId($lesson->getId())
            ->filterByIsVisible(true)
            ->orderBySortableRank()
            ->find();

        return array(
            'questions' => $questions,
        );
    }

    /**
     * Get list of available quizes with user data
     * @param \Smirik\CourseBundle\Model\Lesson $lesson
     * @param $user
     * @return array
     */
    private function getQuiz($lesson, $user_id)
    {
        $quiz = QuizQuery::create()
            ->useLessonQuizQuery()
            ->filterByLessonId($lesson->getId())
            ->orderBySortableRank()
            ->endUse()
            ->groupBy('Id')
            ->find();

        $quiz_ids = array();
        foreach ($quiz as $item) {
            $quiz_ids[] = $item->getId();
        }

        $user_quiz = UserQuizQuery::create()
            ->select(array('Id', 'QuizId'))
            ->filterByUserId($user_id)
            ->filterByQuizId($quiz_ids)
            ->find()
            ->toKeyValue('Id', 'QuizId');
        return array(
            'quiz' => $quiz,
            'user_quiz' => $user_quiz,
        );
    }

    /**
     * Get list of tasks
     * @param \Smirik\CourseBundle\Model\Lesson $lesson
     * @param $user
     * @return array
     */
    public function getTasks($lesson, $user_id)
    {
        $tasks = TaskQuery::create()
            ->filterByLessonId($lesson->getId())
            ->find();

        $user_tasks = UserTaskQuery::create('ut')
            ->filterByUserId($user_id)
            ->filterByLessonId($lesson->getId())
            ->leftJoin('ut.UserTaskReview')
            ->find();

        $ut = array();
        if (count($user_tasks) > 0)
        {
            foreach ($user_tasks as $user_task) {
                $ut[$user_task->getTaskId()] = $user_task;
            }
        }

        return array(
            'tasks' => $tasks,
            'user_tasks' => $ut,
        );
    }

    /**
     * @param \Smirik\CourseBundle\Model\Lesson $lesson
     * @param $user
     * @return array
     */
    public function getContent($lesson, $user_id)
    {
        $text_response       = $this->getText($lesson);
        $slideshare_response = $this->getSlideshare($lesson);
        $youtube_response    = $this->getYoutube($lesson);
        $questions_response  = $this->getQuestions($lesson);
        $quiz_response       = $this->getQuiz($lesson, $user_id);
        $tasks_response      = $this->getTasks($lesson, $user_id);

        $response = array_merge($text_response, $slideshare_response, $youtube_response, $questions_response, $quiz_response, $tasks_response);
        
        return $response;
    }

    /**
     * Get status of current lesson for user
     * @param \Smirik\CourseBundle\Model\Lesson $lesson
     * @param $user
     * @return int
     */
    public function getStatus($lesson, $user_id)
    {
        $user_lesson = UserLessonQuery::create()
            ->filterByUserId($user_id)
            ->filterByLessonId($lesson->getId())
            ->findOne();

        $status = 0;
        if ($user_lesson && is_object($user_lesson)) {
            if ($user_lesson->getIsClosed()) {
                $status = 5;
            } elseif ($user_lesson->getIsPassed()) {
                $status = 3;
                if ($lesson->canBeClosedByUser($user_id)) {
                    $status = 4;
                }
            } else {
                $status = 2;
            }
        } else {
            if ($lesson->canBeStartedByUser($user_id)) {
                $status = 1;
            } else {
                if ($lesson->getCourse()->getType() == 1) {
                    $status = -2;
                    // return $this->redirect($this->generateUrl('course_show', array('id' => $lesson->getCourseId())));
                } else {
                    $status = -1;
                }
            }
        }
        return $status;
    }

    /**
     * Get open lessons list. All or by Course
     * @param  \FOS\UserBundle\Propel\User|int  $user
     * @param  Course|int  [$course=null]
     * @return Course[]
     */
    public function getOpenLessons($user, $course = null)
    {
        return LessonQuery::create()->openForUser($user, $course)->find();
    }
    
    public function getLessons($course)
    {
        return LessonQuery::create()
            ->filterPublished()
            ->filterByCourseId($course->getId())
            ->find()
        ;
    } 
    
    /**
     * @param $user
     * @param \Smirik\CourseBundle\Model\Course $course
     * @return array
     */
    public function getForUser($user_id, $course)
    {
        $lessons = UserLessonQuery::create()
            ->filterByUserId($user_id)
            ->filterByCourseId($course->getId())
            ->orderByStartedAt()
            ->find();

        $array = array();

        foreach ($lessons as $lesson) {
            $status = 1;
            if ($lesson->getIsPassed()) {
                if ($lesson->getIsClosed()) {
                    $status = 3;
                } else {
                    $status = 2;
                }
            }
            $array[$lesson->getLessonId()] = $status;
        }

        return $array;
    }

    /**
     * @param $course
     * @param $user
     * @param bool $join_courses
     * @param bool $join_user_lesson
     * @return mixed
     */
    public function getLastavailableNumber($course, $user_id, $join_courses = false, $join_user_lesson = false)
    {
        $last_lesson = LessonQuery::create()
            ->useUserLessonQuery()
            ->filterByUserId($user_id)
            ->filterByCourseId($course->getId())
            ->filterByIsPassed(true)
            ->filterByIsClosed(true)
            ->endUse()
            ->_if($join_courses)
            ->joinCourse()
            ->_endIf()
            ->_if($join_user_lesson)
            ->joinUserLesson()
            ->_endIf()
            ->orderBySortableRank('desc')
            ->findOne();

        return $last_lesson;
    }

    /**
     * Find or create user quiz related to parameters
     * @param  integer                              $quiz_id
     * @param  Smirik\CourseBundle\Model\Lesson     $lesson
     * @return Smirik\CourseBundle\Model\LessonQuiz
     */
    public function findOrCreateLessonQuiz($quiz_id, $lesson)
    {
        $lesson_quiz = LessonQuizQuery::create()
            ->filterByQuizId($quiz_id)
            ->filterByLessonId($lesson->getId())
            ->findOne();

        if (!is_object($lesson_quiz)) {
            $lesson_quiz = new LessonQuiz();
            $lesson_quiz->setQuizId($quiz_id);
            $lesson_quiz->setLessonId($lesson->getId());
            $lesson_quiz->save();
        }

        return $lesson_quiz;
    }

    /**
     * @param $lessons_ids
     * @param $user
     * @return array
     */
    public function getUserQuestions($lessons_ids, $user)
    {
        $user_questions = LessonQuestionQuery::create()
            ->filterByLessonId($lessons_ids)
            ->filterByUserId($user->getId())
            ->find();

        $questions = array();
        $visible = array();
        foreach ($lessons_ids as $id) {
            $questions[$id] = 0;
            $visible[$id] = 0;
        }

        foreach ($user_questions as $user_question) {
            $questions[$user_question->getLessonId()] += 1;
            if ($user_question->getIsVisible()) {
                $visible[$user_question->getLessonId()] += 1;
            }
        }

        return array(
            'questions' => $questions,
            'visible' => $visible,
        );

    }

    /**
     * @param \Smirik\CourseBundle\Model\UserBundle $user_course
     * @return PropelObjectCollection
     */
    public function getByUserCourse($user_course)
    {
        return
            LessonQuery::create('l')
                ->filterByCourseId($user_course->getCourseId())
                ->useUserLessonQuery()
                    ->filterByUserId($user_course->getUserId())
                ->endUse()
                ->leftJoinWith('l.UserLesson')
                ->orderBySortableRank()
                ->find()
            ;
    }

    /**
     * @param \Smirik\CourseBundle\Model\Lesson $lesson
     * @param \Smirik\QuizBundle\Model\Quiz $quiz
     * @return PropelObjectCollection
     */
    public function hasQuiz($lesson, $quiz)
    {
        return
            LessonQuizQuery::create()
                ->filterByLessonId($lesson->getId())
                ->filterByQuizId($quiz->getId())
                ->findOne()
            ;
    }

    /**
     * Get last N lessons with related data
     * @param integer $limit
     * @return PropelObjectCollection
     */
    public function last($limit = 10)
    {
        return LessonQuery::create()
            ->orderById('desc')
            ->leftJoinTask()
            ->groupBy('Id')
            ->limit($limit)
            ->find()
        ;
    }
    
    public function count($courses)
    {
        return LessonQuery::create()
            ->select(array('CourseId', 'num'))
            ->filterByCourseId($courses->getPrimaryKeys())
            ->withColumn('COUNT(*)', 'num')
            ->groupBy('CourseId')
            ->find()
            ->toKeyValue('CourseId', 'num')
        ;
    }

    public function countCompleted($courses, $user)
    {
        return UserLessonQuery::create()
            ->select(array('CourseId', 'num'))
            ->filterByUserId($user->getId())
            ->filterByCourseId($courses->getPrimaryKeys())
            ->completed()
            ->withColumn('COUNT(*)', 'num')
            ->groupBy('CourseId')
            ->find()
            ->toKeyValue('CourseId', 'num')
        ;
    }
    
}
