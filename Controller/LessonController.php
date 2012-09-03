<?php

namespace Smirik\CourseBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Response;

use JMS\SecurityExtraBundle\Annotation\Secure;

use Smirik\CourseBundle\Model\LessonQuery;
use Smirik\CourseBundle\Model\LessonQuestionQuery;
use Smirik\CourseBundle\Model\LessonQuizQuery;
use Smirik\CourseBundle\Model\UserLesson;
use Smirik\CourseBundle\Model\UserLessonQuery;
use Smirik\CourseBundle\Model\LessonQuestion;
use Smirik\CourseBundle\Model\TaskQuery;
use Smirik\CourseBundle\Model\UserTaskQuery;
use Smirik\CourseBundle\Form\Type\UserTaskAnswerType;
use Smirik\QuizBundle\Model\UserQuizQuery;
use Smirik\QuizBundle\Model\QuizQuery;

/**
 * @Route("/lessons")
 */
class LessonController extends Controller
{
	
	/**
	 * @Route("/{id}/addQuestion", name="lesson_add_question")
	 * @Secure(roles="ROLE_USER")
	 */
	public function addQuestionAction($id)
	{
		$user = $this->get('security.context')->getToken()->getUser();
		$lesson = LessonQuery::create()->findPk($id);
		
		$title = $this->getRequest()->request->get('title', false);
		$text  = $this->getRequest()->request->get('text', false);
		
		$question = new LessonQuestion();
		$question->setUserId($user->getId());
		$question->setLessonId($id);
		$question->setTitle($title);
		$question->setText($text);
		$question->setIsVisible(false);
		$question->save();
		return new Response('{"status": 1 }');
	}
	
	/**
	 * @Route("/{id}", name="lesson_index")
	 * @Secure(roles="ROLE_USER")
	 * @Template()
	 */
	public function indexAction($id)
	{
		$user   = $this->get('security.context')->getToken()->getUser();
		$lesson = LessonQuery::create()->findPk($id);
		$cm     = $this->get('course.manager');
		
		if (!$lesson)
		{
			throw $this->createNotFoundException('Not found');
		}
		
		$course = $lesson->getCourse();
		if (!$course->getIsPublic() && (!$cm->hasUserStartedCourse($user->getId(), $course->getId())))
		{
			return $this->redirect($this->generateUrl('course_index'));
		}
		
		/**
		 * Get text content
		 */
		$main_text = $lesson->getMainText();
		$additional_texts = false;
		if ($main_text)
		{
			$additional_texts = $lesson->getTextExcept(array($main_text->getId()));
		}
		
		/**
		 * Get main slideshare keynote.
		 */
		$main_slide = $lesson->getMainSlideshare();

		/**
		 * Get list of questions to the lesson
		 */
		$questions = LessonQuestionQuery::create()
			->filterByLessonId($lesson->getId())
			->filterByIsVisible(true)
			->orderBySortableRank()
			->find();
		
		/**
		 * Get list of avaliable quizes with user data
		 */
		$quiz = QuizQuery::create()
			->useLessonQuizQuery()
				->filterByLessonId($lesson->getId())
				->orderBySortableRank()
			->endUse()
			->groupBy('Id')
			->find();

		$quiz_ids = array();
		foreach ($quiz as $item)
		{
			$quiz_ids[] = $item->getId();
		}

		$users_quiz = UserQuizQuery::create()
			->select(array('Id', 'QuizId'))
			->filterByUserId($user->getId())
			->filterByQuizId($quiz_ids)
			->find()
			->toArray();
		$res = array_walk($users_quiz, function(&$value)
		{
			$value = (int)$value['QuizId'];
		});
		
		/**
		 * Get status of current lesson for user
		 */
		$user_lesson = UserLessonQuery::create()
			->filterByUserId($user->getId())
			->filterByLessonId($lesson->getId())
			->findOne();
		
		$status = 0;
		if ($user_lesson && is_object($user_lesson))
		{
			if ($user_lesson->getIsClosed())
			{
				$status = 5;
			} elseif ($user_lesson->getIsPassed())
			{
				$status = 3;
				if ($lesson->canBeClosedByUser($user->getId()))
				{
					$status = 4;
				}
			} else
			{
				$status = 2;
			}
		} else
		{
			if ($lesson->canBeStartedByUser($user->getId()))
			{
				$status = 1;
			} else
			{
				if ($lesson->getCourse()->getType() == 1)
				{
					return $this->redirect($this->generateUrl('course_show', array('id' => $lesson->getCourseId())));
				} else
				{
					$status = -1;
				}
			}
		}
		
		/**
		 * Get list of tasks
		 */
		$tasks = TaskQuery::create()
			->filterByLessonId($lesson->getId())
			->find();
		
		$users_tasks = UserTaskQuery::create('ut')
			->filterByUserId($user->getId())
			->filterByLessonId($lesson->getId())
			->leftJoin('ut.UserTaskReview')
			->find();
		
		$ut = array();
		foreach ($users_tasks as $user_task)
		{
			$ut[$user_task->getTaskId()] = $user_task;
		}
		
		$forms = array();
		foreach ($tasks as $task)
		{
			if (isset($ut[$task->getId()]))
			{
				$form = $this->createForm(new UserTaskAnswerType('UserTaskAnswer'.$task->getId()), $ut[$task->getId()]);
			} else
			{
				$form = $this->createForm(new UserTaskAnswerType('UserTaskAnswer'.$task->getId()));
			}
			$forms[$task->getId()] = $form->createView();
		}

		/**
		 * @todo Check is it allowed to see this lesson
		 */
		return array(
			'lesson'           => $lesson,
			'maintext'         => $main_text,
			'mainslideshare'   => $main_slide,
			'additional_texts' => $additional_texts,
			'questions'        => $questions,
			'quiz'             => $quiz,
			'users_quiz'       => $users_quiz,
			'status'           => $status,
			'tasks'            => $tasks,
			'tasks_forms'			 => $forms,
			'users_tasks'			 => $ut,
		);
	}
	
