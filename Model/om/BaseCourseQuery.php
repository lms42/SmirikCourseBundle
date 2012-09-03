<?php

namespace Smirik\CourseBundle\Model\om;

use \Criteria;
use \Exception;
use \ModelCriteria;
use \ModelJoin;
use \PDO;
use \Propel;
use \PropelCollection;
use \PropelException;
use \PropelObjectCollection;
use \PropelPDO;
use Smirik\CourseBundle\Model\Course;
use Smirik\CourseBundle\Model\CoursePeer;
use Smirik\CourseBundle\Model\CourseQuery;
use Smirik\CourseBundle\Model\Lesson;
use Smirik\CourseBundle\Model\UserCourse;
use Smirik\CourseBundle\Model\UserLesson;

/**
 * @method CourseQuery orderById($order = Criteria::ASC) Order by the id column
 * @method CourseQuery orderByPid($order = Criteria::ASC) Order by the pid column
 * @method CourseQuery orderByTitle($order = Criteria::ASC) Order by the title column
 * @method CourseQuery orderByDescription($order = Criteria::ASC) Order by the description column
 * @method CourseQuery orderByType($order = Criteria::ASC) Order by the type column
 * @method CourseQuery orderByFile($order = Criteria::ASC) Order by the file column
 * @method CourseQuery orderByIsPublic($order = Criteria::ASC) Order by the is_public column
 * @method CourseQuery orderByIsActive($order = Criteria::ASC) Order by the is_active column
 * @method CourseQuery orderByCreatedAt($order = Criteria::ASC) Order by the created_at column
 * @method CourseQuery orderByUpdatedAt($order = Criteria::ASC) Order by the updated_at column
 *
 * @method CourseQuery groupById() Group by the id column
 * @method CourseQuery groupByPid() Group by the pid column
 * @method CourseQuery groupByTitle() Group by the title column
 * @method CourseQuery groupByDescription() Group by the description column
 * @method CourseQuery groupByType() Group by the type column
 * @method CourseQuery groupByFile() Group by the file column
 * @method CourseQuery groupByIsPublic() Group by the is_public column
 * @method CourseQuery groupByIsActive() Group by the is_active column
 * @method CourseQuery groupByCreatedAt() Group by the created_at column
 * @method CourseQuery groupByUpdatedAt() Group by the updated_at column
 *
 * @method CourseQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method CourseQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method CourseQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method CourseQuery leftJoinCourseRelatedByPid($relationAlias = null) Adds a LEFT JOIN clause to the query using the CourseRelatedByPid relation
 * @method CourseQuery rightJoinCourseRelatedByPid($relationAlias = null) Adds a RIGHT JOIN clause to the query using the CourseRelatedByPid relation
 * @method CourseQuery innerJoinCourseRelatedByPid($relationAlias = null) Adds a INNER JOIN clause to the query using the CourseRelatedByPid relation
 *
 * @method CourseQuery leftJoinCourseRelatedById($relationAlias = null) Adds a LEFT JOIN clause to the query using the CourseRelatedById relation
 * @method CourseQuery rightJoinCourseRelatedById($relationAlias = null) Adds a RIGHT JOIN clause to the query using the CourseRelatedById relation
 * @method CourseQuery innerJoinCourseRelatedById($relationAlias = null) Adds a INNER JOIN clause to the query using the CourseRelatedById relation
 *
 * @method CourseQuery leftJoinLesson($relationAlias = null) Adds a LEFT JOIN clause to the query using the Lesson relation
 * @method CourseQuery rightJoinLesson($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Lesson relation
 * @method CourseQuery innerJoinLesson($relationAlias = null) Adds a INNER JOIN clause to the query using the Lesson relation
 *
 * @method CourseQuery leftJoinUserCourse($relationAlias = null) Adds a LEFT JOIN clause to the query using the UserCourse relation
 * @method CourseQuery rightJoinUserCourse($relationAlias = null) Adds a RIGHT JOIN clause to the query using the UserCourse relation
 * @method CourseQuery innerJoinUserCourse($relationAlias = null) Adds a INNER JOIN clause to the query using the UserCourse relation
 *
 * @method CourseQuery leftJoinUserLesson($relationAlias = null) Adds a LEFT JOIN clause to the query using the UserLesson relation
 * @method CourseQuery rightJoinUserLesson($relationAlias = null) Adds a RIGHT JOIN clause to the query using the UserLesson relation
 * @method CourseQuery innerJoinUserLesson($relationAlias = null) Adds a INNER JOIN clause to the query using the UserLesson relation
 *
 * @method Course findOne(PropelPDO $con = null) Return the first Course matching the query
 * @method Course findOneOrCreate(PropelPDO $con = null) Return the first Course matching the query, or a new Course object populated from the query conditions when no match is found
 *
 * @method Course findOneByPid(int $pid) Return the first Course filtered by the pid column
 * @method Course findOneByTitle(string $title) Return the first Course filtered by the title column
 * @method Course findOneByDescription(string $description) Return the first Course filtered by the description column
 * @method Course findOneByType(int $type) Return the first Course filtered by the type column
 * @method Course findOneByFile(string $file) Return the first Course filtered by the file column
 * @method Course findOneByIsPublic(boolean $is_public) Return the first Course filtered by the is_public column
 * @method Course findOneByIsActive(boolean $is_active) Return the first Course filtered by the is_active column
 * @method Course findOneByCreatedAt(string $created_at) Return the first Course filtered by the created_at column
 * @method Course findOneByUpdatedAt(string $updated_at) Return the first Course filtered by the updated_at column
 *
 * @method array findById(int $id) Return Course objects filtered by the id column
 * @method array findByPid(int $pid) Return Course objects filtered by the pid column
 * @method array findByTitle(string $title) Return Course objects filtered by the title column
 * @method array findByDescription(string $description) Return Course objects filtered by the description column
 * @method array findByType(int $type) Return Course objects filtered by the type column
 * @method array findByFile(string $file) Return Course objects filtered by the file column
 * @method array findByIsPublic(boolean $is_public) Return Course objects filtered by the is_public column
 * @method array findByIsActive(boolean $is_active) Return Course objects filtered by the is_active column
 * @method array findByCreatedAt(string $created_at) Return Course objects filtered by the created_at column
 * @method array findByUpdatedAt(string $updated_at) Return Course objects filtered by the updated_at column
 */
