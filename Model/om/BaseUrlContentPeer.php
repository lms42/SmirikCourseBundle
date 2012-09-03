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
use Smirik\CourseBundle\Model\LessonPeer;
use Smirik\CourseBundle\Model\UrlContent;
use Smirik\CourseBundle\Model\UrlContentPeer;
use Smirik\CourseBundle\Model\map\UrlContentTableMap;

abstract class BaseUrlContentPeer extends ContentPeer
{

    /** the default database name for this class */
    const DATABASE_NAME = 'default';

    /** the table name for this class */
    const TABLE_NAME = 'lessons_content_urls';

    /** the related Propel class for this table */
    const OM_CLASS = 'Smirik\\CourseBundle\\Model\\UrlContent';

    /** the related TableMap class for this table */
    const TM_CLASS = 'UrlContentTableMap';

    /** The total number of columns. */
    const NUM_COLUMNS = 8;

    /** The number of lazy-loaded columns. */
    const NUM_LAZY_LOAD_COLUMNS = 0;

    /** The number of columns to hydrate (NUM_COLUMNS - NUM_LAZY_LOAD_COLUMNS) */
    const NUM_HYDRATE_COLUMNS = 8;

    /** the column name for the URL field */
    const URL = 'lessons_content_urls.URL';

    /** the column name for the ID field */
    const ID = 'lessons_content_urls.ID';

    /** the column name for the LESSON_ID field */
    const LESSON_ID = 'lessons_content_urls.LESSON_ID';

    /** the column name for the TITLE field */
    const TITLE = 'lessons_content_urls.TITLE';

    /** the column name for the DESCRIPTION field */
    const DESCRIPTION = 'lessons_content_urls.DESCRIPTION';

    /** the column name for the CREATED_AT field */
    const CREATED_AT = 'lessons_content_urls.CREATED_AT';

    /** the column name for the UPDATED_AT field */
    const UPDATED_AT = 'lessons_content_urls.UPDATED_AT';

    /** the column name for the SORTABLE_RANK field */
    const SORTABLE_RANK = 'lessons_content_urls.SORTABLE_RANK';

    /** The default string format for model objects of the related table **/
    const DEFAULT_STRING_FORMAT = 'YAML';

    /**
     * An identiy map to hold any loaded instances of UrlContent objects.
     * This must be public so that other peer classes can access this when hydrating from JOIN
     * queries.
     * @var        array UrlContent[]
     */
    public static $instances = array();


    // sortable behavior

    /**
     * rank column
     */
    const RANK_COL = 'lessons_content_urls.SORTABLE_RANK';

    /**
     * Scope column for the set
     */
    const SCOPE_COL = 'lessons_content_urls.LESSON_ID';

    /**
     * holds an array of fieldnames
     *
     * first dimension keys are the type constants
     * e.g. UrlContentPeer::$fieldNames[UrlContentPeer::TYPE_PHPNAME][0] = 'Id'
     */
    protected static $fieldNames = array (
        BasePeer::TYPE_PHPNAME => array ('Url', 'Id', 'LessonId', 'Title', 'Description', 'CreatedAt', 'UpdatedAt', 'SortableRank', ),
        BasePeer::TYPE_STUDLYPHPNAME => array ('url', 'id', 'lessonId', 'title', 'description', 'createdAt', 'updatedAt', 'sortableRank', ),
        BasePeer::TYPE_COLNAME => array (UrlContentPeer::URL, UrlContentPeer::ID, UrlContentPeer::LESSON_ID, UrlContentPeer::TITLE, UrlContentPeer::DESCRIPTION, UrlContentPeer::CREATED_AT, UrlContentPeer::UPDATED_AT, UrlContentPeer::SORTABLE_RANK, ),
        BasePeer::TYPE_RAW_COLNAME => array ('URL', 'ID', 'LESSON_ID', 'TITLE', 'DESCRIPTION', 'CREATED_AT', 'UPDATED_AT', 'SORTABLE_RANK', ),
        BasePeer::TYPE_FIELDNAME => array ('url', 'id', 'lesson_id', 'title', 'description', 'created_at', 'updated_at', 'sortable_rank', ),
        BasePeer::TYPE_NUM => array (0, 1, 2, 3, 4, 5, 6, 7, )
    );

