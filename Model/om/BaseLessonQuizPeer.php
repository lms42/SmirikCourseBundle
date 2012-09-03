<?php

namespace Smirik\CourseBundle\Model\om;

use \BasePeer;
use \Criteria;
use \PDO;
use \PDOStatement;
use \Propel;
use \PropelException;
use \PropelPDO;
use Smirik\CourseBundle\Model\LessonPeer;
use Smirik\CourseBundle\Model\LessonQuiz;
use Smirik\CourseBundle\Model\LessonQuizPeer;
use Smirik\CourseBundle\Model\map\LessonQuizTableMap;
use Smirik\QuizBundle\Model\QuizPeer;

abstract class BaseLessonQuizPeer
{

    /** the default database name for this class */
    const DATABASE_NAME = 'default';

    /** the table name for this class */
    const TABLE_NAME = 'lessons_quizes';

    /** the related Propel class for this table */
    const OM_CLASS = 'Smirik\\CourseBundle\\Model\\LessonQuiz';

    /** the related TableMap class for this table */
    const TM_CLASS = 'LessonQuizTableMap';

    /** The total number of columns. */
    const NUM_COLUMNS = 4;

    /** The number of lazy-loaded columns. */
    const NUM_LAZY_LOAD_COLUMNS = 0;

    /** The number of columns to hydrate (NUM_COLUMNS - NUM_LAZY_LOAD_COLUMNS) */
    const NUM_HYDRATE_COLUMNS = 4;

    /** the column name for the ID field */
    const ID = 'lessons_quizes.ID';

    /** the column name for the LESSON_ID field */
    const LESSON_ID = 'lessons_quizes.LESSON_ID';

    /** the column name for the QUIZ_ID field */
    const QUIZ_ID = 'lessons_quizes.QUIZ_ID';

    /** the column name for the SORTABLE_RANK field */
    const SORTABLE_RANK = 'lessons_quizes.SORTABLE_RANK';

    /** The default string format for model objects of the related table **/
    const DEFAULT_STRING_FORMAT = 'YAML';

    /**
     * An identiy map to hold any loaded instances of LessonQuiz objects.
     * This must be public so that other peer classes can access this when hydrating from JOIN
     * queries.
     * @var        array LessonQuiz[]
     */
    public static $instances = array();


    // sortable behavior

    /**
     * rank column
     */
    const RANK_COL = 'lessons_quizes.SORTABLE_RANK';

    /**
     * Scope column for the set
     */
    const SCOPE_COL = 'lessons_quizes.LESSON_ID';

    /**
     * holds an array of fieldnames
     *
     * first dimension keys are the type constants
     * e.g. LessonQuizPeer::$fieldNames[LessonQuizPeer::TYPE_PHPNAME][0] = 'Id'
     */
    protected static $fieldNames = array (
        BasePeer::TYPE_PHPNAME => array ('Id', 'LessonId', 'QuizId', 'SortableRank', ),
        BasePeer::TYPE_STUDLYPHPNAME => array ('id', 'lessonId', 'quizId', 'sortableRank', ),
        BasePeer::TYPE_COLNAME => array (LessonQuizPeer::ID, LessonQuizPeer::LESSON_ID, LessonQuizPeer::QUIZ_ID, LessonQuizPeer::SORTABLE_RANK, ),
        BasePeer::TYPE_RAW_COLNAME => array ('ID', 'LESSON_ID', 'QUIZ_ID', 'SORTABLE_RANK', ),
        BasePeer::TYPE_FIELDNAME => array ('id', 'lesson_id', 'quiz_id', 'sortable_rank', ),
        BasePeer::TYPE_NUM => array (0, 1, 2, 3, )
    );

