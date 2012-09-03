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
use FOS\UserBundle\Propel\User;
use Smirik\CourseBundle\Model\Course;
use Smirik\CourseBundle\Model\UserCourse;
use Smirik\CourseBundle\Model\UserCoursePeer;
use Smirik\CourseBundle\Model\UserCourseQuery;

/**
 * @method UserCourseQuery orderById($order = Criteria::ASC) Order by the id column
 * @method UserCourseQuery orderByUserId($order = Criteria::ASC) Order by the user_id column
 * @method UserCourseQuery orderByCourseId($order = Criteria::ASC) Order by the course_id column
 * @method UserCourseQuery orderByStartedAt($order = Criteria::ASC) Order by the started_at column
 * @method UserCourseQuery orderByStoppedAt($order = Criteria::ASC) Order by the stopped_at column
 * @method UserCourseQuery orderByIsPassed($order = Criteria::ASC) Order by the is_passed column
 * @method UserCourseQuery orderByUpdatedAt($order = Criteria::ASC) Order by the updated_at column
 *
 * @method UserCourseQuery groupById() Group by the id column
 * @method UserCourseQuery groupByUserId() Group by the user_id column
 * @method UserCourseQuery groupByCourseId() Group by the course_id column
 * @method UserCourseQuery groupByStartedAt() Group by the started_at column
 * @method UserCourseQuery groupByStoppedAt() Group by the stopped_at column
 * @method UserCourseQuery groupByIsPassed() Group by the is_passed column
 * @method UserCourseQuery groupByUpdatedAt() Group by the updated_at column
 *
 * @method UserCourseQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method UserCourseQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method UserCourseQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method UserCourseQuery leftJoinUser($relationAlias = null) Adds a LEFT JOIN clause to the query using the User relation
 * @method UserCourseQuery rightJoinUser($relationAlias = null) Adds a RIGHT JOIN clause to the query using the User relation
 * @method UserCourseQuery innerJoinUser($relationAlias = null) Adds a INNER JOIN clause to the query using the User relation
 *
 * @method UserCourseQuery leftJoinCourse($relationAlias = null) Adds a LEFT JOIN clause to the query using the Course relation
 * @method UserCourseQuery rightJoinCourse($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Course relation
 * @method UserCourseQuery innerJoinCourse($relationAlias = null) Adds a INNER JOIN clause to the query using the Course relation
 *
 * @method UserCourse findOne(PropelPDO $con = null) Return the first UserCourse matching the query
 * @method UserCourse findOneOrCreate(PropelPDO $con = null) Return the first UserCourse matching the query, or a new UserCourse object populated from the query conditions when no match is found
 *
 * @method UserCourse findOneByUserId(int $user_id) Return the first UserCourse filtered by the user_id column
 * @method UserCourse findOneByCourseId(int $course_id) Return the first UserCourse filtered by the course_id column
 * @method UserCourse findOneByStartedAt(string $started_at) Return the first UserCourse filtered by the started_at column
 * @method UserCourse findOneByStoppedAt(string $stopped_at) Return the first UserCourse filtered by the stopped_at column
 * @method UserCourse findOneByIsPassed(boolean $is_passed) Return the first UserCourse filtered by the is_passed column
 * @method UserCourse findOneByUpdatedAt(string $updated_at) Return the first UserCourse filtered by the updated_at column
 *
 * @method array findById(int $id) Return UserCourse objects filtered by the id column
 * @method array findByUserId(int $user_id) Return UserCourse objects filtered by the user_id column
 * @method array findByCourseId(int $course_id) Return UserCourse objects filtered by the course_id column
 * @method array findByStartedAt(string $started_at) Return UserCourse objects filtered by the started_at column
 * @method array findByStoppedAt(string $stopped_at) Return UserCourse objects filtered by the stopped_at column
 * @method array findByIsPassed(boolean $is_passed) Return UserCourse objects filtered by the is_passed column
 * @method array findByUpdatedAt(string $updated_at) Return UserCourse objects filtered by the updated_at column
 */
