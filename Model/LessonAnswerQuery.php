<?php

namespace Smirik\CourseBundle\Model;

use Smirik\CourseBundle\Model\om\BaseLessonAnswerQuery;

class LessonAnswerQuery extends BaseLessonAnswerQuery
{
	
	public function filterByLesson($lesson, $comparison = null)
	{
		return $this
			->useLessonQuery()
				->filterByTitle($lesson, $comparison)
			->endUse();
	}

	public function orderByLesson($order)
	{
		return $this
			->useLessonQuery()
				->orderByTitle($order)
			->endUse();
	}
	
	public function filterByLessonQuestion($question, $comparison = null)
	{
		return $this
			->useLessonQuestionQuery()
				->filterByTitle($question, $comparison)
			->endUse();
	}

	public function orderByLessonQuestion($order)
	{
		return $this
			->useLessonQuestionQuery()
				->orderByTitle($order)
			->endUse();
	}
	
}
