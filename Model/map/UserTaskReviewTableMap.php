<?php

namespace Smirik\CourseBundle\Model\map;

use \RelationMap;
use \TableMap;


/**
 * This class defines the structure of the 'users_tasks_reviews' table.
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
class UserTaskReviewTableMap extends TableMap
{

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'src.Smirik.CourseBundle.Model.map.UserTaskReviewTableMap';

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
        $this->setName('users_tasks_reviews');
        $this->setPhpName('UserTaskReview');
        $this->setClassname('Smirik\\CourseBundle\\Model\\UserTaskReview');
        $this->setPackage('src.Smirik.CourseBundle.Model');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('ID', 'Id', 'INTEGER', true, null, null);
        $this->addForeignKey('USER_TASK_ID', 'UserTaskId', 'INTEGER', 'users_tasks', 'ID', true, null, null);
        $this->addForeignKey('USER_ID', 'UserId', 'INTEGER', 'fos_user', 'ID', true, null, null);
        $this->addColumn('TEXT', 'Text', 'LONGVARCHAR', false, null, null);
        $this->addColumn('CREATED_AT', 'CreatedAt', 'TIMESTAMP', false, null, null);
        $this->addColumn('UPDATED_AT', 'UpdatedAt', 'TIMESTAMP', false, null, null);
        // validators
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('UserTask', 'Smirik\\CourseBundle\\Model\\UserTask', RelationMap::MANY_TO_ONE, array('user_task_id' => 'id', ), 'CASCADE', null);
        $this->addRelation('User', 'FOS\\UserBundle\\Propel\\User', RelationMap::MANY_TO_ONE, array('user_id' => 'id', ), 'CASCADE', null);
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

} // UserTaskReviewTableMap
