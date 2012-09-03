<?php

namespace Smirik\CourseBundle\Model\map;

use \RelationMap;
use \TableMap;


/**
 * This class defines the structure of the 'lessons_content_texts' table.
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
class TextContentTableMap extends TableMap
{

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'src.Smirik.CourseBundle.Model.map.TextContentTableMap';

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
        $this->setName('lessons_content_texts');
        $this->setPhpName('TextContent');
        $this->setClassname('Smirik\\CourseBundle\\Model\\TextContent');
        $this->setPackage('src.Smirik.CourseBundle.Model');
        $this->setUseIdGenerator(false);
        // columns
        $this->addColumn('TEXT', 'Text', 'LONGVARCHAR', false, null, null);
        $this->addForeignPrimaryKey('ID', 'Id', 'INTEGER' , 'lessons_content', 'ID', true, null, null);
        $this->addForeignKey('LESSON_ID', 'LessonId', 'INTEGER', 'lessons', 'ID', false, null, null);
        $this->addColumn('TITLE', 'Title', 'VARCHAR', true, 200, null);
        $this->addColumn('DESCRIPTION', 'Description', 'LONGVARCHAR', false, null, null);
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
        $this->addRelation('Content', 'Smirik\\CourseBundle\\Model\\Content', RelationMap::MANY_TO_ONE, array('id' => 'id', ), 'CASCADE', null);
        $this->addRelation('Lesson', 'Smirik\\CourseBundle\\Model\\Lesson', RelationMap::MANY_TO_ONE, array('lesson_id' => 'id', ), 'CASCADE', null);
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
            'concrete_inheritance' => array('extends' => 'lessons_content', 'descendant_column' => 'descendant_class', 'copy_data_to_parent' => 'true', 'schema' => '', ),
            'timestampable' => array('create_column' => 'created_at', 'update_column' => 'updated_at', 'disable_updated_at' => 'false', ),
            'sortable' => array('rank_column' => 'sortable_rank', 'use_scope' => 'true', 'scope_column' => 'lesson_id', ),
        );
    } // getBehaviors()

} // TextContentTableMap
