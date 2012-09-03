<?php

namespace Smirik\CourseBundle\Model\map;

use \RelationMap;
use \TableMap;


/**
 * This class defines the structure of the 'lessons_content_files' table.
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
class ContentFileTableMap extends TableMap
{

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'src.Smirik.CourseBundle.Model.map.ContentFileTableMap';

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
        $this->setName('lessons_content_files');
        $this->setPhpName('ContentFile');
        $this->setClassname('Smirik\\CourseBundle\\Model\\ContentFile');
        $this->setPackage('src.Smirik.CourseBundle.Model');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('ID', 'Id', 'INTEGER', true, null, null);
        $this->addForeignKey('LESSON_CONTENT_ID', 'LessonContentId', 'INTEGER', 'lessons_content', 'ID', false, null, null);
        $this->addColumn('FILE', 'File', 'VARCHAR', true, 255, null);
        $this->addColumn('TYPE', 'Type', 'VARCHAR', true, 50, null);
        $this->addColumn('TITLE', 'Title', 'VARCHAR', false, 200, null);
        $this->addColumn('CAPTION', 'Caption', 'LONGVARCHAR', false, null, null);
        $this->addColumn('CREATED_AT', 'CreatedAt', 'TIMESTAMP', false, null, null);
        $this->addColumn('UPDATED_AT', 'UpdatedAt', 'TIMESTAMP', false, null, null);
        // validators
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('Content', 'Smirik\\CourseBundle\\Model\\Content', RelationMap::MANY_TO_ONE, array('lesson_content_id' => 'id', ), 'CASCADE', null);
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
        );
    } // getBehaviors()

} // ContentFileTableMap
