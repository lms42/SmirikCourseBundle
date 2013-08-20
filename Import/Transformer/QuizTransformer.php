<?php

namespace Smirik\CourseBundle\Import\Transformer;
use Smirik\CourseBundle\Import\CourseImportException;

use Smirik\QuizBundle\Model\Quiz;

class QuizTransformer
{
    
    public function transform($row, $lesson, $counter)
    {
        try {
            $quiz = \Smirik\QuizBundle\Model\QuizQuery::create()
                ->useLessonQuizQuery()
                    ->filterByLessonId($lesson->getId())
                ->endUse()
                ->filterByTitle($row[2])
                ->findOne()
            ;
            
            if (!($quiz instanceof Quiz)) {
                $quiz = new Quiz();
            }
            
            $quiz->setTitle($row[2]);
            $quiz->setDescription($row[3]);
            $quiz->setTime($row[4]);
            $quiz->setNumQuestions($row[5]);
            $quiz->save();
            
            $lesson_quiz = new \Smirik\CourseBundle\Model\LessonQuiz();
            $lesson_quiz->setLessonId($lesson->getId());
            $lesson_quiz->setQuizId($quiz->getId());
            $lesson_quiz->setSortableRank($counter);
            $lesson_quiz->save();
            
        } catch (Exception $e) {
            throw new CourseImportException('Quiz data are not valid');
        }
        return $quiz;
    }
 
}