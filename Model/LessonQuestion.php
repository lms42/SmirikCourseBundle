<?php

namespace Smirik\CourseBundle\Model;

use Smirik\CourseBundle\Model\om\BaseLessonQuestion;

class LessonQuestion extends BaseLessonQuestion
{
	
	public function getVisibleAnswers()
	{
		$answers = LessonAnswerQuery::create()
			->filterByQuestionId($this->getId())
			->filterByIsVisible(true)
			->find();
		return $answers;
	}
	
	public function hasVisibleAnswers()
	{
		$answers = LessonAnswerQuery::create()
			->filterByQuestionId($this->getId())
			->filterByIsVisible(true)
			->findOne();
		if ($answers && is_object($answers))
		{
			return true;
		}
		return false;
	}
	
	public function addAnswer($user_id, $text)
	{
		$answer = new LessonAnswer();
		$answer->setUserId($user_id);
		$answer->setText($text);
		$answer->setQuestionId($this->getId());
		$answer->setLessonId($this->getLessonId());
		$answer->setIsAccepted(false);
		$answer->setIsVisible(false);
		$answer->save();
		return $answer;
	}
	
	public function __toString()
	{
		return $this->getTitle();
	}
	
}
