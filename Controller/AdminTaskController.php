<?php

namespace Smirik\CourseBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use Smirik\PropelAdminBundle\Controller\AdminAbstractController as AbstractController;

class AdminTaskController extends AbstractController
{
	
	public $layout = 'SmirikAdminBundle::layout.html.twig';
	public $name   = 'tasks';
	public $bundle = 'SmirikCourseBundle';

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

