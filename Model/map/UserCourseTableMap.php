<?php

namespace Smirik\CourseBundle\Model\map;

use \RelationMap;
use \TableMap;


/**
 * This class defines the structure of the 'users_courses' table.
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
class UserCourseTableMap extends TableMap
{

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'src.Smirik.CourseBundle.Model.map.UserCourseTableMap';

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
        $this->setName('users_courses');
        $this->setPhpName('UserCourse');
        $this->setClassname('Smirik\\CourseBundle\\Model\\UserCourse');
        $this->setPackage('src.Smirik.CourseBundle.Model');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('ID', 'Id', 'INTEGER', true, null, null);
        $this->addForeignKey('USER_ID', 'UserId', 'INTEGER', 'fos_user', 'ID', true, null, null);
        $this->addForeignKey('COURSE_ID', 'CourseId', 'INTEGER', 'courses', 'ID', true, null, null);
        $this->addColumn('STARTED_AT', 'StartedAt', 'TIMESTAMP', false, null, null);
        $this->addColumn('STOPPED_AT', 'StoppedAt', 'TIMESTAMP', false, null, null);
        $this->addColumn('IS_PASSED', 'IsPassed', 'BOOLEAN', false, 1, false);
        $this->addColumn('UPDATED_AT', 'UpdatedAt', 'TIMESTAMP', false, null, null);
        // validators
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('User', 'FOS\\UserBundle\\Propel\\User', RelationMap::MANY_TO_ONE, array('user_id' => 'id', ), 'CASCADE', null);
        $this->addRelation('Course', 'Smirik\\CourseBundle\\Model\\Course', RelationMap::MANY_TO_ONE, array('course_id' => 'id', ), 'CASCADE', null);
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
            'timestampable' => array('create_column' => 'started_at', 'update_column' => 'updated_at', 'disable_updated_at' => 'false', ),
        );
    } // getBehaviors()

} // UserCourseTableMap
