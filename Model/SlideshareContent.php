<?php

namespace Smirik\CourseBundle\Model;

use Smirik\CourseBundle\Model\om\BaseSlideshareContent;

class SlideshareContent extends BaseSlideshareContent
{
	
	public function getHtml()
	{
		$url  = $this->getUrl();
		$tmp  = explode('/', $url);
		$tmp2 = explode('-', $tmp[count($tmp)-1]);
		$code = $tmp2[count($tmp2)-1];
		$html = '<iframe src="http://www.slideshare.net/slideshow/embed_code/'.$code.'" width="597" height="486" frameborder="0" marginwidth="0" marginheight="0" scrolling="no" style="border:1px solid #CCC;border-width:1px 1px 0;margin-bottom:5px" allowfullscreen> </iframe> <div style="margin-bottom:5px"> <strong> <a href="'.$url.'" title="'.$this->getTitle().'" target="_blank">'.$this->getTitle().'</a> </strong> from <strong><a href="http://www.slideshare.net/smirik" target="_blank">Evgeny Smirnov</a></strong></div>';
		return $html;
	}
	
}
