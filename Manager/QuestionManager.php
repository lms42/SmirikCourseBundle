<?php

namespace Smirik\CourseBundle\Manager;

use Smirik\CourseBundle\Model\LessonQuestion;

class QuestionManager
{

    /**
     * @param array $array
     * @return \Smirik\CourseBundle\Model\LessonQuestion
     */
    public function add($array)
    {
        $question = new LessonQuestion();
        $question->fromArray($array);
        $question->setIsVisible(false);
        $question->save();
        return $question;
    }
    
}
