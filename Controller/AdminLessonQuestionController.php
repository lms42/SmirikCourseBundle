<?php

namespace Smirik\CourseBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use Smirik\PropelAdminBundle\Controller\AdminAbstractController as AbstractController;

class AdminLessonQuestionController extends AbstractController
{
	
	public $layout = 'SmirikAdminBundle::layout.html.twig';
	public $name   = 'lessons_questions';
	public $bundle = 'SmirikCourseBundle';

	public function getQuery()
	{
		return \Smirik\CourseBundle\Model\LessonQuestionQuery::create();
	}
	
	public function getForm()
	{
		return new \Smirik\CourseBundle\Form\Type\LessonQuestionType;
	}
	
	public function getObject()
	{
		return new \Smirik\CourseBundle\Model\LessonQuestion;
	}

}

