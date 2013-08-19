<?php

namespace Smirik\CourseBundle\Model;

use Smirik\CourseBundle\Model\om\BaseCourseQuery;

class CourseQuery extends BaseCourseQuery
{
    
    public function active()
    {
        return $this->filterByIsActive(1);
    }
    
    public function open()
    {
        return $this->filterByIsPublic(1);
    }
    
}
