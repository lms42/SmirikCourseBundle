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

class AdminCourseController extends AbstractController
{
	
	public $layout = 'SmirikAdminBundle::layout.html.twig';
	public $name   = 'courses';

	public function setup()
	{
		$this->configure(array(
		                 array('name' => 'id', 'label' => 'Id', 'type' => 'integer', 'options' => array(
											 'editable' => false,
											 'listable' => true,
											 'sortable' => true,
											 'filterable' => true)),
										 array('name' => 'title', 'label' => 'Title', 'type' => 'string', 'options' => array(
											 'editable' => true,
											 'listable' => true,
											 'sortable' => true,
											 'filterable' => true)),
										 array('name' => 'description', 'label' => 'Description', 'type' => 'text', 'options' => array(
											 'editable' => true,
											 'listable' => false,
											 'sortable' => true,
											 'filterable' => true)),
										 array('name' => 'type', 'label' => 'Type', 'type' => 'boolean', 'options' => array(
											 'editable' => true,
											 'listable' => true,
											 'sortable' => true,
											 'filterable' => true)),
										 array('name' => 'file', 'label' => 'File', 'type' => 'string', 'options' => array(
											 'editable' => true,
											 'listable' => false,
											 'sortable' => true,
											 'filterable' => true)),
										 array('name' => 'is_public', 'label' => 'Public', 'type' => 'boolean', 'options' => array(
											 'editable' => true,
											 'listable' => true,
											 'sortable' => true,
											 'filterable' => true)),
										 array('name' => 'is_active', 'label' => 'Enabled', 'type' => 'boolean', 'options' => array(
											 'editable' => true,
											 'listable' => true,
											 'sortable' => true,
											 'filterable' => true))
		                 ),
		                 array('new' => new SingleAction('New', 'new', 'admin_courses_new', true),
											'edit' => new ObjectAction('Edit', 'edit', 'admin_courses_edit', true),
											'delete' => new ObjectAction('Delete', 'delete', 'admin_courses_delete', true, true))
		                );
	}

	public function getQuery()
	{
		return \Smirik\CourseBundle\Model\CourseQuery::create();
	}
	
	public function getForm()
	{
		return new \Smirik\CourseBundle\Form\Type\CourseType;
	}
	
	public function getObject()
	{
		return new \Smirik\CourseBundle\Model\Course;
	}

}

