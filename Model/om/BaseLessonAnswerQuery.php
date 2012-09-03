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
use Smirik\CourseBundle\Model\LessonAnswer;
use Smirik\CourseBundle\Model\LessonAnswerPeer;
use Smirik\CourseBundle\Model\LessonAnswerQuery;
use Smirik\CourseBundle\Model\LessonQuestion;

/**
 * @method LessonAnswerQuery orderById($order = Criteria::ASC) Order by the id column
 * @method LessonAnswerQuery orderByUserId($order = Criteria::ASC) Order by the user_id column
 * @method LessonAnswerQuery orderByLessonId($order = Criteria::ASC) Order by the lesson_id column
 * @method LessonAnswerQuery orderByQuestionId($order = Criteria::ASC) Order by the question_id column
 * @method LessonAnswerQuery orderByText($order = Criteria::ASC) Order by the text column
 * @method LessonAnswerQuery orderByIsVisible($order = Criteria::ASC) Order by the is_visible column
 * @method LessonAnswerQuery orderByIsAccepted($order = Criteria::ASC) Order by the is_accepted column
 * @method LessonAnswerQuery orderByAcceptedBy($order = Criteria::ASC) Order by the accepted_by column
 * @method LessonAnswerQuery orderByCreatedAt($order = Criteria::ASC) Order by the created_at column
 * @method LessonAnswerQuery orderByUpdatedAt($order = Criteria::ASC) Order by the updated_at column
 *
 * @method LessonAnswerQuery groupById() Group by the id column
 * @method LessonAnswerQuery groupByUserId() Group by the user_id column
 * @method LessonAnswerQuery groupByLessonId() Group by the lesson_id column
 * @method LessonAnswerQuery groupByQuestionId() Group by the question_id column
 * @method LessonAnswerQuery groupByText() Group by the text column
 * @method LessonAnswerQuery groupByIsVisible() Group by the is_visible column
 * @method LessonAnswerQuery groupByIsAccepted() Group by the is_accepted column
 * @method LessonAnswerQuery groupByAcceptedBy() Group by the accepted_by column
 * @method LessonAnswerQuery groupByCreatedAt() Group by the created_at column
 * @method LessonAnswerQuery groupByUpdatedAt() Group by the updated_at column
 *
 * @method LessonAnswerQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method LessonAnswerQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method LessonAnswerQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method LessonAnswerQuery leftJoinLesson($relationAlias = null) Adds a LEFT JOIN clause to the query using the Lesson relation
 * @method LessonAnswerQuery rightJoinLesson($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Lesson relation
 * @method LessonAnswerQuery innerJoinLesson($relationAlias = null) Adds a INNER JOIN clause to the query using the Lesson relation
 *
 * @method LessonAnswerQuery leftJoinLessonQuestion($relationAlias = null) Adds a LEFT JOIN clause to the query using the LessonQuestion relation
 * @method LessonAnswerQuery rightJoinLessonQuestion($relationAlias = null) Adds a RIGHT JOIN clause to the query using the LessonQuestion relation
 * @method LessonAnswerQuery innerJoinLessonQuestion($relationAlias = null) Adds a INNER JOIN clause to the query using the LessonQuestion relation
 *
 * @method LessonAnswerQuery leftJoinUser($relationAlias = null) Adds a LEFT JOIN clause to the query using the User relation
 * @method LessonAnswerQuery rightJoinUser($relationAlias = null) Adds a RIGHT JOIN clause to the query using the User relation
 * @method LessonAnswerQuery innerJoinUser($relationAlias = null) Adds a INNER JOIN clause to the query using the User relation
 *
 * @method LessonAnswer findOne(PropelPDO $con = null) Return the first LessonAnswer matching the query
 * @method LessonAnswer findOneOrCreate(PropelPDO $con = null) Return the first LessonAnswer matching the query, or a new LessonAnswer object populated from the query conditions when no match is found
 *
 * @method LessonAnswer findOneByUserId(int $user_id) Return the first LessonAnswer filtered by the user_id column
 * @method LessonAnswer findOneByLessonId(int $lesson_id) Return the first LessonAnswer filtered by the lesson_id column
 * @method LessonAnswer findOneByQuestionId(int $question_id) Return the first LessonAnswer filtered by the question_id column
 * @method LessonAnswer findOneByText(string $text) Return the first LessonAnswer filtered by the text column
 * @method LessonAnswer findOneByIsVisible(boolean $is_visible) Return the first LessonAnswer filtered by the is_visible column
 * @method LessonAnswer findOneByIsAccepted(boolean $is_accepted) Return the first LessonAnswer filtered by the is_accepted column
 * @method LessonAnswer findOneByAcceptedBy(int $accepted_by) Return the first LessonAnswer filtered by the accepted_by column
 * @method LessonAnswer findOneByCreatedAt(string $created_at) Return the first LessonAnswer filtered by the created_at column
 * @method LessonAnswer findOneByUpdatedAt(string $updated_at) Return the first LessonAnswer filtered by the updated_at column
 *
 * @method array findById(int $id) Return LessonAnswer objects filtered by the id column
 * @method array findByUserId(int $user_id) Return LessonAnswer objects filtered by the user_id column
 * @method array findByLessonId(int $lesson_id) Return LessonAnswer objects filtered by the lesson_id column
 * @method array findByQuestionId(int $question_id) Return LessonAnswer objects filtered by the question_id column
 * @method array findByText(string $text) Return LessonAnswer objects filtered by the text column
 * @method array findByIsVisible(boolean $is_visible) Return LessonAnswer objects filtered by the is_visible column
 * @method array findByIsAccepted(boolean $is_accepted) Return LessonAnswer objects filtered by the is_accepted column
 * @method array findByAcceptedBy(int $accepted_by) Return LessonAnswer objects filtered by the accepted_by column
 * @method array findByCreatedAt(string $created_at) Return LessonAnswer objects filtered by the created_at column
 * @method array findByUpdatedAt(string $updated_at) Return LessonAnswer objects filtered by the updated_at column
 */