	/**
	 * @Route("/{id}/task/{task_id}/save", name="lesson_task_save")
	 * @Secure(roles="ROLE_USER")
	 */
	public function taskAction($id, $task_id)
	{
		$user    = $this->get('security.context')->getToken()->getUser();
		$request = $this->getRequest();
		
		$user_task = UserTaskQuery::create()
			->filterByLessonId($id)
			->filterByTaskId($task_id)
			->filterByUserId($user->getId())
			->findOne();
			
		if (!$user_task || !is_object($user_task))
		{
			$user_task = new UserTask();
			$user_task->setUserId($user->getId());
			$user_task->setTaskId($task_id);
			$user_task->setLessonId($id);
		}
		
		if (!$this->getRequest()->isXmlHttpRequest())
		{
			$user_task->setStatus(1);
		}
		
		$form = $this->createForm(new UserTaskAnswerType('UserTaskAnswer'.$task_id), $user_task);
		
		if ('POST' == $request->getMethod())
		{
			$form->bindRequest($request);
			if ($form->isValid())
			{
				$user_task->save();
				if ($this->getRequest()->isXmlHttpRequest())
				{
					return new Response('{"status": 1, "text": "'.json_encode($_POST).'" }');
				} else
				{
					return $this->redirect($this->generateUrl('lesson_index', array('id' => $id)));
				}
			}
		}
		
		if ($this->getRequest()->isXmlHttpRequest())
		{
			return new Response('{"status": 0 }');
		} else
		{
			return $this->redirect($this->generateUrl('lesson_index', array('id' => $id)));
		}
	}
	
	/**
	 * @Route("/{id}/{action}", name="lesson_action")
	 * @Secure(roles="ROLE_USER")
	 */
	public function actionAction($id, $action)
	{
		$user   = $this->get('security.context')->getToken()->getUser();
		$lesson = LessonQuery::create()->findPk($id);
		$cm     = $this->get('course.manager');
		
		$user_lesson = UserLessonQuery::create()
			->filterByUserId($user->getId())
			->filterByLessonId($lesson->getId())
			->findOne();
		
		if ($user_lesson && is_object($user_lesson))
		{
			if ($action == 'finish')
			{
				/**
				 * @todo Check quizes
				 */
				$user_lesson->setIsPassed(true);
				$user_lesson->save();
			} elseif ($action == 'close')
			{
				if ($lesson->canBeClosedByUser($user->getId()))
				{
					$user_lesson->setIsClosed(true);
					$user_lesson->setStoppedAt(time());
					$user_lesson->save();
				}
			}
		} else
		{
			if ($action == 'start' && $lesson->canBeStartedByUser($user->getId()))
			{
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
	 * @Route("/{id}/quiz/{quiz_id}", name="lesson_quiz")
	 * @Secure(roles="ROLE_USER")
	 */
	public function quizAction($id, $quiz_id)
	{
		$user   = $this->get('security.context')->getToken()->getUser();
		$lesson = LessonQuery::create()->findPk($id);
		$quiz		= QuizQuery::create()->findPk($quiz_id);
		$cm     = $this->get('course.manager');
		$qm     = $this->get('quiz.manager');
		
		if (!$quiz->getIsOpened())
		{
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
		if (!$ul || !is_object($ul))
		{
			return $this->redirect($this->generateUrl('lesson_index', array('id' => $id)));
		}
		
		$lq = LessonQuizQuery::create()
			->filterByLessonId($lesson->getId())
			->filterByQuizId($quiz->getId())
			->findOne();
		
		if (!$lq || !is_object($lq))
		{
			return $this->redirect($this->generateUrl('lesson_index', array('id' => $id)));
		}
		
		/**
		 * Starting or getting quiz if it's open
		 */
		$user_quiz  =$qm->findOrCreateUserQuiz($user->getId(), $quiz);
		if ($user_quiz->getIsClosed())
		{
			return $this->redirect($this->generateUrl('lesson_index', array('id' => $id)));
		}
		
		return $this->redirect($this->generateUrl('smirik_quiz_go', array('uq_id' => $user_quiz->getId(), 'number' => 0)));
	}
	
	/**
	 * @Route("/{id}/addAnswer/{question_id}", name="lesson_add_answer")
	 * @Secure(roles="ROLE_USER")
	 */
	public function addAnswerAction($id, $question_id)
	{
		$user = $this->get('security.context')->getToken()->getUser();
		$lesson = LessonQuery::create()->findPk($id);
		$question = LessonQuestionQuery::create()->findPk($question_id);
		
		$text = $this->getRequest()->request->get('text', false);
		
		$question->addAnswer($user->getId(), $text);
		return new Response('{"status": 1 }');
	}

	
}
