<?php

namespace Smirik\CourseBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use Symfony\Component\HttpFoundation\Response;
use Smirik\CourseBundle\Model\UserTask;
use Smirik\CourseBundle\Model\UserTaskReview;
use Smirik\CourseBundle\Model\UserTaskQuery;
use Smirik\CourseBundle\Form\Type\UserTaskReviewRejectType;

use Smirik\CourseBundle\Controller\Base\AdminUserTaskController as BaseController;

class AdminUserTaskController extends BaseController
{
	
	/**
	 * @Route("/admin/users_tasks/{id}/accept", name="admin_users_tasks_accept")
	 */
	public function acceptAction($id)
	{
		$user_task = UserTaskQuery::create()
		    ->joinLesson()
		    ->joinTask()
		    ->joinUser()
		    ->findPk($id);

		//$user_task->setStatus(3);
		$user_task->save();
		//return $this->redirect($this->generateUrl('admin_users_tasks_index'));
		return $this->render('SmirikCourseBundle:Admin/UserTask:accept.html.twig', array(
		  'id' => $id,
		  'user_task' => $user_task,
		));
	}
	
	/**
	 * @Route("/admin/users_tasks/{id}/save_review", name="admin_users_tasks_save_review")
	 */
	public function saveReviewAction($id)
	{
	    if ($this->getRequest()->isXmlHttpRequest())
		{
		    $mark    = $this->getRequest()->request->get('mark', 5);
		    $comment = $this->getRequest()->request->get('comment', false);
		    $action  = $this->getRequest()->request->get('action', false);
		    $user = $this->getUser();
    		$user_task = UserTaskQuery::create()->findPk($id);
		    if ($comment && $comment != '')
		    {

        		$user_task_review = new UserTaskReview();
        		$user_task_review->setUserTaskId($id);
        		$user_task_review->setUserId($user->getId());
        		$user_task_review->setText($comment);
        		$user_task_review->save();
		    }
            
		    if ($action == 'accept')
		    {
		        $user_task->setAccepted();
		        $user_task->setMark($mark);
		    } elseif ($action == 'reject')
		    {
		        $user_task->setRejected();
		    }
		    $user_task->save();
		    
		    $data = array('result' => $id);
		    return new Response(json_encode($data));
		}
	    $data = array('result' => false);
		return new Response(json_encode($data));
	}

}

