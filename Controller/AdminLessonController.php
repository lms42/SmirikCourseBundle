<?php

namespace Smirik\CourseBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Response;

use Smirik\PropelAdminBundle\Controller\AdminAbstractController as AbstractController;

use Smirik\PropelAdminBundle\Column\Column;
use Smirik\PropelAdminBundle\Column\CollectionColumn;
use Smirik\PropelAdminBundle\Action\Action;
use Smirik\PropelAdminBundle\Action\ObjectAction;
use Smirik\PropelAdminBundle\Action\SingleAction;
use Smirik\PropelAdminBundle\Action\MassAction;

use Smirik\CourseBundle\Model\LessonQuizQuery;
use Smirik\CourseBundle\Model\LessonQuiz;

class AdminLessonController extends AbstractController
{
	
	public $layout = 'SmirikAdminBundle::layout.html.twig';
	public $name   = 'lessons';

	public function setup()
	{
		$this->configure(array(
		                 array('name' => 'id', 'label' => 'Id', 'type' => 'integer', 'options' => array(
											 'editable' => false,
											 'listable' => true,
											 'sortable' => true,
											 'filterable' => true)),
										 array('name' => 'course', 'label' => 'Course', 'type' => 'string', 'options' => array(
											 'editable' => true,
											 'listable' => true,
											 'sortable' => true,
											 'filterable' => true)),
										 array('name' => 'title', 'label' => 'Title', 'type' => 'string', 'options' => array(
											 'editable' => true,
											 'listable' => true,
											 'sortable' => true,
											 'filterable' => true))
		                 ),
		                 array(
											'new'    => new SingleAction('New', 'new', 'admin_lessons_new', true),
											'assign' => new ObjectAction('Assign', 'assign', 'admin_lessons_assign', true),
											'edit'   => new ObjectAction('Edit', 'edit', 'admin_lessons_edit', true),
											'delete' => new ObjectAction('Delete', 'delete', 'admin_lessons_delete', true, true))
		                );
	}

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
	
	/**
	 * @Template("SmirikCourseBundle:Admin/Lesson:edit.html.twig")
	 */
	public function editAction($id)
	{
		$this->setup();
		$this->generateRoutes();
		$this->object = $this->getQuery()->findPk($id);
		if (!$this->object)
		{
			throw $this->createNotFoundException('Not found');
		}
		
		$request = $this->getRequest();
		
		$form = $this->createForm($this->getForm(), $this->object);

		if ('POST' == $request->getMethod())
		{
			$form->bindRequest($request);
			if ($form->isValid())
			{
				$this->object->save();
				return $this->redirect($this->generateUrl($this->routes['index']));
			}
		}

		return array(
			'layout' => $this->layout,
			'object' => $this->object,
			'form'   => $form->createView(),
			'columns' => $this->columns,
			'routes' => $this->routes,
		);
	}
	
	/**
	 * @Route("/admin/lessons/{id}/assign", name="admin_lessons_assign")
	 * @Template("SmirikCourseBundle:Admin/Lesson:assign.html.twig")
	 */
	public function assignAction($id)
	{
		$lesson = $this->getQuery()->findPk($id);
		$cm     = $this->get('course.manager');
		
		if ($this->getRequest()->isXmlHttpRequest())
		{
			$ids = $this->getRequest()->request->get('ids', false); 
			if ($ids && count($ids) > 0)
			{
				foreach ($ids as $uid)
				{
					$cm->findOrCreateLessonQuiz($uid, $lesson);
				}
			}
			$tmp = LessonQuizQuery::create()
				->filterByLessonId($id)
				->filterByQuizId($ids, \Criteria::NOT_IN)
				->delete();
			return new Response('{}');
		}
		
		$lq_ids = LessonQuizQuery::create()
			->select('QuizId')
			->filterByLessonId($id)
			->find()
			->toArray();
		return array(
			'lesson' => $lesson,
			'route'  => $this->generateUrl('admin_lessons_assign', array('id' => $id)),
			'lq_ids' => json_encode($lq_ids),
		);
	}
	

}

