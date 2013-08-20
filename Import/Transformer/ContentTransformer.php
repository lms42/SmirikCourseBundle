<?php

namespace Smirik\CourseBundle\Import\Transformer;

class ContentTransformer
{
    
    public function transform($row, $lesson)
    {
        try {
            // определяем либо текст либо ссылка
            if (strpos($row[3], 'http://') === 0 || strpos($row[3], 'https://') === 0) {
                $content = \Smirik\CourseBundle\Model\UrlContentQuery::create()
                    ->filterByLessonId($lesson->getId())
                    ->filterByUrl($row[3])
                    ->findOneOrCreate()
                ;
                
                $content->setUrl($row[3]);
            } else {
                $content = \Smirik\CourseBundle\Model\TextContentQuery::create()
                    ->filterByLessonId($lesson->getId())
                    ->filterByTitle($row[2])
                    ->findOneOrCreate();
                
                $content->setDescription($row[3]);
            }

            $content->setLessonId($lesson->getId());
            $content->setTitle($row[2]);
            $content->save();
        } catch (Exception $e) {
            throw new \CourseImportException('Content data are not valid');
        }
        return $content;
    }
    
    
}