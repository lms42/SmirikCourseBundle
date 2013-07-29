<?php

namespace Smirik\CourseBundle\Model;

use Smirik\CourseBundle\Model\om\BaseCourse;

class Course extends BaseCourse
{
	
	public function __toString()
	{
		return $this->getTitle();
	}
    
    public function getLogo()
    {
        $file = $this->getFile();
        
        if ($file) {
            return '/uploads/courses/'.$file;
        }
        return '/bundles/smirikcourse/images/default.gif';
    }
	
}
