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
	
}
