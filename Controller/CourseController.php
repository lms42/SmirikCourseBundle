<?php

namespace Smirik\CourseBundle\Controller;

use FOS\UserBundle\Propel\User;
use Smirik\CourseBundle\Model\Course;
use Smirik\CourseBundle\Model\UserCourse;
use Smirik\CourseBundle\Model\UserCourseQuery;
use Smirik\CourseBundle\Model\UserLessonQuery;
use Smirik\CourseBundle\Model\UserTaskQuery;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use JMS\SecurityExtraBundle\Annotation\Secure;

use Smirik\CourseBundle\Model\CourseQuery;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @Route("/courses")
 */
class CourseController extends Controller
{
	/**
	 * @Route("/", name="course_index")
	 * @Template()
	 */
	public function indexAction()
	{
		$user = $this->getUser();
        $cm   = $this->get('course.manager');

		$my_courses = $cm->my($user);
		$ids = $my_courses->getPrimaryKeys();
		$available_courses = $cm->available($ids);

		return array(
			'available_courses' => $available_courses,
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
        $lm   = $this->get('lesson.manager');
		
		$user_id = false;
		if (is_object($user))
		{
		    $user_id = $user->getId();
		}
		
		$course = CourseQuery::create()->findPk($id);

        if (!$course) {
            throw new NotFoundHttpException;
        }
		
		if (!$course->getIsPublic() && !$this->get('security.context')->isGranted('ROLE_USER'))
		{
		    return $this->redirect($this->generateUrl('homepage'));
		}
		
		$lessons = $course->getLessons();
			
		$has_course        = $cm->hasUserStartedCourse($user_id, $course->getId());
		$finish_course     = $cm->hasUserFinishedCourse($user_id, $course->getId());
		$users_lessons     = $lm->getForUser($user_id, $course);
		$last_available    = $lm->getLastavailableNumber($course, $user_id);
		$last_available_id = false;
		if ($last_available)
		{
    		$last_available_id = $last_available->getId();
		}

		$count = 0;
		if (is_null($last_available_id) || !$last_available_id)
		{
			$count = -1;
		}

		return array(
			'course'            => $course,
			'lessons'           => $lessons,
			'has_course'        => $has_course,
			'finish_course'     => $finish_course,
			'users_lessons'     => $users_lessons,
			'last_available_id' => $last_available_id,
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
        $response = $this->get('course.manager')->getResults($user);
        return $response;
	}
    
    /**
     * @Route("/updates", name="course_updates")
     * @Template("SmirikCourseBundle:Course:updates.html.twig")
     */
    public function updatesAction()
    {
        $lessons = $this->get('lesson.manager')->last(5);
        
        return array(
            'lessons' => $lessons,
        );
    }

    /**
     * @Route("/{id}/unsubscribe", name="course_unsubscribe")
     * @Template()
     * @Secure(roles="ROLE_USER")
     */
    public function unsubscribeAction($id)
    {
        /** @var User $user */
        $user = $this->getUser();

        /** @var Course $course */
        $course = CourseQuery::create()->findPk($id);
        if (!$course) {
            throw $this->createNotFoundException('Course not found');
        }
        
        $this->get('user_course.manager')->unsubscribe($user, $course);
        $this->get('session')->getFlashBag()->add('notice', 'Course revoked');

        return $this->redirect($this->generateUrl('course_index'));
    }
    
    /**
     * @Route("/progress", name="course_progress")
     * @Template("SmirikCourseBundle:Course:_progress.html.twig")
     * @Secure(roles="ROLE_USER")
     */
    public function progressAction()
    {
        $courses = $this->get('course.manager')->my($this->getUser());
        $progress = $this->get('course.manager')->progress($courses, $this->getUser());
        return array(
            'courses'  => $courses,
            'progress' => $progress,
        );
    }
	
}
