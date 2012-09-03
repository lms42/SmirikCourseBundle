<?php

namespace Smirik\CourseBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use JMS\SecurityExtraBundle\Annotation\Secure;

use Smirik\CourseBundle\Model\CourseQuery;
use Smirik\CourseBundle\Model\UserCourseQuery;

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
		$user = $this->get('security.context')->getToken()->getUser();

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
	 * @Secure(roles="ROLE_USER")
	 */
	public function showAction($id)
	{
		$user = $this->get('security.context')->getToken()->getUser();
		$cm   = $this->get('course.manager');
		
		$course = CourseQuery::create()->findPk($id);
		
		$user_course = UserCourseQuery::create()
			->filterByUserId($user->getId())
			->filterByCourseId($course->getId())
			->findOne();
			
		$lessons = $course->getLessons();
			
		$has_course        = $cm->hasUserStartedCourse($user->getId(), $course->getId());
		$finish_course     = $cm->hasUserFinishedCourse($user->getId(), $course->getId());
		$users_lessons     = $cm->getLessonsForUser($user->getId(), $course->getId());
		$last_avaliable_id = $cm->getLastAvaliableLessonNumber($course, $lessons, $user->getId());

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
			'count'						  => $count,
		);
	}
	
	/**
	 * @Route("/{id}/start", name="course_start")
	 * @Template()
	 * @Secure(roles="ROLE_USER")
	 */
	public function startAction($id)
	{
		$user   = $this->get('security.context')->getToken()->getUser();
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
	
}
