<?php

namespace Smirik\CourseBundle\Model;

use Smirik\CourseBundle\Model\om\BaseUserTask;

class UserTask extends BaseUserTask
{
	public function __toString()
	{
		return (string)$this->getUser()->getUsername().':'.$this->getTask()->getTitle();
	}
	
	public function fail()
	{
	    $this->setStatus(4);
	    $this->setMark(1);
	}
    
    public function isFinished()
    {
        if (in_array($this->getStatus(), array(3,4)))
        {
            return true;
        }
        return false;
    }
    
    public function isPending()
    {
        if ($this->getStatus() == 1)
        {
            return true;
        }
        return false;
    }
    
    public function isRejected()
    {
        if ($this->getStatus() == 2)
        {
            return true;
        }
        return false;
    }
    
    public function setAccepted()
    {
        $this->setStatus(3);
    }

    public function setRejected()
    {
        $this->setStatus(2);
    }

    public function setFailed()
    {
        $this->setStatus(4);
    }
    
    public function getName()
    {
        return $this->getUser()->getName();
    }

}
