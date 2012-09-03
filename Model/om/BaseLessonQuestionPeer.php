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
use Smirik\CourseBundle\Model\LessonAnswerPeer;
use Smirik\CourseBundle\Model\LessonPeer;
use Smirik\CourseBundle\Model\LessonQuestion;
use Smirik\CourseBundle\Model\LessonQuestionPeer;
use Smirik\CourseBundle\Model\map\LessonQuestionTableMap;

abstract class BaseLessonQuestionPeer
{

    /** the default database name for this class */
    const DATABASE_NAME = 'default';

    /** the table name for this class */
    const TABLE_NAME = 'lessons_questions';

    /** the related Propel class for this table */
    const OM_CLASS = 'Smirik\\CourseBundle\\Model\\LessonQuestion';

    /** the related TableMap class for this table */
    const TM_CLASS = 'LessonQuestionTableMap';

    /** The total number of columns. */
    const NUM_COLUMNS = 10;

    /** The number of lazy-loaded columns. */
    const NUM_LAZY_LOAD_COLUMNS = 0;

    /** The number of columns to hydrate (NUM_COLUMNS - NUM_LAZY_LOAD_COLUMNS) */
    const NUM_HYDRATE_COLUMNS = 10;

    /** the column name for the ID field */
    const ID = 'lessons_questions.ID';

    /** the column name for the USER_ID field */
    const USER_ID = 'lessons_questions.USER_ID';

    /** the column name for the LESSON_ID field */
    const LESSON_ID = 'lessons_questions.LESSON_ID';

    /** the column name for the TITLE field */
    const TITLE = 'lessons_questions.TITLE';

    /** the column name for the TEXT field */
    const TEXT = 'lessons_questions.TEXT';

    /** the column name for the IS_VISIBLE field */
    const IS_VISIBLE = 'lessons_questions.IS_VISIBLE';

    /** the column name for the IS_ANSWERED field */
    const IS_ANSWERED = 'lessons_questions.IS_ANSWERED';

    /** the column name for the CREATED_AT field */
    const CREATED_AT = 'lessons_questions.CREATED_AT';

    /** the column name for the UPDATED_AT field */
    const UPDATED_AT = 'lessons_questions.UPDATED_AT';

    /** the column name for the SORTABLE_RANK field */
    const SORTABLE_RANK = 'lessons_questions.SORTABLE_RANK';

    /** The default string format for model objects of the related table **/
    const DEFAULT_STRING_FORMAT = 'YAML';

    /**
     * An identiy map to hold any loaded instances of LessonQuestion objects.
     * This must be public so that other peer classes can access this when hydrating from JOIN
     * queries.
     * @var        array LessonQuestion[]
     */
    public static $instances = array();


    // sortable behavior

    /**
     * rank column
     */
    const RANK_COL = 'lessons_questions.SORTABLE_RANK';

    /**
     * Scope column for the set
     */
    const SCOPE_COL = 'lessons_questions.LESSON_ID';

    /**
     * holds an array of fieldnames
     *
     * first dimension keys are the type constants
     * e.g. LessonQuestionPeer::$fieldNames[LessonQuestionPeer::TYPE_PHPNAME][0] = 'Id'
     */
    protected static $fieldNames = array (
        BasePeer::TYPE_PHPNAME => array ('Id', 'UserId', 'LessonId', 'Title', 'Text', 'IsVisible', 'IsAnswered', 'CreatedAt', 'UpdatedAt', 'SortableRank', ),
        BasePeer::TYPE_STUDLYPHPNAME => array ('id', 'userId', 'lessonId', 'title', 'text', 'isVisible', 'isAnswered', 'createdAt', 'updatedAt', 'sortableRank', ),
        BasePeer::TYPE_COLNAME => array (LessonQuestionPeer::ID, LessonQuestionPeer::USER_ID, LessonQuestionPeer::LESSON_ID, LessonQuestionPeer::TITLE, LessonQuestionPeer::TEXT, LessonQuestionPeer::IS_VISIBLE, LessonQuestionPeer::IS_ANSWERED, LessonQuestionPeer::CREATED_AT, LessonQuestionPeer::UPDATED_AT, LessonQuestionPeer::SORTABLE_RANK, ),
        BasePeer::TYPE_RAW_COLNAME => array ('ID', 'USER_ID', 'LESSON_ID', 'TITLE', 'TEXT', 'IS_VISIBLE', 'IS_ANSWERED', 'CREATED_AT', 'UPDATED_AT', 'SORTABLE_RANK', ),
        BasePeer::TYPE_FIELDNAME => array ('id', 'user_id', 'lesson_id', 'title', 'text', 'is_visible', 'is_answered', 'created_at', 'updated_at', 'sortable_rank', ),
        BasePeer::TYPE_NUM => array (0, 1, 2, 3, 4, 5, 6, 7, 8, 9, )
    );

