<?php

namespace Smirik\CourseBundle\Import\Transformer;
use Smirik\CourseBundle\Import\CourseImportException;

class TaskTransformer
{
    
    public function transform($row, $lesson)
    {
        try {
            $task = \Smirik\CourseBundle\Model\TaskQuery::create()
                ->filterByLessonId($lesson->getId())  
                ->filterByText($row[3])
                ->findOneOrCreate()
            ;
            $task->setTitle($row[2]);
            $task->setSolution($row[6]);
            $task->save();
        } catch (Exception $e) {
            throw new CourseImportException('Task data are not valid');
        }
        return $task;
    }
    
    
}