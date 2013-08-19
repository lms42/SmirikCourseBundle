<?php
    
namespace Smirik\CourseBundle\Import;

class CourseImporter
{
    
    public function import($content, $debug = false, $output = false)
    {
        $data = explode("\n", $content);
        $num = count($data);
        
        // $con = \Propel::getConnection(\Smirik\CourseBundle\Model\CoursePeer::DATABASE_NAME);
        // $con->beginTransaction();
        
        for ($i=0; $i<$num; $i++) {
            $arr = str_getcsv($data[$i], ';');
            if ($i == 0) {
                if (!$debug) {
                    $transformer = new Transformer\CourseTransformer();
                    $course = $transformer->transform($arr);
                    $course->save();
                } else {
                    $output->writeln("Course {$arr[0]} is created.");
                }
            }
        }
        return;
    }
    
    
}