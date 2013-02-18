<?php

namespace Smirik\CourseBundle\Controller\Base;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use Smirik\PropelAdminBundle\Controller\AdminAbstractController as AbstractController;

class AdminLessonController extends AbstractController
{
	
	public $layout = 'SmirikAdminBundle::layout.html.twig';
	public $name   = 'lessons';
	public $bundle = 'SmirikCourseBundle';

	public function getQuery()
	{
		return \Smirik\CourseBundle\Model\LessonQuery::create();
	}
	
	public function getForm()
	{
		return new \Smirik\CourseBundle\Form\Type\LessonType;
	}
	
	public function getObject()
	{
		return new \Smirik\CourseBundle\Model\Lesson;
	}

}