abstract class BaseLessonAnswerQuery extends ModelCriteria
{
    /**
     * Initializes internal state of BaseLessonAnswerQuery object.
     *
     * @param     string $dbName The dabase name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = 'Smirik\\CourseBundle\\Model\\LessonAnswer', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new LessonAnswerQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     LessonAnswerQuery|Criteria $criteria Optional Criteria to build the query from
     *
     * @return LessonAnswerQuery
     */
    public static function create($modelAlias = null, $criteria = null)
    {
        if ($criteria instanceof LessonAnswerQuery) {
            return $criteria;
        }
        $query = new LessonAnswerQuery();
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
     * @return   LessonAnswer|LessonAnswer[]|mixed the result, formatted by the current formatter
     */
    public function findPk($key, $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = LessonAnswerPeer::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is alredy in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getConnection(LessonAnswerPeer::DATABASE_NAME, Propel::CONNECTION_READ);
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
     * @return   LessonAnswer A model object, or null if the key is not found
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
     * @return   LessonAnswer A model object, or null if the key is not found
     * @throws   PropelException
     */
    protected function findPkSimple($key, $con)
    {
        $sql = 'SELECT `ID`, `USER_ID`, `LESSON_ID`, `QUESTION_ID`, `TEXT`, `IS_VISIBLE`, `IS_ACCEPTED`, `ACCEPTED_BY`, `CREATED_AT`, `UPDATED_AT` FROM `lessons_answers` WHERE `ID` = :p0';
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
            $obj = new LessonAnswer();
            $obj->hydrate($row);
            LessonAnswerPeer::addInstanceToPool($obj, (string) $key);
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
     * @return LessonAnswer|LessonAnswer[]|mixed the result, formatted by the current formatter
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
     * @return PropelObjectCollection|LessonAnswer[]|mixed the list of results, formatted by the current formatter
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
     * @return LessonAnswerQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(LessonAnswerPeer::ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return LessonAnswerQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(LessonAnswerPeer::ID, $keys, Criteria::IN);
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
     * @return LessonAnswerQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id) && null === $comparison) {
            $comparison = Criteria::IN;
        }

        return $this->addUsingAlias(LessonAnswerPeer::ID, $id, $comparison);
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
     * @return LessonAnswerQuery The current query, for fluid interface
     */
    public function filterByUserId($userId = null, $comparison = null)
    {
        if (is_array($userId)) {
            $useMinMax = false;
            if (isset($userId['min'])) {
                $this->addUsingAlias(LessonAnswerPeer::USER_ID, $userId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($userId['max'])) {
                $this->addUsingAlias(LessonAnswerPeer::USER_ID, $userId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(LessonAnswerPeer::USER_ID, $userId, $comparison);
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
     * @return LessonAnswerQuery The current query, for fluid interface
     */
    public function filterByLessonId($lessonId = null, $comparison = null)
    {
        if (is_array($lessonId)) {
            $useMinMax = false;
            if (isset($lessonId['min'])) {
                $this->addUsingAlias(LessonAnswerPeer::LESSON_ID, $lessonId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($lessonId['max'])) {
                $this->addUsingAlias(LessonAnswerPeer::LESSON_ID, $lessonId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(LessonAnswerPeer::LESSON_ID, $lessonId, $comparison);
    }

    /**
     * Filter the query on the question_id column
     *
     * Example usage:
     * <code>
     * $query->filterByQuestionId(1234); // WHERE question_id = 1234
     * $query->filterByQuestionId(array(12, 34)); // WHERE question_id IN (12, 34)
     * $query->filterByQuestionId(array('min' => 12)); // WHERE question_id > 12
     * </code>
     *
     * @see       filterByLessonQuestion()
     *
     * @param     mixed $questionId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return LessonAnswerQuery The current query, for fluid interface
     */
    public function filterByQuestionId($questionId = null, $comparison = null)
    {
        if (is_array($questionId)) {
            $useMinMax = false;
            if (isset($questionId['min'])) {
                $this->addUsingAlias(LessonAnswerPeer::QUESTION_ID, $questionId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($questionId['max'])) {
                $this->addUsingAlias(LessonAnswerPeer::QUESTION_ID, $questionId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(LessonAnswerPeer::QUESTION_ID, $questionId, $comparison);
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
     * @return LessonAnswerQuery The current query, for fluid interface
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

        return $this->addUsingAlias(LessonAnswerPeer::TEXT, $text, $comparison);
    }

    /**
     * Filter the query on the is_visible column
     *
     * Example usage:
     * <code>
     * $query->filterByIsVisible(true); // WHERE is_visible = true
     * $query->filterByIsVisible('yes'); // WHERE is_visible = true
     * </code>
     *
     * @param     boolean|string $isVisible The value to use as filter.
     *              Non-boolean arguments are converted using the following rules:
     *                * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *                * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     *              Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return LessonAnswerQuery The current query, for fluid interface
     */
    public function filterByIsVisible($isVisible = null, $comparison = null)
    {
        if (is_string($isVisible)) {
            $is_visible = in_array(strtolower($isVisible), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
        }

        return $this->addUsingAlias(LessonAnswerPeer::IS_VISIBLE, $isVisible, $comparison);
    }

    /**
     * Filter the query on the is_accepted column
     *
     * Example usage:
     * <code>
     * $query->filterByIsAccepted(true); // WHERE is_accepted = true
     * $query->filterByIsAccepted('yes'); // WHERE is_accepted = true
     * </code>
     *
     * @param     boolean|string $isAccepted The value to use as filter.
     *              Non-boolean arguments are converted using the following rules:
     *                * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *                * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     *              Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return LessonAnswerQuery The current query, for fluid interface
     */
    public function filterByIsAccepted($isAccepted = null, $comparison = null)
    {
        if (is_string($isAccepted)) {
            $is_accepted = in_array(strtolower($isAccepted), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
        }

        return $this->addUsingAlias(LessonAnswerPeer::IS_ACCEPTED, $isAccepted, $comparison);
    }

    /**
     * Filter the query on the accepted_by column
     *
     * Example usage:
     * <code>
     * $query->filterByAcceptedBy(1234); // WHERE accepted_by = 1234
     * $query->filterByAcceptedBy(array(12, 34)); // WHERE accepted_by IN (12, 34)
     * $query->filterByAcceptedBy(array('min' => 12)); // WHERE accepted_by > 12
     * </code>
     *
     * @param     mixed $acceptedBy The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return LessonAnswerQuery The current query, for fluid interface
     */
    public function filterByAcceptedBy($acceptedBy = null, $comparison = null)
    {
        if (is_array($acceptedBy)) {
            $useMinMax = false;
            if (isset($acceptedBy['min'])) {
                $this->addUsingAlias(LessonAnswerPeer::ACCEPTED_BY, $acceptedBy['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($acceptedBy['max'])) {
                $this->addUsingAlias(LessonAnswerPeer::ACCEPTED_BY, $acceptedBy['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(LessonAnswerPeer::ACCEPTED_BY, $acceptedBy, $comparison);
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
     * @return LessonAnswerQuery The current query, for fluid interface
     */
    public function filterByCreatedAt($createdAt = null, $comparison = null)
    {
        if (is_array($createdAt)) {
            $useMinMax = false;
            if (isset($createdAt['min'])) {
                $this->addUsingAlias(LessonAnswerPeer::CREATED_AT, $createdAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($createdAt['max'])) {
                $this->addUsingAlias(LessonAnswerPeer::CREATED_AT, $createdAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(LessonAnswerPeer::CREATED_AT, $createdAt, $comparison);
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
     * @return LessonAnswerQuery The current query, for fluid interface
     */
    public function filterByUpdatedAt($updatedAt = null, $comparison = null)
    {
        if (is_array($updatedAt)) {
            $useMinMax = false;
            if (isset($updatedAt['min'])) {
                $this->addUsingAlias(LessonAnswerPeer::UPDATED_AT, $updatedAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($updatedAt['max'])) {
                $this->addUsingAlias(LessonAnswerPeer::UPDATED_AT, $updatedAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(LessonAnswerPeer::UPDATED_AT, $updatedAt, $comparison);
    }

    /**
     * Filter the query by a related Lesson object
     *
     * @param   Lesson|PropelObjectCollection $lesson The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return   LessonAnswerQuery The current query, for fluid interface
     * @throws   PropelException - if the provided filter is invalid.
     */
    public function filterByLesson($lesson, $comparison = null)
    {
        if ($lesson instanceof Lesson) {
            return $this
                ->addUsingAlias(LessonAnswerPeer::LESSON_ID, $lesson->getId(), $comparison);
        } elseif ($lesson instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(LessonAnswerPeer::LESSON_ID, $lesson->toKeyValue('PrimaryKey', 'Id'), $comparison);
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
     * @return LessonAnswerQuery The current query, for fluid interface
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
     * Filter the query by a related LessonQuestion object
     *
     * @param   LessonQuestion|PropelObjectCollection $lessonQuestion The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return   LessonAnswerQuery The current query, for fluid interface
     * @throws   PropelException - if the provided filter is invalid.
     */
    public function filterByLessonQuestion($lessonQuestion, $comparison = null)
    {
        if ($lessonQuestion instanceof LessonQuestion) {
            return $this
                ->addUsingAlias(LessonAnswerPeer::QUESTION_ID, $lessonQuestion->getId(), $comparison);
        } elseif ($lessonQuestion instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(LessonAnswerPeer::QUESTION_ID, $lessonQuestion->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByLessonQuestion() only accepts arguments of type LessonQuestion or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the LessonQuestion relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return LessonAnswerQuery The current query, for fluid interface
     */
    public function joinLessonQuestion($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('LessonQuestion');

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
            $this->addJoinObject($join, 'LessonQuestion');
        }

        return $this;
    }

    /**
     * Use the LessonQuestion relation LessonQuestion object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \Smirik\CourseBundle\Model\LessonQuestionQuery A secondary query class using the current class as primary query
     */
    public function useLessonQuestionQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinLessonQuestion($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'LessonQuestion', '\Smirik\CourseBundle\Model\LessonQuestionQuery');
    }

    /**
     * Filter the query by a related User object
     *
     * @param   User|PropelObjectCollection $user The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return   LessonAnswerQuery The current query, for fluid interface
     * @throws   PropelException - if the provided filter is invalid.
     */
    public function filterByUser($user, $comparison = null)
    {
        if ($user instanceof User) {
            return $this
                ->addUsingAlias(LessonAnswerPeer::USER_ID, $user->getId(), $comparison);
        } elseif ($user instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(LessonAnswerPeer::USER_ID, $user->toKeyValue('PrimaryKey', 'Id'), $comparison);
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
     * @return LessonAnswerQuery The current query, for fluid interface
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
     * @param   LessonAnswer $lessonAnswer Object to remove from the list of results
     *
     * @return LessonAnswerQuery The current query, for fluid interface
     */
    public function prune($lessonAnswer = null)
    {
        if ($lessonAnswer) {
            $this->addUsingAlias(LessonAnswerPeer::ID, $lessonAnswer->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    // timestampable behavior

    /**
     * Filter by the latest updated
     *
     * @param      int $nbDays Maximum age of the latest update in days
     *
     * @return     LessonAnswerQuery The current query, for fluid interface
     */
    public function recentlyUpdated($nbDays = 7)
    {
        return $this->addUsingAlias(LessonAnswerPeer::UPDATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by update date desc
     *
     * @return     LessonAnswerQuery The current query, for fluid interface
     */
    public function lastUpdatedFirst()
    {
        return $this->addDescendingOrderByColumn(LessonAnswerPeer::UPDATED_AT);
    }

    /**
     * Order by update date asc
     *
     * @return     LessonAnswerQuery The current query, for fluid interface
     */
    public function firstUpdatedFirst()
    {
        return $this->addAscendingOrderByColumn(LessonAnswerPeer::UPDATED_AT);
    }

    /**
     * Filter by the latest created
     *
     * @param      int $nbDays Maximum age of in days
     *
     * @return     LessonAnswerQuery The current query, for fluid interface
     */
    public function recentlyCreated($nbDays = 7)
    {
        return $this->addUsingAlias(LessonAnswerPeer::CREATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by create date desc
     *
     * @return     LessonAnswerQuery The current query, for fluid interface
     */
    public function lastCreatedFirst()
    {
        return $this->addDescendingOrderByColumn(LessonAnswerPeer::CREATED_AT);
    }

    /**
     * Order by create date asc
     *
     * @return     LessonAnswerQuery The current query, for fluid interface
     */
    public function firstCreatedFirst()
    {
        return $this->addAscendingOrderByColumn(LessonAnswerPeer::CREATED_AT);
    }
}
