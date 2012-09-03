<?php

namespace Smirik\CourseBundle\Model\om;

use \BasePeer;
use \Criteria;
use \PDO;
use \PDOStatement;
use \Propel;
use \PropelException;
use \PropelPDO;
use Smirik\CourseBundle\Model\ContentPeer;
use Smirik\CourseBundle\Model\CoursePeer;
use Smirik\CourseBundle\Model\Lesson;
use Smirik\CourseBundle\Model\LessonAnswerPeer;
use Smirik\CourseBundle\Model\LessonPeer;
use Smirik\CourseBundle\Model\LessonQuestionPeer;
use Smirik\CourseBundle\Model\LessonQuizPeer;
use Smirik\CourseBundle\Model\SlideshareContentPeer;
use Smirik\CourseBundle\Model\TaskPeer;
use Smirik\CourseBundle\Model\TextContentPeer;
use Smirik\CourseBundle\Model\UrlContentPeer;
use Smirik\CourseBundle\Model\UserLessonPeer;
use Smirik\CourseBundle\Model\UserTaskPeer;
use Smirik\CourseBundle\Model\YoutubeContentPeer;
use Smirik\CourseBundle\Model\map\LessonTableMap;

abstract class BaseLessonPeer
{

    /** the default database name for this class */
    const DATABASE_NAME = 'default';

    /** the table name for this class */
    const TABLE_NAME = 'lessons';

    /** the related Propel class for this table */
    const OM_CLASS = 'Smirik\\CourseBundle\\Model\\Lesson';

    /** the related TableMap class for this table */
    const TM_CLASS = 'LessonTableMap';

    /** The total number of columns. */
    const NUM_COLUMNS = 6;

    /** The number of lazy-loaded columns. */
    const NUM_LAZY_LOAD_COLUMNS = 0;

    /** The number of columns to hydrate (NUM_COLUMNS - NUM_LAZY_LOAD_COLUMNS) */
    const NUM_HYDRATE_COLUMNS = 6;

    /** the column name for the ID field */
    const ID = 'lessons.ID';

    /** the column name for the COURSE_ID field */
    const COURSE_ID = 'lessons.COURSE_ID';

    /** the column name for the TITLE field */
    const TITLE = 'lessons.TITLE';

    /** the column name for the CREATED_AT field */
    const CREATED_AT = 'lessons.CREATED_AT';

    /** the column name for the UPDATED_AT field */
    const UPDATED_AT = 'lessons.UPDATED_AT';

    /** the column name for the SORTABLE_RANK field */
    const SORTABLE_RANK = 'lessons.SORTABLE_RANK';

    /** The default string format for model objects of the related table **/
    const DEFAULT_STRING_FORMAT = 'YAML';

    /**
     * An identiy map to hold any loaded instances of Lesson objects.
     * This must be public so that other peer classes can access this when hydrating from JOIN
     * queries.
     * @var        array Lesson[]
     */
    public static $instances = array();


    // sortable behavior

    /**
     * rank column
     */
    const RANK_COL = 'lessons.SORTABLE_RANK';

    /**
     * Scope column for the set
     */
    const SCOPE_COL = 'lessons.COURSE_ID';

    /**
     * holds an array of fieldnames
     *
     * first dimension keys are the type constants
     * e.g. LessonPeer::$fieldNames[LessonPeer::TYPE_PHPNAME][0] = 'Id'
     */
    protected static $fieldNames = array (
        BasePeer::TYPE_PHPNAME => array ('Id', 'CourseId', 'Title', 'CreatedAt', 'UpdatedAt', 'SortableRank', ),
        BasePeer::TYPE_STUDLYPHPNAME => array ('id', 'courseId', 'title', 'createdAt', 'updatedAt', 'sortableRank', ),
        BasePeer::TYPE_COLNAME => array (LessonPeer::ID, LessonPeer::COURSE_ID, LessonPeer::TITLE, LessonPeer::CREATED_AT, LessonPeer::UPDATED_AT, LessonPeer::SORTABLE_RANK, ),
        BasePeer::TYPE_RAW_COLNAME => array ('ID', 'COURSE_ID', 'TITLE', 'CREATED_AT', 'UPDATED_AT', 'SORTABLE_RANK', ),
        BasePeer::TYPE_FIELDNAME => array ('id', 'course_id', 'title', 'created_at', 'updated_at', 'sortable_rank', ),
        BasePeer::TYPE_NUM => array (0, 1, 2, 3, 4, 5, )
    );

