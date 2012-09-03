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
use Smirik\PropelAdminBundle\Action\SingleAction;

class AdminUserTaskReviewController extends AbstractController
{
	
	public $layout = 'SmirikAdminBundle::layout.html.twig';
	public $name   = 'users_tasks_reviews';

	public function setup()
	{
		$this->configure(array(
										 array('name' => 'id', 'label' => 'Id', 'type' => 'integer', 'options' => array(
											 'editable' => false,
											 'listable' => true,
											 'sortable' => true,
											 'filterable' => true)),
		                 array('name' => 'user_task', 'label' => 'User task', 'type' => 'string', 'options' => array(
											 'editable' => true,
											 'listable' => true,
											 'sortable' => true,
											 'filterable' => true)),
										 array('name' => 'text', 'label' => 'Text', 'type' => 'text', 'options' => array(
											 'editable' => true,
											 'listable' => true,
											 'sortable' => true,
											 'filterable' => true))
		                 ),
		                 array('new' => new SingleAction('New', 'new', 'admin_users_tasks_reviews_new', true),
											'edit' => new ObjectAction('Edit', 'edit', 'admin_users_tasks_reviews_edit', true),
											'delete' => new ObjectAction('Delete', 'delete', 'admin_users_tasks_reviews_delete', true, true))
		                );
	}

	public function getQuery()
	{
		return \Smirik\CourseBundle\Model\UserTaskReviewQuery::create();
	}
	
	public function getForm()
	{
		return new \Smirik\CourseBundle\Form\Type\UserTaskReviewType;
	}
	
	public function getObject()
	{
		return new \Smirik\CourseBundle\Model\UserTaskReview;
	}

}

