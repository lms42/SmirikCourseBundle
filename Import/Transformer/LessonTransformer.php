<?php

namespace Smirik\CourseBundle\Import\Transformer;
use Smirik\CourseBundle\Import\CourseImportException;

class LessonTransformer
{
    
    public function transform($row, $course)
    {
        try {
            $lesson = \Smirik\CourseBundle\Model\LessonQuery::create()
                ->filterByCourseId($course->getId())  
                ->filterByTitle($row[1])
                ->findOneOrCreate()
            ;
            $lesson->setTitle($row[1]);
            $lesson->save();
        } catch (Exception $e) {
            throw new CourseImportException('Lesson data are not valid');
        }
        return $lesson;
    }
    
    
}