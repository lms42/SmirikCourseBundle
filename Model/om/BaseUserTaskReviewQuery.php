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
use Smirik\CourseBundle\Model\UserTask;
use Smirik\CourseBundle\Model\UserTaskReview;
use Smirik\CourseBundle\Model\UserTaskReviewPeer;
use Smirik\CourseBundle\Model\UserTaskReviewQuery;

/**
 * @method UserTaskReviewQuery orderById($order = Criteria::ASC) Order by the id column
 * @method UserTaskReviewQuery orderByUserTaskId($order = Criteria::ASC) Order by the user_task_id column
 * @method UserTaskReviewQuery orderByUserId($order = Criteria::ASC) Order by the user_id column
 * @method UserTaskReviewQuery orderByText($order = Criteria::ASC) Order by the text column
 * @method UserTaskReviewQuery orderByCreatedAt($order = Criteria::ASC) Order by the created_at column
 * @method UserTaskReviewQuery orderByUpdatedAt($order = Criteria::ASC) Order by the updated_at column
 *
 * @method UserTaskReviewQuery groupById() Group by the id column
 * @method UserTaskReviewQuery groupByUserTaskId() Group by the user_task_id column
 * @method UserTaskReviewQuery groupByUserId() Group by the user_id column
 * @method UserTaskReviewQuery groupByText() Group by the text column
 * @method UserTaskReviewQuery groupByCreatedAt() Group by the created_at column
 * @method UserTaskReviewQuery groupByUpdatedAt() Group by the updated_at column
 *
 * @method UserTaskReviewQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method UserTaskReviewQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method UserTaskReviewQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method UserTaskReviewQuery leftJoinUserTask($relationAlias = null) Adds a LEFT JOIN clause to the query using the UserTask relation
 * @method UserTaskReviewQuery rightJoinUserTask($relationAlias = null) Adds a RIGHT JOIN clause to the query using the UserTask relation
 * @method UserTaskReviewQuery innerJoinUserTask($relationAlias = null) Adds a INNER JOIN clause to the query using the UserTask relation
 *
 * @method UserTaskReviewQuery leftJoinUser($relationAlias = null) Adds a LEFT JOIN clause to the query using the User relation
 * @method UserTaskReviewQuery rightJoinUser($relationAlias = null) Adds a RIGHT JOIN clause to the query using the User relation
 * @method UserTaskReviewQuery innerJoinUser($relationAlias = null) Adds a INNER JOIN clause to the query using the User relation
 *
 * @method UserTaskReview findOne(PropelPDO $con = null) Return the first UserTaskReview matching the query
 * @method UserTaskReview findOneOrCreate(PropelPDO $con = null) Return the first UserTaskReview matching the query, or a new UserTaskReview object populated from the query conditions when no match is found
 *
 * @method UserTaskReview findOneByUserTaskId(int $user_task_id) Return the first UserTaskReview filtered by the user_task_id column
 * @method UserTaskReview findOneByUserId(int $user_id) Return the first UserTaskReview filtered by the user_id column
 * @method UserTaskReview findOneByText(string $text) Return the first UserTaskReview filtered by the text column
 * @method UserTaskReview findOneByCreatedAt(string $created_at) Return the first UserTaskReview filtered by the created_at column
 * @method UserTaskReview findOneByUpdatedAt(string $updated_at) Return the first UserTaskReview filtered by the updated_at column
 *
 * @method array findById(int $id) Return UserTaskReview objects filtered by the id column
 * @method array findByUserTaskId(int $user_task_id) Return UserTaskReview objects filtered by the user_task_id column
 * @method array findByUserId(int $user_id) Return UserTaskReview objects filtered by the user_id column
 * @method array findByText(string $text) Return UserTaskReview objects filtered by the text column
 * @method array findByCreatedAt(string $created_at) Return UserTaskReview objects filtered by the created_at column
 * @method array findByUpdatedAt(string $updated_at) Return UserTaskReview objects filtered by the updated_at column
 */