abstract class BaseCourseQuery extends ModelCriteria
{
    /**
     * Initializes internal state of BaseCourseQuery object.
     *
     * @param     string $dbName The dabase name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = 'Smirik\\CourseBundle\\Model\\Course', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new CourseQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     CourseQuery|Criteria $criteria Optional Criteria to build the query from
     *
     * @return CourseQuery
     */
    public static function create($modelAlias = null, $criteria = null)
    {
        if ($criteria instanceof CourseQuery) {
            return $criteria;
        }
        $query = new CourseQuery();
        if (null !== $modelAlias) {
            $query->setModelAlias($modelAlias);
        }
        if ($criteria instanceof Criteria) {
            $query->mergeWith($criteria);
        }

        return $query;
    }

    /**
     * Find object by primary key.
     * Propel uses the instance pool to skip the database if the object exists.
     * Go fast if the query is untouched.
     *
     * <code>
     * $obj  = $c->findPk(12, $con);
     * </code>
     *
     * @param mixed $key Primary key to use for the query
     * @param     PropelPDO $con an optional connection object
     *
     * @return   Course|Course[]|mixed the result, formatted by the current formatter
     */
    public function findPk($key, $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = CoursePeer::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is alredy in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getConnection(CoursePeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }
        $this->basePreSelect($con);
        if ($this->formatter || $this->modelAlias || $this->with || $this->select
         || $this->selectColumns || $this->asColumns || $this->selectModifiers
         || $this->map || $this->having || $this->joins) {
            return $this->findPkComplex($key, $con);
        } else {
            return $this->findPkSimple($key, $con);
        }
    }

    /**
     * Alias of findPk to use instance pooling
     *
     * @param     mixed $key Primary key to use for the query
     * @param     PropelPDO $con A connection object
     *
     * @return   Course A model object, or null if the key is not found
     * @throws   PropelException
     */
     public function findOneById($key, $con = null)
     {
        return $this->findPk($key, $con);
     }

