<?php

namespace Smirik\CourseBundle\Model;

use Smirik\CourseBundle\Model\om\BaseTaskQuery;

class TaskQuery extends BaseTaskQuery
{
	
	public function filterByLesson($lesson, $scope = null)
	{
		return $this
			->useLessonQuery()
				->filterByTitle($lesson, $scope)
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
