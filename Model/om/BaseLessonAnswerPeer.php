<?php

namespace Smirik\CourseBundle\Model\om;

use \BasePeer;
use \Criteria;
use \PDO;
use \PDOStatement;
use \Propel;
use \PropelException;
use \PropelPDO;
use FOS\UserBundle\Propel\UserPeer;
use Smirik\CourseBundle\Model\LessonAnswer;
use Smirik\CourseBundle\Model\LessonAnswerPeer;
use Smirik\CourseBundle\Model\LessonPeer;
use Smirik\CourseBundle\Model\LessonQuestionPeer;
use Smirik\CourseBundle\Model\map\LessonAnswerTableMap;

abstract class BaseLessonAnswerPeer
{

    /** the default database name for this class */
    const DATABASE_NAME = 'default';

    /** the table name for this class */
    const TABLE_NAME = 'lessons_answers';

    /** the related Propel class for this table */
    const OM_CLASS = 'Smirik\\CourseBundle\\Model\\LessonAnswer';

    /** the related TableMap class for this table */
    const TM_CLASS = 'LessonAnswerTableMap';

    /** The total number of columns. */
    const NUM_COLUMNS = 10;

    /** The number of lazy-loaded columns. */
    const NUM_LAZY_LOAD_COLUMNS = 0;

    /** The number of columns to hydrate (NUM_COLUMNS - NUM_LAZY_LOAD_COLUMNS) */
    const NUM_HYDRATE_COLUMNS = 10;

    /** the column name for the ID field */
    const ID = 'lessons_answers.ID';

    /** the column name for the USER_ID field */
    const USER_ID = 'lessons_answers.USER_ID';

    /** the column name for the LESSON_ID field */
    const LESSON_ID = 'lessons_answers.LESSON_ID';

    /** the column name for the QUESTION_ID field */
    const QUESTION_ID = 'lessons_answers.QUESTION_ID';

    /** the column name for the TEXT field */
    const TEXT = 'lessons_answers.TEXT';

    /** the column name for the IS_VISIBLE field */
    const IS_VISIBLE = 'lessons_answers.IS_VISIBLE';

    /** the column name for the IS_ACCEPTED field */
    const IS_ACCEPTED = 'lessons_answers.IS_ACCEPTED';

    /** the column name for the ACCEPTED_BY field */
    const ACCEPTED_BY = 'lessons_answers.ACCEPTED_BY';

    /** the column name for the CREATED_AT field */
    const CREATED_AT = 'lessons_answers.CREATED_AT';

    /** the column name for the UPDATED_AT field */
    const UPDATED_AT = 'lessons_answers.UPDATED_AT';

    /** The default string format for model objects of the related table **/
    const DEFAULT_STRING_FORMAT = 'YAML';

    /**
     * An identiy map to hold any loaded instances of LessonAnswer objects.
     * This must be public so that other peer classes can access this when hydrating from JOIN
     * queries.
     * @var        array LessonAnswer[]
     */
    public static $instances = array();


    /**
     * holds an array of fieldnames
     *
     * first dimension keys are the type constants
     * e.g. LessonAnswerPeer::$fieldNames[LessonAnswerPeer::TYPE_PHPNAME][0] = 'Id'
     */
    protected static $fieldNames = array (
        BasePeer::TYPE_PHPNAME => array ('Id', 'UserId', 'LessonId', 'QuestionId', 'Text', 'IsVisible', 'IsAccepted', 'AcceptedBy', 'CreatedAt', 'UpdatedAt', ),
        BasePeer::TYPE_STUDLYPHPNAME => array ('id', 'userId', 'lessonId', 'questionId', 'text', 'isVisible', 'isAccepted', 'acceptedBy', 'createdAt', 'updatedAt', ),
        BasePeer::TYPE_COLNAME => array (LessonAnswerPeer::ID, LessonAnswerPeer::USER_ID, LessonAnswerPeer::LESSON_ID, LessonAnswerPeer::QUESTION_ID, LessonAnswerPeer::TEXT, LessonAnswerPeer::IS_VISIBLE, LessonAnswerPeer::IS_ACCEPTED, LessonAnswerPeer::ACCEPTED_BY, LessonAnswerPeer::CREATED_AT, LessonAnswerPeer::UPDATED_AT, ),
        BasePeer::TYPE_RAW_COLNAME => array ('ID', 'USER_ID', 'LESSON_ID', 'QUESTION_ID', 'TEXT', 'IS_VISIBLE', 'IS_ACCEPTED', 'ACCEPTED_BY', 'CREATED_AT', 'UPDATED_AT', ),
        BasePeer::TYPE_FIELDNAME => array ('id', 'user_id', 'lesson_id', 'question_id', 'text', 'is_visible', 'is_accepted', 'accepted_by', 'created_at', 'updated_at', ),
        BasePeer::TYPE_NUM => array (0, 1, 2, 3, 4, 5, 6, 7, 8, 9, )
    );

    /**
     * holds an array of keys for quick access to the fieldnames array
     *
     * first dimension keys are the type constants
     * e.g. LessonAnswerPeer::$fieldNames[BasePeer::TYPE_PHPNAME]['Id'] = 0
     */
    protected static $fieldKeys = array (
        BasePeer::TYPE_PHPNAME => array ('Id' => 0, 'UserId' => 1, 'LessonId' => 2, 'QuestionId' => 3, 'Text' => 4, 'IsVisible' => 5, 'IsAccepted' => 6, 'AcceptedBy' => 7, 'CreatedAt' => 8, 'UpdatedAt' => 9, ),
        BasePeer::TYPE_STUDLYPHPNAME => array ('id' => 0, 'userId' => 1, 'lessonId' => 2, 'questionId' => 3, 'text' => 4, 'isVisible' => 5, 'isAccepted' => 6, 'acceptedBy' => 7, 'createdAt' => 8, 'updatedAt' => 9, ),
        BasePeer::TYPE_COLNAME => array (LessonAnswerPeer::ID => 0, LessonAnswerPeer::USER_ID => 1, LessonAnswerPeer::LESSON_ID => 2, LessonAnswerPeer::QUESTION_ID => 3, LessonAnswerPeer::TEXT => 4, LessonAnswerPeer::IS_VISIBLE => 5, LessonAnswerPeer::IS_ACCEPTED => 6, LessonAnswerPeer::ACCEPTED_BY => 7, LessonAnswerPeer::CREATED_AT => 8, LessonAnswerPeer::UPDATED_AT => 9, ),
        BasePeer::TYPE_RAW_COLNAME => array ('ID' => 0, 'USER_ID' => 1, 'LESSON_ID' => 2, 'QUESTION_ID' => 3, 'TEXT' => 4, 'IS_VISIBLE' => 5, 'IS_ACCEPTED' => 6, 'ACCEPTED_BY' => 7, 'CREATED_AT' => 8, 'UPDATED_AT' => 9, ),
        BasePeer::TYPE_FIELDNAME => array ('id' => 0, 'user_id' => 1, 'lesson_id' => 2, 'question_id' => 3, 'text' => 4, 'is_visible' => 5, 'is_accepted' => 6, 'accepted_by' => 7, 'created_at' => 8, 'updated_at' => 9, ),
        BasePeer::TYPE_NUM => array (0, 1, 2, 3, 4, 5, 6, 7, 8, 9, )
    );

