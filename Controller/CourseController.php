<?php

namespace Smirik\CourseBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use JMS\SecurityExtraBundle\Annotation\Secure;

use Smirik\CourseBundle\Model\CourseQuery;
use Smirik\CourseBundle\Model\UserCourseQuery;
use Smirik\CourseBundle\Model\LessonQuery;

/**
 * @Route("/courses")
 */
class CourseController extends Controller
{
	/**
	 * @Route("/", name="course_index")
	 * @Template()
	 * @Secure(roles="ROLE_USER")
	 */
	public function indexAction()
	{
		$user = $this->getUser();

		$my_courses = CourseQuery::create()
			->useUserCourseQuery()
				->filterByUserId($user->getId())
			->endUse()
			->joinWith('UserCourse')
			->find();
		
		$ids = array();
		foreach ($my_courses as $course)
		{
			$ids[] = $course->getId();
		}
		
		$avaliable_courses = CourseQuery::create()
			->filterByIsPublic(true)
			->filterByIsActive(true)
			->filterByPid(null)
			->_if(count($ids) > 0)
				->filterById($ids, \Criteria::NOT_IN)
			->_endIf()
			->find();
		
			
		return array(
			'avaliable_courses' => $avaliable_courses,
			'my_courses'        => $my_courses,
		);
	}
	
	/**
	 * @Route("/{id}/show", name="course_show")
	 * @Template()
	 */
	public function showAction($id)
	{
		$user = $this->getUser();
		$cm   = $this->get('course.manager');
		
		$user_id = false;
		if (is_object($user))
		{
		    $user_id = $user->getId();
		}
		
		$course = CourseQuery::create()->findPk($id);
		
		if (!$course->getIsPublic() && !$this->get('security.context')->isGranted('ROLE_USER'))
		{
		    return $this->redirect($this->generateUrl('homepage'));
		}
		
		$user_course = UserCourseQuery::create()
			->filterByUserId($user_id)
			->filterByCourseId($course->getId())
			->findOne();
			
		$lessons = $course->getLessons();
			
		$has_course        = $cm->hasUserStartedCourse($user_id, $course->getId());
		$finish_course     = $cm->hasUserFinishedCourse($user_id, $course->getId());
		$users_lessons     = $cm->getLessonsForUser($user_id, $course->getId());
		$last_avaliable    = $cm->getLastAvaliableLessonNumber($course->getId(), $user_id);
		$last_avaliable_id = false;
		if ($last_avaliable)
		{
    		$last_avaliable_id = $last_avaliable->getId();
		}

		$count = 0;
		if (is_null($last_avaliable_id) || !$last_avaliable_id)
		{
			$count = -1;
		}

		return array(
			'course'            => $course,
			'lessons'           => $lessons,
			'has_course'        => $has_course,
			'finish_course'     => $finish_course,
			'users_lessons'     => $users_lessons,
			'last_avaliable_id' => $last_avaliable_id,
			'count'             => $count,
			'user_id'           => $user_id,
		);
	}
	
	/**
	 * @Route("/{id}/start", name="course_start")
	 * @Template()
	 * @Secure(roles="ROLE_USER")
	 */
	public function startAction($id)
	{
		$user   = $this->getUser();
		$course = CourseQuery::create()->findPk($id);
		$cm     = $this->get('course.manager');
		
		if (!$course->getIsPublic())
		{
			return $this->redirect($this->generateUrl('course_show', array('id' => $id)));
		}
		
		$has_course = $cm->hasUserStartedCourse($user->getId(), $course->getId());
		if ($has_course)
		{
			return $this->redirect($this->generateUrl('course_show', array('id' => $id)));
		}
		
		$cm->startCourse($course->getId(), $user->getId());
		return $this->redirect($this->generateUrl('course_show', array('id' => $id)));
	}
	
	/**
	 * @Route("/results", name="course_results")
	 * @Template()
	 * @Secure(roles="ROLE_USER")
	 */
	public function resultsAction()
	{
	    $user = $this->getUser();
		$cm   = $this->get('course.manager');
		$qm   = $this->get('quiz.manager');
		
		$users_courses = UserCourseQuery::create('uc')
			->filterByUserId($user->getId())
			->leftJoin('uc.Course')
			->find();
		
		$courses_lessons = array();
		$tasks_data      = array();
		$questions_data  = array();
		foreach ($users_courses as $user_course)
		{
			$lessons = LessonQuery::create('l')
				->filterByCourseId($user_course->getCourseId())
				->useUserLessonQuery()
				    ->filterByUserId($user->getId())
				->endUse()
				->joinWith('l.UserLesson')
				->orderBySortableRank()
				->find();
			
			$lessons_ids = array_map(function($v){return $v['Id'];}, $lessons->toArray());
			$tasks_data[$user_course->getCourseId()] = $cm->getUserTasksForLesson($lessons_ids, $user->getId());
			$questions_data[$user_course->getCourseId()] = $cm->getUserQuestionsForLesson($lessons_ids, $user->getId());
			$courses_lessons[$user_course->getCourseId()] = $lessons;
		}
		
		$user_quiz = $qm->getQuizesForUser($user->getId());
		
		return array(
			'user'            => $user,
			'courses_lessons' => $courses_lessons,
			'users_courses'   => $users_courses,
			'tasks_data'      => $tasks_data,
			'questions_data'  => $questions_data,
			'user_quizes'     => $user_quiz,
		);
	}
	
}
