<?php

namespace Smirik\CourseBundle\Model;

use Smirik\CourseBundle\Model\om\BaseLesson;

use Smirik\QuizBundle\Model\UserQuizQuery;

class Lesson extends BaseLesson
{
	
	public function getMainText()
	{
		$text = TextContentQuery::create()
			->filterByLessonId($this->getId())
			->orderBySortableRank()
			->findOne();
		return $text;
	}
	
	public function getTextExcept($ids)
	{
		$texts = TextContentQuery::create()
			->filterById($ids, \Criteria::NOT_IN)
			->filterByLessonId($this->getId())
			->orderBySortableRank()
			->find();
		return $texts;
	}
	
	public function getMainSlideshare()
	{
		$slide = SlideshareContentQuery::create()
			->filterByLessonId($this->getId())
			->orderBySortableRank()
			->findOne();
		return $slide;
	}
	
	public function __toString()
	{
		return $this->getTitle();
	}
	
	public function canBeStartedByUser($user_id)
	{
		$last_lesson = LessonQuery::create()
			->useUserLessonQuery()
				->filterByUserId($user_id)
				->filterByCourseId($this->getCourseId())
				->filterByIsPassed(true)
				->filterByIsClosed(true)
			->endUse()
			->orderBySortableRank('desc')
			->findOne();

		if ($last_lesson && is_object($last_lesson))
		{
			$last_rank = $last_lesson->getSortableRank();
		} else
		{
			$last_rank = 0;
		}
		
		if ($last_rank >= $this->getSortableRank())
		{
			return true;
		}
		
		$next = LessonQuery::create()
			->filterByCourseId($this->getCourseId())
			->filterBySortableRank(array('min' => $last_rank+1))
			->orderBySortableRank()
			->findOne();
		
		if ($next && is_object($next))
		{
			if ($next->getSortableRank() >= $this->getSortableRank())
			{
				return true;
			}
			return false;
		}
		return true;
	}
	
	/**
	 * @param integer $user_id
	 * @return boolean
	 */
	public function canBeClosedByUser($user_id)
	{
		/**
		 * 1. Is_passed should be true
		 * 2. User should pass all quizes (with status is_closed)
		 * 3. User should pass all tasks
		 */
		$ul = UserLessonQuery::create()
			->filterByUserId($user_id)
			->filterByLessonId($this->getId())
			/* ->filterByIsPassed(true) */
			->filterByIsClosed(false)
			->findOne();
		
		/**
		 * Step 1
		 */
		if ($ul && is_object($ul)) 
		{
			/**
			 * Step 2
			 */
			$lq = LessonQuizQuery::create()
				->select('QuizId')
				->filterByLessonId($this->getId())
				->groupBy('QuizId')
				->find()
				->toArray();

			$uq = UserQuizQuery::create()
				->filterByUserId($user_id)
				->filterByQuizId($lq)
				->filterByIsClosed(true)
				->groupBy('QuizId')
				->count();

			if ($uq != count($lq))
			{
				return false;
			}
			
			/**
			 * Step 3
			 */
			$ut = UserTaskQuery::create()
				->filterByUserId($user_id)
				->filterByLessonId($this->getId())
                ->notFinished()
				->count();
			
			if ($ut > 0)
			{
				return false;
			}
			return true;
			
		}
		return false;
	}
    
}
