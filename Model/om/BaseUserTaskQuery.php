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
use Smirik\CourseBundle\Model\Lesson;
use Smirik\CourseBundle\Model\Task;
use Smirik\CourseBundle\Model\UserTask;
use Smirik\CourseBundle\Model\UserTaskPeer;
use Smirik\CourseBundle\Model\UserTaskQuery;
use Smirik\CourseBundle\Model\UserTaskReview;

/**
 * @method UserTaskQuery orderById($order = Criteria::ASC) Order by the id column
 * @method UserTaskQuery orderByLessonId($order = Criteria::ASC) Order by the lesson_id column
 * @method UserTaskQuery orderByTaskId($order = Criteria::ASC) Order by the task_id column
 * @method UserTaskQuery orderByUserId($order = Criteria::ASC) Order by the user_id column
 * @method UserTaskQuery orderByText($order = Criteria::ASC) Order by the text column
 * @method UserTaskQuery orderByUrl($order = Criteria::ASC) Order by the url column
 * @method UserTaskQuery orderByFile($order = Criteria::ASC) Order by the file column
 * @method UserTaskQuery orderByStatus($order = Criteria::ASC) Order by the status column
 * @method UserTaskQuery orderByMark($order = Criteria::ASC) Order by the mark column
 * @method UserTaskQuery orderByCreatedAt($order = Criteria::ASC) Order by the created_at column
 * @method UserTaskQuery orderByUpdatedAt($order = Criteria::ASC) Order by the updated_at column
 *
 * @method UserTaskQuery groupById() Group by the id column
 * @method UserTaskQuery groupByLessonId() Group by the lesson_id column
 * @method UserTaskQuery groupByTaskId() Group by the task_id column
 * @method UserTaskQuery groupByUserId() Group by the user_id column
 * @method UserTaskQuery groupByText() Group by the text column
 * @method UserTaskQuery groupByUrl() Group by the url column
 * @method UserTaskQuery groupByFile() Group by the file column
 * @method UserTaskQuery groupByStatus() Group by the status column
 * @method UserTaskQuery groupByMark() Group by the mark column
 * @method UserTaskQuery groupByCreatedAt() Group by the created_at column
 * @method UserTaskQuery groupByUpdatedAt() Group by the updated_at column
 *
 * @method UserTaskQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method UserTaskQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method UserTaskQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method UserTaskQuery leftJoinLesson($relationAlias = null) Adds a LEFT JOIN clause to the query using the Lesson relation
 * @method UserTaskQuery rightJoinLesson($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Lesson relation
 * @method UserTaskQuery innerJoinLesson($relationAlias = null) Adds a INNER JOIN clause to the query using the Lesson relation
 *
 * @method UserTaskQuery leftJoinUser($relationAlias = null) Adds a LEFT JOIN clause to the query using the User relation
 * @method UserTaskQuery rightJoinUser($relationAlias = null) Adds a RIGHT JOIN clause to the query using the User relation
 * @method UserTaskQuery innerJoinUser($relationAlias = null) Adds a INNER JOIN clause to the query using the User relation
 *
 * @method UserTaskQuery leftJoinTask($relationAlias = null) Adds a LEFT JOIN clause to the query using the Task relation
 * @method UserTaskQuery rightJoinTask($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Task relation
 * @method UserTaskQuery innerJoinTask($relationAlias = null) Adds a INNER JOIN clause to the query using the Task relation
 *
 * @method UserTaskQuery leftJoinUserTaskReview($relationAlias = null) Adds a LEFT JOIN clause to the query using the UserTaskReview relation
 * @method UserTaskQuery rightJoinUserTaskReview($relationAlias = null) Adds a RIGHT JOIN clause to the query using the UserTaskReview relation
 * @method UserTaskQuery innerJoinUserTaskReview($relationAlias = null) Adds a INNER JOIN clause to the query using the UserTaskReview relation
 *
 * @method UserTask findOne(PropelPDO $con = null) Return the first UserTask matching the query
 * @method UserTask findOneOrCreate(PropelPDO $con = null) Return the first UserTask matching the query, or a new UserTask object populated from the query conditions when no match is found
 *
 * @method UserTask findOneByLessonId(int $lesson_id) Return the first UserTask filtered by the lesson_id column
 * @method UserTask findOneByTaskId(int $task_id) Return the first UserTask filtered by the task_id column
 * @method UserTask findOneByUserId(int $user_id) Return the first UserTask filtered by the user_id column
 * @method UserTask findOneByText(string $text) Return the first UserTask filtered by the text column
 * @method UserTask findOneByUrl(string $url) Return the first UserTask filtered by the url column
 * @method UserTask findOneByFile(string $file) Return the first UserTask filtered by the file column
 * @method UserTask findOneByStatus(int $status) Return the first UserTask filtered by the status column
 * @method UserTask findOneByMark(int $mark) Return the first UserTask filtered by the mark column
 * @method UserTask findOneByCreatedAt(string $created_at) Return the first UserTask filtered by the created_at column
 * @method UserTask findOneByUpdatedAt(string $updated_at) Return the first UserTask filtered by the updated_at column
 *
 * @method array findById(int $id) Return UserTask objects filtered by the id column
 * @method array findByLessonId(int $lesson_id) Return UserTask objects filtered by the lesson_id column
 * @method array findByTaskId(int $task_id) Return UserTask objects filtered by the task_id column
 * @method array findByUserId(int $user_id) Return UserTask objects filtered by the user_id column
 * @method array findByText(string $text) Return UserTask objects filtered by the text column
 * @method array findByUrl(string $url) Return UserTask objects filtered by the url column
 * @method array findByFile(string $file) Return UserTask objects filtered by the file column
 * @method array findByStatus(int $status) Return UserTask objects filtered by the status column
 * @method array findByMark(int $mark) Return UserTask objects filtered by the mark column
 * @method array findByCreatedAt(string $created_at) Return UserTask objects filtered by the created_at column
 * @method array findByUpdatedAt(string $updated_at) Return UserTask objects filtered by the updated_at column
 */