    /**
     * Translates a fieldname to another type
     *
     * @param      string $name field name
     * @param      string $fromType One of the class type constants BasePeer::TYPE_PHPNAME, BasePeer::TYPE_STUDLYPHPNAME
     *                         BasePeer::TYPE_COLNAME, BasePeer::TYPE_FIELDNAME, BasePeer::TYPE_NUM
     * @param      string $toType   One of the class type constants
     * @return string          translated name of the field.
     * @throws PropelException - if the specified name could not be found in the fieldname mappings.
     */
    public static function translateFieldName($name, $fromType, $toType)
    {
        $toNames = LessonAnswerPeer::getFieldNames($toType);
        $key = isset(LessonAnswerPeer::$fieldKeys[$fromType][$name]) ? LessonAnswerPeer::$fieldKeys[$fromType][$name] : null;
        if ($key === null) {
            throw new PropelException("'$name' could not be found in the field names of type '$fromType'. These are: " . print_r(LessonAnswerPeer::$fieldKeys[$fromType], true));
        }

        return $toNames[$key];
    }

    /**
     * Returns an array of field names.
     *
     * @param      string $type The type of fieldnames to return:
     *                      One of the class type constants BasePeer::TYPE_PHPNAME, BasePeer::TYPE_STUDLYPHPNAME
     *                      BasePeer::TYPE_COLNAME, BasePeer::TYPE_FIELDNAME, BasePeer::TYPE_NUM
     * @return array           A list of field names
     * @throws PropelException - if the type is not valid.
     */
    public static function getFieldNames($type = BasePeer::TYPE_PHPNAME)
    {
        if (!array_key_exists($type, LessonAnswerPeer::$fieldNames)) {
            throw new PropelException('Method getFieldNames() expects the parameter $type to be one of the class constants BasePeer::TYPE_PHPNAME, BasePeer::TYPE_STUDLYPHPNAME, BasePeer::TYPE_COLNAME, BasePeer::TYPE_FIELDNAME, BasePeer::TYPE_NUM. ' . $type . ' was given.');
        }

        return LessonAnswerPeer::$fieldNames[$type];
    }

    /**
     * Convenience method which changes table.column to alias.column.
     *
     * Using this method you can maintain SQL abstraction while using column aliases.
     * <code>
     *		$c->addAlias("alias1", TablePeer::TABLE_NAME);
     *		$c->addJoin(TablePeer::alias("alias1", TablePeer::PRIMARY_KEY_COLUMN), TablePeer::PRIMARY_KEY_COLUMN);
     * </code>
     * @param      string $alias The alias for the current table.
     * @param      string $column The column name for current table. (i.e. LessonAnswerPeer::COLUMN_NAME).
     * @return string
     */
    public static function alias($alias, $column)
    {
        return str_replace(LessonAnswerPeer::TABLE_NAME.'.', $alias.'.', $column);
    }

