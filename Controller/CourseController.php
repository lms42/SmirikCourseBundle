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
		$avaliable_courses = $cm->avaliable($ids);

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
		$last_avaliable    = $lm->getLastAvaliableNumber($course, $user_id);
		$last_available_id = false;
		if ($last_avaliable)
		{
    		$last_available_id = $last_avaliable->getId();
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
     * @Route("/{id}/revoke", name="course_revoke")
     * @Template()
     * @Secure(roles="ROLE_USER")
     */
    public function revokeAction($id)
    {
        /** @var User $user */
        $user = $this->getUser();

        /** @var Course $course */
        $course = CourseQuery::create()->findPk($id);
        if (!$course) {
            throw new NotFoundHttpException;
        }

        /** @var UserCourse $userCourse */
        $userCourse = UserCourseQuery::create()
            ->filterByCourse($course)
            ->filterByUser($user)
            ->findOne();
        if (!$userCourse) {
            throw new NotFoundHttpException;
        }

        $userLessons = UserLessonQuery::create()
            ->filterByCourseId($course->getId())
            ->filterByUserId($user->getId())
            ->find();

        foreach($userLessons as $userLesson) {
            UserTaskQuery::create()
                ->filterByUserId($user->getId())
                ->filterByLessonId($userLesson->getLessonId())
                ->delete();

            $userLesson->delete();
        }

        $userCourse->delete();

        $this->container->get('session')->getFlashBag()->add('notice', 'Course revoked');

        return $this->redirect($this->generateUrl('course_index'));
    }
	
}