    /**
     * holds an array of keys for quick access to the fieldnames array
     *
     * first dimension keys are the type constants
     * e.g. LessonQuestionPeer::$fieldNames[BasePeer::TYPE_PHPNAME]['Id'] = 0
     */
    protected static $fieldKeys = array (
        BasePeer::TYPE_PHPNAME => array ('Id' => 0, 'UserId' => 1, 'LessonId' => 2, 'Title' => 3, 'Text' => 4, 'IsVisible' => 5, 'IsAnswered' => 6, 'CreatedAt' => 7, 'UpdatedAt' => 8, 'SortableRank' => 9, ),
        BasePeer::TYPE_STUDLYPHPNAME => array ('id' => 0, 'userId' => 1, 'lessonId' => 2, 'title' => 3, 'text' => 4, 'isVisible' => 5, 'isAnswered' => 6, 'createdAt' => 7, 'updatedAt' => 8, 'sortableRank' => 9, ),
        BasePeer::TYPE_COLNAME => array (LessonQuestionPeer::ID => 0, LessonQuestionPeer::USER_ID => 1, LessonQuestionPeer::LESSON_ID => 2, LessonQuestionPeer::TITLE => 3, LessonQuestionPeer::TEXT => 4, LessonQuestionPeer::IS_VISIBLE => 5, LessonQuestionPeer::IS_ANSWERED => 6, LessonQuestionPeer::CREATED_AT => 7, LessonQuestionPeer::UPDATED_AT => 8, LessonQuestionPeer::SORTABLE_RANK => 9, ),
        BasePeer::TYPE_RAW_COLNAME => array ('ID' => 0, 'USER_ID' => 1, 'LESSON_ID' => 2, 'TITLE' => 3, 'TEXT' => 4, 'IS_VISIBLE' => 5, 'IS_ANSWERED' => 6, 'CREATED_AT' => 7, 'UPDATED_AT' => 8, 'SORTABLE_RANK' => 9, ),
        BasePeer::TYPE_FIELDNAME => array ('id' => 0, 'user_id' => 1, 'lesson_id' => 2, 'title' => 3, 'text' => 4, 'is_visible' => 5, 'is_answered' => 6, 'created_at' => 7, 'updated_at' => 8, 'sortable_rank' => 9, ),
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
        $toNames = LessonQuestionPeer::getFieldNames($toType);
        $key = isset(LessonQuestionPeer::$fieldKeys[$fromType][$name]) ? LessonQuestionPeer::$fieldKeys[$fromType][$name] : null;
        if ($key === null) {
            throw new PropelException("'$name' could not be found in the field names of type '$fromType'. These are: " . print_r(LessonQuestionPeer::$fieldKeys[$fromType], true));
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
        if (!array_key_exists($type, LessonQuestionPeer::$fieldNames)) {
            throw new PropelException('Method getFieldNames() expects the parameter $type to be one of the class constants BasePeer::TYPE_PHPNAME, BasePeer::TYPE_STUDLYPHPNAME, BasePeer::TYPE_COLNAME, BasePeer::TYPE_FIELDNAME, BasePeer::TYPE_NUM. ' . $type . ' was given.');
        }

        return LessonQuestionPeer::$fieldNames[$type];
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
     * @param      string $column The column name for current table. (i.e. LessonQuestionPeer::COLUMN_NAME).
     * @return string
     */
    public static function alias($alias, $column)
    {
        return str_replace(LessonQuestionPeer::TABLE_NAME.'.', $alias.'.', $column);
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
            $criteria->addSelectColumn(LessonQuestionPeer::ID);
            $criteria->addSelectColumn(LessonQuestionPeer::USER_ID);
            $criteria->addSelectColumn(LessonQuestionPeer::LESSON_ID);
            $criteria->addSelectColumn(LessonQuestionPeer::TITLE);
            $criteria->addSelectColumn(LessonQuestionPeer::TEXT);
            $criteria->addSelectColumn(LessonQuestionPeer::IS_VISIBLE);
            $criteria->addSelectColumn(LessonQuestionPeer::IS_ANSWERED);
            $criteria->addSelectColumn(LessonQuestionPeer::CREATED_AT);
            $criteria->addSelectColumn(LessonQuestionPeer::UPDATED_AT);
            $criteria->addSelectColumn(LessonQuestionPeer::SORTABLE_RANK);
        } else {
            $criteria->addSelectColumn($alias . '.ID');
            $criteria->addSelectColumn($alias . '.USER_ID');
            $criteria->addSelectColumn($alias . '.LESSON_ID');
            $criteria->addSelectColumn($alias . '.TITLE');
            $criteria->addSelectColumn($alias . '.TEXT');
            $criteria->addSelectColumn($alias . '.IS_VISIBLE');
            $criteria->addSelectColumn($alias . '.IS_ANSWERED');
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
        $criteria->setPrimaryTableName(LessonQuestionPeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            LessonQuestionPeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY won't ever affect the count
        $criteria->setDbName(LessonQuestionPeer::DATABASE_NAME); // Set the correct dbName

        if ($con === null) {
            $con = Propel::getConnection(LessonQuestionPeer::DATABASE_NAME, Propel::CONNECTION_READ);
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
     * @return                 LessonQuestion
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectOne(Criteria $criteria, PropelPDO $con = null)
    {
        $critcopy = clone $criteria;
        $critcopy->setLimit(1);
        $objects = LessonQuestionPeer::doSelect($critcopy, $con);
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
        return LessonQuestionPeer::populateObjects(LessonQuestionPeer::doSelectStmt($criteria, $con));
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
            $con = Propel::getConnection(LessonQuestionPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        if (!$criteria->hasSelectClause()) {
            $criteria = clone $criteria;
            LessonQuestionPeer::addSelectColumns($criteria);
        }

        // Set the correct dbName
        $criteria->setDbName(LessonQuestionPeer::DATABASE_NAME);

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
     * @param      LessonQuestion $obj A LessonQuestion object.
     * @param      string $key (optional) key to use for instance map (for performance boost if key was already calculated externally).
     */
    public static function addInstanceToPool($obj, $key = null)
    {
        if (Propel::isInstancePoolingEnabled()) {
            if ($key === null) {
                $key = (string) $obj->getId();
            } // if key === null
            LessonQuestionPeer::$instances[$key] = $obj;
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
     * @param      mixed $value A LessonQuestion object or a primary key value.
     *
     * @return void
     * @throws PropelException - if the value is invalid.
     */
    public static function removeInstanceFromPool($value)
    {
        if (Propel::isInstancePoolingEnabled() && $value !== null) {
            if (is_object($value) && $value instanceof LessonQuestion) {
                $key = (string) $value->getId();
            } elseif (is_scalar($value)) {
                // assume we've been passed a primary key
                $key = (string) $value;
            } else {
                $e = new PropelException("Invalid value passed to removeInstanceFromPool().  Expected primary key or LessonQuestion object; got " . (is_object($value) ? get_class($value) . ' object.' : var_export($value,true)));
                throw $e;
            }

            unset(LessonQuestionPeer::$instances[$key]);
        }
    } // removeInstanceFromPool()

    /**
     * Retrieves a string version of the primary key from the DB resultset row that can be used to uniquely identify a row in this table.
     *
     * For tables with a single-column primary key, that simple pkey value will be returned.  For tables with
     * a multi-column primary key, a serialize()d version of the primary key will be returned.
     *
     * @param      string $key The key (@see getPrimaryKeyHash()) for this instance.
     * @return   LessonQuestion Found object or null if 1) no instance exists for specified key or 2) instance pooling has been disabled.
     * @see        getPrimaryKeyHash()
     */
    public static function getInstanceFromPool($key)
    {
        if (Propel::isInstancePoolingEnabled()) {
            if (isset(LessonQuestionPeer::$instances[$key])) {
                return LessonQuestionPeer::$instances[$key];
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
        LessonQuestionPeer::$instances = array();
    }

    /**
     * Method to invalidate the instance pool of all tables related to lessons_questions
     * by a foreign key with ON DELETE CASCADE
     */
    public static function clearRelatedInstancePool()
    {
        // Invalidate objects in LessonAnswerPeer instance pool,
        // since one or more of them may be deleted by ON DELETE CASCADE/SETNULL rule.
        LessonAnswerPeer::clearInstancePool();
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
        $cls = LessonQuestionPeer::getOMClass();
        // populate the object(s)
        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key = LessonQuestionPeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj = LessonQuestionPeer::getInstanceFromPool($key))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj->hydrate($row, 0, true); // rehydrate
                $results[] = $obj;
            } else {
                $obj = new $cls();
                $obj->hydrate($row);
                $results[] = $obj;
                LessonQuestionPeer::addInstanceToPool($obj, $key);
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
     * @return array (LessonQuestion object, last column rank)
     */
    public static function populateObject($row, $startcol = 0)
    {
        $key = LessonQuestionPeer::getPrimaryKeyHashFromRow($row, $startcol);
        if (null !== ($obj = LessonQuestionPeer::getInstanceFromPool($key))) {
            // We no longer rehydrate the object, since this can cause data loss.
            // See http://www.propelorm.org/ticket/509
            // $obj->hydrate($row, $startcol, true); // rehydrate
            $col = $startcol + LessonQuestionPeer::NUM_HYDRATE_COLUMNS;
        } else {
            $cls = LessonQuestionPeer::OM_CLASS;
            $obj = new $cls();
            $col = $obj->hydrate($row, $startcol);
            LessonQuestionPeer::addInstanceToPool($obj, $key);
        }

        return array($obj, $col);
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
        $criteria->setPrimaryTableName(LessonQuestionPeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            LessonQuestionPeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY won't ever affect the count

        // Set the correct dbName
        $criteria->setDbName(LessonQuestionPeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(LessonQuestionPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(LessonQuestionPeer::USER_ID, UserPeer::ID, $join_behavior);

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
        $criteria->setPrimaryTableName(LessonQuestionPeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            LessonQuestionPeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY won't ever affect the count

        // Set the correct dbName
        $criteria->setDbName(LessonQuestionPeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(LessonQuestionPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(LessonQuestionPeer::LESSON_ID, LessonPeer::ID, $join_behavior);

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
     * Selects a collection of LessonQuestion objects pre-filled with their User objects.
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of LessonQuestion objects.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectJoinUser(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $criteria = clone $criteria;

        // Set the correct dbName if it has not been overridden
        if ($criteria->getDbName() == Propel::getDefaultDB()) {
            $criteria->setDbName(LessonQuestionPeer::DATABASE_NAME);
        }

        LessonQuestionPeer::addSelectColumns($criteria);
        $startcol = LessonQuestionPeer::NUM_HYDRATE_COLUMNS;
        UserPeer::addSelectColumns($criteria);

        $criteria->addJoin(LessonQuestionPeer::USER_ID, UserPeer::ID, $join_behavior);

        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = LessonQuestionPeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = LessonQuestionPeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {

                $cls = LessonQuestionPeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                LessonQuestionPeer::addInstanceToPool($obj1, $key1);
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

                // Add the $obj1 (LessonQuestion) to $obj2 (User)
                $obj2->addLessonQuestion($obj1);

            } // if joined row was not null

            $results[] = $obj1;
        }
        $stmt->closeCursor();

        return $results;
    }


    /**
     * Selects a collection of LessonQuestion objects pre-filled with their Lesson objects.
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of LessonQuestion objects.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectJoinLesson(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $criteria = clone $criteria;

        // Set the correct dbName if it has not been overridden
        if ($criteria->getDbName() == Propel::getDefaultDB()) {
            $criteria->setDbName(LessonQuestionPeer::DATABASE_NAME);
        }

        LessonQuestionPeer::addSelectColumns($criteria);
        $startcol = LessonQuestionPeer::NUM_HYDRATE_COLUMNS;
        LessonPeer::addSelectColumns($criteria);

        $criteria->addJoin(LessonQuestionPeer::LESSON_ID, LessonPeer::ID, $join_behavior);

        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = LessonQuestionPeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = LessonQuestionPeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {

                $cls = LessonQuestionPeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                LessonQuestionPeer::addInstanceToPool($obj1, $key1);
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

                // Add the $obj1 (LessonQuestion) to $obj2 (Lesson)
                $obj2->addLessonQuestion($obj1);

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
        $criteria->setPrimaryTableName(LessonQuestionPeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            LessonQuestionPeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY won't ever affect the count

        // Set the correct dbName
        $criteria->setDbName(LessonQuestionPeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(LessonQuestionPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(LessonQuestionPeer::USER_ID, UserPeer::ID, $join_behavior);

        $criteria->addJoin(LessonQuestionPeer::LESSON_ID, LessonPeer::ID, $join_behavior);

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
     * Selects a collection of LessonQuestion objects pre-filled with all related objects.
     *
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of LessonQuestion objects.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectJoinAll(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $criteria = clone $criteria;

        // Set the correct dbName if it has not been overridden
        if ($criteria->getDbName() == Propel::getDefaultDB()) {
            $criteria->setDbName(LessonQuestionPeer::DATABASE_NAME);
        }

        LessonQuestionPeer::addSelectColumns($criteria);
        $startcol2 = LessonQuestionPeer::NUM_HYDRATE_COLUMNS;

        UserPeer::addSelectColumns($criteria);
        $startcol3 = $startcol2 + UserPeer::NUM_HYDRATE_COLUMNS;

        LessonPeer::addSelectColumns($criteria);
        $startcol4 = $startcol3 + LessonPeer::NUM_HYDRATE_COLUMNS;

        $criteria->addJoin(LessonQuestionPeer::USER_ID, UserPeer::ID, $join_behavior);

        $criteria->addJoin(LessonQuestionPeer::LESSON_ID, LessonPeer::ID, $join_behavior);

        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = LessonQuestionPeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = LessonQuestionPeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {
                $cls = LessonQuestionPeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                LessonQuestionPeer::addInstanceToPool($obj1, $key1);
            } // if obj1 already loaded

            // Add objects for joined User rows

            $key2 = UserPeer::getPrimaryKeyHashFromRow($row, $startcol2);
            if ($key2 !== null) {
                $obj2 = UserPeer::getInstanceFromPool($key2);
                if (!$obj2) {

                    $cls = UserPeer::getOMClass();

                    $obj2 = new $cls();
                    $obj2->hydrate($row, $startcol2);
                    UserPeer::addInstanceToPool($obj2, $key2);
                } // if obj2 loaded

                // Add the $obj1 (LessonQuestion) to the collection in $obj2 (User)
                $obj2->addLessonQuestion($obj1);
            } // if joined row not null

            // Add objects for joined Lesson rows

            $key3 = LessonPeer::getPrimaryKeyHashFromRow($row, $startcol3);
            if ($key3 !== null) {
                $obj3 = LessonPeer::getInstanceFromPool($key3);
                if (!$obj3) {

                    $cls = LessonPeer::getOMClass();

                    $obj3 = new $cls();
                    $obj3->hydrate($row, $startcol3);
                    LessonPeer::addInstanceToPool($obj3, $key3);
                } // if obj3 loaded

                // Add the $obj1 (LessonQuestion) to the collection in $obj3 (Lesson)
                $obj3->addLessonQuestion($obj1);
            } // if joined row not null

            $results[] = $obj1;
        }
        $stmt->closeCursor();

        return $results;
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
        $criteria->setPrimaryTableName(LessonQuestionPeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            LessonQuestionPeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY should not affect count

        // Set the correct dbName
        $criteria->setDbName(LessonQuestionPeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(LessonQuestionPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(LessonQuestionPeer::LESSON_ID, LessonPeer::ID, $join_behavior);

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
        $criteria->setPrimaryTableName(LessonQuestionPeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            LessonQuestionPeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY should not affect count

        // Set the correct dbName
        $criteria->setDbName(LessonQuestionPeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(LessonQuestionPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(LessonQuestionPeer::USER_ID, UserPeer::ID, $join_behavior);

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
     * Selects a collection of LessonQuestion objects pre-filled with all related objects except User.
     *
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of LessonQuestion objects.
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
            $criteria->setDbName(LessonQuestionPeer::DATABASE_NAME);
        }

        LessonQuestionPeer::addSelectColumns($criteria);
        $startcol2 = LessonQuestionPeer::NUM_HYDRATE_COLUMNS;

        LessonPeer::addSelectColumns($criteria);
        $startcol3 = $startcol2 + LessonPeer::NUM_HYDRATE_COLUMNS;

        $criteria->addJoin(LessonQuestionPeer::LESSON_ID, LessonPeer::ID, $join_behavior);


        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = LessonQuestionPeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = LessonQuestionPeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {
                $cls = LessonQuestionPeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                LessonQuestionPeer::addInstanceToPool($obj1, $key1);
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

                // Add the $obj1 (LessonQuestion) to the collection in $obj2 (Lesson)
                $obj2->addLessonQuestion($obj1);

            } // if joined row is not null

            $results[] = $obj1;
        }
        $stmt->closeCursor();

        return $results;
    }


    /**
     * Selects a collection of LessonQuestion objects pre-filled with all related objects except Lesson.
     *
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of LessonQuestion objects.
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
            $criteria->setDbName(LessonQuestionPeer::DATABASE_NAME);
        }

        LessonQuestionPeer::addSelectColumns($criteria);
        $startcol2 = LessonQuestionPeer::NUM_HYDRATE_COLUMNS;

        UserPeer::addSelectColumns($criteria);
        $startcol3 = $startcol2 + UserPeer::NUM_HYDRATE_COLUMNS;

        $criteria->addJoin(LessonQuestionPeer::USER_ID, UserPeer::ID, $join_behavior);


        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = LessonQuestionPeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = LessonQuestionPeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {
                $cls = LessonQuestionPeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                LessonQuestionPeer::addInstanceToPool($obj1, $key1);
            } // if obj1 already loaded

                // Add objects for joined User rows

                $key2 = UserPeer::getPrimaryKeyHashFromRow($row, $startcol2);
                if ($key2 !== null) {
                    $obj2 = UserPeer::getInstanceFromPool($key2);
                    if (!$obj2) {

                        $cls = UserPeer::getOMClass();

                    $obj2 = new $cls();
                    $obj2->hydrate($row, $startcol2);
                    UserPeer::addInstanceToPool($obj2, $key2);
                } // if $obj2 already loaded

                // Add the $obj1 (LessonQuestion) to the collection in $obj2 (User)
                $obj2->addLessonQuestion($obj1);

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
        return Propel::getDatabaseMap(LessonQuestionPeer::DATABASE_NAME)->getTable(LessonQuestionPeer::TABLE_NAME);
    }

    /**
     * Add a TableMap instance to the database for this peer class.
     */
    public static function buildTableMap()
    {
      $dbMap = Propel::getDatabaseMap(BaseLessonQuestionPeer::DATABASE_NAME);
      if (!$dbMap->hasTable(BaseLessonQuestionPeer::TABLE_NAME)) {
        $dbMap->addTableObject(new LessonQuestionTableMap());
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
        return LessonQuestionPeer::OM_CLASS;
    }

    /**
     * Performs an INSERT on the database, given a LessonQuestion or Criteria object.
     *
     * @param      mixed $values Criteria or LessonQuestion object containing data that is used to create the INSERT statement.
     * @param      PropelPDO $con the PropelPDO connection to use
     * @return mixed           The new primary key.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doInsert($values, PropelPDO $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection(LessonQuestionPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        if ($values instanceof Criteria) {
            $criteria = clone $values; // rename for clarity
        } else {
            $criteria = $values->buildCriteria(); // build Criteria from LessonQuestion object
        }

        if ($criteria->containsKey(LessonQuestionPeer::ID) && $criteria->keyContainsValue(LessonQuestionPeer::ID) ) {
            throw new PropelException('Cannot insert a value for auto-increment primary key ('.LessonQuestionPeer::ID.')');
        }


        // Set the correct dbName
        $criteria->setDbName(LessonQuestionPeer::DATABASE_NAME);

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
     * Performs an UPDATE on the database, given a LessonQuestion or Criteria object.
     *
     * @param      mixed $values Criteria or LessonQuestion object containing data that is used to create the UPDATE statement.
     * @param      PropelPDO $con The connection to use (specify PropelPDO connection object to exert more control over transactions).
     * @return int             The number of affected rows (if supported by underlying database driver).
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doUpdate($values, PropelPDO $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection(LessonQuestionPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        $selectCriteria = new Criteria(LessonQuestionPeer::DATABASE_NAME);

        if ($values instanceof Criteria) {
            $criteria = clone $values; // rename for clarity

            $comparison = $criteria->getComparison(LessonQuestionPeer::ID);
            $value = $criteria->remove(LessonQuestionPeer::ID);
            if ($value) {
                $selectCriteria->add(LessonQuestionPeer::ID, $value, $comparison);
            } else {
                $selectCriteria->setPrimaryTableName(LessonQuestionPeer::TABLE_NAME);
            }

        } else { // $values is LessonQuestion object
            $criteria = $values->buildCriteria(); // gets full criteria
            $selectCriteria = $values->buildPkeyCriteria(); // gets criteria w/ primary key(s)
        }

        // set the correct dbName
        $criteria->setDbName(LessonQuestionPeer::DATABASE_NAME);

        return BasePeer::doUpdate($selectCriteria, $criteria, $con);
    }

    /**
     * Deletes all rows from the lessons_questions table.
     *
     * @param      PropelPDO $con the connection to use
     * @return int             The number of affected rows (if supported by underlying database driver).
     * @throws PropelException
     */
    public static function doDeleteAll(PropelPDO $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection(LessonQuestionPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }
        $affectedRows = 0; // initialize var to track total num of affected rows
        try {
            // use transaction because $criteria could contain info
            // for more than one table or we could emulating ON DELETE CASCADE, etc.
            $con->beginTransaction();
            $affectedRows += LessonQuestionPeer::doOnDeleteCascade(new Criteria(LessonQuestionPeer::DATABASE_NAME), $con);
            $affectedRows += BasePeer::doDeleteAll(LessonQuestionPeer::TABLE_NAME, $con, LessonQuestionPeer::DATABASE_NAME);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            LessonQuestionPeer::clearInstancePool();
            LessonQuestionPeer::clearRelatedInstancePool();
            $con->commit();

            return $affectedRows;
        } catch (PropelException $e) {
            $con->rollBack();
            throw $e;
        }
    }

    /**
     * Performs a DELETE on the database, given a LessonQuestion or Criteria object OR a primary key value.
     *
     * @param      mixed $values Criteria or LessonQuestion object or primary key or array of primary keys
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
            $con = Propel::getConnection(LessonQuestionPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        if ($values instanceof Criteria) {
            // rename for clarity
            $criteria = clone $values;
        } elseif ($values instanceof LessonQuestion) { // it's a model object
            // create criteria based on pk values
            $criteria = $values->buildPkeyCriteria();
        } else { // it's a primary key, or an array of pks
            $criteria = new Criteria(LessonQuestionPeer::DATABASE_NAME);
            $criteria->add(LessonQuestionPeer::ID, (array) $values, Criteria::IN);
        }

        // Set the correct dbName
        $criteria->setDbName(LessonQuestionPeer::DATABASE_NAME);

        $affectedRows = 0; // initialize var to track total num of affected rows

        try {
            // use transaction because $criteria could contain info
            // for more than one table or we could emulating ON DELETE CASCADE, etc.
            $con->beginTransaction();

            // cloning the Criteria in case it's modified by doSelect() or doSelectStmt()
            $c = clone $criteria;
            $affectedRows += LessonQuestionPeer::doOnDeleteCascade($c, $con);

            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            if ($values instanceof Criteria) {
                LessonQuestionPeer::clearInstancePool();
            } elseif ($values instanceof LessonQuestion) { // it's a model object
                LessonQuestionPeer::removeInstanceFromPool($values);
            } else { // it's a primary key, or an array of pks
                foreach ((array) $values as $singleval) {
                    LessonQuestionPeer::removeInstanceFromPool($singleval);
                }
            }

            $affectedRows += BasePeer::doDelete($criteria, $con);
            LessonQuestionPeer::clearRelatedInstancePool();
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
        $objects = LessonQuestionPeer::doSelect($criteria, $con);
        foreach ($objects as $obj) {


            // delete related LessonAnswer objects
            $criteria = new Criteria(LessonAnswerPeer::DATABASE_NAME);

            $criteria->add(LessonAnswerPeer::QUESTION_ID, $obj->getId());
            $affectedRows += LessonAnswerPeer::doDelete($criteria, $con);
        }

        return $affectedRows;
    }

    /**
     * Validates all modified columns of given LessonQuestion object.
     * If parameter $columns is either a single column name or an array of column names
     * than only those columns are validated.
     *
     * NOTICE: This does not apply to primary or foreign keys for now.
     *
     * @param      LessonQuestion $obj The object to validate.
     * @param      mixed $cols Column name or array of column names.
     *
     * @return mixed TRUE if all columns are valid or the error message of the first invalid column.
     */
    public static function doValidate($obj, $cols = null)
    {
        $columns = array();

        if ($cols) {
            $dbMap = Propel::getDatabaseMap(LessonQuestionPeer::DATABASE_NAME);
            $tableMap = $dbMap->getTable(LessonQuestionPeer::TABLE_NAME);

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

        return BasePeer::doValidate(LessonQuestionPeer::DATABASE_NAME, LessonQuestionPeer::TABLE_NAME, $columns);
    }

    /**
     * Retrieve a single object by pkey.
     *
     * @param      int $pk the primary key.
     * @param      PropelPDO $con the connection to use
     * @return LessonQuestion
     */
    public static function retrieveByPK($pk, PropelPDO $con = null)
    {

        if (null !== ($obj = LessonQuestionPeer::getInstanceFromPool((string) $pk))) {
            return $obj;
        }

        if ($con === null) {
            $con = Propel::getConnection(LessonQuestionPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria = new Criteria(LessonQuestionPeer::DATABASE_NAME);
        $criteria->add(LessonQuestionPeer::ID, $pk);

        $v = LessonQuestionPeer::doSelect($criteria, $con);

        return !empty($v) > 0 ? $v[0] : null;
    }

    /**
     * Retrieve multiple objects by pkey.
     *
     * @param      array $pks List of primary keys
     * @param      PropelPDO $con the connection to use
     * @return LessonQuestion[]
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function retrieveByPKs($pks, PropelPDO $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection(LessonQuestionPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $objs = null;
        if (empty($pks)) {
            $objs = array();
        } else {
            $criteria = new Criteria(LessonQuestionPeer::DATABASE_NAME);
            $criteria->add(LessonQuestionPeer::ID, $pks, Criteria::IN);
            $objs = LessonQuestionPeer::doSelect($criteria, $con);
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
            $con = Propel::getConnection(LessonQuestionPeer::DATABASE_NAME);
        }
        // shift the objects with a position lower than the one of object
        $c = new Criteria();
        $c->addSelectColumn('MAX(' . LessonQuestionPeer::RANK_COL . ')');
        $c->add(LessonQuestionPeer::SCOPE_COL, $scope, Criteria::EQUAL);
        $stmt = LessonQuestionPeer::doSelectStmt($c, $con);

        return $stmt->fetchColumn();
    }

    /**
     * Get an item from the list based on its rank
     *
     * @param     integer   $rank rank
     * @param      int $scope		Scope to determine which suite to consider
     * @param     PropelPDO $con optional connection
     *
     * @return LessonQuestion
     */
    public static function retrieveByRank($rank, $scope = null, PropelPDO $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection(LessonQuestionPeer::DATABASE_NAME);
        }

        $c = new Criteria;
        $c->add(LessonQuestionPeer::RANK_COL, $rank);
        $c->add(LessonQuestionPeer::SCOPE_COL, $scope, Criteria::EQUAL);

        return LessonQuestionPeer::doSelectOne($c, $con);
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
            $con = Propel::getConnection(LessonQuestionPeer::DATABASE_NAME);
        }

        $con->beginTransaction();
        try {
            $ids = array_keys($order);
            $objects = LessonQuestionPeer::retrieveByPKs($ids);
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
            $con = Propel::getConnection(LessonQuestionPeer::DATABASE_NAME);
        }

        if ($criteria === null) {
            $criteria = new Criteria();
        } elseif ($criteria instanceof Criteria) {
            $criteria = clone $criteria;
        }

        $criteria->clearOrderByColumns();

        if ($order == Criteria::ASC) {
            $criteria->addAscendingOrderByColumn(LessonQuestionPeer::RANK_COL);
        } else {
            $criteria->addDescendingOrderByColumn(LessonQuestionPeer::RANK_COL);
        }

        return LessonQuestionPeer::doSelect($criteria, $con);
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
        $c->add(LessonQuestionPeer::SCOPE_COL, $scope);

        return LessonQuestionPeer::doSelectOrderByRank($c, $order, $con);
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
        $c->add(LessonQuestionPeer::SCOPE_COL, $scope);

        return LessonQuestionPeer::doCount($c, $con);
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
        $c->add(LessonQuestionPeer::SCOPE_COL, $scope);

        return LessonQuestionPeer::doDelete($c, $con);
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
            $con = Propel::getConnection(LessonQuestionPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        $whereCriteria = new Criteria(LessonQuestionPeer::DATABASE_NAME);
        $criterion = $whereCriteria->getNewCriterion(LessonQuestionPeer::RANK_COL, $first, Criteria::GREATER_EQUAL);
        if (null !== $last) {
            $criterion->addAnd($whereCriteria->getNewCriterion(LessonQuestionPeer::RANK_COL, $last, Criteria::LESS_EQUAL));
        }
        $whereCriteria->add($criterion);
        $whereCriteria->add(LessonQuestionPeer::SCOPE_COL, $scope, Criteria::EQUAL);

        $valuesCriteria = new Criteria(LessonQuestionPeer::DATABASE_NAME);
        $valuesCriteria->add(LessonQuestionPeer::RANK_COL, array('raw' => LessonQuestionPeer::RANK_COL . ' + ?', 'value' => $delta), Criteria::CUSTOM_EQUAL);

        BasePeer::doUpdate($whereCriteria, $valuesCriteria, $con);
        LessonQuestionPeer::clearInstancePool();
    }

} // BaseLessonQuestionPeer

// This is the static code needed to register the TableMap for this table with the main Propel class.
//
BaseLessonQuestionPeer::buildTableMap();

