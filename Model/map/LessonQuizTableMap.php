<?php

namespace Smirik\CourseBundle\Model\map;

use \RelationMap;
use \TableMap;


/**
 * This class defines the structure of the 'lessons_quizes' table.
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
class LessonQuizTableMap extends TableMap
{

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'src.Smirik.CourseBundle.Model.map.LessonQuizTableMap';

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
        $this->setName('lessons_quizes');
        $this->setPhpName('LessonQuiz');
        $this->setClassname('Smirik\\CourseBundle\\Model\\LessonQuiz');
        $this->setPackage('src.Smirik.CourseBundle.Model');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('ID', 'Id', 'INTEGER', true, null, null);
        $this->addForeignKey('LESSON_ID', 'LessonId', 'INTEGER', 'lessons', 'ID', true, null, null);
        $this->addForeignKey('QUIZ_ID', 'QuizId', 'INTEGER', 'quiz', 'ID', true, null, null);
        $this->addColumn('SORTABLE_RANK', 'SortableRank', 'INTEGER', false, null, null);
        // validators
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('Lesson', 'Smirik\\CourseBundle\\Model\\Lesson', RelationMap::MANY_TO_ONE, array('lesson_id' => 'id', ), 'CASCADE', null);
        $this->addRelation('Quiz', 'Smirik\\QuizBundle\\Model\\Quiz', RelationMap::MANY_TO_ONE, array('quiz_id' => 'id', ), 'CASCADE', null);
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
            'sortable' => array('rank_column' => 'sortable_rank', 'use_scope' => 'true', 'scope_column' => 'lesson_id', ),
        );
    } // getBehaviors()

} // LessonQuizTableMap
