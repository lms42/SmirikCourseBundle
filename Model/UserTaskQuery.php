<?php

namespace Smirik\CourseBundle\Model;

use Smirik\CourseBundle\Model\om\BaseUserTaskQuery;

class UserTaskQuery extends BaseUserTaskQuery
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
	
	public function filterByTask($text, $scope = null)
	{
		return $this
			->useTaskQuery()
				->filterByTitle($text, $scope)
			->endUse();
	}
	
	public function orderByTask($order)
	{
		return $this
			->useTaskQuery()
				->orderByTitle($order)
			->endUse();
	}

	public function filterByUser($text, $scope = null)
	{
		return $this
			->useUserQuery()
				->filterByUsername($text, $scope)
			->endUse();
	}
	
	public function orderByUser($order)
	{
		return $this
			->useUserQuery()
				->orderByUsername($order)
			->endUse();
	}
    
    public function notFinished()
    {
        return $this
            ->filterByStatus(array(3, 4), \Criteria::NOT_IN)
        ;
    }
	
	
}
