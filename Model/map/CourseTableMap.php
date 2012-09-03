<?php

namespace Smirik\CourseBundle\Model\map;

use \RelationMap;
use \TableMap;


/**
 * This class defines the structure of the 'courses' table.
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
class CourseTableMap extends TableMap
{

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'src.Smirik.CourseBundle.Model.map.CourseTableMap';

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
        $this->setName('courses');
        $this->setPhpName('Course');
        $this->setClassname('Smirik\\CourseBundle\\Model\\Course');
        $this->setPackage('src.Smirik.CourseBundle.Model');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('ID', 'Id', 'INTEGER', true, null, null);
        $this->addForeignKey('PID', 'Pid', 'INTEGER', 'courses', 'ID', false, null, null);
        $this->addColumn('TITLE', 'Title', 'VARCHAR', true, 200, null);
        $this->addColumn('DESCRIPTION', 'Description', 'LONGVARCHAR', true, null, null);
        $this->addColumn('TYPE', 'Type', 'INTEGER', false, null, 1);
        $this->addColumn('FILE', 'File', 'VARCHAR', false, 100, null);
        $this->addColumn('IS_PUBLIC', 'IsPublic', 'BOOLEAN', false, 1, null);
        $this->addColumn('IS_ACTIVE', 'IsActive', 'BOOLEAN', false, 1, null);
        $this->addColumn('CREATED_AT', 'CreatedAt', 'TIMESTAMP', false, null, null);
        $this->addColumn('UPDATED_AT', 'UpdatedAt', 'TIMESTAMP', false, null, null);
        // validators
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('CourseRelatedByPid', 'Smirik\\CourseBundle\\Model\\Course', RelationMap::MANY_TO_ONE, array('pid' => 'id', ), null, null);
        $this->addRelation('CourseRelatedById', 'Smirik\\CourseBundle\\Model\\Course', RelationMap::ONE_TO_MANY, array('id' => 'pid', ), null, null, 'CoursesRelatedById');
        $this->addRelation('Lesson', 'Smirik\\CourseBundle\\Model\\Lesson', RelationMap::ONE_TO_MANY, array('id' => 'course_id', ), 'CASCADE', null, 'Lessons');
        $this->addRelation('UserCourse', 'Smirik\\CourseBundle\\Model\\UserCourse', RelationMap::ONE_TO_MANY, array('id' => 'course_id', ), 'CASCADE', null, 'UserCourses');
        $this->addRelation('UserLesson', 'Smirik\\CourseBundle\\Model\\UserLesson', RelationMap::ONE_TO_MANY, array('id' => 'course_id', ), 'CASCADE', null, 'UserLessons');
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

} // CourseTableMap
