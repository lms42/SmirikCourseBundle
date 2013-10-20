<?php

namespace Smirik\CourseBundle\Model;

use Smirik\CourseBundle\Model\om\BaseTask;

class Task extends BaseTask
{
	
	public function __toString()
	{
		return $this->getTitle();
	}
    
    public function setFile($file)
    {
        if ($file instanceOf \Symfony\Component\HttpFoundation\File\UploadedFile)
        {
            $reflector = new \ReflectionClass("AppKernel");
            $fn = $reflector->getFileName();
            $upload_dir = '/uploads/tasks';
            $dir = realpath(dirname($fn).'/../web'.$upload_dir);
            $filename = time().'_'.$file->getClientOriginalName();
            $new_file = $file->move($dir, $filename);
            
            parent::setFile($upload_dir.'/'.$filename);
            
        } else {
            $current = parent::getFile();
            if (($current != '') && ($file == '')) {
                return false;
            }
            parent::setFile($file);
        }
    }
    
    public function guessExtension()
    {
        $tmp = explode('.', $this->getFile());
        $ext = $tmp[count($tmp)-1];
        return $ext;
    }
	
}
