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

class AdminTaskController extends AbstractController
{
	
	public $layout = 'SmirikAdminBundle::layout.html.twig';
	public $name   = 'tasks';

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
										 array('name' => 'title', 'label' => 'Title', 'type' => 'string', 'options' => array(
											 'editable' => true,
											 'listable' => true,
											 'sortable' => true,
											 'filterable' => true)),
										 array('name' => 'text', 'label' => 'Text', 'type' => 'text', 'options' => array(
											 'editable' => true,
											 'listable' => false,
											 'sortable' => true,
											 'filterable' => true)),
										 array('name' => 'file', 'label' => 'File', 'type' => 'string', 'options' => array(
											 'editable' => true,
											 'listable' => false,
											 'sortable' => true,
											 'filterable' => true))
		                 ),
		                 array('new' => new SingleAction('New', 'new', 'admin_tasks_new', true),
											'edit' => new ObjectAction('Edit', 'edit', 'admin_tasks_edit', true),
											'delete' => new ObjectAction('Delete', 'delete', 'admin_tasks_delete', true, true))
		                );
	}

	public function getQuery()
	{
		return \Smirik\CourseBundle\Model\TaskQuery::create();
	}
	
	public function getForm()
	{
		return new \Smirik\CourseBundle\Form\Type\TaskType;
	}
	
	public function getObject()
	{
		return new \Smirik\CourseBundle\Model\Task;
	}

}

