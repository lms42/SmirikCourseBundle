<?php

namespace Smirik\CourseBundle\Model;

use Smirik\CourseBundle\Model\om\BaseLessonQuestionQuery;

class LessonQuestionQuery extends BaseLessonQuestionQuery
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
	
}
