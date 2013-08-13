<?php

namespace Smirik\CourseBundle\Model;

use Smirik\CourseBundle\Model\om\BaseUserLessonQuery;

class UserLessonQuery extends BaseUserLessonQuery
{
    public function filterByOpened()
    {
        return $this->filterByIsClosed(0);
    }
    
    public function completed()
    {
        return $this
            ->filterByIsPassed(true)
            ->filterByIsClosed(true)
        ;
    }
    
}
