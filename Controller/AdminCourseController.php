<?php

namespace Smirik\CourseBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use Smirik\PropelAdminBundle\Controller\AdminAbstractController as AbstractController;

class AdminCourseController extends AbstractController
{
	
	public $layout = 'SmirikAdminBundle::layout.html.twig';
	public $name   = 'courses';
	public $bundle = 'SmirikCourseBundle';

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