abstract class BaseUserTaskQuery extends ModelCriteria
{
    /**
     * Initializes internal state of BaseUserTaskQuery object.
     *
     * @param     string $dbName The dabase name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = 'Smirik\\CourseBundle\\Model\\UserTask', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new UserTaskQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     UserTaskQuery|Criteria $criteria Optional Criteria to build the query from
     *
     * @return UserTaskQuery
     */
    public static function create($modelAlias = null, $criteria = null)
    {
        if ($criteria instanceof UserTaskQuery) {
            return $criteria;
        }
        $query = new UserTaskQuery();
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
     * @return   UserTask|UserTask[]|mixed the result, formatted by the current formatter
     */
    public function findPk($key, $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = UserTaskPeer::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is alredy in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getConnection(UserTaskPeer::DATABASE_NAME, Propel::CONNECTION_READ);
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
     * @return   UserTask A model object, or null if the key is not found
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
     * @return   UserTask A model object, or null if the key is not found
     * @throws   PropelException
     */
    protected function findPkSimple($key, $con)
    {
        $sql = 'SELECT `ID`, `LESSON_ID`, `TASK_ID`, `USER_ID`, `TEXT`, `URL`, `FILE`, `STATUS`, `MARK`, `CREATED_AT`, `UPDATED_AT` FROM `users_tasks` WHERE `ID` = :p0';
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
            $obj = new UserTask();
            $obj->hydrate($row);
            UserTaskPeer::addInstanceToPool($obj, (string) $key);
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
     * @return UserTask|UserTask[]|mixed the result, formatted by the current formatter
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
     * @return PropelObjectCollection|UserTask[]|mixed the list of results, formatted by the current formatter
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
     * @return UserTaskQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(UserTaskPeer::ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return UserTaskQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(UserTaskPeer::ID, $keys, Criteria::IN);
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
     * @return UserTaskQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id) && null === $comparison) {
            $comparison = Criteria::IN;
        }

        return $this->addUsingAlias(UserTaskPeer::ID, $id, $comparison);
    }

    /**
     * Filter the query on the lesson_id column
     *
     * Example usage:
     * <code>
     * $query->filterByLessonId(1234); // WHERE lesson_id = 1234
     * $query->filterByLessonId(array(12, 34)); // WHERE lesson_id IN (12, 34)
     * $query->filterByLessonId(array('min' => 12)); // WHERE lesson_id > 12
     * </code>
     *
     * @see       filterByLesson()
     *
     * @param     mixed $lessonId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return UserTaskQuery The current query, for fluid interface
     */
    public function filterByLessonId($lessonId = null, $comparison = null)
    {
        if (is_array($lessonId)) {
            $useMinMax = false;
            if (isset($lessonId['min'])) {
                $this->addUsingAlias(UserTaskPeer::LESSON_ID, $lessonId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($lessonId['max'])) {
                $this->addUsingAlias(UserTaskPeer::LESSON_ID, $lessonId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(UserTaskPeer::LESSON_ID, $lessonId, $comparison);
    }

    /**
     * Filter the query on the task_id column
     *
     * Example usage:
     * <code>
     * $query->filterByTaskId(1234); // WHERE task_id = 1234
     * $query->filterByTaskId(array(12, 34)); // WHERE task_id IN (12, 34)
     * $query->filterByTaskId(array('min' => 12)); // WHERE task_id > 12
     * </code>
     *
     * @see       filterByTask()
     *
     * @param     mixed $taskId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return UserTaskQuery The current query, for fluid interface
     */
    public function filterByTaskId($taskId = null, $comparison = null)
    {
        if (is_array($taskId)) {
            $useMinMax = false;
            if (isset($taskId['min'])) {
                $this->addUsingAlias(UserTaskPeer::TASK_ID, $taskId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($taskId['max'])) {
                $this->addUsingAlias(UserTaskPeer::TASK_ID, $taskId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(UserTaskPeer::TASK_ID, $taskId, $comparison);
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
     * @return UserTaskQuery The current query, for fluid interface
     */
    public function filterByUserId($userId = null, $comparison = null)
    {
        if (is_array($userId)) {
            $useMinMax = false;
            if (isset($userId['min'])) {
                $this->addUsingAlias(UserTaskPeer::USER_ID, $userId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($userId['max'])) {
                $this->addUsingAlias(UserTaskPeer::USER_ID, $userId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(UserTaskPeer::USER_ID, $userId, $comparison);
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
     * @return UserTaskQuery The current query, for fluid interface
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

        return $this->addUsingAlias(UserTaskPeer::TEXT, $text, $comparison);
    }

    /**
     * Filter the query on the url column
     *
     * Example usage:
     * <code>
     * $query->filterByUrl('fooValue');   // WHERE url = 'fooValue'
     * $query->filterByUrl('%fooValue%'); // WHERE url LIKE '%fooValue%'
     * </code>
     *
     * @param     string $url The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return UserTaskQuery The current query, for fluid interface
     */
    public function filterByUrl($url = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($url)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $url)) {
                $url = str_replace('*', '%', $url);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(UserTaskPeer::URL, $url, $comparison);
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
     * @return UserTaskQuery The current query, for fluid interface
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

        return $this->addUsingAlias(UserTaskPeer::FILE, $file, $comparison);
    }

    /**
     * Filter the query on the status column
     *
     * Example usage:
     * <code>
     * $query->filterByStatus(1234); // WHERE status = 1234
     * $query->filterByStatus(array(12, 34)); // WHERE status IN (12, 34)
     * $query->filterByStatus(array('min' => 12)); // WHERE status > 12
     * </code>
     *
     * @param     mixed $status The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return UserTaskQuery The current query, for fluid interface
     */
    public function filterByStatus($status = null, $comparison = null)
    {
        if (is_array($status)) {
            $useMinMax = false;
            if (isset($status['min'])) {
                $this->addUsingAlias(UserTaskPeer::STATUS, $status['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($status['max'])) {
                $this->addUsingAlias(UserTaskPeer::STATUS, $status['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(UserTaskPeer::STATUS, $status, $comparison);
    }

    /**
     * Filter the query on the mark column
     *
     * Example usage:
     * <code>
     * $query->filterByMark(1234); // WHERE mark = 1234
     * $query->filterByMark(array(12, 34)); // WHERE mark IN (12, 34)
     * $query->filterByMark(array('min' => 12)); // WHERE mark > 12
     * </code>
     *
     * @param     mixed $mark The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return UserTaskQuery The current query, for fluid interface
     */
    public function filterByMark($mark = null, $comparison = null)
    {
        if (is_array($mark)) {
            $useMinMax = false;
            if (isset($mark['min'])) {
                $this->addUsingAlias(UserTaskPeer::MARK, $mark['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($mark['max'])) {
                $this->addUsingAlias(UserTaskPeer::MARK, $mark['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(UserTaskPeer::MARK, $mark, $comparison);
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
     * @return UserTaskQuery The current query, for fluid interface
     */
    public function filterByCreatedAt($createdAt = null, $comparison = null)
    {
        if (is_array($createdAt)) {
            $useMinMax = false;
            if (isset($createdAt['min'])) {
                $this->addUsingAlias(UserTaskPeer::CREATED_AT, $createdAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($createdAt['max'])) {
                $this->addUsingAlias(UserTaskPeer::CREATED_AT, $createdAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(UserTaskPeer::CREATED_AT, $createdAt, $comparison);
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
     * @return UserTaskQuery The current query, for fluid interface
     */
    public function filterByUpdatedAt($updatedAt = null, $comparison = null)
    {
        if (is_array($updatedAt)) {
            $useMinMax = false;
            if (isset($updatedAt['min'])) {
                $this->addUsingAlias(UserTaskPeer::UPDATED_AT, $updatedAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($updatedAt['max'])) {
                $this->addUsingAlias(UserTaskPeer::UPDATED_AT, $updatedAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(UserTaskPeer::UPDATED_AT, $updatedAt, $comparison);
    }

    /**
     * Filter the query by a related Lesson object
     *
     * @param   Lesson|PropelObjectCollection $lesson The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return   UserTaskQuery The current query, for fluid interface
     * @throws   PropelException - if the provided filter is invalid.
     */
    public function filterByLesson($lesson, $comparison = null)
    {
        if ($lesson instanceof Lesson) {
            return $this
                ->addUsingAlias(UserTaskPeer::LESSON_ID, $lesson->getId(), $comparison);
        } elseif ($lesson instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(UserTaskPeer::LESSON_ID, $lesson->toKeyValue('PrimaryKey', 'Id'), $comparison);
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
     * @return UserTaskQuery The current query, for fluid interface
     */
    public function joinLesson($relationAlias = null, $joinType = Criteria::INNER_JOIN)
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
    public function useLessonQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinLesson($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Lesson', '\Smirik\CourseBundle\Model\LessonQuery');
    }

    /**
     * Filter the query by a related User object
     *
     * @param   User|PropelObjectCollection $user The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return   UserTaskQuery The current query, for fluid interface
     * @throws   PropelException - if the provided filter is invalid.
     */
    public function filterByUser($user, $comparison = null)
    {
        if ($user instanceof User) {
            return $this
                ->addUsingAlias(UserTaskPeer::USER_ID, $user->getId(), $comparison);
        } elseif ($user instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(UserTaskPeer::USER_ID, $user->toKeyValue('PrimaryKey', 'Id'), $comparison);
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
     * @return UserTaskQuery The current query, for fluid interface
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
     * Filter the query by a related Task object
     *
     * @param   Task|PropelObjectCollection $task The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return   UserTaskQuery The current query, for fluid interface
     * @throws   PropelException - if the provided filter is invalid.
     */
    public function filterByTask($task, $comparison = null)
    {
        if ($task instanceof Task) {
            return $this
                ->addUsingAlias(UserTaskPeer::TASK_ID, $task->getId(), $comparison);
        } elseif ($task instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(UserTaskPeer::TASK_ID, $task->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByTask() only accepts arguments of type Task or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Task relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return UserTaskQuery The current query, for fluid interface
     */
    public function joinTask($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Task');

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
            $this->addJoinObject($join, 'Task');
        }

        return $this;
    }

    /**
     * Use the Task relation Task object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \Smirik\CourseBundle\Model\TaskQuery A secondary query class using the current class as primary query
     */
    public function useTaskQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinTask($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Task', '\Smirik\CourseBundle\Model\TaskQuery');
    }

    /**
     * Filter the query by a related UserTaskReview object
     *
     * @param   UserTaskReview|PropelObjectCollection $userTaskReview  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return   UserTaskQuery The current query, for fluid interface
     * @throws   PropelException - if the provided filter is invalid.
     */
    public function filterByUserTaskReview($userTaskReview, $comparison = null)
    {
        if ($userTaskReview instanceof UserTaskReview) {
            return $this
                ->addUsingAlias(UserTaskPeer::ID, $userTaskReview->getUserTaskId(), $comparison);
        } elseif ($userTaskReview instanceof PropelObjectCollection) {
            return $this
                ->useUserTaskReviewQuery()
                ->filterByPrimaryKeys($userTaskReview->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByUserTaskReview() only accepts arguments of type UserTaskReview or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the UserTaskReview relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return UserTaskQuery The current query, for fluid interface
     */
    public function joinUserTaskReview($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('UserTaskReview');

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
            $this->addJoinObject($join, 'UserTaskReview');
        }

        return $this;
    }

    /**
     * Use the UserTaskReview relation UserTaskReview object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \Smirik\CourseBundle\Model\UserTaskReviewQuery A secondary query class using the current class as primary query
     */
    public function useUserTaskReviewQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinUserTaskReview($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'UserTaskReview', '\Smirik\CourseBundle\Model\UserTaskReviewQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   UserTask $userTask Object to remove from the list of results
     *
     * @return UserTaskQuery The current query, for fluid interface
     */
    public function prune($userTask = null)
    {
        if ($userTask) {
            $this->addUsingAlias(UserTaskPeer::ID, $userTask->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    // timestampable behavior

    /**
     * Filter by the latest updated
     *
     * @param      int $nbDays Maximum age of the latest update in days
     *
     * @return     UserTaskQuery The current query, for fluid interface
     */
    public function recentlyUpdated($nbDays = 7)
    {
        return $this->addUsingAlias(UserTaskPeer::UPDATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by update date desc
     *
     * @return     UserTaskQuery The current query, for fluid interface
     */
    public function lastUpdatedFirst()
    {
        return $this->addDescendingOrderByColumn(UserTaskPeer::UPDATED_AT);
    }

    /**
     * Order by update date asc
     *
     * @return     UserTaskQuery The current query, for fluid interface
     */
    public function firstUpdatedFirst()
    {
        return $this->addAscendingOrderByColumn(UserTaskPeer::UPDATED_AT);
    }

    /**
     * Filter by the latest created
     *
     * @param      int $nbDays Maximum age of in days
     *
     * @return     UserTaskQuery The current query, for fluid interface
     */
    public function recentlyCreated($nbDays = 7)
    {
        return $this->addUsingAlias(UserTaskPeer::CREATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by create date desc
     *
     * @return     UserTaskQuery The current query, for fluid interface
     */
    public function lastCreatedFirst()
    {
        return $this->addDescendingOrderByColumn(UserTaskPeer::CREATED_AT);
    }

    /**
     * Order by create date asc
     *
     * @return     UserTaskQuery The current query, for fluid interface
     */
    public function firstCreatedFirst()
    {
        return $this->addAscendingOrderByColumn(UserTaskPeer::CREATED_AT);
    }
}
