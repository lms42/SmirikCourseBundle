<?php

namespace Smirik\CourseBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use JMS\SecurityExtraBundle\Annotation\Secure;

use Smirik\CourseBundle\Model\LessonQuery;
use Smirik\CourseBundle\Model\LessonQuizQuery;
use Smirik\CourseBundle\Model\UserLessonQuery;
use Smirik\CourseBundle\Model\UserTaskQuery;
use Smirik\CourseBundle\Form\Type\UserTaskAnswerType;
use Smirik\QuizBundle\Model\QuizQuery;

/**
 * @Route("/lessons")
 */
class LessonController extends Controller
{

    private function createTaskAnswersForms($content)
    {
        $forms = array();
        foreach ($content['tasks'] as $task) {
            if (isset($content['user_tasks'][$task->getId()])) {
                $form = $this->createForm(new UserTaskAnswerType('UserTaskAnswer'.$task->getId()), $content['user_tasks'][$task->getId()]);
            } else {
                $form = $this->createForm(new UserTaskAnswerType('UserTaskAnswer'.$task->getId()));
            }
            $forms[$task->getId()] = $form->createView();
        }

        return $forms;
    }

    /**
     * @Route("/{id}", name="lesson_index")
     * @Secure(roles="ROLE_USER")
     * @ParamConverter("lesson", options={ "mapping"={ "id" : "id" }})
     * @Template()
     */
    public function indexAction(\Smirik\CourseBundle\Model\Lesson $lesson)
    {
        $user = $this->getUser();
        $cm   = $this->get('course.manager');

        $course = $lesson->getCourse();

        /**
         * Deny access for private courses
         */
        if (!$course->getIsPublic() && (!$cm->hasUserStartedCourse($user->getId(), $course->getId()))) {
            return $this->redirect($this->generateUrl('course_index'));
        }

        $lesson_manager = $this->get('lesson.manager');
        $content        = $lesson_manager->getContent($lesson, $user);
        $status         = $lesson_manager->getStatus($lesson, $user);

        if ($status == -2) {
            return $this->redirect($this->generateUrl('course_show', array('id' => $lesson->getCourseId())));
        }

        $forms = $this->createTaskAnswersForms($content);

        /**
         * @todo Check is it allowed to see this lesson
         */

        return array(
            'lesson'           => $lesson,
            'maintext'         => $content['main_text'],
            'mainslideshare'   => $content['main_slideshare'],
            'additional_texts' => $content['additional_texts'],
            'questions'        => $content['questions'],
            'quiz'             => $content['quiz'],
            'users_quiz'       => $content['user_quiz'],
            'status'           => $status,
            'tasks'            => $content['tasks'],
            'tasks_forms'      => $forms,
            'user_tasks'       => $content['user_tasks'],
        );
    }

    /**
     * @Route("/{id}/task/{task_id}/save", name="lesson_task_save")
     * @ParamConverter("lesson", options={ "mapping"={ "id" : "id" }})
     * @ParamConverter("task", options={ "mapping"={ "task_id" : "id" }})
     * @Secure(roles="ROLE_USER")
     */
    public function taskAction(\Smirik\CourseBundle\Model\Lesson $lesson, \Smirik\CourseBundle\Model\Task $task)
    {
        $user    = $this->getUser();
        $request = $this->getRequest();

        $task_manager = $this->get('task.manager');
        $user_task    = $task_manager->findOrCreate($lesson, $task, $user);

        if (!$this->getRequest()->isXmlHttpRequest()) {
            $user_task->setStatus(1);
        }

        $form = $this->createForm(new UserTaskAnswerType('UserTaskAnswer'.$task->getId()), $user_task);

        if ('POST' == $request->getMethod()) {
            $form->bindRequest($request);
            if ($form->isValid()) {
                $user_task->save();
                if ($this->getRequest()->isXmlHttpRequest()) {
                    return new Response('{"status": 1, "text": "'.json_encode($_POST).'" }');
                } else {
                    return $this->redirect($this->generateUrl('lesson_index', array('id' => $lesson->getId())));
                }
            }
        }

        if ($this->getRequest()->isXmlHttpRequest()) {
            return new Response('{"status": 0 }');
        } else {
            return $this->redirect($this->generateUrl('lesson_index', array('id' => $lesson->getId())));
        }
    }

