<?php

namespace Smirik\CourseBundle\Import\Transformer;

class CourseTransformer
{
    
    public function transform($row)
    {
        try {
            $course = \Smirik\CourseBundle\Model\CourseQuery::create()
                ->filterByTitle($row[0])
                ->findOneOrCreate()
            ;
            $course->setTitle($row[0]);
            $course->setDescription($row[1]);
            $course->save();
        } catch (Exception $e) {
            throw new \CourseImportException('Course data are not valid');
        }
        return $course;
    }
    
    
}