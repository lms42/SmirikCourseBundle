<?php

namespace Smirik\CourseBundle\Model;

use Smirik\CourseBundle\Model\om\BaseUserLesson;

class UserLesson extends BaseUserLesson
{
	
	public function getStatus()
	{
		if ($this->getIsPassed() && $this->getIsClosed())
		{
			return 'Closed';
		} elseif ($this->getIsPassed())
		{
			return 'Passed';
		} else
		{
			return 'Started';
		}
	}

	public function getBootstrapClass()
	{
		if ($this->getIsPassed() && $this->getIsClosed())
		{
			return 'success';
		} elseif ($this->getIsPassed())
		{
			return 'info';
		} else
		{
			return 'info';
		}
	}
	
	/**
	 * Close currect lesson for user
	 * @return void
	 */
	public function close()
	{
	    $this->setIsClosed(true);
        $this->setStoppedAt(time());
        $this->save();
	}

	/**
	 * Finish currect lesson for user
	 * @return void
	 */
	public function finish()
	{
	    $this->setIsPassed(true);
        $this->save();
	}
	
}
