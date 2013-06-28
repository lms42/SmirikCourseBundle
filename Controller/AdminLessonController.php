<?php

namespace Smirik\CourseBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Response;

use Smirik\CourseBundle\Controller\Base\AdminLessonController as BaseController;

use Smirik\CourseBundle\Model\LessonQuizQuery;

class AdminLessonController extends BaseController
{
    /**
     * @Route("/admin/lessons/{id}/assign", name="admin_lessons_assign")
     * @Template("SmirikCourseBundle:Admin/Lesson:assign.html.twig")
     */
    public function assignAction($id)
    {
        $lesson = $this->getQuery()->findPk($id);
        $lm     = $this->get('lesson.manager');

        if ($this->getRequest()->isXmlHttpRequest()) {
            $ids = $this->getRequest()->request->get('ids', false);
            if ($ids && count($ids) > 0) {
                foreach ($ids as $uid) {
                    $lm->findOrCreateLessonQuiz($uid, $lesson);
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