    /**
     * holds an array of keys for quick access to the fieldnames array
     *
     * first dimension keys are the type constants
     * e.g. LessonPeer::$fieldNames[BasePeer::TYPE_PHPNAME]['Id'] = 0
     */
    protected static $fieldKeys = array (
        BasePeer::TYPE_PHPNAME => array ('Id' => 0, 'CourseId' => 1, 'Title' => 2, 'CreatedAt' => 3, 'UpdatedAt' => 4, 'SortableRank' => 5, ),
        BasePeer::TYPE_STUDLYPHPNAME => array ('id' => 0, 'courseId' => 1, 'title' => 2, 'createdAt' => 3, 'updatedAt' => 4, 'sortableRank' => 5, ),
        BasePeer::TYPE_COLNAME => array (LessonPeer::ID => 0, LessonPeer::COURSE_ID => 1, LessonPeer::TITLE => 2, LessonPeer::CREATED_AT => 3, LessonPeer::UPDATED_AT => 4, LessonPeer::SORTABLE_RANK => 5, ),
        BasePeer::TYPE_RAW_COLNAME => array ('ID' => 0, 'COURSE_ID' => 1, 'TITLE' => 2, 'CREATED_AT' => 3, 'UPDATED_AT' => 4, 'SORTABLE_RANK' => 5, ),
        BasePeer::TYPE_FIELDNAME => array ('id' => 0, 'course_id' => 1, 'title' => 2, 'created_at' => 3, 'updated_at' => 4, 'sortable_rank' => 5, ),
        BasePeer::TYPE_NUM => array (0, 1, 2, 3, 4, 5, )
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
        $toNames = LessonPeer::getFieldNames($toType);
        $key = isset(LessonPeer::$fieldKeys[$fromType][$name]) ? LessonPeer::$fieldKeys[$fromType][$name] : null;
        if ($key === null) {
            throw new PropelException("'$name' could not be found in the field names of type '$fromType'. These are: " . print_r(LessonPeer::$fieldKeys[$fromType], true));
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
        if (!array_key_exists($type, LessonPeer::$fieldNames)) {
            throw new PropelException('Method getFieldNames() expects the parameter $type to be one of the class constants BasePeer::TYPE_PHPNAME, BasePeer::TYPE_STUDLYPHPNAME, BasePeer::TYPE_COLNAME, BasePeer::TYPE_FIELDNAME, BasePeer::TYPE_NUM. ' . $type . ' was given.');
        }

        return LessonPeer::$fieldNames[$type];
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
     * @param      string $column The column name for current table. (i.e. LessonPeer::COLUMN_NAME).
     * @return string
     */
    public static function alias($alias, $column)
    {
        return str_replace(LessonPeer::TABLE_NAME.'.', $alias.'.', $column);
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
            $criteria->addSelectColumn(LessonPeer::ID);
            $criteria->addSelectColumn(LessonPeer::COURSE_ID);
            $criteria->addSelectColumn(LessonPeer::TITLE);
            $criteria->addSelectColumn(LessonPeer::CREATED_AT);
            $criteria->addSelectColumn(LessonPeer::UPDATED_AT);
            $criteria->addSelectColumn(LessonPeer::SORTABLE_RANK);
        } else {
            $criteria->addSelectColumn($alias . '.ID');
            $criteria->addSelectColumn($alias . '.COURSE_ID');
            $criteria->addSelectColumn($alias . '.TITLE');
            $criteria->addSelectColumn($alias . '.CREATED_AT');
            $criteria->addSelectColumn($alias . '.UPDATED_AT');
            $criteria->addSelectColumn($alias . '.SORTABLE_RANK');
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
        $criteria->setPrimaryTableName(LessonPeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            LessonPeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY won't ever affect the count
        $criteria->setDbName(LessonPeer::DATABASE_NAME); // Set the correct dbName

        if ($con === null) {
            $con = Propel::getConnection(LessonPeer::DATABASE_NAME, Propel::CONNECTION_READ);
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
     * @return                 Lesson
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectOne(Criteria $criteria, PropelPDO $con = null)
    {
        $critcopy = clone $criteria;
        $critcopy->setLimit(1);
        $objects = LessonPeer::doSelect($critcopy, $con);
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
        return LessonPeer::populateObjects(LessonPeer::doSelectStmt($criteria, $con));
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
            $con = Propel::getConnection(LessonPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        if (!$criteria->hasSelectClause()) {
            $criteria = clone $criteria;
            LessonPeer::addSelectColumns($criteria);
        }

        // Set the correct dbName
        $criteria->setDbName(LessonPeer::DATABASE_NAME);

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
     * @param      Lesson $obj A Lesson object.
     * @param      string $key (optional) key to use for instance map (for performance boost if key was already calculated externally).
     */
    public static function addInstanceToPool($obj, $key = null)
    {
        if (Propel::isInstancePoolingEnabled()) {
            if ($key === null) {
                $key = (string) $obj->getId();
            } // if key === null
            LessonPeer::$instances[$key] = $obj;
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
     * @param      mixed $value A Lesson object or a primary key value.
     *
     * @return void
     * @throws PropelException - if the value is invalid.
     */
    public static function removeInstanceFromPool($value)
    {
        if (Propel::isInstancePoolingEnabled() && $value !== null) {
            if (is_object($value) && $value instanceof Lesson) {
                $key = (string) $value->getId();
            } elseif (is_scalar($value)) {
                // assume we've been passed a primary key
                $key = (string) $value;
            } else {
                $e = new PropelException("Invalid value passed to removeInstanceFromPool().  Expected primary key or Lesson object; got " . (is_object($value) ? get_class($value) . ' object.' : var_export($value,true)));
                throw $e;
            }

            unset(LessonPeer::$instances[$key]);
        }
    } // removeInstanceFromPool()

    /**
     * Retrieves a string version of the primary key from the DB resultset row that can be used to uniquely identify a row in this table.
     *
     * For tables with a single-column primary key, that simple pkey value will be returned.  For tables with
     * a multi-column primary key, a serialize()d version of the primary key will be returned.
     *
     * @param      string $key The key (@see getPrimaryKeyHash()) for this instance.
     * @return   Lesson Found object or null if 1) no instance exists for specified key or 2) instance pooling has been disabled.
     * @see        getPrimaryKeyHash()
     */
    public static function getInstanceFromPool($key)
    {
        if (Propel::isInstancePoolingEnabled()) {
            if (isset(LessonPeer::$instances[$key])) {
                return LessonPeer::$instances[$key];
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
        LessonPeer::$instances = array();
    }

    /**
     * Method to invalidate the instance pool of all tables related to lessons
     * by a foreign key with ON DELETE CASCADE
     */
    public static function clearRelatedInstancePool()
    {
        // Invalidate objects in UserLessonPeer instance pool,
        // since one or more of them may be deleted by ON DELETE CASCADE/SETNULL rule.
        UserLessonPeer::clearInstancePool();
        // Invalidate objects in LessonQuizPeer instance pool,
        // since one or more of them may be deleted by ON DELETE CASCADE/SETNULL rule.
        LessonQuizPeer::clearInstancePool();
        // Invalidate objects in LessonQuestionPeer instance pool,
        // since one or more of them may be deleted by ON DELETE CASCADE/SETNULL rule.
        LessonQuestionPeer::clearInstancePool();
        // Invalidate objects in LessonAnswerPeer instance pool,
        // since one or more of them may be deleted by ON DELETE CASCADE/SETNULL rule.
        LessonAnswerPeer::clearInstancePool();
        // Invalidate objects in ContentPeer instance pool,
        // since one or more of them may be deleted by ON DELETE CASCADE/SETNULL rule.
        ContentPeer::clearInstancePool();
        // Invalidate objects in TaskPeer instance pool,
        // since one or more of them may be deleted by ON DELETE CASCADE/SETNULL rule.
        TaskPeer::clearInstancePool();
        // Invalidate objects in UserTaskPeer instance pool,
        // since one or more of them may be deleted by ON DELETE CASCADE/SETNULL rule.
        UserTaskPeer::clearInstancePool();
        // Invalidate objects in TextContentPeer instance pool,
        // since one or more of them may be deleted by ON DELETE CASCADE/SETNULL rule.
        TextContentPeer::clearInstancePool();
        // Invalidate objects in UrlContentPeer instance pool,
        // since one or more of them may be deleted by ON DELETE CASCADE/SETNULL rule.
        UrlContentPeer::clearInstancePool();
        // Invalidate objects in YoutubeContentPeer instance pool,
        // since one or more of them may be deleted by ON DELETE CASCADE/SETNULL rule.
        YoutubeContentPeer::clearInstancePool();
        // Invalidate objects in SlideshareContentPeer instance pool,
        // since one or more of them may be deleted by ON DELETE CASCADE/SETNULL rule.
        SlideshareContentPeer::clearInstancePool();
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
        $cls = LessonPeer::getOMClass();
        // populate the object(s)
        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key = LessonPeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj = LessonPeer::getInstanceFromPool($key))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj->hydrate($row, 0, true); // rehydrate
                $results[] = $obj;
            } else {
                $obj = new $cls();
                $obj->hydrate($row);
                $results[] = $obj;
                LessonPeer::addInstanceToPool($obj, $key);
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
     * @return array (Lesson object, last column rank)
     */
    public static function populateObject($row, $startcol = 0)
    {
        $key = LessonPeer::getPrimaryKeyHashFromRow($row, $startcol);
        if (null !== ($obj = LessonPeer::getInstanceFromPool($key))) {
            // We no longer rehydrate the object, since this can cause data loss.
            // See http://www.propelorm.org/ticket/509
            // $obj->hydrate($row, $startcol, true); // rehydrate
            $col = $startcol + LessonPeer::NUM_HYDRATE_COLUMNS;
        } else {
            $cls = LessonPeer::OM_CLASS;
            $obj = new $cls();
            $col = $obj->hydrate($row, $startcol);
            LessonPeer::addInstanceToPool($obj, $key);
        }

        return array($obj, $col);
    }


    /**
     * Returns the number of rows matching criteria, joining the related Course table
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return int Number of matching rows.
     */
    public static function doCountJoinCourse(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        // we're going to modify criteria, so copy it first
        $criteria = clone $criteria;

        // We need to set the primary table name, since in the case that there are no WHERE columns
        // it will be impossible for the BasePeer::createSelectSql() method to determine which
        // tables go into the FROM clause.
        $criteria->setPrimaryTableName(LessonPeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            LessonPeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY won't ever affect the count

        // Set the correct dbName
        $criteria->setDbName(LessonPeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(LessonPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(LessonPeer::COURSE_ID, CoursePeer::ID, $join_behavior);

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
     * Selects a collection of Lesson objects pre-filled with their Course objects.
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of Lesson objects.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectJoinCourse(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $criteria = clone $criteria;

        // Set the correct dbName if it has not been overridden
        if ($criteria->getDbName() == Propel::getDefaultDB()) {
            $criteria->setDbName(LessonPeer::DATABASE_NAME);
        }

        LessonPeer::addSelectColumns($criteria);
        $startcol = LessonPeer::NUM_HYDRATE_COLUMNS;
        CoursePeer::addSelectColumns($criteria);

        $criteria->addJoin(LessonPeer::COURSE_ID, CoursePeer::ID, $join_behavior);

        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = LessonPeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = LessonPeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {

                $cls = LessonPeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                LessonPeer::addInstanceToPool($obj1, $key1);
            } // if $obj1 already loaded

            $key2 = CoursePeer::getPrimaryKeyHashFromRow($row, $startcol);
            if ($key2 !== null) {
                $obj2 = CoursePeer::getInstanceFromPool($key2);
                if (!$obj2) {

                    $cls = CoursePeer::getOMClass();

                    $obj2 = new $cls();
                    $obj2->hydrate($row, $startcol);
                    CoursePeer::addInstanceToPool($obj2, $key2);
                } // if obj2 already loaded

                // Add the $obj1 (Lesson) to $obj2 (Course)
                $obj2->addLesson($obj1);

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
        $criteria->setPrimaryTableName(LessonPeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            LessonPeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY won't ever affect the count

        // Set the correct dbName
        $criteria->setDbName(LessonPeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(LessonPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(LessonPeer::COURSE_ID, CoursePeer::ID, $join_behavior);

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
     * Selects a collection of Lesson objects pre-filled with all related objects.
     *
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of Lesson objects.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectJoinAll(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $criteria = clone $criteria;

        // Set the correct dbName if it has not been overridden
        if ($criteria->getDbName() == Propel::getDefaultDB()) {
            $criteria->setDbName(LessonPeer::DATABASE_NAME);
        }

        LessonPeer::addSelectColumns($criteria);
        $startcol2 = LessonPeer::NUM_HYDRATE_COLUMNS;

        CoursePeer::addSelectColumns($criteria);
        $startcol3 = $startcol2 + CoursePeer::NUM_HYDRATE_COLUMNS;

        $criteria->addJoin(LessonPeer::COURSE_ID, CoursePeer::ID, $join_behavior);

        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = LessonPeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = LessonPeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {
                $cls = LessonPeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                LessonPeer::addInstanceToPool($obj1, $key1);
            } // if obj1 already loaded

            // Add objects for joined Course rows

            $key2 = CoursePeer::getPrimaryKeyHashFromRow($row, $startcol2);
            if ($key2 !== null) {
                $obj2 = CoursePeer::getInstanceFromPool($key2);
                if (!$obj2) {

                    $cls = CoursePeer::getOMClass();

                    $obj2 = new $cls();
                    $obj2->hydrate($row, $startcol2);
                    CoursePeer::addInstanceToPool($obj2, $key2);
                } // if obj2 loaded

                // Add the $obj1 (Lesson) to the collection in $obj2 (Course)
                $obj2->addLesson($obj1);
            } // if joined row not null

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
        return Propel::getDatabaseMap(LessonPeer::DATABASE_NAME)->getTable(LessonPeer::TABLE_NAME);
    }

    /**
     * Add a TableMap instance to the database for this peer class.
     */
    public static function buildTableMap()
    {
      $dbMap = Propel::getDatabaseMap(BaseLessonPeer::DATABASE_NAME);
      if (!$dbMap->hasTable(BaseLessonPeer::TABLE_NAME)) {
        $dbMap->addTableObject(new LessonTableMap());
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
        return LessonPeer::OM_CLASS;
    }

    /**
     * Performs an INSERT on the database, given a Lesson or Criteria object.
     *
     * @param      mixed $values Criteria or Lesson object containing data that is used to create the INSERT statement.
     * @param      PropelPDO $con the PropelPDO connection to use
     * @return mixed           The new primary key.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doInsert($values, PropelPDO $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection(LessonPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        if ($values instanceof Criteria) {
            $criteria = clone $values; // rename for clarity
        } else {
            $criteria = $values->buildCriteria(); // build Criteria from Lesson object
        }

        if ($criteria->containsKey(LessonPeer::ID) && $criteria->keyContainsValue(LessonPeer::ID) ) {
            throw new PropelException('Cannot insert a value for auto-increment primary key ('.LessonPeer::ID.')');
        }


        // Set the correct dbName
        $criteria->setDbName(LessonPeer::DATABASE_NAME);

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
     * Performs an UPDATE on the database, given a Lesson or Criteria object.
     *
     * @param      mixed $values Criteria or Lesson object containing data that is used to create the UPDATE statement.
     * @param      PropelPDO $con The connection to use (specify PropelPDO connection object to exert more control over transactions).
     * @return int             The number of affected rows (if supported by underlying database driver).
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doUpdate($values, PropelPDO $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection(LessonPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        $selectCriteria = new Criteria(LessonPeer::DATABASE_NAME);

        if ($values instanceof Criteria) {
            $criteria = clone $values; // rename for clarity

            $comparison = $criteria->getComparison(LessonPeer::ID);
            $value = $criteria->remove(LessonPeer::ID);
            if ($value) {
                $selectCriteria->add(LessonPeer::ID, $value, $comparison);
            } else {
                $selectCriteria->setPrimaryTableName(LessonPeer::TABLE_NAME);
            }

        } else { // $values is Lesson object
            $criteria = $values->buildCriteria(); // gets full criteria
            $selectCriteria = $values->buildPkeyCriteria(); // gets criteria w/ primary key(s)
        }

        // set the correct dbName
        $criteria->setDbName(LessonPeer::DATABASE_NAME);

        return BasePeer::doUpdate($selectCriteria, $criteria, $con);
    }

    /**
     * Deletes all rows from the lessons table.
     *
     * @param      PropelPDO $con the connection to use
     * @return int             The number of affected rows (if supported by underlying database driver).
     * @throws PropelException
     */
    public static function doDeleteAll(PropelPDO $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection(LessonPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }
        $affectedRows = 0; // initialize var to track total num of affected rows
        try {
            // use transaction because $criteria could contain info
            // for more than one table or we could emulating ON DELETE CASCADE, etc.
            $con->beginTransaction();
            $affectedRows += LessonPeer::doOnDeleteCascade(new Criteria(LessonPeer::DATABASE_NAME), $con);
            $affectedRows += BasePeer::doDeleteAll(LessonPeer::TABLE_NAME, $con, LessonPeer::DATABASE_NAME);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            LessonPeer::clearInstancePool();
            LessonPeer::clearRelatedInstancePool();
            $con->commit();

            return $affectedRows;
        } catch (PropelException $e) {
            $con->rollBack();
            throw $e;
        }
    }

    /**
     * Performs a DELETE on the database, given a Lesson or Criteria object OR a primary key value.
     *
     * @param      mixed $values Criteria or Lesson object or primary key or array of primary keys
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
            $con = Propel::getConnection(LessonPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        if ($values instanceof Criteria) {
            // rename for clarity
            $criteria = clone $values;
        } elseif ($values instanceof Lesson) { // it's a model object
            // create criteria based on pk values
            $criteria = $values->buildPkeyCriteria();
        } else { // it's a primary key, or an array of pks
            $criteria = new Criteria(LessonPeer::DATABASE_NAME);
            $criteria->add(LessonPeer::ID, (array) $values, Criteria::IN);
        }

        // Set the correct dbName
        $criteria->setDbName(LessonPeer::DATABASE_NAME);

        $affectedRows = 0; // initialize var to track total num of affected rows

        try {
            // use transaction because $criteria could contain info
            // for more than one table or we could emulating ON DELETE CASCADE, etc.
            $con->beginTransaction();

            // cloning the Criteria in case it's modified by doSelect() or doSelectStmt()
            $c = clone $criteria;
            $affectedRows += LessonPeer::doOnDeleteCascade($c, $con);

            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            if ($values instanceof Criteria) {
                LessonPeer::clearInstancePool();
            } elseif ($values instanceof Lesson) { // it's a model object
                LessonPeer::removeInstanceFromPool($values);
            } else { // it's a primary key, or an array of pks
                foreach ((array) $values as $singleval) {
                    LessonPeer::removeInstanceFromPool($singleval);
                }
            }

            $affectedRows += BasePeer::doDelete($criteria, $con);
            LessonPeer::clearRelatedInstancePool();
            $con->commit();

            return $affectedRows;
        } catch (PropelException $e) {
            $con->rollBack();
            throw $e;
        }
    }

    /**
     * This is a method for emulating ON DELETE CASCADE for DBs that don't support this
     * feature (like MySQL or SQLite).
     *
     * This method is not very speedy because it must perform a query first to get
     * the implicated records and then perform the deletes by calling those Peer classes.
     *
     * This method should be used within a transaction if possible.
     *
     * @param      Criteria $criteria
     * @param      PropelPDO $con
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    protected static function doOnDeleteCascade(Criteria $criteria, PropelPDO $con)
    {
        // initialize var to track total num of affected rows
        $affectedRows = 0;

        // first find the objects that are implicated by the $criteria
        $objects = LessonPeer::doSelect($criteria, $con);
        foreach ($objects as $obj) {


            // delete related UserLesson objects
            $criteria = new Criteria(UserLessonPeer::DATABASE_NAME);

            $criteria->add(UserLessonPeer::LESSON_ID, $obj->getId());
            $affectedRows += UserLessonPeer::doDelete($criteria, $con);

            // delete related LessonQuiz objects
            $criteria = new Criteria(LessonQuizPeer::DATABASE_NAME);

            $criteria->add(LessonQuizPeer::LESSON_ID, $obj->getId());
            $affectedRows += LessonQuizPeer::doDelete($criteria, $con);

            // delete related LessonQuestion objects
            $criteria = new Criteria(LessonQuestionPeer::DATABASE_NAME);

            $criteria->add(LessonQuestionPeer::LESSON_ID, $obj->getId());
            $affectedRows += LessonQuestionPeer::doDelete($criteria, $con);

            // delete related LessonAnswer objects
            $criteria = new Criteria(LessonAnswerPeer::DATABASE_NAME);

            $criteria->add(LessonAnswerPeer::LESSON_ID, $obj->getId());
            $affectedRows += LessonAnswerPeer::doDelete($criteria, $con);

            // delete related Content objects
            $criteria = new Criteria(ContentPeer::DATABASE_NAME);

            $criteria->add(ContentPeer::LESSON_ID, $obj->getId());
            $affectedRows += ContentPeer::doDelete($criteria, $con);

            // delete related Task objects
            $criteria = new Criteria(TaskPeer::DATABASE_NAME);

            $criteria->add(TaskPeer::LESSON_ID, $obj->getId());
            $affectedRows += TaskPeer::doDelete($criteria, $con);

            // delete related UserTask objects
            $criteria = new Criteria(UserTaskPeer::DATABASE_NAME);

            $criteria->add(UserTaskPeer::LESSON_ID, $obj->getId());
            $affectedRows += UserTaskPeer::doDelete($criteria, $con);

            // delete related TextContent objects
            $criteria = new Criteria(TextContentPeer::DATABASE_NAME);

            $criteria->add(TextContentPeer::LESSON_ID, $obj->getId());
            $affectedRows += TextContentPeer::doDelete($criteria, $con);

            // delete related UrlContent objects
            $criteria = new Criteria(UrlContentPeer::DATABASE_NAME);

            $criteria->add(UrlContentPeer::LESSON_ID, $obj->getId());
            $affectedRows += UrlContentPeer::doDelete($criteria, $con);

            // delete related YoutubeContent objects
            $criteria = new Criteria(YoutubeContentPeer::DATABASE_NAME);

            $criteria->add(YoutubeContentPeer::LESSON_ID, $obj->getId());
            $affectedRows += YoutubeContentPeer::doDelete($criteria, $con);

            // delete related SlideshareContent objects
            $criteria = new Criteria(SlideshareContentPeer::DATABASE_NAME);

            $criteria->add(SlideshareContentPeer::LESSON_ID, $obj->getId());
            $affectedRows += SlideshareContentPeer::doDelete($criteria, $con);
        }

        return $affectedRows;
    }

    /**
     * Validates all modified columns of given Lesson object.
     * If parameter $columns is either a single column name or an array of column names
     * than only those columns are validated.
     *
     * NOTICE: This does not apply to primary or foreign keys for now.
     *
     * @param      Lesson $obj The object to validate.
     * @param      mixed $cols Column name or array of column names.
     *
     * @return mixed TRUE if all columns are valid or the error message of the first invalid column.
     */
    public static function doValidate($obj, $cols = null)
    {
        $columns = array();

        if ($cols) {
            $dbMap = Propel::getDatabaseMap(LessonPeer::DATABASE_NAME);
            $tableMap = $dbMap->getTable(LessonPeer::TABLE_NAME);

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

        return BasePeer::doValidate(LessonPeer::DATABASE_NAME, LessonPeer::TABLE_NAME, $columns);
    }

    /**
     * Retrieve a single object by pkey.
     *
     * @param      int $pk the primary key.
     * @param      PropelPDO $con the connection to use
     * @return Lesson
     */
    public static function retrieveByPK($pk, PropelPDO $con = null)
    {

        if (null !== ($obj = LessonPeer::getInstanceFromPool((string) $pk))) {
            return $obj;
        }

        if ($con === null) {
            $con = Propel::getConnection(LessonPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria = new Criteria(LessonPeer::DATABASE_NAME);
        $criteria->add(LessonPeer::ID, $pk);

        $v = LessonPeer::doSelect($criteria, $con);

        return !empty($v) > 0 ? $v[0] : null;
    }

    /**
     * Retrieve multiple objects by pkey.
     *
     * @param      array $pks List of primary keys
     * @param      PropelPDO $con the connection to use
     * @return Lesson[]
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function retrieveByPKs($pks, PropelPDO $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection(LessonPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $objs = null;
        if (empty($pks)) {
            $objs = array();
        } else {
            $criteria = new Criteria(LessonPeer::DATABASE_NAME);
            $criteria->add(LessonPeer::ID, $pks, Criteria::IN);
            $objs = LessonPeer::doSelect($criteria, $con);
        }

        return $objs;
    }

    // sortable behavior

    /**
     * Get the highest rank
     *
     * @param      int $scope		Scope to determine which suite to consider
     * @param     PropelPDO optional connection
     *
     * @return    integer highest position
     */
    public static function getMaxRank($scope = null, PropelPDO $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection(LessonPeer::DATABASE_NAME);
        }
        // shift the objects with a position lower than the one of object
        $c = new Criteria();
        $c->addSelectColumn('MAX(' . LessonPeer::RANK_COL . ')');
        $c->add(LessonPeer::SCOPE_COL, $scope, Criteria::EQUAL);
        $stmt = LessonPeer::doSelectStmt($c, $con);

        return $stmt->fetchColumn();
    }

    /**
     * Get an item from the list based on its rank
     *
     * @param     integer   $rank rank
     * @param      int $scope		Scope to determine which suite to consider
     * @param     PropelPDO $con optional connection
     *
     * @return Lesson
     */
    public static function retrieveByRank($rank, $scope = null, PropelPDO $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection(LessonPeer::DATABASE_NAME);
        }

        $c = new Criteria;
        $c->add(LessonPeer::RANK_COL, $rank);
        $c->add(LessonPeer::SCOPE_COL, $scope, Criteria::EQUAL);

        return LessonPeer::doSelectOne($c, $con);
    }

    /**
     * Reorder a set of sortable objects based on a list of id/position
     * Beware that there is no check made on the positions passed
     * So incoherent positions will result in an incoherent list
     *
     * @param     array     $order id => rank pairs
     * @param     PropelPDO $con   optional connection
     *
     * @return    boolean true if the reordering took place, false if a database problem prevented it
     */
    public static function reorder(array $order, PropelPDO $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection(LessonPeer::DATABASE_NAME);
        }

        $con->beginTransaction();
        try {
            $ids = array_keys($order);
            $objects = LessonPeer::retrieveByPKs($ids);
            foreach ($objects as $object) {
                $pk = $object->getPrimaryKey();
                if ($object->getSortableRank() != $order[$pk]) {
                    $object->setSortableRank($order[$pk]);
                    $object->save($con);
                }
            }
            $con->commit();

            return true;
        } catch (PropelException $e) {
            $con->rollback();
            throw $e;
        }
    }

    /**
     * Return an array of sortable objects ordered by position
     *
     * @param     Criteria  $criteria  optional criteria object
     * @param     string    $order     sorting order, to be chosen between Criteria::ASC (default) and Criteria::DESC
     * @param     PropelPDO $con       optional connection
     *
     * @return    array list of sortable objects
     */
    public static function doSelectOrderByRank(Criteria $criteria = null, $order = Criteria::ASC, PropelPDO $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection(LessonPeer::DATABASE_NAME);
        }

        if ($criteria === null) {
            $criteria = new Criteria();
        } elseif ($criteria instanceof Criteria) {
            $criteria = clone $criteria;
        }

        $criteria->clearOrderByColumns();

        if ($order == Criteria::ASC) {
            $criteria->addAscendingOrderByColumn(LessonPeer::RANK_COL);
        } else {
            $criteria->addDescendingOrderByColumn(LessonPeer::RANK_COL);
        }

        return LessonPeer::doSelect($criteria, $con);
    }

    /**
     * Return an array of sortable objects in the given scope ordered by position
     *
     * @param     int       $scope  the scope of the list
     * @param     string    $order  sorting order, to be chosen between Criteria::ASC (default) and Criteria::DESC
     * @param     PropelPDO $con    optional connection
     *
     * @return    array list of sortable objects
     */
    public static function retrieveList($scope, $order = Criteria::ASC, PropelPDO $con = null)
    {
        $c = new Criteria();
        $c->add(LessonPeer::SCOPE_COL, $scope);

        return LessonPeer::doSelectOrderByRank($c, $order, $con);
    }

    /**
     * Return the number of sortable objects in the given scope
     *
     * @param     int       $scope  the scope of the list
     * @param     PropelPDO $con    optional connection
     *
     * @return    array list of sortable objects
     */
    public static function countList($scope, PropelPDO $con = null)
    {
        $c = new Criteria();
        $c->add(LessonPeer::SCOPE_COL, $scope);

        return LessonPeer::doCount($c, $con);
    }

    /**
     * Deletes the sortable objects in the given scope
     *
     * @param     int       $scope  the scope of the list
     * @param     PropelPDO $con    optional connection
     *
     * @return    int number of deleted objects
     */
    public static function deleteList($scope, PropelPDO $con = null)
    {
        $c = new Criteria();
        $c->add(LessonPeer::SCOPE_COL, $scope);

        return LessonPeer::doDelete($c, $con);
    }

    /**
     * Adds $delta to all Rank values that are >= $first and <= $last.
     * '$delta' can also be negative.
     *
     * @param      int $delta Value to be shifted by, can be negative
     * @param      int $first First node to be shifted
     * @param      int $last  Last node to be shifted
     * @param      int $scope Scope to use for the shift
     * @param      PropelPDO $con Connection to use.
     */
    public static function shiftRank($delta, $first, $last = null, $scope = null, PropelPDO $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection(LessonPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        $whereCriteria = new Criteria(LessonPeer::DATABASE_NAME);
        $criterion = $whereCriteria->getNewCriterion(LessonPeer::RANK_COL, $first, Criteria::GREATER_EQUAL);
        if (null !== $last) {
            $criterion->addAnd($whereCriteria->getNewCriterion(LessonPeer::RANK_COL, $last, Criteria::LESS_EQUAL));
        }
        $whereCriteria->add($criterion);
        $whereCriteria->add(LessonPeer::SCOPE_COL, $scope, Criteria::EQUAL);

        $valuesCriteria = new Criteria(LessonPeer::DATABASE_NAME);
        $valuesCriteria->add(LessonPeer::RANK_COL, array('raw' => LessonPeer::RANK_COL . ' + ?', 'value' => $delta), Criteria::CUSTOM_EQUAL);

        BasePeer::doUpdate($whereCriteria, $valuesCriteria, $con);
        LessonPeer::clearInstancePool();
    }

} // BaseLessonPeer

// This is the static code needed to register the TableMap for this table with the main Propel class.
//
BaseLessonPeer::buildTableMap();

