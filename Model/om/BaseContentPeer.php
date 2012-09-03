<?php

namespace Smirik\CourseBundle\Model\om;

use \BasePeer;
use \Criteria;
use \PDO;
use \PDOStatement;
use \Propel;
use \PropelException;
use \PropelPDO;
use Smirik\CourseBundle\Model\Content;
use Smirik\CourseBundle\Model\ContentFilePeer;
use Smirik\CourseBundle\Model\ContentPeer;
use Smirik\CourseBundle\Model\LessonPeer;
use Smirik\CourseBundle\Model\SlideshareContentPeer;
use Smirik\CourseBundle\Model\TextContentPeer;
use Smirik\CourseBundle\Model\UrlContentPeer;
use Smirik\CourseBundle\Model\YoutubeContentPeer;
use Smirik\CourseBundle\Model\map\ContentTableMap;

abstract class BaseContentPeer
{

    /** the default database name for this class */
    const DATABASE_NAME = 'default';

    /** the table name for this class */
    const TABLE_NAME = 'lessons_content';

    /** the related Propel class for this table */
    const OM_CLASS = 'Smirik\\CourseBundle\\Model\\Content';

    /** the related TableMap class for this table */
    const TM_CLASS = 'ContentTableMap';

    /** The total number of columns. */
    const NUM_COLUMNS = 8;

    /** The number of lazy-loaded columns. */
    const NUM_LAZY_LOAD_COLUMNS = 0;

    /** The number of columns to hydrate (NUM_COLUMNS - NUM_LAZY_LOAD_COLUMNS) */
    const NUM_HYDRATE_COLUMNS = 8;

    /** the column name for the ID field */
    const ID = 'lessons_content.ID';

    /** the column name for the LESSON_ID field */
    const LESSON_ID = 'lessons_content.LESSON_ID';

    /** the column name for the TITLE field */
    const TITLE = 'lessons_content.TITLE';

    /** the column name for the DESCRIPTION field */
    const DESCRIPTION = 'lessons_content.DESCRIPTION';

    /** the column name for the CREATED_AT field */
    const CREATED_AT = 'lessons_content.CREATED_AT';

    /** the column name for the UPDATED_AT field */
    const UPDATED_AT = 'lessons_content.UPDATED_AT';

    /** the column name for the SORTABLE_RANK field */
    const SORTABLE_RANK = 'lessons_content.SORTABLE_RANK';

    /** the column name for the DESCENDANT_CLASS field */
    const DESCENDANT_CLASS = 'lessons_content.DESCENDANT_CLASS';

    /** The default string format for model objects of the related table **/
    const DEFAULT_STRING_FORMAT = 'YAML';

    /**
     * An identiy map to hold any loaded instances of Content objects.
     * This must be public so that other peer classes can access this when hydrating from JOIN
     * queries.
     * @var        array Content[]
     */
    public static $instances = array();


    // sortable behavior

    /**
     * rank column
     */
    const RANK_COL = 'lessons_content.SORTABLE_RANK';

    /**
     * Scope column for the set
     */
    const SCOPE_COL = 'lessons_content.LESSON_ID';

    /**
     * holds an array of fieldnames
     *
     * first dimension keys are the type constants
     * e.g. ContentPeer::$fieldNames[ContentPeer::TYPE_PHPNAME][0] = 'Id'
     */
    protected static $fieldNames = array (
        BasePeer::TYPE_PHPNAME => array ('Id', 'LessonId', 'Title', 'Description', 'CreatedAt', 'UpdatedAt', 'SortableRank', 'DescendantClass', ),
        BasePeer::TYPE_STUDLYPHPNAME => array ('id', 'lessonId', 'title', 'description', 'createdAt', 'updatedAt', 'sortableRank', 'descendantClass', ),
        BasePeer::TYPE_COLNAME => array (ContentPeer::ID, ContentPeer::LESSON_ID, ContentPeer::TITLE, ContentPeer::DESCRIPTION, ContentPeer::CREATED_AT, ContentPeer::UPDATED_AT, ContentPeer::SORTABLE_RANK, ContentPeer::DESCENDANT_CLASS, ),
        BasePeer::TYPE_RAW_COLNAME => array ('ID', 'LESSON_ID', 'TITLE', 'DESCRIPTION', 'CREATED_AT', 'UPDATED_AT', 'SORTABLE_RANK', 'DESCENDANT_CLASS', ),
        BasePeer::TYPE_FIELDNAME => array ('id', 'lesson_id', 'title', 'description', 'created_at', 'updated_at', 'sortable_rank', 'descendant_class', ),
        BasePeer::TYPE_NUM => array (0, 1, 2, 3, 4, 5, 6, 7, )
    );

