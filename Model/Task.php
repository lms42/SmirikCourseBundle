<?php

namespace Smirik\CourseBundle\Model;

use Smirik\CourseBundle\Model\om\BaseTask;

class Task extends BaseTask
{
	
	public function __toString()
	{
		return $this->getTitle();
	}
	
}
