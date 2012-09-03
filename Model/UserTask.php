<?php

namespace Smirik\CourseBundle\Model;

use Smirik\CourseBundle\Model\om\BaseUserTask;

class UserTask extends BaseUserTask
{
	
	public function __toString()
	{
		return (string)$this->getUser()->getUsername().':'.$this->getTask()->getTitle();
	}
	
}
