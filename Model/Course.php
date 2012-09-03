<?php

namespace Smirik\CourseBundle\Model;

use Smirik\CourseBundle\Model\om\BaseCourse;

class Course extends BaseCourse
{
	
	public function __toString()
	{
		return $this->getTitle();
	}
	
}
