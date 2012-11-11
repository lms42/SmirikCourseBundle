<?php

namespace Smirik\CourseBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use Smirik\PropelAdminBundle\Controller\AdminAbstractController as AbstractController;

use Smirik\PropelAdminBundle\Column\Column;
use Smirik\PropelAdminBundle\Column\CollectionColumn;
use Smirik\PropelAdminBundle\Action\Action;
use Smirik\PropelAdminBundle\Action\ObjectAction;
use Smirik\PropelAdminBundle\Action\AjaxObjectAction;
use Smirik\PropelAdminBundle\Action\SingleAction;

use Symfony\Component\HttpFoundation\Response;
use Smirik\CourseBundle\Model\UserTask;
use Smirik\CourseBundle\Model\UserTaskReview;
use Smirik\CourseBundle\Model\UserTaskQuery;
use Smirik\CourseBundle\Form\Type\UserTaskReviewRejectType;

class AdminUserTaskController extends AbstractController
{
	
	public $layout = 'SmirikAdminBundle::layout.html.twig';
	public $name   = 'users_tasks';

	public function setup()
	{
		$this->configure(array(
		                 array('name' => 'id', 'label' => 'Id', 'type' => 'integer', 'options' => array(
											 'editable' => false,
											 'listable' => true,
											 'sortable' => true,
											 'filterable' => true)),
										 array('name' => 'lesson', 'label' => 'Lesson', 'type' => 'string', 'options' => array(
											 'editable' => true,
											 'listable' => true,
											 'sortable' => true,
											 'filterable' => true)),
										 array('name' => 'task', 'label' => 'Task', 'type' => 'string', 'options' => array(
											 'editable' => true,
											 'listable' => true,
											 'sortable' => true,
											 'filterable' => true)),
										 array('name' => 'user', 'label' => 'User', 'type' => 'string', 'options' => array(
											 'editable' => true,
											 'listable' => true,
											 'sortable' => true,
											 'filterable' => true)),
										 array('name' => 'text', 'label' => 'Text', 'type' => 'text', 'options' => array(
											 'editable' => true,
											 'listable' => false,
											 'sortable' => true,
											 'filterable' => true)),
										 array('name' => 'url', 'label' => 'Url', 'type' => 'string', 'options' => array(
											 'editable' => true,
											 'listable' => false,
											 'sortable' => true,
											 'filterable' => true)),
										 array('name' => 'file', 'label' => 'File', 'type' => 'string', 'options' => array(
											 'editable' => true,
											 'listable' => false,
											 'sortable' => true,
											 'filterable' => true)),
										 array('name' => 'status', 'label' => 'Status', 'type' => 'integer', 'options' => array(
											 'editable' => true,
											 'listable' => true,
											 'sortable' => true,
											 'filterable' => true)),
										 array('name' => 'mark', 'label' => 'Mark', 'type' => 'integer', 'options' => array(
											 'editable' => true,
											 'listable' => true,
											 'sortable' => true,
											 'filterable' => true))
		                 ),
		                 array('new' => new SingleAction('New', 'new', 'admin_users_tasks_new', true),
	                                        'accept' => new AjaxObjectAction(array(0 => 'В работе', 1 => 'Проверить', 2 => 'Отклонено', '3' => 'Принято', 'default' => 'Отклонить'), 'status', 'admin_users_tasks_accept', true),
                                            // 'reject' => new ObjectAction('Reject', 'reject', 'admin_users_tasks_reject', true),
											'edit' => new ObjectAction('Edit', 'edit', 'admin_users_tasks_edit', true),
											'delete' => new ObjectAction('Delete', 'delete', 'admin_users_tasks_delete', true, true))
		                );
	}

	public function getQuery()
	{
		return \Smirik\CourseBundle\Model\UserTaskQuery::create();
	}
	
	public function getForm()
	{
		return new \Smirik\CourseBundle\Form\Type\UserTaskType;
	}
	
	public function getObject()
	{
		return new \Smirik\CourseBundle\Model\UserTask;
	}
	
	/**
	 * @Route("/admin/users_tasks/{id}/reject", name="admin_users_tasks_reject")
	 * @Template("SmirikCourseBundle:Admin/UserTask:reject.html.twig")
	 */
	public function rejectAction($id)
	{
		$user = $this->get('security.context')->getToken()->getUser();
		$user_task = UserTaskQuery::create()->findPk($id);
		$request   = $this->getRequest();
		
		$user_task_review = new UserTaskReview();
		$user_task_review->setUserTaskId($id);
		$user_task_review->setUserId($user->getId());
		
		$form = $this->createForm(new UserTaskReviewRejectType(), $user_task_review);
		
		if ('POST' == $request->getMethod())
		{
			$form->bindRequest($request);
			if ($form->isValid())
			{
				$user_task_review->save();
				$user_task->setStatus(2);
				$user_task->save();
				if ($this->getRequest()->isXmlHttpRequest())
				{
					return new Response('{"status": 1 }');
				} else
				{
					return $this->redirect($this->generateUrl('admin_users_tasks_index'));
				}
			}
		}
		
		return array(
			'form' => $form->createView(),
			'id'	 => $id,
		);
	}

	/**
	 * @Route("/admin/users_tasks/{id}/accept", name="admin_users_tasks_accept")
	 */
	public function acceptAction($id)
	{
		$user_task = UserTaskQuery::create()->findPk($id);
		$user_task->setStatus(3);
		$user_task->save();
		return $this->redirect($this->generateUrl('admin_users_tasks_index'));
	}

}

