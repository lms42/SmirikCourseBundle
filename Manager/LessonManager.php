<?php

namespace Smirik\CourseBundle\Manager;

use Smirik\QuizBundle\Model\UserQuizQuery;
use Smirik\CourseBundle\Model\LessonQuestionQuery;
use Smirik\QuizBundle\Model\QuizQuery;
use Smirik\CourseBundle\Model\UserLessonQuery;
use Smirik\CourseBundle\Model\TaskQuery;
use Smirik\CourseBundle\Model\UserTaskQuery;

class LessonManager
{
    
    /**
     * Get text content
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
     */
    private function getSlideshare($lesson)
    {
        $main_slideshare = $lesson->getMainSlideshare();
        return array(
            'main_slideshare' => $main_slideshare,
        );
    }
    
    /**
     * Get list of questions to the lesson
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
     * Get list of avaliable quizes with user data
     */
    private function getQuiz($lesson, $user)
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
            ->filterByUserId($user->getId())
            ->filterByQuizId($quiz_ids)
            ->find()
            ->toKeyValue('Id', 'QuizId');
        return array(
            'quiz'      => $quiz,
            'user_quiz' => $user_quiz,
        );
    }
    
    /**
     * Get list of tasks
     */
    private function getTasks($lesson, $user)
    {
        $tasks = TaskQuery::create()
            ->filterByLessonId($lesson->getId())
            ->find();

        $user_tasks = UserTaskQuery::create('ut')
            ->filterByUserId($user->getId())
            ->filterByLessonId($lesson->getId())
            ->leftJoin('ut.UserTaskReview')
            ->find()
        ;
        
        $ut = array();
        foreach ($user_tasks as $user_task)
        {
            $ut[$user_task->getTaskId()] = $user_task;
        }
        
        return array(
            'tasks'      => $tasks,
            'user_tasks' => $ut,
        );
    }
    
    public function getContent($lesson, $user)
    {
        $text_response       = $this->getText($lesson);
        $slideshare_response = $this->getSlideshare($lesson);
        $questions_response  = $this->getQuestions($lesson);
        $quiz_response       = $this->getQuiz($lesson, $user);
        $tasks_response      = $this->getTasks($lesson, $user);
        
        $response = array_merge($text_response, $slideshare_response, $questions_response, $quiz_response, $tasks_response);
        return $response;
    }
    
    /**
     * Get status of current lesson for user
     */
    public function getStatus($lesson, $user)
    {
        $user_lesson = UserLessonQuery::create()
            ->filterByUserId($user->getId())
            ->filterByLessonId($lesson->getId())
            ->findOne();

        $status = 0;
        if ($user_lesson && is_object($user_lesson)) {
            if ($user_lesson->getIsClosed()) {
                $status = 5;
            } elseif ($user_lesson->getIsPassed()) {
                $status = 3;
                if ($lesson->canBeClosedByUser($user->getId())) {
                    $status = 4;
                }
            } else {
                $status = 2;
            }
        } else {
            if ($lesson->canBeStartedByUser($user->getId())) {
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
    
}