abstract class BaseUserTaskReviewQuery extends ModelCriteria
{
    /**
     * Initializes internal state of BaseUserTaskReviewQuery object.
     *
     * @param     string $dbName The dabase name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = 'Smirik\\CourseBundle\\Model\\UserTaskReview', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new UserTaskReviewQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     UserTaskReviewQuery|Criteria $criteria Optional Criteria to build the query from
     *
     * @return UserTaskReviewQuery
     */
    public static function create($modelAlias = null, $criteria = null)
    {
        if ($criteria instanceof UserTaskReviewQuery) {
            return $criteria;
        }
        $query = new UserTaskReviewQuery();
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
     * @return   UserTaskReview|UserTaskReview[]|mixed the result, formatted by the current formatter
     */
    public function findPk($key, $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = UserTaskReviewPeer::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is alredy in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getConnection(UserTaskReviewPeer::DATABASE_NAME, Propel::CONNECTION_READ);
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
     * @return   UserTaskReview A model object, or null if the key is not found
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
     * @return   UserTaskReview A model object, or null if the key is not found
     * @throws   PropelException
     */
    protected function findPkSimple($key, $con)
    {
        $sql = 'SELECT `ID`, `USER_TASK_ID`, `USER_ID`, `TEXT`, `CREATED_AT`, `UPDATED_AT` FROM `users_tasks_reviews` WHERE `ID` = :p0';
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
            $obj = new UserTaskReview();
            $obj->hydrate($row);
            UserTaskReviewPeer::addInstanceToPool($obj, (string) $key);
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
     * @return UserTaskReview|UserTaskReview[]|mixed the result, formatted by the current formatter
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
     * @return PropelObjectCollection|UserTaskReview[]|mixed the list of results, formatted by the current formatter
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
     * @return UserTaskReviewQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(UserTaskReviewPeer::ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return UserTaskReviewQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(UserTaskReviewPeer::ID, $keys, Criteria::IN);
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
     * @return UserTaskReviewQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id) && null === $comparison) {
            $comparison = Criteria::IN;
        }

        return $this->addUsingAlias(UserTaskReviewPeer::ID, $id, $comparison);
    }

    /**
     * Filter the query on the user_task_id column
     *
     * Example usage:
     * <code>
     * $query->filterByUserTaskId(1234); // WHERE user_task_id = 1234
     * $query->filterByUserTaskId(array(12, 34)); // WHERE user_task_id IN (12, 34)
     * $query->filterByUserTaskId(array('min' => 12)); // WHERE user_task_id > 12
     * </code>
     *
     * @see       filterByUserTask()
     *
     * @param     mixed $userTaskId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return UserTaskReviewQuery The current query, for fluid interface
     */
    public function filterByUserTaskId($userTaskId = null, $comparison = null)
    {
        if (is_array($userTaskId)) {
            $useMinMax = false;
            if (isset($userTaskId['min'])) {
                $this->addUsingAlias(UserTaskReviewPeer::USER_TASK_ID, $userTaskId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($userTaskId['max'])) {
                $this->addUsingAlias(UserTaskReviewPeer::USER_TASK_ID, $userTaskId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(UserTaskReviewPeer::USER_TASK_ID, $userTaskId, $comparison);
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
     * @return UserTaskReviewQuery The current query, for fluid interface
     */
    public function filterByUserId($userId = null, $comparison = null)
    {
        if (is_array($userId)) {
            $useMinMax = false;
            if (isset($userId['min'])) {
                $this->addUsingAlias(UserTaskReviewPeer::USER_ID, $userId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($userId['max'])) {
                $this->addUsingAlias(UserTaskReviewPeer::USER_ID, $userId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(UserTaskReviewPeer::USER_ID, $userId, $comparison);
    }

    /**
     * Filter the query on the text column
     *
     * Example usage:
     * <code>
     * $query->filterByText('fooValue');   // WHERE text = 'fooValue'
     * $query->filterByText('%fooValue%'); // WHERE text LIKE '%fooValue%'
     * </code>
     *
     * @param     string $text The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return UserTaskReviewQuery The current query, for fluid interface
     */
    public function filterByText($text = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($text)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $text)) {
                $text = str_replace('*', '%', $text);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(UserTaskReviewPeer::TEXT, $text, $comparison);
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
     * @return UserTaskReviewQuery The current query, for fluid interface
     */
    public function filterByCreatedAt($createdAt = null, $comparison = null)
    {
        if (is_array($createdAt)) {
            $useMinMax = false;
            if (isset($createdAt['min'])) {
                $this->addUsingAlias(UserTaskReviewPeer::CREATED_AT, $createdAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($createdAt['max'])) {
                $this->addUsingAlias(UserTaskReviewPeer::CREATED_AT, $createdAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(UserTaskReviewPeer::CREATED_AT, $createdAt, $comparison);
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
     * @return UserTaskReviewQuery The current query, for fluid interface
     */
    public function filterByUpdatedAt($updatedAt = null, $comparison = null)
    {
        if (is_array($updatedAt)) {
            $useMinMax = false;
            if (isset($updatedAt['min'])) {
                $this->addUsingAlias(UserTaskReviewPeer::UPDATED_AT, $updatedAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($updatedAt['max'])) {
                $this->addUsingAlias(UserTaskReviewPeer::UPDATED_AT, $updatedAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(UserTaskReviewPeer::UPDATED_AT, $updatedAt, $comparison);
    }

    /**
     * Filter the query by a related UserTask object
     *
     * @param   UserTask|PropelObjectCollection $userTask The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return   UserTaskReviewQuery The current query, for fluid interface
     * @throws   PropelException - if the provided filter is invalid.
     */
    public function filterByUserTask($userTask, $comparison = null)
    {
        if ($userTask instanceof UserTask) {
            return $this
                ->addUsingAlias(UserTaskReviewPeer::USER_TASK_ID, $userTask->getId(), $comparison);
        } elseif ($userTask instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(UserTaskReviewPeer::USER_TASK_ID, $userTask->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByUserTask() only accepts arguments of type UserTask or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the UserTask relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return UserTaskReviewQuery The current query, for fluid interface
     */
    public function joinUserTask($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('UserTask');

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
            $this->addJoinObject($join, 'UserTask');
        }

        return $this;
    }

    /**
     * Use the UserTask relation UserTask object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \Smirik\CourseBundle\Model\UserTaskQuery A secondary query class using the current class as primary query
     */
    public function useUserTaskQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinUserTask($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'UserTask', '\Smirik\CourseBundle\Model\UserTaskQuery');
    }

    /**
     * Filter the query by a related User object
     *
     * @param   User|PropelObjectCollection $user The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return   UserTaskReviewQuery The current query, for fluid interface
     * @throws   PropelException - if the provided filter is invalid.
     */
    public function filterByUser($user, $comparison = null)
    {
        if ($user instanceof User) {
            return $this
                ->addUsingAlias(UserTaskReviewPeer::USER_ID, $user->getId(), $comparison);
        } elseif ($user instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(UserTaskReviewPeer::USER_ID, $user->toKeyValue('PrimaryKey', 'Id'), $comparison);
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
     * @return UserTaskReviewQuery The current query, for fluid interface
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
     * Exclude object from result
     *
     * @param   UserTaskReview $userTaskReview Object to remove from the list of results
     *
     * @return UserTaskReviewQuery The current query, for fluid interface
     */
    public function prune($userTaskReview = null)
    {
        if ($userTaskReview) {
            $this->addUsingAlias(UserTaskReviewPeer::ID, $userTaskReview->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    // timestampable behavior

    /**
     * Filter by the latest updated
     *
     * @param      int $nbDays Maximum age of the latest update in days
     *
     * @return     UserTaskReviewQuery The current query, for fluid interface
     */
    public function recentlyUpdated($nbDays = 7)
    {
        return $this->addUsingAlias(UserTaskReviewPeer::UPDATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by update date desc
     *
     * @return     UserTaskReviewQuery The current query, for fluid interface
     */
    public function lastUpdatedFirst()
    {
        return $this->addDescendingOrderByColumn(UserTaskReviewPeer::UPDATED_AT);
    }

    /**
     * Order by update date asc
     *
     * @return     UserTaskReviewQuery The current query, for fluid interface
     */
    public function firstUpdatedFirst()
    {
        return $this->addAscendingOrderByColumn(UserTaskReviewPeer::UPDATED_AT);
    }

    /**
     * Filter by the latest created
     *
     * @param      int $nbDays Maximum age of in days
     *
     * @return     UserTaskReviewQuery The current query, for fluid interface
     */
    public function recentlyCreated($nbDays = 7)
    {
        return $this->addUsingAlias(UserTaskReviewPeer::CREATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by create date desc
     *
     * @return     UserTaskReviewQuery The current query, for fluid interface
     */
    public function lastCreatedFirst()
    {
        return $this->addDescendingOrderByColumn(UserTaskReviewPeer::CREATED_AT);
    }

    /**
     * Order by create date asc
     *
     * @return     UserTaskReviewQuery The current query, for fluid interface
     */
    public function firstCreatedFirst()
    {
        return $this->addAscendingOrderByColumn(UserTaskReviewPeer::CREATED_AT);
    }
}
