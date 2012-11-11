<?php

namespace Smirik\CourseBundle\Manager;

use Smirik\CourseBundle\Model\UserCourseQuery;
use Smirik\CourseBundle\Model\UserLessonQuery;
use Smirik\CourseBundle\Model\LessonQuery;
use Smirik\CourseBundle\Model\LessonQuizQuery;
use Smirik\CourseBundle\Model\TaskQuery;
use Smirik\CourseBundle\Model\UserTaskQuery;
use Smirik\CourseBundle\Model\LessonQuestionQuery;

use Smirik\CourseBundle\Model\UserLesson;
use Smirik\CourseBundle\Model\UserTask;
use Smirik\CourseBundle\Model\UserCourse;
use Smirik\CourseBundle\Model\LessonQuiz;

class CourseManager
{
	
	public function hasUserCourse($user_id, $course_id, $is_passed)
	{
		$uc = UserCourseQuery::create()
			->filterByUserId($user_id)
			->filterByCourseId($course_id)
			->filterByIsPassed($is_passed)
			->findOne();
		
		if ($uc && is_object($uc))
		{
			return true;
		} else
		{
			return false;
		}
	}
	
	public function hasUserFinishedCourse($user_id, $course_id)
	{
		return $this->hasUserCourse($user_id, $course_id, true);
	}
	
	public function hasUserStartedCourse($user_id, $course_id)
	{
		return $this->hasUserCourse($user_id, $course_id, false);
	}
	
	public function getLessonsForUser($user_id, $course_id)
	{
		$lessons = UserLessonQuery::create()
			->filterByUserId($user_id)
			->filterByCourseId($course_id)
			->orderByStartedAt()
			->find();
		
		$array = array();
		foreach ($lessons as $lesson)
		{
			$status = 1;
			if ($lesson->getIsPassed())
			{
				if ($lesson->getIsClosed())
				{
					$status = 3;
				} else
				{
					$status = 2;
				}
			}
			$array[$lesson->getLessonId()] = $status;
		}
		return $array;
	}
	
	public function getLastAvaliableLessonNumber($course_id, $user_id, $join_courses = false, $join_user_lesson = false)
	{
		$last_lesson = LessonQuery::create()
			->useUserLessonQuery()
				->filterByUserId($user_id)
				->filterByCourseId($course_id)
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

	public function startCourse($course_id, $user_id)
	{
		$uc = new UserCourse();
		$uc->setUserId($user_id);
		$uc->setCourseId($course_id);
		$uc->save();
	}

	/**
	 * Find or create user quiz related to parameters
	 * @param integer $quiz_id
	 * @param Smirik\CourseBundle\Model\Lesson $lesson
	 * @return Smirik\CourseBundle\Model\LessonQuiz
	 */
	public function findOrCreateLessonQuiz($quiz_id, $lesson)
	{
		$lesson_quiz = LessonQuizQuery::create()
			->filterByQuizId($quiz_id)
			->filterByLessonId($lesson->getId())
			->findOne();

		if (!is_object($lesson_quiz))
		{
			$lesson_quiz = new LessonQuiz();
			$lesson_quiz->setQuizId($quiz_id);
			$lesson_quiz->setLessonId($lesson->getId());
			$lesson_quiz->save();
		}
    return $lesson_quiz;
	}
	
	/**
	 * Create user lesson (start button)
	 * @param integer $user_id
	 * @param Smirik\CourseBundle\Model\Lesson $lesson
	 * @return boolean
	 */
	public function createUserLesson($user_id, $lesson)
	{
		$user_lesson = new UserLesson();
		$user_lesson->setUserId($user_id);
		$user_lesson->setLessonId($lesson->getId());
		$user_lesson->setCourseId($lesson->getCourseId());
		return $user_lesson->save();
	}
	
	/**
	 * Creates user tasks for given lesson
	 * @param integer $user_id
	 * @param integer $lesson_id
	 * @return void
	 */
	public function generateUserTaskForUser($user_id, $lesson_id)
	{
		$tasks_ids = TaskQuery::create()
			->select('Id')
			->filterByLessonId($lesson_id)
			->find()
			->toArray();
		
		$users_tasks_ids = UserTaskQuery::create()
			->select('TaskId')
			->filterByLessonId($lesson_id)
			->filterByUserId($user_id)
			->find()
			->toArray();
		
		$diff = array_diff($tasks_ids, $users_tasks_ids);
		foreach ($diff as $id)
		{
			$user_task = new UserTask();
			$user_task->setLessonId($lesson_id);
			$user_task->setTaskId($id);
			$user_task->setUserId($user_id);
			$user_task->setStatus(0);
			$user_task->save();
			unset($user_task);
		}
	}
	
	public function getUserTasksForLesson($lessons_ids, $user_id)
	{
		$user_tasks = UserTaskQuery::create()
			->filterByLessonId($lessons_ids)
			->filterByUserId($user_id)
			->find();
		
		$tasks = array();
		$marks = array();
		$count = array();
		foreach ($lessons_ids as $id)
		{
			$tasks[$id] = array('accepted' => 0, 'in_progress' => 0, 'draft' => 0);
			$marks[$id] = 0;
			$count[$id] = 0;
		}
		
		foreach ($user_tasks as $user_task)
		{
			switch ($user_task->getStatus()) {
				case 3:
					$tasks[$user_task->getLessonId()]['accepted']++;
					break;
				
				case 2:
				case 1:
					$tasks[$user_task->getLessonId()]['in_progress']++;
				
				case 0:
				default:
					$tasks[$user_task->getLessonId()]['draft']++;
					break;
			}
			if ($user_task->getMark() > 0)
			{
				$marks[$user_task->getLessonId()] = $marks[$user_task->getLessonId()] + $user_task->getMark();
				$count[$user_task->getLessonId()]++;
			}
		}
		return array(
			'tasks' => $tasks,
			'marks'  => $marks,
			'count' => $count,
		);
	}
	
	public function getUserQuestionsForLesson($lessons_ids, $user_id)
	{
		$user_questions = LessonQuestionQuery::create()
			->filterByLessonId($lessons_ids)
			->filterByUserId($user_id)
			->find();
		
		$questions = array();
		$visible   = array();
		foreach ($lessons_ids as $id)
		{
			$questions[$id] = 0;
			$visible[$id]   = 0;
		}
		
		foreach ($user_questions as $user_question)
		{
			$questions[$user_question->getLessonId()]+=1;
			if ($user_question->getIsVisible())
			{
				$visible[$user_question->getLessonId()]+=1;
			}
		}
		
		return array(
			'questions' => $questions,
			'visible'   => $visible,
		);
		
	}
	
}