    /**
     * holds an array of keys for quick access to the fieldnames array
     *
     * first dimension keys are the type constants
     * e.g. LessonQuizPeer::$fieldNames[BasePeer::TYPE_PHPNAME]['Id'] = 0
     */
    protected static $fieldKeys = array (
        BasePeer::TYPE_PHPNAME => array ('Id' => 0, 'LessonId' => 1, 'QuizId' => 2, 'SortableRank' => 3, ),
        BasePeer::TYPE_STUDLYPHPNAME => array ('id' => 0, 'lessonId' => 1, 'quizId' => 2, 'sortableRank' => 3, ),
        BasePeer::TYPE_COLNAME => array (LessonQuizPeer::ID => 0, LessonQuizPeer::LESSON_ID => 1, LessonQuizPeer::QUIZ_ID => 2, LessonQuizPeer::SORTABLE_RANK => 3, ),
        BasePeer::TYPE_RAW_COLNAME => array ('ID' => 0, 'LESSON_ID' => 1, 'QUIZ_ID' => 2, 'SORTABLE_RANK' => 3, ),
        BasePeer::TYPE_FIELDNAME => array ('id' => 0, 'lesson_id' => 1, 'quiz_id' => 2, 'sortable_rank' => 3, ),
        BasePeer::TYPE_NUM => array (0, 1, 2, 3, )
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
        $toNames = LessonQuizPeer::getFieldNames($toType);
        $key = isset(LessonQuizPeer::$fieldKeys[$fromType][$name]) ? LessonQuizPeer::$fieldKeys[$fromType][$name] : null;
        if ($key === null) {
            throw new PropelException("'$name' could not be found in the field names of type '$fromType'. These are: " . print_r(LessonQuizPeer::$fieldKeys[$fromType], true));
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
        if (!array_key_exists($type, LessonQuizPeer::$fieldNames)) {
            throw new PropelException('Method getFieldNames() expects the parameter $type to be one of the class constants BasePeer::TYPE_PHPNAME, BasePeer::TYPE_STUDLYPHPNAME, BasePeer::TYPE_COLNAME, BasePeer::TYPE_FIELDNAME, BasePeer::TYPE_NUM. ' . $type . ' was given.');
        }

        return LessonQuizPeer::$fieldNames[$type];
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
     * @param      string $column The column name for current table. (i.e. LessonQuizPeer::COLUMN_NAME).
     * @return string
     */
    public static function alias($alias, $column)
    {
        return str_replace(LessonQuizPeer::TABLE_NAME.'.', $alias.'.', $column);
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
            $criteria->addSelectColumn(LessonQuizPeer::ID);
            $criteria->addSelectColumn(LessonQuizPeer::LESSON_ID);
            $criteria->addSelectColumn(LessonQuizPeer::QUIZ_ID);
            $criteria->addSelectColumn(LessonQuizPeer::SORTABLE_RANK);
        } else {
            $criteria->addSelectColumn($alias . '.ID');
            $criteria->addSelectColumn($alias . '.LESSON_ID');
            $criteria->addSelectColumn($alias . '.QUIZ_ID');
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
        $criteria->setPrimaryTableName(LessonQuizPeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            LessonQuizPeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY won't ever affect the count
        $criteria->setDbName(LessonQuizPeer::DATABASE_NAME); // Set the correct dbName

        if ($con === null) {
            $con = Propel::getConnection(LessonQuizPeer::DATABASE_NAME, Propel::CONNECTION_READ);
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
     * @return                 LessonQuiz
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectOne(Criteria $criteria, PropelPDO $con = null)
    {
        $critcopy = clone $criteria;
        $critcopy->setLimit(1);
        $objects = LessonQuizPeer::doSelect($critcopy, $con);
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
        return LessonQuizPeer::populateObjects(LessonQuizPeer::doSelectStmt($criteria, $con));
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
            $con = Propel::getConnection(LessonQuizPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        if (!$criteria->hasSelectClause()) {
            $criteria = clone $criteria;
            LessonQuizPeer::addSelectColumns($criteria);
        }

        // Set the correct dbName
        $criteria->setDbName(LessonQuizPeer::DATABASE_NAME);

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
     * @param      LessonQuiz $obj A LessonQuiz object.
     * @param      string $key (optional) key to use for instance map (for performance boost if key was already calculated externally).
     */
    public static function addInstanceToPool($obj, $key = null)
    {
        if (Propel::isInstancePoolingEnabled()) {
            if ($key === null) {
                $key = (string) $obj->getId();
            } // if key === null
            LessonQuizPeer::$instances[$key] = $obj;
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
     * @param      mixed $value A LessonQuiz object or a primary key value.
     *
     * @return void
     * @throws PropelException - if the value is invalid.
     */
    public static function removeInstanceFromPool($value)
    {
        if (Propel::isInstancePoolingEnabled() && $value !== null) {
            if (is_object($value) && $value instanceof LessonQuiz) {
                $key = (string) $value->getId();
            } elseif (is_scalar($value)) {
                // assume we've been passed a primary key
                $key = (string) $value;
            } else {
                $e = new PropelException("Invalid value passed to removeInstanceFromPool().  Expected primary key or LessonQuiz object; got " . (is_object($value) ? get_class($value) . ' object.' : var_export($value,true)));
                throw $e;
            }

            unset(LessonQuizPeer::$instances[$key]);
        }
    } // removeInstanceFromPool()

    /**
     * Retrieves a string version of the primary key from the DB resultset row that can be used to uniquely identify a row in this table.
     *
     * For tables with a single-column primary key, that simple pkey value will be returned.  For tables with
     * a multi-column primary key, a serialize()d version of the primary key will be returned.
     *
     * @param      string $key The key (@see getPrimaryKeyHash()) for this instance.
     * @return   LessonQuiz Found object or null if 1) no instance exists for specified key or 2) instance pooling has been disabled.
     * @see        getPrimaryKeyHash()
     */
    public static function getInstanceFromPool($key)
    {
        if (Propel::isInstancePoolingEnabled()) {
            if (isset(LessonQuizPeer::$instances[$key])) {
                return LessonQuizPeer::$instances[$key];
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
        LessonQuizPeer::$instances = array();
    }

    /**
     * Method to invalidate the instance pool of all tables related to lessons_quizes
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
        $cls = LessonQuizPeer::getOMClass();
        // populate the object(s)
        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key = LessonQuizPeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj = LessonQuizPeer::getInstanceFromPool($key))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj->hydrate($row, 0, true); // rehydrate
                $results[] = $obj;
            } else {
                $obj = new $cls();
                $obj->hydrate($row);
                $results[] = $obj;
                LessonQuizPeer::addInstanceToPool($obj, $key);
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
     * @return array (LessonQuiz object, last column rank)
     */
    public static function populateObject($row, $startcol = 0)
    {
        $key = LessonQuizPeer::getPrimaryKeyHashFromRow($row, $startcol);
        if (null !== ($obj = LessonQuizPeer::getInstanceFromPool($key))) {
            // We no longer rehydrate the object, since this can cause data loss.
            // See http://www.propelorm.org/ticket/509
            // $obj->hydrate($row, $startcol, true); // rehydrate
            $col = $startcol + LessonQuizPeer::NUM_HYDRATE_COLUMNS;
        } else {
            $cls = LessonQuizPeer::OM_CLASS;
            $obj = new $cls();
            $col = $obj->hydrate($row, $startcol);
            LessonQuizPeer::addInstanceToPool($obj, $key);
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
        $criteria->setPrimaryTableName(LessonQuizPeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            LessonQuizPeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY won't ever affect the count

        // Set the correct dbName
        $criteria->setDbName(LessonQuizPeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(LessonQuizPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(LessonQuizPeer::LESSON_ID, LessonPeer::ID, $join_behavior);

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
     * Returns the number of rows matching criteria, joining the related Quiz table
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return int Number of matching rows.
     */
    public static function doCountJoinQuiz(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        // we're going to modify criteria, so copy it first
        $criteria = clone $criteria;

        // We need to set the primary table name, since in the case that there are no WHERE columns
        // it will be impossible for the BasePeer::createSelectSql() method to determine which
        // tables go into the FROM clause.
        $criteria->setPrimaryTableName(LessonQuizPeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            LessonQuizPeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY won't ever affect the count

        // Set the correct dbName
        $criteria->setDbName(LessonQuizPeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(LessonQuizPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(LessonQuizPeer::QUIZ_ID, QuizPeer::ID, $join_behavior);

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
     * Selects a collection of LessonQuiz objects pre-filled with their Lesson objects.
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of LessonQuiz objects.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectJoinLesson(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $criteria = clone $criteria;

        // Set the correct dbName if it has not been overridden
        if ($criteria->getDbName() == Propel::getDefaultDB()) {
            $criteria->setDbName(LessonQuizPeer::DATABASE_NAME);
        }

        LessonQuizPeer::addSelectColumns($criteria);
        $startcol = LessonQuizPeer::NUM_HYDRATE_COLUMNS;
        LessonPeer::addSelectColumns($criteria);

        $criteria->addJoin(LessonQuizPeer::LESSON_ID, LessonPeer::ID, $join_behavior);

        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = LessonQuizPeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = LessonQuizPeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {

                $cls = LessonQuizPeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                LessonQuizPeer::addInstanceToPool($obj1, $key1);
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

                // Add the $obj1 (LessonQuiz) to $obj2 (Lesson)
                $obj2->addLessonQuiz($obj1);

            } // if joined row was not null

            $results[] = $obj1;
        }
        $stmt->closeCursor();

        return $results;
    }


    /**
     * Selects a collection of LessonQuiz objects pre-filled with their Quiz objects.
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of LessonQuiz objects.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectJoinQuiz(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $criteria = clone $criteria;

        // Set the correct dbName if it has not been overridden
        if ($criteria->getDbName() == Propel::getDefaultDB()) {
            $criteria->setDbName(LessonQuizPeer::DATABASE_NAME);
        }

        LessonQuizPeer::addSelectColumns($criteria);
        $startcol = LessonQuizPeer::NUM_HYDRATE_COLUMNS;
        QuizPeer::addSelectColumns($criteria);

        $criteria->addJoin(LessonQuizPeer::QUIZ_ID, QuizPeer::ID, $join_behavior);

        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = LessonQuizPeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = LessonQuizPeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {

                $cls = LessonQuizPeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                LessonQuizPeer::addInstanceToPool($obj1, $key1);
            } // if $obj1 already loaded

            $key2 = QuizPeer::getPrimaryKeyHashFromRow($row, $startcol);
            if ($key2 !== null) {
                $obj2 = QuizPeer::getInstanceFromPool($key2);
                if (!$obj2) {

                    $cls = QuizPeer::getOMClass();

                    $obj2 = new $cls();
                    $obj2->hydrate($row, $startcol);
                    QuizPeer::addInstanceToPool($obj2, $key2);
                } // if obj2 already loaded

                // Add the $obj1 (LessonQuiz) to $obj2 (Quiz)
                $obj2->addLessonQuiz($obj1);

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
        $criteria->setPrimaryTableName(LessonQuizPeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            LessonQuizPeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY won't ever affect the count

        // Set the correct dbName
        $criteria->setDbName(LessonQuizPeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(LessonQuizPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(LessonQuizPeer::LESSON_ID, LessonPeer::ID, $join_behavior);

        $criteria->addJoin(LessonQuizPeer::QUIZ_ID, QuizPeer::ID, $join_behavior);

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
     * Selects a collection of LessonQuiz objects pre-filled with all related objects.
     *
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of LessonQuiz objects.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectJoinAll(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $criteria = clone $criteria;

        // Set the correct dbName if it has not been overridden
        if ($criteria->getDbName() == Propel::getDefaultDB()) {
            $criteria->setDbName(LessonQuizPeer::DATABASE_NAME);
        }

        LessonQuizPeer::addSelectColumns($criteria);
        $startcol2 = LessonQuizPeer::NUM_HYDRATE_COLUMNS;

        LessonPeer::addSelectColumns($criteria);
        $startcol3 = $startcol2 + LessonPeer::NUM_HYDRATE_COLUMNS;

        QuizPeer::addSelectColumns($criteria);
        $startcol4 = $startcol3 + QuizPeer::NUM_HYDRATE_COLUMNS;

        $criteria->addJoin(LessonQuizPeer::LESSON_ID, LessonPeer::ID, $join_behavior);

        $criteria->addJoin(LessonQuizPeer::QUIZ_ID, QuizPeer::ID, $join_behavior);

        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = LessonQuizPeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = LessonQuizPeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {
                $cls = LessonQuizPeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                LessonQuizPeer::addInstanceToPool($obj1, $key1);
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

                // Add the $obj1 (LessonQuiz) to the collection in $obj2 (Lesson)
                $obj2->addLessonQuiz($obj1);
            } // if joined row not null

            // Add objects for joined Quiz rows

            $key3 = QuizPeer::getPrimaryKeyHashFromRow($row, $startcol3);
            if ($key3 !== null) {
                $obj3 = QuizPeer::getInstanceFromPool($key3);
                if (!$obj3) {

                    $cls = QuizPeer::getOMClass();

                    $obj3 = new $cls();
                    $obj3->hydrate($row, $startcol3);
                    QuizPeer::addInstanceToPool($obj3, $key3);
                } // if obj3 loaded

                // Add the $obj1 (LessonQuiz) to the collection in $obj3 (Quiz)
                $obj3->addLessonQuiz($obj1);
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
        $criteria->setPrimaryTableName(LessonQuizPeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            LessonQuizPeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY should not affect count

        // Set the correct dbName
        $criteria->setDbName(LessonQuizPeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(LessonQuizPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(LessonQuizPeer::QUIZ_ID, QuizPeer::ID, $join_behavior);

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
     * Returns the number of rows matching criteria, joining the related Quiz table
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return int Number of matching rows.
     */
    public static function doCountJoinAllExceptQuiz(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        // we're going to modify criteria, so copy it first
        $criteria = clone $criteria;

        // We need to set the primary table name, since in the case that there are no WHERE columns
        // it will be impossible for the BasePeer::createSelectSql() method to determine which
        // tables go into the FROM clause.
        $criteria->setPrimaryTableName(LessonQuizPeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            LessonQuizPeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY should not affect count

        // Set the correct dbName
        $criteria->setDbName(LessonQuizPeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(LessonQuizPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(LessonQuizPeer::LESSON_ID, LessonPeer::ID, $join_behavior);

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
     * Selects a collection of LessonQuiz objects pre-filled with all related objects except Lesson.
     *
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of LessonQuiz objects.
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
            $criteria->setDbName(LessonQuizPeer::DATABASE_NAME);
        }

        LessonQuizPeer::addSelectColumns($criteria);
        $startcol2 = LessonQuizPeer::NUM_HYDRATE_COLUMNS;

        QuizPeer::addSelectColumns($criteria);
        $startcol3 = $startcol2 + QuizPeer::NUM_HYDRATE_COLUMNS;

        $criteria->addJoin(LessonQuizPeer::QUIZ_ID, QuizPeer::ID, $join_behavior);


        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = LessonQuizPeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = LessonQuizPeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {
                $cls = LessonQuizPeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                LessonQuizPeer::addInstanceToPool($obj1, $key1);
            } // if obj1 already loaded

                // Add objects for joined Quiz rows

                $key2 = QuizPeer::getPrimaryKeyHashFromRow($row, $startcol2);
                if ($key2 !== null) {
                    $obj2 = QuizPeer::getInstanceFromPool($key2);
                    if (!$obj2) {

                        $cls = QuizPeer::getOMClass();

                    $obj2 = new $cls();
                    $obj2->hydrate($row, $startcol2);
                    QuizPeer::addInstanceToPool($obj2, $key2);
                } // if $obj2 already loaded

                // Add the $obj1 (LessonQuiz) to the collection in $obj2 (Quiz)
                $obj2->addLessonQuiz($obj1);

            } // if joined row is not null

            $results[] = $obj1;
        }
        $stmt->closeCursor();

        return $results;
    }


    /**
     * Selects a collection of LessonQuiz objects pre-filled with all related objects except Quiz.
     *
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of LessonQuiz objects.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectJoinAllExceptQuiz(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $criteria = clone $criteria;

        // Set the correct dbName if it has not been overridden
        // $criteria->getDbName() will return the same object if not set to another value
        // so == check is okay and faster
        if ($criteria->getDbName() == Propel::getDefaultDB()) {
            $criteria->setDbName(LessonQuizPeer::DATABASE_NAME);
        }

        LessonQuizPeer::addSelectColumns($criteria);
        $startcol2 = LessonQuizPeer::NUM_HYDRATE_COLUMNS;

        LessonPeer::addSelectColumns($criteria);
        $startcol3 = $startcol2 + LessonPeer::NUM_HYDRATE_COLUMNS;

        $criteria->addJoin(LessonQuizPeer::LESSON_ID, LessonPeer::ID, $join_behavior);


        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = LessonQuizPeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = LessonQuizPeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {
                $cls = LessonQuizPeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                LessonQuizPeer::addInstanceToPool($obj1, $key1);
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

                // Add the $obj1 (LessonQuiz) to the collection in $obj2 (Lesson)
                $obj2->addLessonQuiz($obj1);

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
        return Propel::getDatabaseMap(LessonQuizPeer::DATABASE_NAME)->getTable(LessonQuizPeer::TABLE_NAME);
    }

    /**
     * Add a TableMap instance to the database for this peer class.
     */
    public static function buildTableMap()
    {
      $dbMap = Propel::getDatabaseMap(BaseLessonQuizPeer::DATABASE_NAME);
      if (!$dbMap->hasTable(BaseLessonQuizPeer::TABLE_NAME)) {
        $dbMap->addTableObject(new LessonQuizTableMap());
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
        return LessonQuizPeer::OM_CLASS;
    }

    /**
     * Performs an INSERT on the database, given a LessonQuiz or Criteria object.
     *
     * @param      mixed $values Criteria or LessonQuiz object containing data that is used to create the INSERT statement.
     * @param      PropelPDO $con the PropelPDO connection to use
     * @return mixed           The new primary key.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doInsert($values, PropelPDO $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection(LessonQuizPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        if ($values instanceof Criteria) {
            $criteria = clone $values; // rename for clarity
        } else {
            $criteria = $values->buildCriteria(); // build Criteria from LessonQuiz object
        }

        if ($criteria->containsKey(LessonQuizPeer::ID) && $criteria->keyContainsValue(LessonQuizPeer::ID) ) {
            throw new PropelException('Cannot insert a value for auto-increment primary key ('.LessonQuizPeer::ID.')');
        }


        // Set the correct dbName
        $criteria->setDbName(LessonQuizPeer::DATABASE_NAME);

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
     * Performs an UPDATE on the database, given a LessonQuiz or Criteria object.
     *
     * @param      mixed $values Criteria or LessonQuiz object containing data that is used to create the UPDATE statement.
     * @param      PropelPDO $con The connection to use (specify PropelPDO connection object to exert more control over transactions).
     * @return int             The number of affected rows (if supported by underlying database driver).
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doUpdate($values, PropelPDO $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection(LessonQuizPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        $selectCriteria = new Criteria(LessonQuizPeer::DATABASE_NAME);

        if ($values instanceof Criteria) {
            $criteria = clone $values; // rename for clarity

            $comparison = $criteria->getComparison(LessonQuizPeer::ID);
            $value = $criteria->remove(LessonQuizPeer::ID);
            if ($value) {
                $selectCriteria->add(LessonQuizPeer::ID, $value, $comparison);
            } else {
                $selectCriteria->setPrimaryTableName(LessonQuizPeer::TABLE_NAME);
            }

        } else { // $values is LessonQuiz object
            $criteria = $values->buildCriteria(); // gets full criteria
            $selectCriteria = $values->buildPkeyCriteria(); // gets criteria w/ primary key(s)
        }

        // set the correct dbName
        $criteria->setDbName(LessonQuizPeer::DATABASE_NAME);

        return BasePeer::doUpdate($selectCriteria, $criteria, $con);
    }

    /**
     * Deletes all rows from the lessons_quizes table.
     *
     * @param      PropelPDO $con the connection to use
     * @return int             The number of affected rows (if supported by underlying database driver).
     * @throws PropelException
     */
    public static function doDeleteAll(PropelPDO $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection(LessonQuizPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }
        $affectedRows = 0; // initialize var to track total num of affected rows
        try {
            // use transaction because $criteria could contain info
            // for more than one table or we could emulating ON DELETE CASCADE, etc.
            $con->beginTransaction();
            $affectedRows += BasePeer::doDeleteAll(LessonQuizPeer::TABLE_NAME, $con, LessonQuizPeer::DATABASE_NAME);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            LessonQuizPeer::clearInstancePool();
            LessonQuizPeer::clearRelatedInstancePool();
            $con->commit();

            return $affectedRows;
        } catch (PropelException $e) {
            $con->rollBack();
            throw $e;
        }
    }

    /**
     * Performs a DELETE on the database, given a LessonQuiz or Criteria object OR a primary key value.
     *
     * @param      mixed $values Criteria or LessonQuiz object or primary key or array of primary keys
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
            $con = Propel::getConnection(LessonQuizPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        if ($values instanceof Criteria) {
            // invalidate the cache for all objects of this type, since we have no
            // way of knowing (without running a query) what objects should be invalidated
            // from the cache based on this Criteria.
            LessonQuizPeer::clearInstancePool();
            // rename for clarity
            $criteria = clone $values;
        } elseif ($values instanceof LessonQuiz) { // it's a model object
            // invalidate the cache for this single object
            LessonQuizPeer::removeInstanceFromPool($values);
            // create criteria based on pk values
            $criteria = $values->buildPkeyCriteria();
        } else { // it's a primary key, or an array of pks
            $criteria = new Criteria(LessonQuizPeer::DATABASE_NAME);
            $criteria->add(LessonQuizPeer::ID, (array) $values, Criteria::IN);
            // invalidate the cache for this object(s)
            foreach ((array) $values as $singleval) {
                LessonQuizPeer::removeInstanceFromPool($singleval);
            }
        }

        // Set the correct dbName
        $criteria->setDbName(LessonQuizPeer::DATABASE_NAME);

        $affectedRows = 0; // initialize var to track total num of affected rows

        try {
            // use transaction because $criteria could contain info
            // for more than one table or we could emulating ON DELETE CASCADE, etc.
            $con->beginTransaction();

            $affectedRows += BasePeer::doDelete($criteria, $con);
            LessonQuizPeer::clearRelatedInstancePool();
            $con->commit();

            return $affectedRows;
        } catch (PropelException $e) {
            $con->rollBack();
            throw $e;
        }
    }

    /**
     * Validates all modified columns of given LessonQuiz object.
     * If parameter $columns is either a single column name or an array of column names
     * than only those columns are validated.
     *
     * NOTICE: This does not apply to primary or foreign keys for now.
     *
     * @param      LessonQuiz $obj The object to validate.
     * @param      mixed $cols Column name or array of column names.
     *
     * @return mixed TRUE if all columns are valid or the error message of the first invalid column.
     */
    public static function doValidate($obj, $cols = null)
    {
        $columns = array();

        if ($cols) {
            $dbMap = Propel::getDatabaseMap(LessonQuizPeer::DATABASE_NAME);
            $tableMap = $dbMap->getTable(LessonQuizPeer::TABLE_NAME);

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

        return BasePeer::doValidate(LessonQuizPeer::DATABASE_NAME, LessonQuizPeer::TABLE_NAME, $columns);
    }

    /**
     * Retrieve a single object by pkey.
     *
     * @param      int $pk the primary key.
     * @param      PropelPDO $con the connection to use
     * @return LessonQuiz
     */
    public static function retrieveByPK($pk, PropelPDO $con = null)
    {

        if (null !== ($obj = LessonQuizPeer::getInstanceFromPool((string) $pk))) {
            return $obj;
        }

        if ($con === null) {
            $con = Propel::getConnection(LessonQuizPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria = new Criteria(LessonQuizPeer::DATABASE_NAME);
        $criteria->add(LessonQuizPeer::ID, $pk);

        $v = LessonQuizPeer::doSelect($criteria, $con);

        return !empty($v) > 0 ? $v[0] : null;
    }

    /**
     * Retrieve multiple objects by pkey.
     *
     * @param      array $pks List of primary keys
     * @param      PropelPDO $con the connection to use
     * @return LessonQuiz[]
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function retrieveByPKs($pks, PropelPDO $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection(LessonQuizPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $objs = null;
        if (empty($pks)) {
            $objs = array();
        } else {
            $criteria = new Criteria(LessonQuizPeer::DATABASE_NAME);
            $criteria->add(LessonQuizPeer::ID, $pks, Criteria::IN);
            $objs = LessonQuizPeer::doSelect($criteria, $con);
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
            $con = Propel::getConnection(LessonQuizPeer::DATABASE_NAME);
        }
        // shift the objects with a position lower than the one of object
        $c = new Criteria();
        $c->addSelectColumn('MAX(' . LessonQuizPeer::RANK_COL . ')');
        $c->add(LessonQuizPeer::SCOPE_COL, $scope, Criteria::EQUAL);
        $stmt = LessonQuizPeer::doSelectStmt($c, $con);

        return $stmt->fetchColumn();
    }

    /**
     * Get an item from the list based on its rank
     *
     * @param     integer   $rank rank
     * @param      int $scope		Scope to determine which suite to consider
     * @param     PropelPDO $con optional connection
     *
     * @return LessonQuiz
     */
    public static function retrieveByRank($rank, $scope = null, PropelPDO $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection(LessonQuizPeer::DATABASE_NAME);
        }

        $c = new Criteria;
        $c->add(LessonQuizPeer::RANK_COL, $rank);
        $c->add(LessonQuizPeer::SCOPE_COL, $scope, Criteria::EQUAL);

        return LessonQuizPeer::doSelectOne($c, $con);
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
            $con = Propel::getConnection(LessonQuizPeer::DATABASE_NAME);
        }

        $con->beginTransaction();
        try {
            $ids = array_keys($order);
            $objects = LessonQuizPeer::retrieveByPKs($ids);
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
            $con = Propel::getConnection(LessonQuizPeer::DATABASE_NAME);
        }

        if ($criteria === null) {
            $criteria = new Criteria();
        } elseif ($criteria instanceof Criteria) {
            $criteria = clone $criteria;
        }

        $criteria->clearOrderByColumns();

        if ($order == Criteria::ASC) {
            $criteria->addAscendingOrderByColumn(LessonQuizPeer::RANK_COL);
        } else {
            $criteria->addDescendingOrderByColumn(LessonQuizPeer::RANK_COL);
        }

        return LessonQuizPeer::doSelect($criteria, $con);
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
        $c->add(LessonQuizPeer::SCOPE_COL, $scope);

        return LessonQuizPeer::doSelectOrderByRank($c, $order, $con);
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
        $c->add(LessonQuizPeer::SCOPE_COL, $scope);

        return LessonQuizPeer::doCount($c, $con);
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
        $c->add(LessonQuizPeer::SCOPE_COL, $scope);

        return LessonQuizPeer::doDelete($c, $con);
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
            $con = Propel::getConnection(LessonQuizPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        $whereCriteria = new Criteria(LessonQuizPeer::DATABASE_NAME);
        $criterion = $whereCriteria->getNewCriterion(LessonQuizPeer::RANK_COL, $first, Criteria::GREATER_EQUAL);
        if (null !== $last) {
            $criterion->addAnd($whereCriteria->getNewCriterion(LessonQuizPeer::RANK_COL, $last, Criteria::LESS_EQUAL));
        }
        $whereCriteria->add($criterion);
        $whereCriteria->add(LessonQuizPeer::SCOPE_COL, $scope, Criteria::EQUAL);

        $valuesCriteria = new Criteria(LessonQuizPeer::DATABASE_NAME);
        $valuesCriteria->add(LessonQuizPeer::RANK_COL, array('raw' => LessonQuizPeer::RANK_COL . ' + ?', 'value' => $delta), Criteria::CUSTOM_EQUAL);

        BasePeer::doUpdate($whereCriteria, $valuesCriteria, $con);
        LessonQuizPeer::clearInstancePool();
    }

} // BaseLessonQuizPeer

// This is the static code needed to register the TableMap for this table with the main Propel class.
//
BaseLessonQuizPeer::buildTableMap();

