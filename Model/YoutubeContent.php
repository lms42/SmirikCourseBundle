<?php

namespace Smirik\CourseBundle\Model;

use Smirik\CourseBundle\Model\om\BaseYoutubeContent;

class YoutubeContent extends BaseYoutubeContent
{
    
    public function getEmbedCode()
    {
        $tmp = parse_url($this->getUrl());
        $query = $tmp['query'];
        
        foreach (explode('&', $query) as $chunk) {
            $param = explode("=", $chunk);

            if ($param && (urldecode($param[0]) == 'v')) {
                return urldecode($param[1]);
            }
        }
        return false;
    }
    
}
