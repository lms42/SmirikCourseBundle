<?php
    
namespace Smirik\CourseBundle\Import;

class CourseImporter
{

    protected $transformer;
    protected $quizCounter;

    public function import($content, $dry_run = false)
    {
        $data = explode("\n", $content);
        $num = count($data);
        
        // $con = \Propel::getConnection(\Smirik\CourseBundle\Model\CoursePeer::DATABASE_NAME);
        // $con->beginTransaction();
        
        $course = null;
        $lesson = null;
        $this->resetTransformer();
        $content = null;
        
        for ($i=0; $i<$num; $i++) {
            $row = str_getcsv($data[$i], ';');
            
            if (isset($row[0]) && !empty($row[0])) {
                $lesson  = null;
                $this->resetTransformer();
                $content = null;
                $course  = $this->importCourse($row, $dry_run);
            } elseif (isset($row[1]) && !empty($row[1])) {
                $this->resetTransformer();
                $content = null;
                $lesson  = $this->importLessonByCourse($row, $course, $dry_run);
            } elseif (isset($row[2]) && !empty($row[2])) { /** Add lesson's content, task, quizes */
                $content = $this->importContentByLesson($row, $lesson, $dry_run);
            } else if (isset($row[3]) && !empty($row[3]) && ($content instanceof \Smirik\QuizBundle\Model\Quiz)) { /** Add lesson's questions & quiz */
                $this->importQuestionByQuiz($row, $content, $dry_run);
            }
            
        }
        
        return;
    }
    
    /**
     * 
     * @param array $row
     * @param boolean $dry_run
     * @return \Smirik\CourseBundle\Model\Course
     */
    public function importCourse($row, $dry_run = false)
    {
        if (!$dry_run) {
            $transformer = new Transformer\CourseTransformer();
            $course = $transformer->transform($row);
        } else {
            echo "Course {$row[0]} is created." . PHP_EOL;
            $course = $row[0];
        }
        return $course;
    }
    
    /**
     * 
     * @param array $row
     * @param \Smirik\CourseBundle\Model\Course $course
     * @param boolean $dry_run
     * @return \Smirik\CourseBundle\Model\Lesson
     * @throws CourseImportException
     */
    public function importLessonByCourse($row, $course, $dry_run = false)
    {
        if (empty($course)) {
            throw new CourseImportException('File is not valid');
        }
        
        $this->quizCounter = 1;

        if (!$dry_run) {
            $transformer = new Transformer\LessonTransformer();
            $lesson = $transformer->transform($row, $course);
        } else {
            echo "Lesson {$row[1]} is added.".PHP_EOL;
            $lesson = $row[1];
        }
        return $lesson;
    }
    
        
    /**
     * 
     * @param array $row
     * @param \Smirik\CourseBundle\Model\Lesson $lesson
     * @param boolean $dry_run
     * @return \Smirik\CourseBundle\Model\Content
     * @throws CourseImportException
     */
    public function importContentByLesson($row, $lesson, $dry_run = false)
    {
        if (empty($lesson)) {
            throw new CourseImportException('File is not valid');
        }
        
        $res = $this->setTransformer($row[2]);
        $content = false;
        if (!$res && is_object($this->transformer)) {
            if (!$dry_run) {
                if ($this->transformer instanceof Transformer\QuizTransformer) {
                    $content = $this->transformer->transform($row, $lesson, $this->quizCounter);
                    $this->quizCounter++;
                } else {
                    $content = $this->transformer->transform($row, $lesson);
                }
                
            } else {
                echo "Content added: {$row[2]}.".PHP_EOL;
                $content = $row[2];
            }
        }
        
        return $content;
    }
    
    /**
     * 
     * @param array $row
     * @param \Smirik\QuizBundle\Model\Quiz $quiz
     * @param boolean $dry_run
     * @return \Smirik\CourseBundle\Model\Question
     */
    public function importQuestionByQuiz($row, \Smirik\QuizBundle\Model\Quiz $quiz, $dry_run = false)
    {
        if (!$dry_run) {
            $transformer = new Transformer\QuestionTransformer();
            $question = $transformer->transform($row, $quiz);
        } else {
            echo "Question {$row[3]} is added.";
            $question = $row[3];
        }
        return $question;
    }
    
    public function setTransformer($title)
    {
        if ($title === 'Содержание') {
            $this->transformer = new Transformer\ContentTransformer();
        } elseif ($title === 'Задания') {
            $this->transformer = new Transformer\TaskTransformer();
        } elseif ($title === 'Тесты') {
            $this->transformer = new Transformer\QuizTransformer();
        } else {
            return false;
        }
        return true;
    }
    
    public function resetTransformer()
    {
        $this->transformer = null;
    }
    
}