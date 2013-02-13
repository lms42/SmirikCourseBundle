<?php

namespace Smirik\CourseBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Response;

use JMS\SecurityExtraBundle\Annotation\Secure;
use Smirik\CourseBundle\Model\LessonQuestionQuery;
/**
 * @Route("/lessons")
 */
class QuestionController extends Controller
{

    /**
     * @Route("/{id}/questions/add", name="lesson_add_question")
     * @Secure(roles="ROLE_USER")
     */
    public function addAction($id)
    {
        $user   = $this->getUser();

        $title = $this->getRequest()->request->get('title', false);
        $text  = $this->getRequest()->request->get('text', false);
        
        $question_manager = $this->get('question.manager');
        $question_manager->add(array(
            'UserId'   => $user->getId(),
            'LessonId' => $id,
            'Title'    => $title,
            'Text'     => $text,
        ));

        return new Response('{"status": 1 }');
    }
    
    /**
     * @Route("/{id}/questions/{question_id}/answer", name="lesson_add_answer")
     * @Secure(roles="ROLE_USER")
     */
    public function answerAction($id, $question_id)
    {
        $user = $this->getUser();
        $text = $this->getRequest()->request->get('text', false);
        
        $question = LessonQuestionQuery::create()->findPk($question_id);
        $question->addAnswer($user->getId(), $text);
        
        return new Response('{"status": 1 }');
    }
    
}