    /**
     * @Route("/{id}/{action}", name="lesson_action")
     * @Secure(roles="ROLE_USER")
     */
    public function actionAction($id, $action)
    {
        $user   = $this->getUser();
        $lesson = LessonQuery::create()->findPk($id);
        $cm     = $this->get('course.manager');

        $user_lesson = UserLessonQuery::create()
            ->filterByUserId($user->getId())
            ->filterByLessonId($lesson->getId())
            ->findOne();

        if ($user_lesson && is_object($user_lesson)) {
            if ($action == 'finish') {
                /**
                 * @todo Check quizes
                 */
                $user_lesson->setIsPassed(true);
                $user_lesson->save();
            } elseif ($action == 'close') {
                if ($lesson->canBeClosedByUser($user->getId())) {
                    $user_lesson->setIsClosed(true);
                    $user_lesson->setStoppedAt(time());
                    $user_lesson->save();
                }
            }
        } else {
            if ($action == 'start' && $lesson->canBeStartedByUser($user->getId())) {
                $cm->createUserLesson($user->getId(), $lesson);
                /**
                 * Generate user tasks
                 */
                $cm->generateUserTaskForUser($user->getId(), $lesson->getId());

            }
        }

        return $this->redirect($this->generateUrl('lesson_index', array('id' => $id)));
    }

    /**
     * @Route("/{id}/task/{task_id}/fail", name="lesson_task_fail")
     * @Template("")
     * @ParamConverter("lesson", options={ "mapping"={ "id" : "id" }})
     * @ParamConverter("task", options={ "mapping"={ "task_id" : "id" }})
     * @Secure(roles="ROLE_USER")
    */
    public function failAction(\Smirik\CourseBundle\Model\Lesson $lesson, \Smirik\CourseBundle\Model\Task $task)
    {
        $user         = $this->getUser();
        $task_manager = $this->get('task.manager');
        $user_task    = $task_manager->findOrCreate($lesson, $task, $user);
        $user_task->fail();
        $user_task->save();
        return $this->redirect($this->generateUrl('lesson_index', array('id' => $lesson->getId())));
    }


    /**
     * @Route("/{id}/quiz/{quiz_id}", name="lesson_quiz")
     * @Secure(roles="ROLE_USER")
     */
    public function quizAction($id, $quiz_id)
    {
        $user   = $this->getUser();
        $lesson = LessonQuery::create()->findPk($id);
        $quiz		= QuizQuery::create()->findPk($quiz_id);
        $cm     = $this->get('course.manager');
        $qm     = $this->get('quiz.manager');

        if (!$quiz->getIsOpened()) {
            return $this->redirect($this->generateUrl('lesson_index', array('id' => $id)));
        }

        /**
         * Check permissions
         * 1. User should start current lesson (and not stop)
         * 2. This quiz should belong to the lesson.
         * 3. User should not have been already started this quiz.
         */
        $ul = UserLessonQuery::create()
            ->filterByUserId($user->getId())
            ->filterByLessonId($lesson->getId())
            ->findOne();
        if (!$ul || !is_object($ul)) {
            return $this->redirect($this->generateUrl('lesson_index', array('id' => $id)));
        }

        $lq = LessonQuizQuery::create()
            ->filterByLessonId($lesson->getId())
            ->filterByQuizId($quiz->getId())
            ->findOne();

        if (!$lq || !is_object($lq)) {
            return $this->redirect($this->generateUrl('lesson_index', array('id' => $id)));
        }

        /**
         * Starting or getting quiz if it's open
         */
        $user_quiz  =$qm->findOrCreateUserQuiz($user->getId(), $quiz);
        if ($user_quiz->getIsClosed()) {
            return $this->redirect($this->generateUrl('lesson_index', array('id' => $id)));
        }

        return $this->redirect($this->generateUrl('smirik_quiz_go', array('uq_id' => $user_quiz->getId(), 'number' => 0)));
    }

}