abstract class BaseUserCourseQuery extends ModelCriteria
{
    /**
     * Initializes internal state of BaseUserCourseQuery object.
     *
     * @param     string $dbName The dabase name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = 'Smirik\\CourseBundle\\Model\\UserCourse', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new UserCourseQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     UserCourseQuery|Criteria $criteria Optional Criteria to build the query from
     *
     * @return UserCourseQuery
     */
    public static function create($modelAlias = null, $criteria = null)
    {
        if ($criteria instanceof UserCourseQuery) {
            return $criteria;
        }
        $query = new UserCourseQuery();
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
     * @return   UserCourse|UserCourse[]|mixed the result, formatted by the current formatter
     */
    public function findPk($key, $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = UserCoursePeer::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is alredy in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getConnection(UserCoursePeer::DATABASE_NAME, Propel::CONNECTION_READ);
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
     * @return   UserCourse A model object, or null if the key is not found
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
     * @return   UserCourse A model object, or null if the key is not found
     * @throws   PropelException
     */
    protected function findPkSimple($key, $con)
    {
        $sql = 'SELECT `ID`, `USER_ID`, `COURSE_ID`, `STARTED_AT`, `STOPPED_AT`, `IS_PASSED`, `UPDATED_AT` FROM `users_courses` WHERE `ID` = :p0';
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
            $obj = new UserCourse();
            $obj->hydrate($row);
            UserCoursePeer::addInstanceToPool($obj, (string) $key);
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
     * @return UserCourse|UserCourse[]|mixed the result, formatted by the current formatter
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
     * @return PropelObjectCollection|UserCourse[]|mixed the list of results, formatted by the current formatter
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
     * @return UserCourseQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(UserCoursePeer::ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return UserCourseQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(UserCoursePeer::ID, $keys, Criteria::IN);
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
     * @return UserCourseQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id) && null === $comparison) {
            $comparison = Criteria::IN;
        }

        return $this->addUsingAlias(UserCoursePeer::ID, $id, $comparison);
    }

    /**
     * Filter the query on the user_id column
     *
     * Example usage:
     * <code>
     * $query->filterByUserId(1234); // WHERE user_id = 1234
     * $query->filterByUserId(array(12, 34)); // WHERE user_id IN (12, 34)
     * $query->filterByUserId(array('min' => 12)); // WHERE user_id > 12
     * </code>
     *
     * @see       filterByUser()
     *
     * @param     mixed $userId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return UserCourseQuery The current query, for fluid interface
     */
    public function filterByUserId($userId = null, $comparison = null)
    {
        if (is_array($userId)) {
            $useMinMax = false;
            if (isset($userId['min'])) {
                $this->addUsingAlias(UserCoursePeer::USER_ID, $userId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($userId['max'])) {
                $this->addUsingAlias(UserCoursePeer::USER_ID, $userId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(UserCoursePeer::USER_ID, $userId, $comparison);
    }

    /**
     * Filter the query on the course_id column
     *
     * Example usage:
     * <code>
     * $query->filterByCourseId(1234); // WHERE course_id = 1234
     * $query->filterByCourseId(array(12, 34)); // WHERE course_id IN (12, 34)
     * $query->filterByCourseId(array('min' => 12)); // WHERE course_id > 12
     * </code>
     *
     * @see       filterByCourse()
     *
     * @param     mixed $courseId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return UserCourseQuery The current query, for fluid interface
     */
    public function filterByCourseId($courseId = null, $comparison = null)
    {
        if (is_array($courseId)) {
            $useMinMax = false;
            if (isset($courseId['min'])) {
                $this->addUsingAlias(UserCoursePeer::COURSE_ID, $courseId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($courseId['max'])) {
                $this->addUsingAlias(UserCoursePeer::COURSE_ID, $courseId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(UserCoursePeer::COURSE_ID, $courseId, $comparison);
    }

    /**
     * Filter the query on the started_at column
     *
     * Example usage:
     * <code>
     * $query->filterByStartedAt('2011-03-14'); // WHERE started_at = '2011-03-14'
     * $query->filterByStartedAt('now'); // WHERE started_at = '2011-03-14'
     * $query->filterByStartedAt(array('max' => 'yesterday')); // WHERE started_at > '2011-03-13'
     * </code>
     *
     * @param     mixed $startedAt The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return UserCourseQuery The current query, for fluid interface
     */
    public function filterByStartedAt($startedAt = null, $comparison = null)
    {
        if (is_array($startedAt)) {
            $useMinMax = false;
            if (isset($startedAt['min'])) {
                $this->addUsingAlias(UserCoursePeer::STARTED_AT, $startedAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($startedAt['max'])) {
                $this->addUsingAlias(UserCoursePeer::STARTED_AT, $startedAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(UserCoursePeer::STARTED_AT, $startedAt, $comparison);
    }

    /**
     * Filter the query on the stopped_at column
     *
     * Example usage:
     * <code>
     * $query->filterByStoppedAt('2011-03-14'); // WHERE stopped_at = '2011-03-14'
     * $query->filterByStoppedAt('now'); // WHERE stopped_at = '2011-03-14'
     * $query->filterByStoppedAt(array('max' => 'yesterday')); // WHERE stopped_at > '2011-03-13'
     * </code>
     *
     * @param     mixed $stoppedAt The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return UserCourseQuery The current query, for fluid interface
     */
    public function filterByStoppedAt($stoppedAt = null, $comparison = null)
    {
        if (is_array($stoppedAt)) {
            $useMinMax = false;
            if (isset($stoppedAt['min'])) {
                $this->addUsingAlias(UserCoursePeer::STOPPED_AT, $stoppedAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($stoppedAt['max'])) {
                $this->addUsingAlias(UserCoursePeer::STOPPED_AT, $stoppedAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(UserCoursePeer::STOPPED_AT, $stoppedAt, $comparison);
    }

    /**
     * Filter the query on the is_passed column
     *
     * Example usage:
     * <code>
     * $query->filterByIsPassed(true); // WHERE is_passed = true
     * $query->filterByIsPassed('yes'); // WHERE is_passed = true
     * </code>
     *
     * @param     boolean|string $isPassed The value to use as filter.
     *              Non-boolean arguments are converted using the following rules:
     *                * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *                * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     *              Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return UserCourseQuery The current query, for fluid interface
     */
    public function filterByIsPassed($isPassed = null, $comparison = null)
    {
        if (is_string($isPassed)) {
            $is_passed = in_array(strtolower($isPassed), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
        }

        return $this->addUsingAlias(UserCoursePeer::IS_PASSED, $isPassed, $comparison);
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
     * @return UserCourseQuery The current query, for fluid interface
     */
    public function filterByUpdatedAt($updatedAt = null, $comparison = null)
    {
        if (is_array($updatedAt)) {
            $useMinMax = false;
            if (isset($updatedAt['min'])) {
                $this->addUsingAlias(UserCoursePeer::UPDATED_AT, $updatedAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($updatedAt['max'])) {
                $this->addUsingAlias(UserCoursePeer::UPDATED_AT, $updatedAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(UserCoursePeer::UPDATED_AT, $updatedAt, $comparison);
    }

    /**
     * Filter the query by a related User object
     *
     * @param   User|PropelObjectCollection $user The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return   UserCourseQuery The current query, for fluid interface
     * @throws   PropelException - if the provided filter is invalid.
     */
    public function filterByUser($user, $comparison = null)
    {
        if ($user instanceof User) {
            return $this
                ->addUsingAlias(UserCoursePeer::USER_ID, $user->getId(), $comparison);
        } elseif ($user instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(UserCoursePeer::USER_ID, $user->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByUser() only accepts arguments of type User or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the User relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return UserCourseQuery The current query, for fluid interface
     */
    public function joinUser($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('User');

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
            $this->addJoinObject($join, 'User');
        }

        return $this;
    }

    /**
     * Use the User relation User object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \FOS\UserBundle\Propel\UserQuery A secondary query class using the current class as primary query
     */
    public function useUserQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinUser($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'User', '\FOS\UserBundle\Propel\UserQuery');
    }

    /**
     * Filter the query by a related Course object
     *
     * @param   Course|PropelObjectCollection $course The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return   UserCourseQuery The current query, for fluid interface
     * @throws   PropelException - if the provided filter is invalid.
     */
    public function filterByCourse($course, $comparison = null)
    {
        if ($course instanceof Course) {
            return $this
                ->addUsingAlias(UserCoursePeer::COURSE_ID, $course->getId(), $comparison);
        } elseif ($course instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(UserCoursePeer::COURSE_ID, $course->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByCourse() only accepts arguments of type Course or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Course relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return UserCourseQuery The current query, for fluid interface
     */
    public function joinCourse($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Course');

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
            $this->addJoinObject($join, 'Course');
        }

        return $this;
    }

    /**
     * Use the Course relation Course object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \Smirik\CourseBundle\Model\CourseQuery A secondary query class using the current class as primary query
     */
    public function useCourseQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinCourse($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Course', '\Smirik\CourseBundle\Model\CourseQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   UserCourse $userCourse Object to remove from the list of results
     *
     * @return UserCourseQuery The current query, for fluid interface
     */
    public function prune($userCourse = null)
    {
        if ($userCourse) {
            $this->addUsingAlias(UserCoursePeer::ID, $userCourse->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    // timestampable behavior

    /**
     * Filter by the latest updated
     *
     * @param      int $nbDays Maximum age of the latest update in days
     *
     * @return     UserCourseQuery The current query, for fluid interface
     */
    public function recentlyUpdated($nbDays = 7)
    {
        return $this->addUsingAlias(UserCoursePeer::UPDATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by update date desc
     *
     * @return     UserCourseQuery The current query, for fluid interface
     */
    public function lastUpdatedFirst()
    {
        return $this->addDescendingOrderByColumn(UserCoursePeer::UPDATED_AT);
    }

    /**
     * Order by update date asc
     *
     * @return     UserCourseQuery The current query, for fluid interface
     */
    public function firstUpdatedFirst()
    {
        return $this->addAscendingOrderByColumn(UserCoursePeer::UPDATED_AT);
    }

    /**
     * Filter by the latest created
     *
     * @param      int $nbDays Maximum age of in days
     *
     * @return     UserCourseQuery The current query, for fluid interface
     */
    public function recentlyCreated($nbDays = 7)
    {
        return $this->addUsingAlias(UserCoursePeer::STARTED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by create date desc
     *
     * @return     UserCourseQuery The current query, for fluid interface
     */
    public function lastCreatedFirst()
    {
        return $this->addDescendingOrderByColumn(UserCoursePeer::STARTED_AT);
    }

    /**
     * Order by create date asc
     *
     * @return     UserCourseQuery The current query, for fluid interface
     */
    public function firstCreatedFirst()
    {
        return $this->addAscendingOrderByColumn(UserCoursePeer::STARTED_AT);
    }
}