    /**
     * holds an array of keys for quick access to the fieldnames array
     *
     * first dimension keys are the type constants
     * e.g. UrlContentPeer::$fieldNames[BasePeer::TYPE_PHPNAME]['Id'] = 0
     */
    protected static $fieldKeys = array (
        BasePeer::TYPE_PHPNAME => array ('Url' => 0, 'Id' => 1, 'LessonId' => 2, 'Title' => 3, 'Description' => 4, 'CreatedAt' => 5, 'UpdatedAt' => 6, 'SortableRank' => 7, ),
        BasePeer::TYPE_STUDLYPHPNAME => array ('url' => 0, 'id' => 1, 'lessonId' => 2, 'title' => 3, 'description' => 4, 'createdAt' => 5, 'updatedAt' => 6, 'sortableRank' => 7, ),
        BasePeer::TYPE_COLNAME => array (UrlContentPeer::URL => 0, UrlContentPeer::ID => 1, UrlContentPeer::LESSON_ID => 2, UrlContentPeer::TITLE => 3, UrlContentPeer::DESCRIPTION => 4, UrlContentPeer::CREATED_AT => 5, UrlContentPeer::UPDATED_AT => 6, UrlContentPeer::SORTABLE_RANK => 7, ),
        BasePeer::TYPE_RAW_COLNAME => array ('URL' => 0, 'ID' => 1, 'LESSON_ID' => 2, 'TITLE' => 3, 'DESCRIPTION' => 4, 'CREATED_AT' => 5, 'UPDATED_AT' => 6, 'SORTABLE_RANK' => 7, ),
        BasePeer::TYPE_FIELDNAME => array ('url' => 0, 'id' => 1, 'lesson_id' => 2, 'title' => 3, 'description' => 4, 'created_at' => 5, 'updated_at' => 6, 'sortable_rank' => 7, ),
        BasePeer::TYPE_NUM => array (0, 1, 2, 3, 4, 5, 6, 7, )
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
        $toNames = UrlContentPeer::getFieldNames($toType);
        $key = isset(UrlContentPeer::$fieldKeys[$fromType][$name]) ? UrlContentPeer::$fieldKeys[$fromType][$name] : null;
        if ($key === null) {
            throw new PropelException("'$name' could not be found in the field names of type '$fromType'. These are: " . print_r(UrlContentPeer::$fieldKeys[$fromType], true));
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
        if (!array_key_exists($type, UrlContentPeer::$fieldNames)) {
            throw new PropelException('Method getFieldNames() expects the parameter $type to be one of the class constants BasePeer::TYPE_PHPNAME, BasePeer::TYPE_STUDLYPHPNAME, BasePeer::TYPE_COLNAME, BasePeer::TYPE_FIELDNAME, BasePeer::TYPE_NUM. ' . $type . ' was given.');
        }

        return UrlContentPeer::$fieldNames[$type];
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
     * @param      string $column The column name for current table. (i.e. UrlContentPeer::COLUMN_NAME).
     * @return string
     */
    public static function alias($alias, $column)
    {
        return str_replace(UrlContentPeer::TABLE_NAME.'.', $alias.'.', $column);
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
            $criteria->addSelectColumn(UrlContentPeer::URL);
            $criteria->addSelectColumn(UrlContentPeer::ID);
            $criteria->addSelectColumn(UrlContentPeer::LESSON_ID);
            $criteria->addSelectColumn(UrlContentPeer::TITLE);
            $criteria->addSelectColumn(UrlContentPeer::DESCRIPTION);
            $criteria->addSelectColumn(UrlContentPeer::CREATED_AT);
            $criteria->addSelectColumn(UrlContentPeer::UPDATED_AT);
            $criteria->addSelectColumn(UrlContentPeer::SORTABLE_RANK);
        } else {
            $criteria->addSelectColumn($alias . '.URL');
            $criteria->addSelectColumn($alias . '.ID');
            $criteria->addSelectColumn($alias . '.LESSON_ID');
            $criteria->addSelectColumn($alias . '.TITLE');
            $criteria->addSelectColumn($alias . '.DESCRIPTION');
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
        $criteria->setPrimaryTableName(UrlContentPeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            UrlContentPeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY won't ever affect the count
        $criteria->setDbName(UrlContentPeer::DATABASE_NAME); // Set the correct dbName

        if ($con === null) {
            $con = Propel::getConnection(UrlContentPeer::DATABASE_NAME, Propel::CONNECTION_READ);
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
     * @return                 UrlContent
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectOne(Criteria $criteria, PropelPDO $con = null)
    {
        $critcopy = clone $criteria;
        $critcopy->setLimit(1);
        $objects = UrlContentPeer::doSelect($critcopy, $con);
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
        return UrlContentPeer::populateObjects(UrlContentPeer::doSelectStmt($criteria, $con));
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
            $con = Propel::getConnection(UrlContentPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        if (!$criteria->hasSelectClause()) {
            $criteria = clone $criteria;
            UrlContentPeer::addSelectColumns($criteria);
        }

        // Set the correct dbName
        $criteria->setDbName(UrlContentPeer::DATABASE_NAME);

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
     * @param      UrlContent $obj A UrlContent object.
     * @param      string $key (optional) key to use for instance map (for performance boost if key was already calculated externally).
     */
    public static function addInstanceToPool($obj, $key = null)
    {
        if (Propel::isInstancePoolingEnabled()) {
            if ($key === null) {
                $key = (string) $obj->getId();
            } // if key === null
            UrlContentPeer::$instances[$key] = $obj;
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
     * @param      mixed $value A UrlContent object or a primary key value.
     *
     * @return void
     * @throws PropelException - if the value is invalid.
     */
    public static function removeInstanceFromPool($value)
    {
        if (Propel::isInstancePoolingEnabled() && $value !== null) {
            if (is_object($value) && $value instanceof UrlContent) {
                $key = (string) $value->getId();
            } elseif (is_scalar($value)) {
                // assume we've been passed a primary key
                $key = (string) $value;
            } else {
                $e = new PropelException("Invalid value passed to removeInstanceFromPool().  Expected primary key or UrlContent object; got " . (is_object($value) ? get_class($value) . ' object.' : var_export($value,true)));
                throw $e;
            }

            unset(UrlContentPeer::$instances[$key]);
        }
    } // removeInstanceFromPool()

    /**
     * Retrieves a string version of the primary key from the DB resultset row that can be used to uniquely identify a row in this table.
     *
     * For tables with a single-column primary key, that simple pkey value will be returned.  For tables with
     * a multi-column primary key, a serialize()d version of the primary key will be returned.
     *
     * @param      string $key The key (@see getPrimaryKeyHash()) for this instance.
     * @return   UrlContent Found object or null if 1) no instance exists for specified key or 2) instance pooling has been disabled.
     * @see        getPrimaryKeyHash()
     */
    public static function getInstanceFromPool($key)
    {
        if (Propel::isInstancePoolingEnabled()) {
            if (isset(UrlContentPeer::$instances[$key])) {
                return UrlContentPeer::$instances[$key];
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
        UrlContentPeer::$instances = array();
    }

    /**
     * Method to invalidate the instance pool of all tables related to lessons_content_urls
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
        if ($row[$startcol + 1] === null) {
            return null;
        }

        return (string) $row[$startcol + 1];
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

        return (int) $row[$startcol + 1];
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
        $cls = UrlContentPeer::getOMClass();
        // populate the object(s)
        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key = UrlContentPeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj = UrlContentPeer::getInstanceFromPool($key))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj->hydrate($row, 0, true); // rehydrate
                $results[] = $obj;
            } else {
                $obj = new $cls();
                $obj->hydrate($row);
                $results[] = $obj;
                UrlContentPeer::addInstanceToPool($obj, $key);
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
     * @return array (UrlContent object, last column rank)
     */
    public static function populateObject($row, $startcol = 0)
    {
        $key = UrlContentPeer::getPrimaryKeyHashFromRow($row, $startcol);
        if (null !== ($obj = UrlContentPeer::getInstanceFromPool($key))) {
            // We no longer rehydrate the object, since this can cause data loss.
            // See http://www.propelorm.org/ticket/509
            // $obj->hydrate($row, $startcol, true); // rehydrate
            $col = $startcol + UrlContentPeer::NUM_HYDRATE_COLUMNS;
        } else {
            $cls = UrlContentPeer::OM_CLASS;
            $obj = new $cls();
            $col = $obj->hydrate($row, $startcol);
            UrlContentPeer::addInstanceToPool($obj, $key);
        }

        return array($obj, $col);
    }


    /**
     * Returns the number of rows matching criteria, joining the related Content table
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return int Number of matching rows.
     */
    public static function doCountJoinContent(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        // we're going to modify criteria, so copy it first
        $criteria = clone $criteria;

        // We need to set the primary table name, since in the case that there are no WHERE columns
        // it will be impossible for the BasePeer::createSelectSql() method to determine which
        // tables go into the FROM clause.
        $criteria->setPrimaryTableName(UrlContentPeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            UrlContentPeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY won't ever affect the count

        // Set the correct dbName
        $criteria->setDbName(UrlContentPeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(UrlContentPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(UrlContentPeer::ID, ContentPeer::ID, $join_behavior);

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
        $criteria->setPrimaryTableName(UrlContentPeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            UrlContentPeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY won't ever affect the count

        // Set the correct dbName
        $criteria->setDbName(UrlContentPeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(UrlContentPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(UrlContentPeer::LESSON_ID, LessonPeer::ID, $join_behavior);

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
     * Selects a collection of UrlContent objects pre-filled with their Content objects.
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of UrlContent objects.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectJoinContent(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $criteria = clone $criteria;

        // Set the correct dbName if it has not been overridden
        if ($criteria->getDbName() == Propel::getDefaultDB()) {
            $criteria->setDbName(UrlContentPeer::DATABASE_NAME);
        }

        UrlContentPeer::addSelectColumns($criteria);
        $startcol = UrlContentPeer::NUM_HYDRATE_COLUMNS;
        ContentPeer::addSelectColumns($criteria);

        $criteria->addJoin(UrlContentPeer::ID, ContentPeer::ID, $join_behavior);

        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = UrlContentPeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = UrlContentPeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {

                $cls = UrlContentPeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                UrlContentPeer::addInstanceToPool($obj1, $key1);
            } // if $obj1 already loaded

            $key2 = ContentPeer::getPrimaryKeyHashFromRow($row, $startcol);
            if ($key2 !== null) {
                $obj2 = ContentPeer::getInstanceFromPool($key2);
                if (!$obj2) {

                    $cls = ContentPeer::getOMClass();

                    $obj2 = new $cls();
                    $obj2->hydrate($row, $startcol);
                    ContentPeer::addInstanceToPool($obj2, $key2);
                } // if obj2 already loaded

                // Add the $obj1 (UrlContent) to $obj2 (Content)
                // one to one relationship
                $obj1->setContent($obj2);

            } // if joined row was not null

            $results[] = $obj1;
        }
        $stmt->closeCursor();

        return $results;
    }


    /**
     * Selects a collection of UrlContent objects pre-filled with their Lesson objects.
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of UrlContent objects.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectJoinLesson(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $criteria = clone $criteria;

        // Set the correct dbName if it has not been overridden
        if ($criteria->getDbName() == Propel::getDefaultDB()) {
            $criteria->setDbName(UrlContentPeer::DATABASE_NAME);
        }

        UrlContentPeer::addSelectColumns($criteria);
        $startcol = UrlContentPeer::NUM_HYDRATE_COLUMNS;
        LessonPeer::addSelectColumns($criteria);

        $criteria->addJoin(UrlContentPeer::LESSON_ID, LessonPeer::ID, $join_behavior);

        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = UrlContentPeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = UrlContentPeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {

                $cls = UrlContentPeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                UrlContentPeer::addInstanceToPool($obj1, $key1);
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

                // Add the $obj1 (UrlContent) to $obj2 (Lesson)
                $obj2->addUrlContent($obj1);

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
        $criteria->setPrimaryTableName(UrlContentPeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            UrlContentPeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY won't ever affect the count

        // Set the correct dbName
        $criteria->setDbName(UrlContentPeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(UrlContentPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(UrlContentPeer::ID, ContentPeer::ID, $join_behavior);

        $criteria->addJoin(UrlContentPeer::LESSON_ID, LessonPeer::ID, $join_behavior);

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
     * Selects a collection of UrlContent objects pre-filled with all related objects.
     *
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of UrlContent objects.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectJoinAll(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $criteria = clone $criteria;

        // Set the correct dbName if it has not been overridden
        if ($criteria->getDbName() == Propel::getDefaultDB()) {
            $criteria->setDbName(UrlContentPeer::DATABASE_NAME);
        }

        UrlContentPeer::addSelectColumns($criteria);
        $startcol2 = UrlContentPeer::NUM_HYDRATE_COLUMNS;

        ContentPeer::addSelectColumns($criteria);
        $startcol3 = $startcol2 + ContentPeer::NUM_HYDRATE_COLUMNS;

        LessonPeer::addSelectColumns($criteria);
        $startcol4 = $startcol3 + LessonPeer::NUM_HYDRATE_COLUMNS;

        $criteria->addJoin(UrlContentPeer::ID, ContentPeer::ID, $join_behavior);

        $criteria->addJoin(UrlContentPeer::LESSON_ID, LessonPeer::ID, $join_behavior);

        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = UrlContentPeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = UrlContentPeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {
                $cls = UrlContentPeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                UrlContentPeer::addInstanceToPool($obj1, $key1);
            } // if obj1 already loaded

            // Add objects for joined Content rows

            $key2 = ContentPeer::getPrimaryKeyHashFromRow($row, $startcol2);
            if ($key2 !== null) {
                $obj2 = ContentPeer::getInstanceFromPool($key2);
                if (!$obj2) {

                    $cls = ContentPeer::getOMClass();

                    $obj2 = new $cls();
                    $obj2->hydrate($row, $startcol2);
                    ContentPeer::addInstanceToPool($obj2, $key2);
                } // if obj2 loaded

                // Add the $obj1 (UrlContent) to the collection in $obj2 (Content)
                $obj1->setContent($obj2);
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

                // Add the $obj1 (UrlContent) to the collection in $obj3 (Lesson)
                $obj3->addUrlContent($obj1);
            } // if joined row not null

            $results[] = $obj1;
        }
        $stmt->closeCursor();

        return $results;
    }


    /**
     * Returns the number of rows matching criteria, joining the related Content table
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return int Number of matching rows.
     */
    public static function doCountJoinAllExceptContent(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        // we're going to modify criteria, so copy it first
        $criteria = clone $criteria;

        // We need to set the primary table name, since in the case that there are no WHERE columns
        // it will be impossible for the BasePeer::createSelectSql() method to determine which
        // tables go into the FROM clause.
        $criteria->setPrimaryTableName(UrlContentPeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            UrlContentPeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY should not affect count

        // Set the correct dbName
        $criteria->setDbName(UrlContentPeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(UrlContentPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(UrlContentPeer::LESSON_ID, LessonPeer::ID, $join_behavior);

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
        $criteria->setPrimaryTableName(UrlContentPeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            UrlContentPeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY should not affect count

        // Set the correct dbName
        $criteria->setDbName(UrlContentPeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(UrlContentPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(UrlContentPeer::ID, ContentPeer::ID, $join_behavior);

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
     * Selects a collection of UrlContent objects pre-filled with all related objects except Content.
     *
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of UrlContent objects.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectJoinAllExceptContent(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $criteria = clone $criteria;

        // Set the correct dbName if it has not been overridden
        // $criteria->getDbName() will return the same object if not set to another value
        // so == check is okay and faster
        if ($criteria->getDbName() == Propel::getDefaultDB()) {
            $criteria->setDbName(UrlContentPeer::DATABASE_NAME);
        }

        UrlContentPeer::addSelectColumns($criteria);
        $startcol2 = UrlContentPeer::NUM_HYDRATE_COLUMNS;

        LessonPeer::addSelectColumns($criteria);
        $startcol3 = $startcol2 + LessonPeer::NUM_HYDRATE_COLUMNS;

        $criteria->addJoin(UrlContentPeer::LESSON_ID, LessonPeer::ID, $join_behavior);


        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = UrlContentPeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = UrlContentPeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {
                $cls = UrlContentPeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                UrlContentPeer::addInstanceToPool($obj1, $key1);
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

                // Add the $obj1 (UrlContent) to the collection in $obj2 (Lesson)
                $obj2->addUrlContent($obj1);

            } // if joined row is not null

            $results[] = $obj1;
        }
        $stmt->closeCursor();

        return $results;
    }


    /**
     * Selects a collection of UrlContent objects pre-filled with all related objects except Lesson.
     *
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of UrlContent objects.
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
            $criteria->setDbName(UrlContentPeer::DATABASE_NAME);
        }

        UrlContentPeer::addSelectColumns($criteria);
        $startcol2 = UrlContentPeer::NUM_HYDRATE_COLUMNS;

        ContentPeer::addSelectColumns($criteria);
        $startcol3 = $startcol2 + ContentPeer::NUM_HYDRATE_COLUMNS;

        $criteria->addJoin(UrlContentPeer::ID, ContentPeer::ID, $join_behavior);


        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = UrlContentPeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = UrlContentPeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {
                $cls = UrlContentPeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                UrlContentPeer::addInstanceToPool($obj1, $key1);
            } // if obj1 already loaded

                // Add objects for joined Content rows

                $key2 = ContentPeer::getPrimaryKeyHashFromRow($row, $startcol2);
                if ($key2 !== null) {
                    $obj2 = ContentPeer::getInstanceFromPool($key2);
                    if (!$obj2) {

                        $cls = ContentPeer::getOMClass();

                    $obj2 = new $cls();
                    $obj2->hydrate($row, $startcol2);
                    ContentPeer::addInstanceToPool($obj2, $key2);
                } // if $obj2 already loaded

                // Add the $obj1 (UrlContent) to the collection in $obj2 (Content)
                $obj1->setContent($obj2);

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
        return Propel::getDatabaseMap(UrlContentPeer::DATABASE_NAME)->getTable(UrlContentPeer::TABLE_NAME);
    }

    /**
     * Add a TableMap instance to the database for this peer class.
     */
    public static function buildTableMap()
    {
      $dbMap = Propel::getDatabaseMap(BaseUrlContentPeer::DATABASE_NAME);
      if (!$dbMap->hasTable(BaseUrlContentPeer::TABLE_NAME)) {
        $dbMap->addTableObject(new UrlContentTableMap());
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
        return UrlContentPeer::OM_CLASS;
    }

    /**
     * Performs an INSERT on the database, given a UrlContent or Criteria object.
     *
     * @param      mixed $values Criteria or UrlContent object containing data that is used to create the INSERT statement.
     * @param      PropelPDO $con the PropelPDO connection to use
     * @return mixed           The new primary key.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doInsert($values, PropelPDO $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection(UrlContentPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        if ($values instanceof Criteria) {
            $criteria = clone $values; // rename for clarity
        } else {
            $criteria = $values->buildCriteria(); // build Criteria from UrlContent object
        }


        // Set the correct dbName
        $criteria->setDbName(UrlContentPeer::DATABASE_NAME);

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
     * Performs an UPDATE on the database, given a UrlContent or Criteria object.
     *
     * @param      mixed $values Criteria or UrlContent object containing data that is used to create the UPDATE statement.
     * @param      PropelPDO $con The connection to use (specify PropelPDO connection object to exert more control over transactions).
     * @return int             The number of affected rows (if supported by underlying database driver).
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doUpdate($values, PropelPDO $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection(UrlContentPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        $selectCriteria = new Criteria(UrlContentPeer::DATABASE_NAME);

        if ($values instanceof Criteria) {
            $criteria = clone $values; // rename for clarity

            $comparison = $criteria->getComparison(UrlContentPeer::ID);
            $value = $criteria->remove(UrlContentPeer::ID);
            if ($value) {
                $selectCriteria->add(UrlContentPeer::ID, $value, $comparison);
            } else {
                $selectCriteria->setPrimaryTableName(UrlContentPeer::TABLE_NAME);
            }

        } else { // $values is UrlContent object
            $criteria = $values->buildCriteria(); // gets full criteria
            $selectCriteria = $values->buildPkeyCriteria(); // gets criteria w/ primary key(s)
        }

        // set the correct dbName
        $criteria->setDbName(UrlContentPeer::DATABASE_NAME);

        return BasePeer::doUpdate($selectCriteria, $criteria, $con);
    }

    /**
     * Deletes all rows from the lessons_content_urls table.
     *
     * @param      PropelPDO $con the connection to use
     * @return int             The number of affected rows (if supported by underlying database driver).
     * @throws PropelException
     */
    public static function doDeleteAll(PropelPDO $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection(UrlContentPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }
        $affectedRows = 0; // initialize var to track total num of affected rows
        try {
            // use transaction because $criteria could contain info
            // for more than one table or we could emulating ON DELETE CASCADE, etc.
            $con->beginTransaction();
            $affectedRows += BasePeer::doDeleteAll(UrlContentPeer::TABLE_NAME, $con, UrlContentPeer::DATABASE_NAME);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            UrlContentPeer::clearInstancePool();
            UrlContentPeer::clearRelatedInstancePool();
            $con->commit();

            return $affectedRows;
        } catch (PropelException $e) {
            $con->rollBack();
            throw $e;
        }
    }

    /**
     * Performs a DELETE on the database, given a UrlContent or Criteria object OR a primary key value.
     *
     * @param      mixed $values Criteria or UrlContent object or primary key or array of primary keys
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
            $con = Propel::getConnection(UrlContentPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        if ($values instanceof Criteria) {
            // invalidate the cache for all objects of this type, since we have no
            // way of knowing (without running a query) what objects should be invalidated
            // from the cache based on this Criteria.
            UrlContentPeer::clearInstancePool();
            // rename for clarity
            $criteria = clone $values;
        } elseif ($values instanceof UrlContent) { // it's a model object
            // invalidate the cache for this single object
            UrlContentPeer::removeInstanceFromPool($values);
            // create criteria based on pk values
            $criteria = $values->buildPkeyCriteria();
        } else { // it's a primary key, or an array of pks
            $criteria = new Criteria(UrlContentPeer::DATABASE_NAME);
            $criteria->add(UrlContentPeer::ID, (array) $values, Criteria::IN);
            // invalidate the cache for this object(s)
            foreach ((array) $values as $singleval) {
                UrlContentPeer::removeInstanceFromPool($singleval);
            }
        }

        // Set the correct dbName
        $criteria->setDbName(UrlContentPeer::DATABASE_NAME);

        $affectedRows = 0; // initialize var to track total num of affected rows

        try {
            // use transaction because $criteria could contain info
            // for more than one table or we could emulating ON DELETE CASCADE, etc.
            $con->beginTransaction();

            $affectedRows += BasePeer::doDelete($criteria, $con);
            UrlContentPeer::clearRelatedInstancePool();
            $con->commit();

            return $affectedRows;
        } catch (PropelException $e) {
            $con->rollBack();
            throw $e;
        }
    }

    /**
     * Validates all modified columns of given UrlContent object.
     * If parameter $columns is either a single column name or an array of column names
     * than only those columns are validated.
     *
     * NOTICE: This does not apply to primary or foreign keys for now.
     *
     * @param      UrlContent $obj The object to validate.
     * @param      mixed $cols Column name or array of column names.
     *
     * @return mixed TRUE if all columns are valid or the error message of the first invalid column.
     */
    public static function doValidate($obj, $cols = null)
    {
        $columns = array();

        if ($cols) {
            $dbMap = Propel::getDatabaseMap(UrlContentPeer::DATABASE_NAME);
            $tableMap = $dbMap->getTable(UrlContentPeer::TABLE_NAME);

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

        return BasePeer::doValidate(UrlContentPeer::DATABASE_NAME, UrlContentPeer::TABLE_NAME, $columns);
    }

    /**
     * Retrieve a single object by pkey.
     *
     * @param      int $pk the primary key.
     * @param      PropelPDO $con the connection to use
     * @return UrlContent
     */
    public static function retrieveByPK($pk, PropelPDO $con = null)
    {

        if (null !== ($obj = UrlContentPeer::getInstanceFromPool((string) $pk))) {
            return $obj;
        }

        if ($con === null) {
            $con = Propel::getConnection(UrlContentPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria = new Criteria(UrlContentPeer::DATABASE_NAME);
        $criteria->add(UrlContentPeer::ID, $pk);

        $v = UrlContentPeer::doSelect($criteria, $con);

        return !empty($v) > 0 ? $v[0] : null;
    }

    /**
     * Retrieve multiple objects by pkey.
     *
     * @param      array $pks List of primary keys
     * @param      PropelPDO $con the connection to use
     * @return UrlContent[]
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function retrieveByPKs($pks, PropelPDO $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection(UrlContentPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $objs = null;
        if (empty($pks)) {
            $objs = array();
        } else {
            $criteria = new Criteria(UrlContentPeer::DATABASE_NAME);
            $criteria->add(UrlContentPeer::ID, $pks, Criteria::IN);
            $objs = UrlContentPeer::doSelect($criteria, $con);
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
            $con = Propel::getConnection(UrlContentPeer::DATABASE_NAME);
        }
        // shift the objects with a position lower than the one of object
        $c = new Criteria();
        $c->addSelectColumn('MAX(' . UrlContentPeer::RANK_COL . ')');
        $c->add(UrlContentPeer::SCOPE_COL, $scope, Criteria::EQUAL);
        $stmt = UrlContentPeer::doSelectStmt($c, $con);

        return $stmt->fetchColumn();
    }

    /**
     * Get an item from the list based on its rank
     *
     * @param     integer   $rank rank
     * @param      int $scope		Scope to determine which suite to consider
     * @param     PropelPDO $con optional connection
     *
     * @return UrlContent
     */
    public static function retrieveByRank($rank, $scope = null, PropelPDO $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection(UrlContentPeer::DATABASE_NAME);
        }

        $c = new Criteria;
        $c->add(UrlContentPeer::RANK_COL, $rank);
        $c->add(UrlContentPeer::SCOPE_COL, $scope, Criteria::EQUAL);

        return UrlContentPeer::doSelectOne($c, $con);
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
            $con = Propel::getConnection(UrlContentPeer::DATABASE_NAME);
        }

        $con->beginTransaction();
        try {
            $ids = array_keys($order);
            $objects = UrlContentPeer::retrieveByPKs($ids);
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
            $con = Propel::getConnection(UrlContentPeer::DATABASE_NAME);
        }

        if ($criteria === null) {
            $criteria = new Criteria();
        } elseif ($criteria instanceof Criteria) {
            $criteria = clone $criteria;
        }

        $criteria->clearOrderByColumns();

        if ($order == Criteria::ASC) {
            $criteria->addAscendingOrderByColumn(UrlContentPeer::RANK_COL);
        } else {
            $criteria->addDescendingOrderByColumn(UrlContentPeer::RANK_COL);
        }

        return UrlContentPeer::doSelect($criteria, $con);
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
        $c->add(UrlContentPeer::SCOPE_COL, $scope);

        return UrlContentPeer::doSelectOrderByRank($c, $order, $con);
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
        $c->add(UrlContentPeer::SCOPE_COL, $scope);

        return UrlContentPeer::doCount($c, $con);
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
        $c->add(UrlContentPeer::SCOPE_COL, $scope);

        return UrlContentPeer::doDelete($c, $con);
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
            $con = Propel::getConnection(UrlContentPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        $whereCriteria = new Criteria(UrlContentPeer::DATABASE_NAME);
        $criterion = $whereCriteria->getNewCriterion(UrlContentPeer::RANK_COL, $first, Criteria::GREATER_EQUAL);
        if (null !== $last) {
            $criterion->addAnd($whereCriteria->getNewCriterion(UrlContentPeer::RANK_COL, $last, Criteria::LESS_EQUAL));
        }
        $whereCriteria->add($criterion);
        $whereCriteria->add(UrlContentPeer::SCOPE_COL, $scope, Criteria::EQUAL);

        $valuesCriteria = new Criteria(UrlContentPeer::DATABASE_NAME);
        $valuesCriteria->add(UrlContentPeer::RANK_COL, array('raw' => UrlContentPeer::RANK_COL . ' + ?', 'value' => $delta), Criteria::CUSTOM_EQUAL);

        BasePeer::doUpdate($whereCriteria, $valuesCriteria, $con);
        UrlContentPeer::clearInstancePool();
    }

} // BaseUrlContentPeer

// This is the static code needed to register the TableMap for this table with the main Propel class.
//
BaseUrlContentPeer::buildTableMap();