    /**
     * Add all the columns needed to create a new object.
     *
     * Note: any columns that were marked with lazyLoad="true" in the
     * XML schema will not be added to the select list and only loaded
     * on demand.
     *
     * @param      Criteria $criteria object containing the columns to add.
     * @param      string   $alias    optional table alias
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function addSelectColumns(Criteria $criteria, $alias = null)
    {
        if (null === $alias) {
            $criteria->addSelectColumn(LessonAnswerPeer::ID);
            $criteria->addSelectColumn(LessonAnswerPeer::USER_ID);
            $criteria->addSelectColumn(LessonAnswerPeer::LESSON_ID);
            $criteria->addSelectColumn(LessonAnswerPeer::QUESTION_ID);
            $criteria->addSelectColumn(LessonAnswerPeer::TEXT);
            $criteria->addSelectColumn(LessonAnswerPeer::IS_VISIBLE);
            $criteria->addSelectColumn(LessonAnswerPeer::IS_ACCEPTED);
            $criteria->addSelectColumn(LessonAnswerPeer::ACCEPTED_BY);
            $criteria->addSelectColumn(LessonAnswerPeer::CREATED_AT);
            $criteria->addSelectColumn(LessonAnswerPeer::UPDATED_AT);
        } else {
            $criteria->addSelectColumn($alias . '.ID');
            $criteria->addSelectColumn($alias . '.USER_ID');
            $criteria->addSelectColumn($alias . '.LESSON_ID');
            $criteria->addSelectColumn($alias . '.QUESTION_ID');
            $criteria->addSelectColumn($alias . '.TEXT');
            $criteria->addSelectColumn($alias . '.IS_VISIBLE');
            $criteria->addSelectColumn($alias . '.IS_ACCEPTED');
            $criteria->addSelectColumn($alias . '.ACCEPTED_BY');
            $criteria->addSelectColumn($alias . '.CREATED_AT');
            $criteria->addSelectColumn($alias . '.UPDATED_AT');
        }
    }

    /**
     * Returns the number of rows matching criteria.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
     * @param      PropelPDO $con
     * @return int Number of matching rows.
     */
    public static function doCount(Criteria $criteria, $distinct = false, PropelPDO $con = null)
    {
        // we may modify criteria, so copy it first
        $criteria = clone $criteria;

        // We need to set the primary table name, since in the case that there are no WHERE columns
        // it will be impossible for the BasePeer::createSelectSql() method to determine which
        // tables go into the FROM clause.
        $criteria->setPrimaryTableName(LessonAnswerPeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            LessonAnswerPeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY won't ever affect the count
        $criteria->setDbName(LessonAnswerPeer::DATABASE_NAME); // Set the correct dbName

        if ($con === null) {
            $con = Propel::getConnection(LessonAnswerPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }
        // BasePeer returns a PDOStatement
        $stmt = BasePeer::doCount($criteria, $con);

        if ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $count = (int) $row[0];
        } else {
            $count = 0; // no rows returned; we infer that means 0 matches.
        }
        $stmt->closeCursor();

        return $count;
    }
    /**
     * Selects one object from the DB.
     *
     * @param      Criteria $criteria object used to create the SELECT statement.
     * @param      PropelPDO $con
     * @return                 LessonAnswer
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectOne(Criteria $criteria, PropelPDO $con = null)
    {
        $critcopy = clone $criteria;
        $critcopy->setLimit(1);
        $objects = LessonAnswerPeer::doSelect($critcopy, $con);
        if ($objects) {
            return $objects[0];
        }

        return null;
    }
    /**
     * Selects several row from the DB.
     *
     * @param      Criteria $criteria The Criteria object used to build the SELECT statement.
     * @param      PropelPDO $con
     * @return array           Array of selected Objects
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelect(Criteria $criteria, PropelPDO $con = null)
    {
        return LessonAnswerPeer::populateObjects(LessonAnswerPeer::doSelectStmt($criteria, $con));
    }
    /**
     * Prepares the Criteria object and uses the parent doSelect() method to execute a PDOStatement.
     *
     * Use this method directly if you want to work with an executed statement durirectly (for example
     * to perform your own object hydration).
     *
     * @param      Criteria $criteria The Criteria object used to build the SELECT statement.
     * @param      PropelPDO $con The connection to use
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     * @return PDOStatement The executed PDOStatement object.
     * @see        BasePeer::doSelect()
     */
    public static function doSelectStmt(Criteria $criteria, PropelPDO $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection(LessonAnswerPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        if (!$criteria->hasSelectClause()) {
            $criteria = clone $criteria;
            LessonAnswerPeer::addSelectColumns($criteria);
        }

        // Set the correct dbName
        $criteria->setDbName(LessonAnswerPeer::DATABASE_NAME);

        // BasePeer returns a PDOStatement
        return BasePeer::doSelect($criteria, $con);
    }
    /**
     * Adds an object to the instance pool.
     *
     * Propel keeps cached copies of objects in an instance pool when they are retrieved
     * from the database.  In some cases -- especially when you override doSelect*()
     * methods in your stub classes -- you may need to explicitly add objects
     * to the cache in order to ensure that the same objects are always returned by doSelect*()
     * and retrieveByPK*() calls.
     *
     * @param      LessonAnswer $obj A LessonAnswer object.
     * @param      string $key (optional) key to use for instance map (for performance boost if key was already calculated externally).
     */
    public static function addInstanceToPool($obj, $key = null)
    {
        if (Propel::isInstancePoolingEnabled()) {
            if ($key === null) {
                $key = (string) $obj->getId();
            } // if key === null
            LessonAnswerPeer::$instances[$key] = $obj;
        }
    }

    /**
     * Removes an object from the instance pool.
     *
     * Propel keeps cached copies of objects in an instance pool when they are retrieved
     * from the database.  In some cases -- especially when you override doDelete
     * methods in your stub classes -- you may need to explicitly remove objects
     * from the cache in order to prevent returning objects that no longer exist.
     *
     * @param      mixed $value A LessonAnswer object or a primary key value.
     *
     * @return void
     * @throws PropelException - if the value is invalid.
     */
    public static function removeInstanceFromPool($value)
    {
        if (Propel::isInstancePoolingEnabled() && $value !== null) {
            if (is_object($value) && $value instanceof LessonAnswer) {
                $key = (string) $value->getId();
            } elseif (is_scalar($value)) {
                // assume we've been passed a primary key
                $key = (string) $value;
            } else {
                $e = new PropelException("Invalid value passed to removeInstanceFromPool().  Expected primary key or LessonAnswer object; got " . (is_object($value) ? get_class($value) . ' object.' : var_export($value,true)));
                throw $e;
            }

            unset(LessonAnswerPeer::$instances[$key]);
        }
    } // removeInstanceFromPool()

    /**
     * Retrieves a string version of the primary key from the DB resultset row that can be used to uniquely identify a row in this table.
     *
     * For tables with a single-column primary key, that simple pkey value will be returned.  For tables with
     * a multi-column primary key, a serialize()d version of the primary key will be returned.
     *
     * @param      string $key The key (@see getPrimaryKeyHash()) for this instance.
     * @return   LessonAnswer Found object or null if 1) no instance exists for specified key or 2) instance pooling has been disabled.
     * @see        getPrimaryKeyHash()
     */
    public static function getInstanceFromPool($key)
    {
        if (Propel::isInstancePoolingEnabled()) {
            if (isset(LessonAnswerPeer::$instances[$key])) {
                return LessonAnswerPeer::$instances[$key];
            }
        }

        return null; // just to be explicit
    }

    /**
     * Clear the instance pool.
     *
     * @return void
     */
    public static function clearInstancePool()
    {
        LessonAnswerPeer::$instances = array();
    }

    /**
     * Method to invalidate the instance pool of all tables related to lessons_answers
     * by a foreign key with ON DELETE CASCADE
     */
    public static function clearRelatedInstancePool()
    {
    }

    /**
     * Retrieves a string version of the primary key from the DB resultset row that can be used to uniquely identify a row in this table.
     *
     * For tables with a single-column primary key, that simple pkey value will be returned.  For tables with
     * a multi-column primary key, a serialize()d version of the primary key will be returned.
     *
     * @param      array $row PropelPDO resultset row.
     * @param      int $startcol The 0-based offset for reading from the resultset row.
     * @return string A string version of PK or null if the components of primary key in result array are all null.
     */
    public static function getPrimaryKeyHashFromRow($row, $startcol = 0)
    {
        // If the PK cannot be derived from the row, return null.
        if ($row[$startcol] === null) {
            return null;
        }

        return (string) $row[$startcol];
    }

    /**
     * Retrieves the primary key from the DB resultset row
     * For tables with a single-column primary key, that simple pkey value will be returned.  For tables with
     * a multi-column primary key, an array of the primary key columns will be returned.
     *
     * @param      array $row PropelPDO resultset row.
     * @param      int $startcol The 0-based offset for reading from the resultset row.
     * @return mixed The primary key of the row
     */
    public static function getPrimaryKeyFromRow($row, $startcol = 0)
    {

        return (int) $row[$startcol];
    }

    /**
     * The returned array will contain objects of the default type or
     * objects that inherit from the default.
     *
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function populateObjects(PDOStatement $stmt)
    {
        $results = array();

        // set the class once to avoid overhead in the loop
        $cls = LessonAnswerPeer::getOMClass();
        // populate the object(s)
        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key = LessonAnswerPeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj = LessonAnswerPeer::getInstanceFromPool($key))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj->hydrate($row, 0, true); // rehydrate
                $results[] = $obj;
            } else {
                $obj = new $cls();
                $obj->hydrate($row);
                $results[] = $obj;
                LessonAnswerPeer::addInstanceToPool($obj, $key);
            } // if key exists
        }
        $stmt->closeCursor();

        return $results;
    }
    /**
     * Populates an object of the default type or an object that inherit from the default.
     *
     * @param      array $row PropelPDO resultset row.
     * @param      int $startcol The 0-based offset for reading from the resultset row.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     * @return array (LessonAnswer object, last column rank)
     */
    public static function populateObject($row, $startcol = 0)
    {
        $key = LessonAnswerPeer::getPrimaryKeyHashFromRow($row, $startcol);
        if (null !== ($obj = LessonAnswerPeer::getInstanceFromPool($key))) {
            // We no longer rehydrate the object, since this can cause data loss.
            // See http://www.propelorm.org/ticket/509
            // $obj->hydrate($row, $startcol, true); // rehydrate
            $col = $startcol + LessonAnswerPeer::NUM_HYDRATE_COLUMNS;
        } else {
            $cls = LessonAnswerPeer::OM_CLASS;
            $obj = new $cls();
            $col = $obj->hydrate($row, $startcol);
            LessonAnswerPeer::addInstanceToPool($obj, $key);
        }

        return array($obj, $col);
    }


    /**
     * Returns the number of rows matching criteria, joining the related Lesson table
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return int Number of matching rows.
     */
    public static function doCountJoinLesson(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        // we're going to modify criteria, so copy it first
        $criteria = clone $criteria;

        // We need to set the primary table name, since in the case that there are no WHERE columns
        // it will be impossible for the BasePeer::createSelectSql() method to determine which
        // tables go into the FROM clause.
        $criteria->setPrimaryTableName(LessonAnswerPeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            LessonAnswerPeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY won't ever affect the count

        // Set the correct dbName
        $criteria->setDbName(LessonAnswerPeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(LessonAnswerPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(LessonAnswerPeer::LESSON_ID, LessonPeer::ID, $join_behavior);

        $stmt = BasePeer::doCount($criteria, $con);

        if ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $count = (int) $row[0];
        } else {
            $count = 0; // no rows returned; we infer that means 0 matches.
        }
        $stmt->closeCursor();

        return $count;
    }


    /**
     * Returns the number of rows matching criteria, joining the related LessonQuestion table
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return int Number of matching rows.
     */
    public static function doCountJoinLessonQuestion(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        // we're going to modify criteria, so copy it first
        $criteria = clone $criteria;

        // We need to set the primary table name, since in the case that there are no WHERE columns
        // it will be impossible for the BasePeer::createSelectSql() method to determine which
        // tables go into the FROM clause.
        $criteria->setPrimaryTableName(LessonAnswerPeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            LessonAnswerPeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY won't ever affect the count

        // Set the correct dbName
        $criteria->setDbName(LessonAnswerPeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(LessonAnswerPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(LessonAnswerPeer::QUESTION_ID, LessonQuestionPeer::ID, $join_behavior);

        $stmt = BasePeer::doCount($criteria, $con);

        if ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $count = (int) $row[0];
        } else {
            $count = 0; // no rows returned; we infer that means 0 matches.
        }
        $stmt->closeCursor();

        return $count;
    }


    /**
     * Returns the number of rows matching criteria, joining the related User table
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return int Number of matching rows.
     */
    public static function doCountJoinUser(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        // we're going to modify criteria, so copy it first
        $criteria = clone $criteria;

        // We need to set the primary table name, since in the case that there are no WHERE columns
        // it will be impossible for the BasePeer::createSelectSql() method to determine which
        // tables go into the FROM clause.
        $criteria->setPrimaryTableName(LessonAnswerPeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            LessonAnswerPeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY won't ever affect the count

        // Set the correct dbName
        $criteria->setDbName(LessonAnswerPeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(LessonAnswerPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(LessonAnswerPeer::USER_ID, UserPeer::ID, $join_behavior);

        $stmt = BasePeer::doCount($criteria, $con);

        if ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $count = (int) $row[0];
        } else {
            $count = 0; // no rows returned; we infer that means 0 matches.
        }
        $stmt->closeCursor();

        return $count;
    }


    /**
     * Selects a collection of LessonAnswer objects pre-filled with their Lesson objects.
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of LessonAnswer objects.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectJoinLesson(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $criteria = clone $criteria;

        // Set the correct dbName if it has not been overridden
        if ($criteria->getDbName() == Propel::getDefaultDB()) {
            $criteria->setDbName(LessonAnswerPeer::DATABASE_NAME);
        }

        LessonAnswerPeer::addSelectColumns($criteria);
        $startcol = LessonAnswerPeer::NUM_HYDRATE_COLUMNS;
        LessonPeer::addSelectColumns($criteria);

        $criteria->addJoin(LessonAnswerPeer::LESSON_ID, LessonPeer::ID, $join_behavior);

        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = LessonAnswerPeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = LessonAnswerPeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {

                $cls = LessonAnswerPeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                LessonAnswerPeer::addInstanceToPool($obj1, $key1);
            } // if $obj1 already loaded

            $key2 = LessonPeer::getPrimaryKeyHashFromRow($row, $startcol);
            if ($key2 !== null) {
                $obj2 = LessonPeer::getInstanceFromPool($key2);
                if (!$obj2) {

                    $cls = LessonPeer::getOMClass();

                    $obj2 = new $cls();
                    $obj2->hydrate($row, $startcol);
                    LessonPeer::addInstanceToPool($obj2, $key2);
                } // if obj2 already loaded

                // Add the $obj1 (LessonAnswer) to $obj2 (Lesson)
                $obj2->addLessonAnswer($obj1);

            } // if joined row was not null

            $results[] = $obj1;
        }
        $stmt->closeCursor();

        return $results;
    }


    /**
     * Selects a collection of LessonAnswer objects pre-filled with their LessonQuestion objects.
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of LessonAnswer objects.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectJoinLessonQuestion(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $criteria = clone $criteria;

        // Set the correct dbName if it has not been overridden
        if ($criteria->getDbName() == Propel::getDefaultDB()) {
            $criteria->setDbName(LessonAnswerPeer::DATABASE_NAME);
        }

        LessonAnswerPeer::addSelectColumns($criteria);
        $startcol = LessonAnswerPeer::NUM_HYDRATE_COLUMNS;
        LessonQuestionPeer::addSelectColumns($criteria);

        $criteria->addJoin(LessonAnswerPeer::QUESTION_ID, LessonQuestionPeer::ID, $join_behavior);

        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = LessonAnswerPeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = LessonAnswerPeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {

                $cls = LessonAnswerPeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                LessonAnswerPeer::addInstanceToPool($obj1, $key1);
            } // if $obj1 already loaded

            $key2 = LessonQuestionPeer::getPrimaryKeyHashFromRow($row, $startcol);
            if ($key2 !== null) {
                $obj2 = LessonQuestionPeer::getInstanceFromPool($key2);
                if (!$obj2) {

                    $cls = LessonQuestionPeer::getOMClass();

                    $obj2 = new $cls();
                    $obj2->hydrate($row, $startcol);
                    LessonQuestionPeer::addInstanceToPool($obj2, $key2);
                } // if obj2 already loaded

                // Add the $obj1 (LessonAnswer) to $obj2 (LessonQuestion)
                $obj2->addLessonAnswer($obj1);

            } // if joined row was not null

            $results[] = $obj1;
        }
        $stmt->closeCursor();

        return $results;
    }


    /**
     * Selects a collection of LessonAnswer objects pre-filled with their User objects.
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of LessonAnswer objects.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectJoinUser(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $criteria = clone $criteria;

        // Set the correct dbName if it has not been overridden
        if ($criteria->getDbName() == Propel::getDefaultDB()) {
            $criteria->setDbName(LessonAnswerPeer::DATABASE_NAME);
        }

        LessonAnswerPeer::addSelectColumns($criteria);
        $startcol = LessonAnswerPeer::NUM_HYDRATE_COLUMNS;
        UserPeer::addSelectColumns($criteria);

        $criteria->addJoin(LessonAnswerPeer::USER_ID, UserPeer::ID, $join_behavior);

        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = LessonAnswerPeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = LessonAnswerPeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {

                $cls = LessonAnswerPeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                LessonAnswerPeer::addInstanceToPool($obj1, $key1);
            } // if $obj1 already loaded

            $key2 = UserPeer::getPrimaryKeyHashFromRow($row, $startcol);
            if ($key2 !== null) {
                $obj2 = UserPeer::getInstanceFromPool($key2);
                if (!$obj2) {

                    $cls = UserPeer::getOMClass();

                    $obj2 = new $cls();
                    $obj2->hydrate($row, $startcol);
                    UserPeer::addInstanceToPool($obj2, $key2);
                } // if obj2 already loaded

                // Add the $obj1 (LessonAnswer) to $obj2 (User)
                $obj2->addLessonAnswer($obj1);

            } // if joined row was not null

            $results[] = $obj1;
        }
        $stmt->closeCursor();

        return $results;
    }


    /**
     * Returns the number of rows matching criteria, joining all related tables
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return int Number of matching rows.
     */
    public static function doCountJoinAll(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        // we're going to modify criteria, so copy it first
        $criteria = clone $criteria;

        // We need to set the primary table name, since in the case that there are no WHERE columns
        // it will be impossible for the BasePeer::createSelectSql() method to determine which
        // tables go into the FROM clause.
        $criteria->setPrimaryTableName(LessonAnswerPeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            LessonAnswerPeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY won't ever affect the count

        // Set the correct dbName
        $criteria->setDbName(LessonAnswerPeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(LessonAnswerPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(LessonAnswerPeer::LESSON_ID, LessonPeer::ID, $join_behavior);

        $criteria->addJoin(LessonAnswerPeer::QUESTION_ID, LessonQuestionPeer::ID, $join_behavior);

        $criteria->addJoin(LessonAnswerPeer::USER_ID, UserPeer::ID, $join_behavior);

        $stmt = BasePeer::doCount($criteria, $con);

        if ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $count = (int) $row[0];
        } else {
            $count = 0; // no rows returned; we infer that means 0 matches.
        }
        $stmt->closeCursor();

        return $count;
    }

    /**
     * Selects a collection of LessonAnswer objects pre-filled with all related objects.
     *
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of LessonAnswer objects.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectJoinAll(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $criteria = clone $criteria;

        // Set the correct dbName if it has not been overridden
        if ($criteria->getDbName() == Propel::getDefaultDB()) {
            $criteria->setDbName(LessonAnswerPeer::DATABASE_NAME);
        }

        LessonAnswerPeer::addSelectColumns($criteria);
        $startcol2 = LessonAnswerPeer::NUM_HYDRATE_COLUMNS;

        LessonPeer::addSelectColumns($criteria);
        $startcol3 = $startcol2 + LessonPeer::NUM_HYDRATE_COLUMNS;

        LessonQuestionPeer::addSelectColumns($criteria);
        $startcol4 = $startcol3 + LessonQuestionPeer::NUM_HYDRATE_COLUMNS;

        UserPeer::addSelectColumns($criteria);
        $startcol5 = $startcol4 + UserPeer::NUM_HYDRATE_COLUMNS;

        $criteria->addJoin(LessonAnswerPeer::LESSON_ID, LessonPeer::ID, $join_behavior);

        $criteria->addJoin(LessonAnswerPeer::QUESTION_ID, LessonQuestionPeer::ID, $join_behavior);

        $criteria->addJoin(LessonAnswerPeer::USER_ID, UserPeer::ID, $join_behavior);

        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = LessonAnswerPeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = LessonAnswerPeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {
                $cls = LessonAnswerPeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                LessonAnswerPeer::addInstanceToPool($obj1, $key1);
            } // if obj1 already loaded

            // Add objects for joined Lesson rows

            $key2 = LessonPeer::getPrimaryKeyHashFromRow($row, $startcol2);
            if ($key2 !== null) {
                $obj2 = LessonPeer::getInstanceFromPool($key2);
                if (!$obj2) {

                    $cls = LessonPeer::getOMClass();

                    $obj2 = new $cls();
                    $obj2->hydrate($row, $startcol2);
                    LessonPeer::addInstanceToPool($obj2, $key2);
                } // if obj2 loaded

                // Add the $obj1 (LessonAnswer) to the collection in $obj2 (Lesson)
                $obj2->addLessonAnswer($obj1);
            } // if joined row not null

            // Add objects for joined LessonQuestion rows

            $key3 = LessonQuestionPeer::getPrimaryKeyHashFromRow($row, $startcol3);
            if ($key3 !== null) {
                $obj3 = LessonQuestionPeer::getInstanceFromPool($key3);
                if (!$obj3) {

                    $cls = LessonQuestionPeer::getOMClass();

                    $obj3 = new $cls();
                    $obj3->hydrate($row, $startcol3);
                    LessonQuestionPeer::addInstanceToPool($obj3, $key3);
                } // if obj3 loaded

                // Add the $obj1 (LessonAnswer) to the collection in $obj3 (LessonQuestion)
                $obj3->addLessonAnswer($obj1);
            } // if joined row not null

            // Add objects for joined User rows

            $key4 = UserPeer::getPrimaryKeyHashFromRow($row, $startcol4);
            if ($key4 !== null) {
                $obj4 = UserPeer::getInstanceFromPool($key4);
                if (!$obj4) {

                    $cls = UserPeer::getOMClass();

                    $obj4 = new $cls();
                    $obj4->hydrate($row, $startcol4);
                    UserPeer::addInstanceToPool($obj4, $key4);
                } // if obj4 loaded

                // Add the $obj1 (LessonAnswer) to the collection in $obj4 (User)
                $obj4->addLessonAnswer($obj1);
            } // if joined row not null

            $results[] = $obj1;
        }
        $stmt->closeCursor();

        return $results;
    }


    /**
     * Returns the number of rows matching criteria, joining the related Lesson table
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return int Number of matching rows.
     */
    public static function doCountJoinAllExceptLesson(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        // we're going to modify criteria, so copy it first
        $criteria = clone $criteria;

        // We need to set the primary table name, since in the case that there are no WHERE columns
        // it will be impossible for the BasePeer::createSelectSql() method to determine which
        // tables go into the FROM clause.
        $criteria->setPrimaryTableName(LessonAnswerPeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            LessonAnswerPeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY should not affect count

        // Set the correct dbName
        $criteria->setDbName(LessonAnswerPeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(LessonAnswerPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(LessonAnswerPeer::QUESTION_ID, LessonQuestionPeer::ID, $join_behavior);

        $criteria->addJoin(LessonAnswerPeer::USER_ID, UserPeer::ID, $join_behavior);

        $stmt = BasePeer::doCount($criteria, $con);

        if ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $count = (int) $row[0];
        } else {
            $count = 0; // no rows returned; we infer that means 0 matches.
        }
        $stmt->closeCursor();

        return $count;
    }


    /**
     * Returns the number of rows matching criteria, joining the related LessonQuestion table
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return int Number of matching rows.
     */
    public static function doCountJoinAllExceptLessonQuestion(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        // we're going to modify criteria, so copy it first
        $criteria = clone $criteria;

        // We need to set the primary table name, since in the case that there are no WHERE columns
        // it will be impossible for the BasePeer::createSelectSql() method to determine which
        // tables go into the FROM clause.
        $criteria->setPrimaryTableName(LessonAnswerPeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            LessonAnswerPeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY should not affect count

        // Set the correct dbName
        $criteria->setDbName(LessonAnswerPeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(LessonAnswerPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(LessonAnswerPeer::LESSON_ID, LessonPeer::ID, $join_behavior);

        $criteria->addJoin(LessonAnswerPeer::USER_ID, UserPeer::ID, $join_behavior);

        $stmt = BasePeer::doCount($criteria, $con);

        if ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $count = (int) $row[0];
        } else {
            $count = 0; // no rows returned; we infer that means 0 matches.
        }
        $stmt->closeCursor();

        return $count;
    }


    /**
     * Returns the number of rows matching criteria, joining the related User table
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return int Number of matching rows.
     */
    public static function doCountJoinAllExceptUser(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        // we're going to modify criteria, so copy it first
        $criteria = clone $criteria;

        // We need to set the primary table name, since in the case that there are no WHERE columns
        // it will be impossible for the BasePeer::createSelectSql() method to determine which
        // tables go into the FROM clause.
        $criteria->setPrimaryTableName(LessonAnswerPeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            LessonAnswerPeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY should not affect count

        // Set the correct dbName
        $criteria->setDbName(LessonAnswerPeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(LessonAnswerPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(LessonAnswerPeer::LESSON_ID, LessonPeer::ID, $join_behavior);

        $criteria->addJoin(LessonAnswerPeer::QUESTION_ID, LessonQuestionPeer::ID, $join_behavior);

        $stmt = BasePeer::doCount($criteria, $con);

        if ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $count = (int) $row[0];
        } else {
            $count = 0; // no rows returned; we infer that means 0 matches.
        }
        $stmt->closeCursor();

        return $count;
    }


    /**
     * Selects a collection of LessonAnswer objects pre-filled with all related objects except Lesson.
     *
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of LessonAnswer objects.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectJoinAllExceptLesson(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $criteria = clone $criteria;

        // Set the correct dbName if it has not been overridden
        // $criteria->getDbName() will return the same object if not set to another value
        // so == check is okay and faster
        if ($criteria->getDbName() == Propel::getDefaultDB()) {
            $criteria->setDbName(LessonAnswerPeer::DATABASE_NAME);
        }

        LessonAnswerPeer::addSelectColumns($criteria);
        $startcol2 = LessonAnswerPeer::NUM_HYDRATE_COLUMNS;

        LessonQuestionPeer::addSelectColumns($criteria);
        $startcol3 = $startcol2 + LessonQuestionPeer::NUM_HYDRATE_COLUMNS;

        UserPeer::addSelectColumns($criteria);
        $startcol4 = $startcol3 + UserPeer::NUM_HYDRATE_COLUMNS;

        $criteria->addJoin(LessonAnswerPeer::QUESTION_ID, LessonQuestionPeer::ID, $join_behavior);

        $criteria->addJoin(LessonAnswerPeer::USER_ID, UserPeer::ID, $join_behavior);


        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = LessonAnswerPeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = LessonAnswerPeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {
                $cls = LessonAnswerPeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                LessonAnswerPeer::addInstanceToPool($obj1, $key1);
            } // if obj1 already loaded

                // Add objects for joined LessonQuestion rows

                $key2 = LessonQuestionPeer::getPrimaryKeyHashFromRow($row, $startcol2);
                if ($key2 !== null) {
                    $obj2 = LessonQuestionPeer::getInstanceFromPool($key2);
                    if (!$obj2) {

                        $cls = LessonQuestionPeer::getOMClass();

                    $obj2 = new $cls();
                    $obj2->hydrate($row, $startcol2);
                    LessonQuestionPeer::addInstanceToPool($obj2, $key2);
                } // if $obj2 already loaded

                // Add the $obj1 (LessonAnswer) to the collection in $obj2 (LessonQuestion)
                $obj2->addLessonAnswer($obj1);

            } // if joined row is not null

                // Add objects for joined User rows

                $key3 = UserPeer::getPrimaryKeyHashFromRow($row, $startcol3);
                if ($key3 !== null) {
                    $obj3 = UserPeer::getInstanceFromPool($key3);
                    if (!$obj3) {

                        $cls = UserPeer::getOMClass();

                    $obj3 = new $cls();
                    $obj3->hydrate($row, $startcol3);
                    UserPeer::addInstanceToPool($obj3, $key3);
                } // if $obj3 already loaded

                // Add the $obj1 (LessonAnswer) to the collection in $obj3 (User)
                $obj3->addLessonAnswer($obj1);

            } // if joined row is not null

            $results[] = $obj1;
        }
        $stmt->closeCursor();

        return $results;
    }


    /**
     * Selects a collection of LessonAnswer objects pre-filled with all related objects except LessonQuestion.
     *
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of LessonAnswer objects.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectJoinAllExceptLessonQuestion(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $criteria = clone $criteria;

        // Set the correct dbName if it has not been overridden
        // $criteria->getDbName() will return the same object if not set to another value
        // so == check is okay and faster
        if ($criteria->getDbName() == Propel::getDefaultDB()) {
            $criteria->setDbName(LessonAnswerPeer::DATABASE_NAME);
        }

        LessonAnswerPeer::addSelectColumns($criteria);
        $startcol2 = LessonAnswerPeer::NUM_HYDRATE_COLUMNS;

        LessonPeer::addSelectColumns($criteria);
        $startcol3 = $startcol2 + LessonPeer::NUM_HYDRATE_COLUMNS;

        UserPeer::addSelectColumns($criteria);
        $startcol4 = $startcol3 + UserPeer::NUM_HYDRATE_COLUMNS;

        $criteria->addJoin(LessonAnswerPeer::LESSON_ID, LessonPeer::ID, $join_behavior);

        $criteria->addJoin(LessonAnswerPeer::USER_ID, UserPeer::ID, $join_behavior);


        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = LessonAnswerPeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = LessonAnswerPeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {
                $cls = LessonAnswerPeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                LessonAnswerPeer::addInstanceToPool($obj1, $key1);
            } // if obj1 already loaded

                // Add objects for joined Lesson rows

                $key2 = LessonPeer::getPrimaryKeyHashFromRow($row, $startcol2);
                if ($key2 !== null) {
                    $obj2 = LessonPeer::getInstanceFromPool($key2);
                    if (!$obj2) {

                        $cls = LessonPeer::getOMClass();

                    $obj2 = new $cls();
                    $obj2->hydrate($row, $startcol2);
                    LessonPeer::addInstanceToPool($obj2, $key2);
                } // if $obj2 already loaded

                // Add the $obj1 (LessonAnswer) to the collection in $obj2 (Lesson)
                $obj2->addLessonAnswer($obj1);

            } // if joined row is not null

                // Add objects for joined User rows

                $key3 = UserPeer::getPrimaryKeyHashFromRow($row, $startcol3);
                if ($key3 !== null) {
                    $obj3 = UserPeer::getInstanceFromPool($key3);
                    if (!$obj3) {

                        $cls = UserPeer::getOMClass();

                    $obj3 = new $cls();
                    $obj3->hydrate($row, $startcol3);
                    UserPeer::addInstanceToPool($obj3, $key3);
                } // if $obj3 already loaded

                // Add the $obj1 (LessonAnswer) to the collection in $obj3 (User)
                $obj3->addLessonAnswer($obj1);

            } // if joined row is not null

            $results[] = $obj1;
        }
        $stmt->closeCursor();

        return $results;
    }


    /**
     * Selects a collection of LessonAnswer objects pre-filled with all related objects except User.
     *
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of LessonAnswer objects.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectJoinAllExceptUser(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $criteria = clone $criteria;

        // Set the correct dbName if it has not been overridden
        // $criteria->getDbName() will return the same object if not set to another value
        // so == check is okay and faster
        if ($criteria->getDbName() == Propel::getDefaultDB()) {
            $criteria->setDbName(LessonAnswerPeer::DATABASE_NAME);
        }

        LessonAnswerPeer::addSelectColumns($criteria);
        $startcol2 = LessonAnswerPeer::NUM_HYDRATE_COLUMNS;

        LessonPeer::addSelectColumns($criteria);
        $startcol3 = $startcol2 + LessonPeer::NUM_HYDRATE_COLUMNS;

        LessonQuestionPeer::addSelectColumns($criteria);
        $startcol4 = $startcol3 + LessonQuestionPeer::NUM_HYDRATE_COLUMNS;

        $criteria->addJoin(LessonAnswerPeer::LESSON_ID, LessonPeer::ID, $join_behavior);

        $criteria->addJoin(LessonAnswerPeer::QUESTION_ID, LessonQuestionPeer::ID, $join_behavior);


        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = LessonAnswerPeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = LessonAnswerPeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {
                $cls = LessonAnswerPeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                LessonAnswerPeer::addInstanceToPool($obj1, $key1);
            } // if obj1 already loaded

                // Add objects for joined Lesson rows

                $key2 = LessonPeer::getPrimaryKeyHashFromRow($row, $startcol2);
                if ($key2 !== null) {
                    $obj2 = LessonPeer::getInstanceFromPool($key2);
                    if (!$obj2) {

                        $cls = LessonPeer::getOMClass();

                    $obj2 = new $cls();
                    $obj2->hydrate($row, $startcol2);
                    LessonPeer::addInstanceToPool($obj2, $key2);
                } // if $obj2 already loaded

                // Add the $obj1 (LessonAnswer) to the collection in $obj2 (Lesson)
                $obj2->addLessonAnswer($obj1);

            } // if joined row is not null

                // Add objects for joined LessonQuestion rows

                $key3 = LessonQuestionPeer::getPrimaryKeyHashFromRow($row, $startcol3);
                if ($key3 !== null) {
                    $obj3 = LessonQuestionPeer::getInstanceFromPool($key3);
                    if (!$obj3) {

                        $cls = LessonQuestionPeer::getOMClass();

                    $obj3 = new $cls();
                    $obj3->hydrate($row, $startcol3);
                    LessonQuestionPeer::addInstanceToPool($obj3, $key3);
                } // if $obj3 already loaded

                // Add the $obj1 (LessonAnswer) to the collection in $obj3 (LessonQuestion)
                $obj3->addLessonAnswer($obj1);

            } // if joined row is not null

            $results[] = $obj1;
        }
        $stmt->closeCursor();

        return $results;
    }

    /**
     * Returns the TableMap related to this peer.
     * This method is not needed for general use but a specific application could have a need.
     * @return TableMap
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function getTableMap()
    {
        return Propel::getDatabaseMap(LessonAnswerPeer::DATABASE_NAME)->getTable(LessonAnswerPeer::TABLE_NAME);
    }

    /**
     * Add a TableMap instance to the database for this peer class.
     */
    public static function buildTableMap()
    {
      $dbMap = Propel::getDatabaseMap(BaseLessonAnswerPeer::DATABASE_NAME);
      if (!$dbMap->hasTable(BaseLessonAnswerPeer::TABLE_NAME)) {
        $dbMap->addTableObject(new LessonAnswerTableMap());
      }
    }

    /**
     * The class that the Peer will make instances of.
     *
     *
     * @return string ClassName
     */
    public static function getOMClass()
    {
        return LessonAnswerPeer::OM_CLASS;
    }

    /**
     * Performs an INSERT on the database, given a LessonAnswer or Criteria object.
     *
     * @param      mixed $values Criteria or LessonAnswer object containing data that is used to create the INSERT statement.
     * @param      PropelPDO $con the PropelPDO connection to use
     * @return mixed           The new primary key.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doInsert($values, PropelPDO $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection(LessonAnswerPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        if ($values instanceof Criteria) {
            $criteria = clone $values; // rename for clarity
        } else {
            $criteria = $values->buildCriteria(); // build Criteria from LessonAnswer object
        }

        if ($criteria->containsKey(LessonAnswerPeer::ID) && $criteria->keyContainsValue(LessonAnswerPeer::ID) ) {
            throw new PropelException('Cannot insert a value for auto-increment primary key ('.LessonAnswerPeer::ID.')');
        }


        // Set the correct dbName
        $criteria->setDbName(LessonAnswerPeer::DATABASE_NAME);

        try {
            // use transaction because $criteria could contain info
            // for more than one table (I guess, conceivably)
            $con->beginTransaction();
            $pk = BasePeer::doInsert($criteria, $con);
            $con->commit();
        } catch (PropelException $e) {
            $con->rollBack();
            throw $e;
        }

        return $pk;
    }

    /**
     * Performs an UPDATE on the database, given a LessonAnswer or Criteria object.
     *
     * @param      mixed $values Criteria or LessonAnswer object containing data that is used to create the UPDATE statement.
     * @param      PropelPDO $con The connection to use (specify PropelPDO connection object to exert more control over transactions).
     * @return int             The number of affected rows (if supported by underlying database driver).
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doUpdate($values, PropelPDO $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection(LessonAnswerPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        $selectCriteria = new Criteria(LessonAnswerPeer::DATABASE_NAME);

        if ($values instanceof Criteria) {
            $criteria = clone $values; // rename for clarity

            $comparison = $criteria->getComparison(LessonAnswerPeer::ID);
            $value = $criteria->remove(LessonAnswerPeer::ID);
            if ($value) {
                $selectCriteria->add(LessonAnswerPeer::ID, $value, $comparison);
            } else {
                $selectCriteria->setPrimaryTableName(LessonAnswerPeer::TABLE_NAME);
            }

        } else { // $values is LessonAnswer object
            $criteria = $values->buildCriteria(); // gets full criteria
            $selectCriteria = $values->buildPkeyCriteria(); // gets criteria w/ primary key(s)
        }

        // set the correct dbName
        $criteria->setDbName(LessonAnswerPeer::DATABASE_NAME);

        return BasePeer::doUpdate($selectCriteria, $criteria, $con);
    }

    /**
     * Deletes all rows from the lessons_answers table.
     *
     * @param      PropelPDO $con the connection to use
     * @return int             The number of affected rows (if supported by underlying database driver).
     * @throws PropelException
     */
    public static function doDeleteAll(PropelPDO $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection(LessonAnswerPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }
        $affectedRows = 0; // initialize var to track total num of affected rows
        try {
            // use transaction because $criteria could contain info
            // for more than one table or we could emulating ON DELETE CASCADE, etc.
            $con->beginTransaction();
            $affectedRows += BasePeer::doDeleteAll(LessonAnswerPeer::TABLE_NAME, $con, LessonAnswerPeer::DATABASE_NAME);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            LessonAnswerPeer::clearInstancePool();
            LessonAnswerPeer::clearRelatedInstancePool();
            $con->commit();

            return $affectedRows;
        } catch (PropelException $e) {
            $con->rollBack();
            throw $e;
        }
    }

    /**
     * Performs a DELETE on the database, given a LessonAnswer or Criteria object OR a primary key value.
     *
     * @param      mixed $values Criteria or LessonAnswer object or primary key or array of primary keys
     *              which is used to create the DELETE statement
     * @param      PropelPDO $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).  This includes CASCADE-related rows
     *				if supported by native driver or if emulated using Propel.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
     public static function doDelete($values, PropelPDO $con = null)
     {
        if ($con === null) {
            $con = Propel::getConnection(LessonAnswerPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        if ($values instanceof Criteria) {
            // invalidate the cache for all objects of this type, since we have no
            // way of knowing (without running a query) what objects should be invalidated
            // from the cache based on this Criteria.
            LessonAnswerPeer::clearInstancePool();
            // rename for clarity
            $criteria = clone $values;
        } elseif ($values instanceof LessonAnswer) { // it's a model object
            // invalidate the cache for this single object
            LessonAnswerPeer::removeInstanceFromPool($values);
            // create criteria based on pk values
            $criteria = $values->buildPkeyCriteria();
        } else { // it's a primary key, or an array of pks
            $criteria = new Criteria(LessonAnswerPeer::DATABASE_NAME);
            $criteria->add(LessonAnswerPeer::ID, (array) $values, Criteria::IN);
            // invalidate the cache for this object(s)
            foreach ((array) $values as $singleval) {
                LessonAnswerPeer::removeInstanceFromPool($singleval);
            }
        }

        // Set the correct dbName
        $criteria->setDbName(LessonAnswerPeer::DATABASE_NAME);

        $affectedRows = 0; // initialize var to track total num of affected rows

        try {
            // use transaction because $criteria could contain info
            // for more than one table or we could emulating ON DELETE CASCADE, etc.
            $con->beginTransaction();

            $affectedRows += BasePeer::doDelete($criteria, $con);
            LessonAnswerPeer::clearRelatedInstancePool();
            $con->commit();

            return $affectedRows;
        } catch (PropelException $e) {
            $con->rollBack();
            throw $e;
        }
    }

    /**
     * Validates all modified columns of given LessonAnswer object.
     * If parameter $columns is either a single column name or an array of column names
     * than only those columns are validated.
     *
     * NOTICE: This does not apply to primary or foreign keys for now.
     *
     * @param      LessonAnswer $obj The object to validate.
     * @param      mixed $cols Column name or array of column names.
     *
     * @return mixed TRUE if all columns are valid or the error message of the first invalid column.
     */
    public static function doValidate($obj, $cols = null)
    {
        $columns = array();

        if ($cols) {
            $dbMap = Propel::getDatabaseMap(LessonAnswerPeer::DATABASE_NAME);
            $tableMap = $dbMap->getTable(LessonAnswerPeer::TABLE_NAME);

            if (! is_array($cols)) {
                $cols = array($cols);
            }

            foreach ($cols as $colName) {
                if ($tableMap->hasColumn($colName)) {
                    $get = 'get' . $tableMap->getColumn($colName)->getPhpName();
                    $columns[$colName] = $obj->$get();
                }
            }
        } else {

        }

        return BasePeer::doValidate(LessonAnswerPeer::DATABASE_NAME, LessonAnswerPeer::TABLE_NAME, $columns);
    }

    /**
     * Retrieve a single object by pkey.
     *
     * @param      int $pk the primary key.
     * @param      PropelPDO $con the connection to use
     * @return LessonAnswer
     */
    public static function retrieveByPK($pk, PropelPDO $con = null)
    {

        if (null !== ($obj = LessonAnswerPeer::getInstanceFromPool((string) $pk))) {
            return $obj;
        }

        if ($con === null) {
            $con = Propel::getConnection(LessonAnswerPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria = new Criteria(LessonAnswerPeer::DATABASE_NAME);
        $criteria->add(LessonAnswerPeer::ID, $pk);

        $v = LessonAnswerPeer::doSelect($criteria, $con);

        return !empty($v) > 0 ? $v[0] : null;
    }

    /**
     * Retrieve multiple objects by pkey.
     *
     * @param      array $pks List of primary keys
     * @param      PropelPDO $con the connection to use
     * @return LessonAnswer[]
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function retrieveByPKs($pks, PropelPDO $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection(LessonAnswerPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $objs = null;
        if (empty($pks)) {
            $objs = array();
        } else {
            $criteria = new Criteria(LessonAnswerPeer::DATABASE_NAME);
            $criteria->add(LessonAnswerPeer::ID, $pks, Criteria::IN);
            $objs = LessonAnswerPeer::doSelect($criteria, $con);
        }

        return $objs;
    }

} // BaseLessonAnswerPeer

// This is the static code needed to register the TableMap for this table with the main Propel class.
//
BaseLessonAnswerPeer::buildTableMap();

