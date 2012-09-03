<?php

namespace Smirik\CourseBundle\Model\map;

use \RelationMap;
use \TableMap;


/**
 * This class defines the structure of the 'lessons' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 *
 * @package    propel.generator.src.Smirik.CourseBundle.Model.map
 */
class LessonTableMap extends TableMap
{

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'src.Smirik.CourseBundle.Model.map.LessonTableMap';

    /**
     * Initialize the table attributes, columns and validators
     * Relations are not initialized by this method since they are lazy loaded
     *
     * @return void
     * @throws PropelException
     */
    public function initialize()
    {
        // attributes
        $this->setName('lessons');
        $this->setPhpName('Lesson');
        $this->setClassname('Smirik\\CourseBundle\\Model\\Lesson');
        $this->setPackage('src.Smirik.CourseBundle.Model');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('ID', 'Id', 'INTEGER', true, null, null);
        $this->addForeignKey('COURSE_ID', 'CourseId', 'INTEGER', 'courses', 'ID', false, null, null);
        $this->addColumn('TITLE', 'Title', 'VARCHAR', true, 255, null);
        $this->addColumn('CREATED_AT', 'CreatedAt', 'TIMESTAMP', false, null, null);
        $this->addColumn('UPDATED_AT', 'UpdatedAt', 'TIMESTAMP', false, null, null);
        $this->addColumn('SORTABLE_RANK', 'SortableRank', 'INTEGER', false, null, null);
        // validators
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('Course', 'Smirik\\CourseBundle\\Model\\Course', RelationMap::MANY_TO_ONE, array('course_id' => 'id', ), 'CASCADE', null);
        $this->addRelation('UserLesson', 'Smirik\\CourseBundle\\Model\\UserLesson', RelationMap::ONE_TO_MANY, array('id' => 'lesson_id', ), 'CASCADE', null, 'UserLessons');
        $this->addRelation('LessonQuiz', 'Smirik\\CourseBundle\\Model\\LessonQuiz', RelationMap::ONE_TO_MANY, array('id' => 'lesson_id', ), 'CASCADE', null, 'Lessonquizzes');
        $this->addRelation('LessonQuestion', 'Smirik\\CourseBundle\\Model\\LessonQuestion', RelationMap::ONE_TO_MANY, array('id' => 'lesson_id', ), 'CASCADE', null, 'LessonQuestions');
        $this->addRelation('LessonAnswer', 'Smirik\\CourseBundle\\Model\\LessonAnswer', RelationMap::ONE_TO_MANY, array('id' => 'lesson_id', ), 'CASCADE', null, 'LessonAnswers');
        $this->addRelation('Content', 'Smirik\\CourseBundle\\Model\\Content', RelationMap::ONE_TO_MANY, array('id' => 'lesson_id', ), 'CASCADE', null, 'Contents');
        $this->addRelation('Task', 'Smirik\\CourseBundle\\Model\\Task', RelationMap::ONE_TO_MANY, array('id' => 'lesson_id', ), 'CASCADE', null, 'Tasks');
        $this->addRelation('UserTask', 'Smirik\\CourseBundle\\Model\\UserTask', RelationMap::ONE_TO_MANY, array('id' => 'lesson_id', ), 'CASCADE', null, 'UserTasks');
        $this->addRelation('TextContent', 'Smirik\\CourseBundle\\Model\\TextContent', RelationMap::ONE_TO_MANY, array('id' => 'lesson_id', ), 'CASCADE', null, 'TextContents');
        $this->addRelation('UrlContent', 'Smirik\\CourseBundle\\Model\\UrlContent', RelationMap::ONE_TO_MANY, array('id' => 'lesson_id', ), 'CASCADE', null, 'UrlContents');
        $this->addRelation('YoutubeContent', 'Smirik\\CourseBundle\\Model\\YoutubeContent', RelationMap::ONE_TO_MANY, array('id' => 'lesson_id', ), 'CASCADE', null, 'YoutubeContents');
        $this->addRelation('SlideshareContent', 'Smirik\\CourseBundle\\Model\\SlideshareContent', RelationMap::ONE_TO_MANY, array('id' => 'lesson_id', ), 'CASCADE', null, 'SlideshareContents');
    } // buildRelations()

    /**
     *
     * Gets the list of behaviors registered for this table
     *
     * @return array Associative array (name => parameters) of behaviors
     */
    public function getBehaviors()
    {
        return array(
            'timestampable' => array('create_column' => 'created_at', 'update_column' => 'updated_at', 'disable_updated_at' => 'false', ),
            'sortable' => array('rank_column' => 'sortable_rank', 'use_scope' => 'true', 'scope_column' => 'course_id', ),
        );
    } // getBehaviors()

} // LessonTableMap
