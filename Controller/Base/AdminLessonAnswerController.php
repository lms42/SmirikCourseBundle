<?php

namespace Smirik\CourseBundle\Controller\Base;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use Smirik\PropelAdminBundle\Controller\AdminAbstractController as AbstractController;

class AdminLessonAnswerController extends AbstractController
{
	
	public $layout = 'SmirikAdminBundle::layout.html.twig';
	public $name   = 'lessons_answers';
	public $bundle = 'SmirikCourseBundle';

	public function getQuery()
	{
		return \Smirik\CourseBundle\Model\LessonAnswerQuery::create();
	}
	
	public function getForm()
	{
		return new \Smirik\CourseBundle\Form\Type\LessonAnswerType;
	}
	
	public function getObject()
	{
		return new \Smirik\CourseBundle\Model\LessonAnswer;
	}

}

