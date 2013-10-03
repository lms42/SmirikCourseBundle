<?php

namespace Smirik\CourseBundle\Import\Transformer;
use Smirik\CourseBundle\Import\CourseImportException;

use Smirik\QuizBundle\Model\Question;

class QuestionTransformer
{
    public function transform($row, $quiz)
    {
        $row = $this->detectType($row);
        
        try {
            $question = \Smirik\QuizBundle\Model\QuestionQuery::create()
                ->useQuizQuestionQuery()
                    ->filterByQuizId($quiz->getId())
                ->endUse()
                ->filterByText($row[3])
                ->findOne()
            ;
            
            if (!($question instanceof Question)) {
                $question = new Question();
            }
            
            $question->setText($row[3]);
            $question->setType($row[4]);
            $question->save();
            
            $quiz_question = \Smirik\QuizBundle\Model\QuizQuestionQuery::create()
                ->filterByQuizId($quiz->getId())
                ->filterByQuestionId($question->getId())
                ->findOneOrCreate()
            ;
            $quiz_question->setQuizId($quiz->getId());
            $quiz_question->setQuestionId($question->getId());
            $quiz_question->save();
            
            $this->transformAnswers($row, $question);
        } catch (Exception $e) {
            throw new CourseImportException('Question data are not valid');
        }
        return $question;
    }
 
    /**
     * 
     * @param array $row
     * @param \Smirik\QuizBundle\Model\Question $question
     */
    protected function transformAnswers($row, \Smirik\QuizBundle\Model\Question $question)
    {
        if ($row[4] === 'text') {
            $this->saveAnswer(null, $row[6], $question);
        } elseif ($row[4] === 'radio') {
            $answer_arr = array_map('trim', explode('|', $row[5]));
            
            foreach ($answer_arr as $answer) {
                $this->saveAnswer($answer, $answer === $row[6]? 1 : 0, $question);
            }
        }
    }
    
    /**
     * 
     * @param string $title
     * @param mix $is_right
     * @param \Smirik\QuizBundle\Model\Question $question
     * @return \Smirik\QuizBundle\Model\Answer
     */
    protected function saveAnswer($title, $is_right, \Smirik\QuizBundle\Model\Question $question)
    {
        $answer = \Smirik\QuizBundle\Model\AnswerQuery::create()
            ->filterByQuestionId($question->getId())
            ->filterByTitle($title)
            ->findOneOrCreate()
        ;
        
        $answer->setQuestionId($question->getId());
        $answer->setTitle($title);
        $answer->setIsRight($is_right);
        $answer->save();
        
        return $answer;
    }
    
    /**
     * 
     * @param array $row
     * @return array
     */
    public function detectType($row)
    {
        $nrow = $row;
        if ($row[4] === 'выбор') {
            $nrow[4] = 'radio';
        } elseif ($row[4] === 'один ответ') {
            $nrow[4] = 'text';
        } else {
            if (strpos($row[5],'|') === false) {
                $nrow[4] = 'text';
            } else {
                $nrow[4] = 'radio';
            }
        }
        
        return $nrow;
    }
}