    /**
     * holds an array of keys for quick access to the fieldnames array
     *
     * first dimension keys are the type constants
     * e.g. ContentPeer::$fieldNames[BasePeer::TYPE_PHPNAME]['Id'] = 0
     */
    protected static $fieldKeys = array (
        BasePeer::TYPE_PHPNAME => array ('Id' => 0, 'LessonId' => 1, 'Title' => 2, 'Description' => 3, 'CreatedAt' => 4, 'UpdatedAt' => 5, 'SortableRank' => 6, 'DescendantClass' => 7, ),
        BasePeer::TYPE_STUDLYPHPNAME => array ('id' => 0, 'lessonId' => 1, 'title' => 2, 'description' => 3, 'createdAt' => 4, 'updatedAt' => 5, 'sortableRank' => 6, 'descendantClass' => 7, ),
        BasePeer::TYPE_COLNAME => array (ContentPeer::ID => 0, ContentPeer::LESSON_ID => 1, ContentPeer::TITLE => 2, ContentPeer::DESCRIPTION => 3, ContentPeer::CREATED_AT => 4, ContentPeer::UPDATED_AT => 5, ContentPeer::SORTABLE_RANK => 6, ContentPeer::DESCENDANT_CLASS => 7, ),
        BasePeer::TYPE_RAW_COLNAME => array ('ID' => 0, 'LESSON_ID' => 1, 'TITLE' => 2, 'DESCRIPTION' => 3, 'CREATED_AT' => 4, 'UPDATED_AT' => 5, 'SORTABLE_RANK' => 6, 'DESCENDANT_CLASS' => 7, ),
        BasePeer::TYPE_FIELDNAME => array ('id' => 0, 'lesson_id' => 1, 'title' => 2, 'description' => 3, 'created_at' => 4, 'updated_at' => 5, 'sortable_rank' => 6, 'descendant_class' => 7, ),
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
        $toNames = ContentPeer::getFieldNames($toType);
        $key = isset(ContentPeer::$fieldKeys[$fromType][$name]) ? ContentPeer::$fieldKeys[$fromType][$name] : null;
        if ($key === null) {
            throw new PropelException("'$name' could not be found in the field names of type '$fromType'. These are: " . print_r(ContentPeer::$fieldKeys[$fromType], true));
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
        if (!array_key_exists($type, ContentPeer::$fieldNames)) {
            throw new PropelException('Method getFieldNames() expects the parameter $type to be one of the class constants BasePeer::TYPE_PHPNAME, BasePeer::TYPE_STUDLYPHPNAME, BasePeer::TYPE_COLNAME, BasePeer::TYPE_FIELDNAME, BasePeer::TYPE_NUM. ' . $type . ' was given.');
        }

        return ContentPeer::$fieldNames[$type];
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
     * @param      string $column The column name for current table. (i.e. ContentPeer::COLUMN_NAME).
     * @return string
     */
    public static function alias($alias, $column)
    {
        return str_replace(ContentPeer::TABLE_NAME.'.', $alias.'.', $column);
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
            $criteria->addSelectColumn(ContentPeer::ID);
            $criteria->addSelectColumn(ContentPeer::LESSON_ID);
            $criteria->addSelectColumn(ContentPeer::TITLE);
            $criteria->addSelectColumn(ContentPeer::DESCRIPTION);
            $criteria->addSelectColumn(ContentPeer::CREATED_AT);
            $criteria->addSelectColumn(ContentPeer::UPDATED_AT);
            $criteria->addSelectColumn(ContentPeer::SORTABLE_RANK);
            $criteria->addSelectColumn(ContentPeer::DESCENDANT_CLASS);
        } else {
            $criteria->addSelectColumn($alias . '.ID');
            $criteria->addSelectColumn($alias . '.LESSON_ID');
            $criteria->addSelectColumn($alias . '.TITLE');
            $criteria->addSelectColumn($alias . '.DESCRIPTION');
            $criteria->addSelectColumn($alias . '.CREATED_AT');
            $criteria->addSelectColumn($alias . '.UPDATED_AT');
            $criteria->addSelectColumn($alias . '.SORTABLE_RANK');
            $criteria->addSelectColumn($alias . '.DESCENDANT_CLASS');
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
        $criteria->setPrimaryTableName(ContentPeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            ContentPeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY won't ever affect the count
        $criteria->setDbName(ContentPeer::DATABASE_NAME); // Set the correct dbName

        if ($con === null) {
            $con = Propel::getConnection(ContentPeer::DATABASE_NAME, Propel::CONNECTION_READ);
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
     * @return                 Content
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectOne(Criteria $criteria, PropelPDO $con = null)
    {
        $critcopy = clone $criteria;
        $critcopy->setLimit(1);
        $objects = ContentPeer::doSelect($critcopy, $con);
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
        return ContentPeer::populateObjects(ContentPeer::doSelectStmt($criteria, $con));
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
            $con = Propel::getConnection(ContentPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        if (!$criteria->hasSelectClause()) {
            $criteria = clone $criteria;
            ContentPeer::addSelectColumns($criteria);
        }

        // Set the correct dbName
        $criteria->setDbName(ContentPeer::DATABASE_NAME);

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
     * @param      Content $obj A Content object.
     * @param      string $key (optional) key to use for instance map (for performance boost if key was already calculated externally).
     */
    public static function addInstanceToPool($obj, $key = null)
    {
        if (Propel::isInstancePoolingEnabled()) {
            if ($key === null) {
                $key = (string) $obj->getId();
            } // if key === null
            ContentPeer::$instances[$key] = $obj;
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
     * @param      mixed $value A Content object or a primary key value.
     *
     * @return void
     * @throws PropelException - if the value is invalid.
     */
    public static function removeInstanceFromPool($value)
    {
        if (Propel::isInstancePoolingEnabled() && $value !== null) {
            if (is_object($value) && $value instanceof Content) {
                $key = (string) $value->getId();
            } elseif (is_scalar($value)) {
                // assume we've been passed a primary key
                $key = (string) $value;
            } else {
                $e = new PropelException("Invalid value passed to removeInstanceFromPool().  Expected primary key or Content object; got " . (is_object($value) ? get_class($value) . ' object.' : var_export($value,true)));
                throw $e;
            }

            unset(ContentPeer::$instances[$key]);
        }
    } // removeInstanceFromPool()

    /**
     * Retrieves a string version of the primary key from the DB resultset row that can be used to uniquely identify a row in this table.
     *
     * For tables with a single-column primary key, that simple pkey value will be returned.  For tables with
     * a multi-column primary key, a serialize()d version of the primary key will be returned.
     *
     * @param      string $key The key (@see getPrimaryKeyHash()) for this instance.
     * @return   Content Found object or null if 1) no instance exists for specified key or 2) instance pooling has been disabled.
     * @see        getPrimaryKeyHash()
     */
    public static function getInstanceFromPool($key)
    {
        if (Propel::isInstancePoolingEnabled()) {
            if (isset(ContentPeer::$instances[$key])) {
                return ContentPeer::$instances[$key];
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
        ContentPeer::$instances = array();
    }

    /**
     * Method to invalidate the instance pool of all tables related to lessons_content
     * by a foreign key with ON DELETE CASCADE
     */
    public static function clearRelatedInstancePool()
    {
        // Invalidate objects in ContentFilePeer instance pool,
        // since one or more of them may be deleted by ON DELETE CASCADE/SETNULL rule.
        ContentFilePeer::clearInstancePool();
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
        $cls = ContentPeer::getOMClass();
        // populate the object(s)
        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key = ContentPeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj = ContentPeer::getInstanceFromPool($key))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj->hydrate($row, 0, true); // rehydrate
                $results[] = $obj;
            } else {
                $obj = new $cls();
                $obj->hydrate($row);
                $results[] = $obj;
                ContentPeer::addInstanceToPool($obj, $key);
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
     * @return array (Content object, last column rank)
     */
    public static function populateObject($row, $startcol = 0)
    {
        $key = ContentPeer::getPrimaryKeyHashFromRow($row, $startcol);
        if (null !== ($obj = ContentPeer::getInstanceFromPool($key))) {
            // We no longer rehydrate the object, since this can cause data loss.
            // See http://www.propelorm.org/ticket/509
            // $obj->hydrate($row, $startcol, true); // rehydrate
            $col = $startcol + ContentPeer::NUM_HYDRATE_COLUMNS;
        } else {
            $cls = ContentPeer::OM_CLASS;
            $obj = new $cls();
            $col = $obj->hydrate($row, $startcol);
            ContentPeer::addInstanceToPool($obj, $key);
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
        $criteria->setPrimaryTableName(ContentPeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            ContentPeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY won't ever affect the count

        // Set the correct dbName
        $criteria->setDbName(ContentPeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(ContentPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(ContentPeer::LESSON_ID, LessonPeer::ID, $join_behavior);

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
     * Selects a collection of Content objects pre-filled with their Lesson objects.
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of Content objects.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectJoinLesson(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $criteria = clone $criteria;

        // Set the correct dbName if it has not been overridden
        if ($criteria->getDbName() == Propel::getDefaultDB()) {
            $criteria->setDbName(ContentPeer::DATABASE_NAME);
        }

        ContentPeer::addSelectColumns($criteria);
        $startcol = ContentPeer::NUM_HYDRATE_COLUMNS;
        LessonPeer::addSelectColumns($criteria);

        $criteria->addJoin(ContentPeer::LESSON_ID, LessonPeer::ID, $join_behavior);

        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = ContentPeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = ContentPeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {

                $cls = ContentPeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                ContentPeer::addInstanceToPool($obj1, $key1);
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

                // Add the $obj1 (Content) to $obj2 (Lesson)
                $obj2->addContent($obj1);

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
        $criteria->setPrimaryTableName(ContentPeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            ContentPeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY won't ever affect the count

        // Set the correct dbName
        $criteria->setDbName(ContentPeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(ContentPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(ContentPeer::LESSON_ID, LessonPeer::ID, $join_behavior);

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
     * Selects a collection of Content objects pre-filled with all related objects.
     *
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of Content objects.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectJoinAll(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $criteria = clone $criteria;

        // Set the correct dbName if it has not been overridden
        if ($criteria->getDbName() == Propel::getDefaultDB()) {
            $criteria->setDbName(ContentPeer::DATABASE_NAME);
        }

        ContentPeer::addSelectColumns($criteria);
        $startcol2 = ContentPeer::NUM_HYDRATE_COLUMNS;

        LessonPeer::addSelectColumns($criteria);
        $startcol3 = $startcol2 + LessonPeer::NUM_HYDRATE_COLUMNS;

        $criteria->addJoin(ContentPeer::LESSON_ID, LessonPeer::ID, $join_behavior);

        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = ContentPeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = ContentPeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {
                $cls = ContentPeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                ContentPeer::addInstanceToPool($obj1, $key1);
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

                // Add the $obj1 (Content) to the collection in $obj2 (Lesson)
                $obj2->addContent($obj1);
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
        return Propel::getDatabaseMap(ContentPeer::DATABASE_NAME)->getTable(ContentPeer::TABLE_NAME);
    }

    /**
     * Add a TableMap instance to the database for this peer class.
     */
    public static function buildTableMap()
    {
      $dbMap = Propel::getDatabaseMap(BaseContentPeer::DATABASE_NAME);
      if (!$dbMap->hasTable(BaseContentPeer::TABLE_NAME)) {
        $dbMap->addTableObject(new ContentTableMap());
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
        return ContentPeer::OM_CLASS;
    }

    /**
     * Performs an INSERT on the database, given a Content or Criteria object.
     *
     * @param      mixed $values Criteria or Content object containing data that is used to create the INSERT statement.
     * @param      PropelPDO $con the PropelPDO connection to use
     * @return mixed           The new primary key.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doInsert($values, PropelPDO $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection(ContentPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        if ($values instanceof Criteria) {
            $criteria = clone $values; // rename for clarity
        } else {
            $criteria = $values->buildCriteria(); // build Criteria from Content object
        }

        if ($criteria->containsKey(ContentPeer::ID) && $criteria->keyContainsValue(ContentPeer::ID) ) {
            throw new PropelException('Cannot insert a value for auto-increment primary key ('.ContentPeer::ID.')');
        }


        // Set the correct dbName
        $criteria->setDbName(ContentPeer::DATABASE_NAME);

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
     * Performs an UPDATE on the database, given a Content or Criteria object.
     *
     * @param      mixed $values Criteria or Content object containing data that is used to create the UPDATE statement.
     * @param      PropelPDO $con The connection to use (specify PropelPDO connection object to exert more control over transactions).
     * @return int             The number of affected rows (if supported by underlying database driver).
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doUpdate($values, PropelPDO $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection(ContentPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        $selectCriteria = new Criteria(ContentPeer::DATABASE_NAME);

        if ($values instanceof Criteria) {
            $criteria = clone $values; // rename for clarity

            $comparison = $criteria->getComparison(ContentPeer::ID);
            $value = $criteria->remove(ContentPeer::ID);
            if ($value) {
                $selectCriteria->add(ContentPeer::ID, $value, $comparison);
            } else {
                $selectCriteria->setPrimaryTableName(ContentPeer::TABLE_NAME);
            }

        } else { // $values is Content object
            $criteria = $values->buildCriteria(); // gets full criteria
            $selectCriteria = $values->buildPkeyCriteria(); // gets criteria w/ primary key(s)
        }

        // set the correct dbName
        $criteria->setDbName(ContentPeer::DATABASE_NAME);

        return BasePeer::doUpdate($selectCriteria, $criteria, $con);
    }

    /**
     * Deletes all rows from the lessons_content table.
     *
     * @param      PropelPDO $con the connection to use
     * @return int             The number of affected rows (if supported by underlying database driver).
     * @throws PropelException
     */
    public static function doDeleteAll(PropelPDO $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection(ContentPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }
        $affectedRows = 0; // initialize var to track total num of affected rows
        try {
            // use transaction because $criteria could contain info
            // for more than one table or we could emulating ON DELETE CASCADE, etc.
            $con->beginTransaction();
            $affectedRows += ContentPeer::doOnDeleteCascade(new Criteria(ContentPeer::DATABASE_NAME), $con);
            $affectedRows += BasePeer::doDeleteAll(ContentPeer::TABLE_NAME, $con, ContentPeer::DATABASE_NAME);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            ContentPeer::clearInstancePool();
            ContentPeer::clearRelatedInstancePool();
            $con->commit();

            return $affectedRows;
        } catch (PropelException $e) {
            $con->rollBack();
            throw $e;
        }
    }

    /**
     * Performs a DELETE on the database, given a Content or Criteria object OR a primary key value.
     *
     * @param      mixed $values Criteria or Content object or primary key or array of primary keys
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
            $con = Propel::getConnection(ContentPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        if ($values instanceof Criteria) {
            // rename for clarity
            $criteria = clone $values;
        } elseif ($values instanceof Content) { // it's a model object
            // create criteria based on pk values
            $criteria = $values->buildPkeyCriteria();
        } else { // it's a primary key, or an array of pks
            $criteria = new Criteria(ContentPeer::DATABASE_NAME);
            $criteria->add(ContentPeer::ID, (array) $values, Criteria::IN);
        }

        // Set the correct dbName
        $criteria->setDbName(ContentPeer::DATABASE_NAME);

        $affectedRows = 0; // initialize var to track total num of affected rows

        try {
            // use transaction because $criteria could contain info
            // for more than one table or we could emulating ON DELETE CASCADE, etc.
            $con->beginTransaction();

            // cloning the Criteria in case it's modified by doSelect() or doSelectStmt()
            $c = clone $criteria;
            $affectedRows += ContentPeer::doOnDeleteCascade($c, $con);

            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            if ($values instanceof Criteria) {
                ContentPeer::clearInstancePool();
            } elseif ($values instanceof Content) { // it's a model object
                ContentPeer::removeInstanceFromPool($values);
            } else { // it's a primary key, or an array of pks
                foreach ((array) $values as $singleval) {
                    ContentPeer::removeInstanceFromPool($singleval);
                }
            }

            $affectedRows += BasePeer::doDelete($criteria, $con);
            ContentPeer::clearRelatedInstancePool();
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
        $objects = ContentPeer::doSelect($criteria, $con);
        foreach ($objects as $obj) {


            // delete related ContentFile objects
            $criteria = new Criteria(ContentFilePeer::DATABASE_NAME);

            $criteria->add(ContentFilePeer::LESSON_CONTENT_ID, $obj->getId());
            $affectedRows += ContentFilePeer::doDelete($criteria, $con);

            // delete related TextContent objects
            $criteria = new Criteria(TextContentPeer::DATABASE_NAME);

            $criteria->add(TextContentPeer::ID, $obj->getId());
            $affectedRows += TextContentPeer::doDelete($criteria, $con);

            // delete related UrlContent objects
            $criteria = new Criteria(UrlContentPeer::DATABASE_NAME);

            $criteria->add(UrlContentPeer::ID, $obj->getId());
            $affectedRows += UrlContentPeer::doDelete($criteria, $con);

            // delete related YoutubeContent objects
            $criteria = new Criteria(YoutubeContentPeer::DATABASE_NAME);

            $criteria->add(YoutubeContentPeer::ID, $obj->getId());
            $affectedRows += YoutubeContentPeer::doDelete($criteria, $con);

            // delete related SlideshareContent objects
            $criteria = new Criteria(SlideshareContentPeer::DATABASE_NAME);

            $criteria->add(SlideshareContentPeer::ID, $obj->getId());
            $affectedRows += SlideshareContentPeer::doDelete($criteria, $con);
        }

        return $affectedRows;
    }

    /**
     * Validates all modified columns of given Content object.
     * If parameter $columns is either a single column name or an array of column names
     * than only those columns are validated.
     *
     * NOTICE: This does not apply to primary or foreign keys for now.
     *
     * @param      Content $obj The object to validate.
     * @param      mixed $cols Column name or array of column names.
     *
     * @return mixed TRUE if all columns are valid or the error message of the first invalid column.
     */
    public static function doValidate($obj, $cols = null)
    {
        $columns = array();

        if ($cols) {
            $dbMap = Propel::getDatabaseMap(ContentPeer::DATABASE_NAME);
            $tableMap = $dbMap->getTable(ContentPeer::TABLE_NAME);

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

        return BasePeer::doValidate(ContentPeer::DATABASE_NAME, ContentPeer::TABLE_NAME, $columns);
    }

    /**
     * Retrieve a single object by pkey.
     *
     * @param      int $pk the primary key.
     * @param      PropelPDO $con the connection to use
     * @return Content
     */
    public static function retrieveByPK($pk, PropelPDO $con = null)
    {

        if (null !== ($obj = ContentPeer::getInstanceFromPool((string) $pk))) {
            return $obj;
        }

        if ($con === null) {
            $con = Propel::getConnection(ContentPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria = new Criteria(ContentPeer::DATABASE_NAME);
        $criteria->add(ContentPeer::ID, $pk);

        $v = ContentPeer::doSelect($criteria, $con);

        return !empty($v) > 0 ? $v[0] : null;
    }

    /**
     * Retrieve multiple objects by pkey.
     *
     * @param      array $pks List of primary keys
     * @param      PropelPDO $con the connection to use
     * @return Content[]
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function retrieveByPKs($pks, PropelPDO $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection(ContentPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $objs = null;
        if (empty($pks)) {
            $objs = array();
        } else {
            $criteria = new Criteria(ContentPeer::DATABASE_NAME);
            $criteria->add(ContentPeer::ID, $pks, Criteria::IN);
            $objs = ContentPeer::doSelect($criteria, $con);
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
            $con = Propel::getConnection(ContentPeer::DATABASE_NAME);
        }
        // shift the objects with a position lower than the one of object
        $c = new Criteria();
        $c->addSelectColumn('MAX(' . ContentPeer::RANK_COL . ')');
        $c->add(ContentPeer::SCOPE_COL, $scope, Criteria::EQUAL);
        $stmt = ContentPeer::doSelectStmt($c, $con);

        return $stmt->fetchColumn();
    }

    /**
     * Get an item from the list based on its rank
     *
     * @param     integer   $rank rank
     * @param      int $scope		Scope to determine which suite to consider
     * @param     PropelPDO $con optional connection
     *
     * @return Content
     */
    public static function retrieveByRank($rank, $scope = null, PropelPDO $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection(ContentPeer::DATABASE_NAME);
        }

        $c = new Criteria;
        $c->add(ContentPeer::RANK_COL, $rank);
        $c->add(ContentPeer::SCOPE_COL, $scope, Criteria::EQUAL);

        return ContentPeer::doSelectOne($c, $con);
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
            $con = Propel::getConnection(ContentPeer::DATABASE_NAME);
        }

        $con->beginTransaction();
        try {
            $ids = array_keys($order);
            $objects = ContentPeer::retrieveByPKs($ids);
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
            $con = Propel::getConnection(ContentPeer::DATABASE_NAME);
        }

        if ($criteria === null) {
            $criteria = new Criteria();
        } elseif ($criteria instanceof Criteria) {
            $criteria = clone $criteria;
        }

        $criteria->clearOrderByColumns();

        if ($order == Criteria::ASC) {
            $criteria->addAscendingOrderByColumn(ContentPeer::RANK_COL);
        } else {
            $criteria->addDescendingOrderByColumn(ContentPeer::RANK_COL);
        }

        return ContentPeer::doSelect($criteria, $con);
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
        $c->add(ContentPeer::SCOPE_COL, $scope);

        return ContentPeer::doSelectOrderByRank($c, $order, $con);
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
        $c->add(ContentPeer::SCOPE_COL, $scope);

        return ContentPeer::doCount($c, $con);
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
        $c->add(ContentPeer::SCOPE_COL, $scope);

        return ContentPeer::doDelete($c, $con);
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
            $con = Propel::getConnection(ContentPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        $whereCriteria = new Criteria(ContentPeer::DATABASE_NAME);
        $criterion = $whereCriteria->getNewCriterion(ContentPeer::RANK_COL, $first, Criteria::GREATER_EQUAL);
        if (null !== $last) {
            $criterion->addAnd($whereCriteria->getNewCriterion(ContentPeer::RANK_COL, $last, Criteria::LESS_EQUAL));
        }
        $whereCriteria->add($criterion);
        $whereCriteria->add(ContentPeer::SCOPE_COL, $scope, Criteria::EQUAL);

        $valuesCriteria = new Criteria(ContentPeer::DATABASE_NAME);
        $valuesCriteria->add(ContentPeer::RANK_COL, array('raw' => ContentPeer::RANK_COL . ' + ?', 'value' => $delta), Criteria::CUSTOM_EQUAL);

        BasePeer::doUpdate($whereCriteria, $valuesCriteria, $con);
        ContentPeer::clearInstancePool();
    }

} // BaseContentPeer

// This is the static code needed to register the TableMap for this table with the main Propel class.
//
BaseContentPeer::buildTableMap();