    /**
     * Find object by primary key using raw SQL to go fast.
     * Bypass doSelect() and the object formatter by using generated code.
     *
     * @param     mixed $key Primary key to use for the query
     * @param     PropelPDO $con A connection object
     *
     * @return   Course A model object, or null if the key is not found
     * @throws   PropelException
     */
    protected function findPkSimple($key, $con)
    {
        $sql = 'SELECT `ID`, `PID`, `TITLE`, `DESCRIPTION`, `TYPE`, `FILE`, `IS_PUBLIC`, `IS_ACTIVE`, `CREATED_AT`, `UPDATED_AT` FROM `courses` WHERE `ID` = :p0';
        try {
            $stmt = $con->prepare($sql);
            $stmt->bindValue(':p0', $key, PDO::PARAM_INT);
            $stmt->execute();
        } catch (Exception $e) {
            Propel::log($e->getMessage(), Propel::LOG_ERR);
            throw new PropelException(sprintf('Unable to execute SELECT statement [%s]', $sql), $e);
        }
        $obj = null;
        if ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $obj = new Course();
            $obj->hydrate($row);
            CoursePeer::addInstanceToPool($obj, (string) $key);
        }
        $stmt->closeCursor();

        return $obj;
    }

    /**
     * Find object by primary key.
     *
     * @param     mixed $key Primary key to use for the query
     * @param     PropelPDO $con A connection object
     *
     * @return Course|Course[]|mixed the result, formatted by the current formatter
     */
    protected function findPkComplex($key, $con)
    {
        // As the query uses a PK condition, no limit(1) is necessary.
        $criteria = $this->isKeepQuery() ? clone $this : $this;
        $stmt = $criteria
            ->filterByPrimaryKey($key)
            ->doSelect($con);

        return $criteria->getFormatter()->init($criteria)->formatOne($stmt);
    }

    /**
     * Find objects by primary key
     * <code>
     * $objs = $c->findPks(array(12, 56, 832), $con);
     * </code>
     * @param     array $keys Primary keys to use for the query
     * @param     PropelPDO $con an optional connection object
     *
     * @return PropelObjectCollection|Course[]|mixed the list of results, formatted by the current formatter
     */
    public function findPks($keys, $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection($this->getDbName(), Propel::CONNECTION_READ);
        }
        $this->basePreSelect($con);
        $criteria = $this->isKeepQuery() ? clone $this : $this;
        $stmt = $criteria
            ->filterByPrimaryKeys($keys)
            ->doSelect($con);

        return $criteria->getFormatter()->init($criteria)->format($stmt);
    }

    /**
     * Filter the query by primary key
     *
     * @param     mixed $key Primary key to use for the query
     *
     * @return CourseQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(CoursePeer::ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return CourseQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(CoursePeer::ID, $keys, Criteria::IN);
    }

    /**
     * Filter the query on the id column
     *
     * Example usage:
     * <code>
     * $query->filterById(1234); // WHERE id = 1234
     * $query->filterById(array(12, 34)); // WHERE id IN (12, 34)
     * $query->filterById(array('min' => 12)); // WHERE id > 12
     * </code>
     *
     * @param     mixed $id The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return CourseQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id) && null === $comparison) {
            $comparison = Criteria::IN;
        }

        return $this->addUsingAlias(CoursePeer::ID, $id, $comparison);
    }

    /**
     * Filter the query on the pid column
     *
     * Example usage:
     * <code>
     * $query->filterByPid(1234); // WHERE pid = 1234
     * $query->filterByPid(array(12, 34)); // WHERE pid IN (12, 34)
     * $query->filterByPid(array('min' => 12)); // WHERE pid > 12
     * </code>
     *
     * @see       filterByCourseRelatedByPid()
     *
     * @param     mixed $pid The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return CourseQuery The current query, for fluid interface
     */
    public function filterByPid($pid = null, $comparison = null)
    {
        if (is_array($pid)) {
            $useMinMax = false;
            if (isset($pid['min'])) {
                $this->addUsingAlias(CoursePeer::PID, $pid['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($pid['max'])) {
                $this->addUsingAlias(CoursePeer::PID, $pid['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(CoursePeer::PID, $pid, $comparison);
    }

    /**
     * Filter the query on the title column
     *
     * Example usage:
     * <code>
     * $query->filterByTitle('fooValue');   // WHERE title = 'fooValue'
     * $query->filterByTitle('%fooValue%'); // WHERE title LIKE '%fooValue%'
     * </code>
     *
     * @param     string $title The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return CourseQuery The current query, for fluid interface
     */
    public function filterByTitle($title = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($title)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $title)) {
                $title = str_replace('*', '%', $title);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(CoursePeer::TITLE, $title, $comparison);
    }

    /**
     * Filter the query on the description column
     *
     * Example usage:
     * <code>
     * $query->filterByDescription('fooValue');   // WHERE description = 'fooValue'
     * $query->filterByDescription('%fooValue%'); // WHERE description LIKE '%fooValue%'
     * </code>
     *
     * @param     string $description The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return CourseQuery The current query, for fluid interface
     */
    public function filterByDescription($description = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($description)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $description)) {
                $description = str_replace('*', '%', $description);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(CoursePeer::DESCRIPTION, $description, $comparison);
    }

    /**
     * Filter the query on the type column
     *
     * Example usage:
     * <code>
     * $query->filterByType(1234); // WHERE type = 1234
     * $query->filterByType(array(12, 34)); // WHERE type IN (12, 34)
     * $query->filterByType(array('min' => 12)); // WHERE type > 12
     * </code>
     *
     * @param     mixed $type The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return CourseQuery The current query, for fluid interface
     */
    public function filterByType($type = null, $comparison = null)
    {
        if (is_array($type)) {
            $useMinMax = false;
            if (isset($type['min'])) {
                $this->addUsingAlias(CoursePeer::TYPE, $type['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($type['max'])) {
                $this->addUsingAlias(CoursePeer::TYPE, $type['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(CoursePeer::TYPE, $type, $comparison);
    }

    /**
     * Filter the query on the file column
     *
     * Example usage:
     * <code>
     * $query->filterByFile('fooValue');   // WHERE file = 'fooValue'
     * $query->filterByFile('%fooValue%'); // WHERE file LIKE '%fooValue%'
     * </code>
     *
     * @param     string $file The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return CourseQuery The current query, for fluid interface
     */
    public function filterByFile($file = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($file)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $file)) {
                $file = str_replace('*', '%', $file);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(CoursePeer::FILE, $file, $comparison);
    }

    /**
     * Filter the query on the is_public column
     *
     * Example usage:
     * <code>
     * $query->filterByIsPublic(true); // WHERE is_public = true
     * $query->filterByIsPublic('yes'); // WHERE is_public = true
     * </code>
     *
     * @param     boolean|string $isPublic The value to use as filter.
     *              Non-boolean arguments are converted using the following rules:
     *                * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *                * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     *              Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return CourseQuery The current query, for fluid interface
     */
    public function filterByIsPublic($isPublic = null, $comparison = null)
    {
        if (is_string($isPublic)) {
            $is_public = in_array(strtolower($isPublic), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
        }

        return $this->addUsingAlias(CoursePeer::IS_PUBLIC, $isPublic, $comparison);
    }

    /**
     * Filter the query on the is_active column
     *
     * Example usage:
     * <code>
     * $query->filterByIsActive(true); // WHERE is_active = true
     * $query->filterByIsActive('yes'); // WHERE is_active = true
     * </code>
     *
     * @param     boolean|string $isActive The value to use as filter.
     *              Non-boolean arguments are converted using the following rules:
     *                * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *                * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     *              Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return CourseQuery The current query, for fluid interface
     */
    public function filterByIsActive($isActive = null, $comparison = null)
    {
        if (is_string($isActive)) {
            $is_active = in_array(strtolower($isActive), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
        }

        return $this->addUsingAlias(CoursePeer::IS_ACTIVE, $isActive, $comparison);
    }

    /**
     * Filter the query on the created_at column
     *
     * Example usage:
     * <code>
     * $query->filterByCreatedAt('2011-03-14'); // WHERE created_at = '2011-03-14'
     * $query->filterByCreatedAt('now'); // WHERE created_at = '2011-03-14'
     * $query->filterByCreatedAt(array('max' => 'yesterday')); // WHERE created_at > '2011-03-13'
     * </code>
     *
     * @param     mixed $createdAt The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return CourseQuery The current query, for fluid interface
     */
    public function filterByCreatedAt($createdAt = null, $comparison = null)
    {
        if (is_array($createdAt)) {
            $useMinMax = false;
            if (isset($createdAt['min'])) {
                $this->addUsingAlias(CoursePeer::CREATED_AT, $createdAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($createdAt['max'])) {
                $this->addUsingAlias(CoursePeer::CREATED_AT, $createdAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(CoursePeer::CREATED_AT, $createdAt, $comparison);
    }

    /**
     * Filter the query on the updated_at column
     *
     * Example usage:
     * <code>
     * $query->filterByUpdatedAt('2011-03-14'); // WHERE updated_at = '2011-03-14'
     * $query->filterByUpdatedAt('now'); // WHERE updated_at = '2011-03-14'
     * $query->filterByUpdatedAt(array('max' => 'yesterday')); // WHERE updated_at > '2011-03-13'
     * </code>
     *
     * @param     mixed $updatedAt The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return CourseQuery The current query, for fluid interface
     */
    public function filterByUpdatedAt($updatedAt = null, $comparison = null)
    {
        if (is_array($updatedAt)) {
            $useMinMax = false;
            if (isset($updatedAt['min'])) {
                $this->addUsingAlias(CoursePeer::UPDATED_AT, $updatedAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($updatedAt['max'])) {
                $this->addUsingAlias(CoursePeer::UPDATED_AT, $updatedAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(CoursePeer::UPDATED_AT, $updatedAt, $comparison);
    }

    /**
     * Filter the query by a related Course object
     *
     * @param   Course|PropelObjectCollection $course The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return   CourseQuery The current query, for fluid interface
     * @throws   PropelException - if the provided filter is invalid.
     */
    public function filterByCourseRelatedByPid($course, $comparison = null)
    {
        if ($course instanceof Course) {
            return $this
                ->addUsingAlias(CoursePeer::PID, $course->getId(), $comparison);
        } elseif ($course instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(CoursePeer::PID, $course->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByCourseRelatedByPid() only accepts arguments of type Course or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the CourseRelatedByPid relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return CourseQuery The current query, for fluid interface
     */
    public function joinCourseRelatedByPid($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('CourseRelatedByPid');

        // create a ModelJoin object for this join
        $join = new ModelJoin();
        $join->setJoinType($joinType);
        $join->setRelationMap($relationMap, $this->useAliasInSQL ? $this->getModelAlias() : null, $relationAlias);
        if ($previousJoin = $this->getPreviousJoin()) {
            $join->setPreviousJoin($previousJoin);
        }

        // add the ModelJoin to the current object
        if ($relationAlias) {
            $this->addAlias($relationAlias, $relationMap->getRightTable()->getName());
            $this->addJoinObject($join, $relationAlias);
        } else {
            $this->addJoinObject($join, 'CourseRelatedByPid');
        }

        return $this;
    }

    /**
     * Use the CourseRelatedByPid relation Course object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \Smirik\CourseBundle\Model\CourseQuery A secondary query class using the current class as primary query
     */
    public function useCourseRelatedByPidQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinCourseRelatedByPid($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'CourseRelatedByPid', '\Smirik\CourseBundle\Model\CourseQuery');
    }

    /**
     * Filter the query by a related Course object
     *
     * @param   Course|PropelObjectCollection $course  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return   CourseQuery The current query, for fluid interface
     * @throws   PropelException - if the provided filter is invalid.
     */
    public function filterByCourseRelatedById($course, $comparison = null)
    {
        if ($course instanceof Course) {
            return $this
                ->addUsingAlias(CoursePeer::ID, $course->getPid(), $comparison);
        } elseif ($course instanceof PropelObjectCollection) {
            return $this
                ->useCourseRelatedByIdQuery()
                ->filterByPrimaryKeys($course->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByCourseRelatedById() only accepts arguments of type Course or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the CourseRelatedById relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return CourseQuery The current query, for fluid interface
     */
    public function joinCourseRelatedById($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('CourseRelatedById');

        // create a ModelJoin object for this join
        $join = new ModelJoin();
        $join->setJoinType($joinType);
        $join->setRelationMap($relationMap, $this->useAliasInSQL ? $this->getModelAlias() : null, $relationAlias);
        if ($previousJoin = $this->getPreviousJoin()) {
            $join->setPreviousJoin($previousJoin);
        }

        // add the ModelJoin to the current object
        if ($relationAlias) {
            $this->addAlias($relationAlias, $relationMap->getRightTable()->getName());
            $this->addJoinObject($join, $relationAlias);
        } else {
            $this->addJoinObject($join, 'CourseRelatedById');
        }

        return $this;
    }

    /**
     * Use the CourseRelatedById relation Course object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \Smirik\CourseBundle\Model\CourseQuery A secondary query class using the current class as primary query
     */
    public function useCourseRelatedByIdQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinCourseRelatedById($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'CourseRelatedById', '\Smirik\CourseBundle\Model\CourseQuery');
    }

    /**
     * Filter the query by a related Lesson object
     *
     * @param   Lesson|PropelObjectCollection $lesson  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return   CourseQuery The current query, for fluid interface
     * @throws   PropelException - if the provided filter is invalid.
     */
    public function filterByLesson($lesson, $comparison = null)
    {
        if ($lesson instanceof Lesson) {
            return $this
                ->addUsingAlias(CoursePeer::ID, $lesson->getCourseId(), $comparison);
        } elseif ($lesson instanceof PropelObjectCollection) {
            return $this
                ->useLessonQuery()
                ->filterByPrimaryKeys($lesson->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByLesson() only accepts arguments of type Lesson or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Lesson relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return CourseQuery The current query, for fluid interface
     */
    public function joinLesson($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Lesson');

        // create a ModelJoin object for this join
        $join = new ModelJoin();
        $join->setJoinType($joinType);
        $join->setRelationMap($relationMap, $this->useAliasInSQL ? $this->getModelAlias() : null, $relationAlias);
        if ($previousJoin = $this->getPreviousJoin()) {
            $join->setPreviousJoin($previousJoin);
        }

        // add the ModelJoin to the current object
        if ($relationAlias) {
            $this->addAlias($relationAlias, $relationMap->getRightTable()->getName());
            $this->addJoinObject($join, $relationAlias);
        } else {
            $this->addJoinObject($join, 'Lesson');
        }

        return $this;
    }

    /**
     * Use the Lesson relation Lesson object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \Smirik\CourseBundle\Model\LessonQuery A secondary query class using the current class as primary query
     */
    public function useLessonQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinLesson($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Lesson', '\Smirik\CourseBundle\Model\LessonQuery');
    }

    /**
     * Filter the query by a related UserCourse object
     *
     * @param   UserCourse|PropelObjectCollection $userCourse  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return   CourseQuery The current query, for fluid interface
     * @throws   PropelException - if the provided filter is invalid.
     */
    public function filterByUserCourse($userCourse, $comparison = null)
    {
        if ($userCourse instanceof UserCourse) {
            return $this
                ->addUsingAlias(CoursePeer::ID, $userCourse->getCourseId(), $comparison);
        } elseif ($userCourse instanceof PropelObjectCollection) {
            return $this
                ->useUserCourseQuery()
                ->filterByPrimaryKeys($userCourse->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByUserCourse() only accepts arguments of type UserCourse or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the UserCourse relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return CourseQuery The current query, for fluid interface
     */
    public function joinUserCourse($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('UserCourse');

        // create a ModelJoin object for this join
        $join = new ModelJoin();
        $join->setJoinType($joinType);
        $join->setRelationMap($relationMap, $this->useAliasInSQL ? $this->getModelAlias() : null, $relationAlias);
        if ($previousJoin = $this->getPreviousJoin()) {
            $join->setPreviousJoin($previousJoin);
        }

        // add the ModelJoin to the current object
        if ($relationAlias) {
            $this->addAlias($relationAlias, $relationMap->getRightTable()->getName());
            $this->addJoinObject($join, $relationAlias);
        } else {
            $this->addJoinObject($join, 'UserCourse');
        }

        return $this;
    }

    /**
     * Use the UserCourse relation UserCourse object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \Smirik\CourseBundle\Model\UserCourseQuery A secondary query class using the current class as primary query
     */
    public function useUserCourseQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinUserCourse($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'UserCourse', '\Smirik\CourseBundle\Model\UserCourseQuery');
    }

    /**
     * Filter the query by a related UserLesson object
     *
     * @param   UserLesson|PropelObjectCollection $userLesson  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return   CourseQuery The current query, for fluid interface
     * @throws   PropelException - if the provided filter is invalid.
     */
    public function filterByUserLesson($userLesson, $comparison = null)
    {
        if ($userLesson instanceof UserLesson) {
            return $this
                ->addUsingAlias(CoursePeer::ID, $userLesson->getCourseId(), $comparison);
        } elseif ($userLesson instanceof PropelObjectCollection) {
            return $this
                ->useUserLessonQuery()
                ->filterByPrimaryKeys($userLesson->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByUserLesson() only accepts arguments of type UserLesson or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the UserLesson relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return CourseQuery The current query, for fluid interface
     */
    public function joinUserLesson($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('UserLesson');

        // create a ModelJoin object for this join
        $join = new ModelJoin();
        $join->setJoinType($joinType);
        $join->setRelationMap($relationMap, $this->useAliasInSQL ? $this->getModelAlias() : null, $relationAlias);
        if ($previousJoin = $this->getPreviousJoin()) {
            $join->setPreviousJoin($previousJoin);
        }

        // add the ModelJoin to the current object
        if ($relationAlias) {
            $this->addAlias($relationAlias, $relationMap->getRightTable()->getName());
            $this->addJoinObject($join, $relationAlias);
        } else {
            $this->addJoinObject($join, 'UserLesson');
        }

        return $this;
    }

    /**
     * Use the UserLesson relation UserLesson object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \Smirik\CourseBundle\Model\UserLessonQuery A secondary query class using the current class as primary query
     */
    public function useUserLessonQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinUserLesson($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'UserLesson', '\Smirik\CourseBundle\Model\UserLessonQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   Course $course Object to remove from the list of results
     *
     * @return CourseQuery The current query, for fluid interface
     */
    public function prune($course = null)
    {
        if ($course) {
            $this->addUsingAlias(CoursePeer::ID, $course->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    // timestampable behavior

    /**
     * Filter by the latest updated
     *
     * @param      int $nbDays Maximum age of the latest update in days
     *
     * @return     CourseQuery The current query, for fluid interface
     */
    public function recentlyUpdated($nbDays = 7)
    {
        return $this->addUsingAlias(CoursePeer::UPDATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by update date desc
     *
     * @return     CourseQuery The current query, for fluid interface
     */
    public function lastUpdatedFirst()
    {
        return $this->addDescendingOrderByColumn(CoursePeer::UPDATED_AT);
    }

    /**
     * Order by update date asc
     *
     * @return     CourseQuery The current query, for fluid interface
     */
    public function firstUpdatedFirst()
    {
        return $this->addAscendingOrderByColumn(CoursePeer::UPDATED_AT);
    }

    /**
     * Filter by the latest created
     *
     * @param      int $nbDays Maximum age of in days
     *
     * @return     CourseQuery The current query, for fluid interface
     */
    public function recentlyCreated($nbDays = 7)
    {
        return $this->addUsingAlias(CoursePeer::CREATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by create date desc
     *
     * @return     CourseQuery The current query, for fluid interface
     */
    public function lastCreatedFirst()
    {
        return $this->addDescendingOrderByColumn(CoursePeer::CREATED_AT);
    }

    /**
     * Order by create date asc
     *
     * @return     CourseQuery The current query, for fluid interface
     */
    public function firstCreatedFirst()
    {
        return $this->addAscendingOrderByColumn(CoursePeer::CREATED_AT);
    }
}
