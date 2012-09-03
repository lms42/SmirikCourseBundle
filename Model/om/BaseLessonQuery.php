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
use Smirik\CourseBundle\Model\Content;
use Smirik\CourseBundle\Model\Course;
use Smirik\CourseBundle\Model\Lesson;
use Smirik\CourseBundle\Model\LessonAnswer;
use Smirik\CourseBundle\Model\LessonPeer;
use Smirik\CourseBundle\Model\LessonQuery;
use Smirik\CourseBundle\Model\LessonQuestion;
use Smirik\CourseBundle\Model\LessonQuiz;
use Smirik\CourseBundle\Model\SlideshareContent;
use Smirik\CourseBundle\Model\Task;
use Smirik\CourseBundle\Model\TextContent;
use Smirik\CourseBundle\Model\UrlContent;
use Smirik\CourseBundle\Model\UserLesson;
use Smirik\CourseBundle\Model\UserTask;
use Smirik\CourseBundle\Model\YoutubeContent;

/**
 * @method LessonQuery orderById($order = Criteria::ASC) Order by the id column
 * @method LessonQuery orderByCourseId($order = Criteria::ASC) Order by the course_id column
 * @method LessonQuery orderByTitle($order = Criteria::ASC) Order by the title column
 * @method LessonQuery orderByCreatedAt($order = Criteria::ASC) Order by the created_at column
 * @method LessonQuery orderByUpdatedAt($order = Criteria::ASC) Order by the updated_at column
 * @method LessonQuery orderBySortableRank($order = Criteria::ASC) Order by the sortable_rank column
 *
 * @method LessonQuery groupById() Group by the id column
 * @method LessonQuery groupByCourseId() Group by the course_id column
 * @method LessonQuery groupByTitle() Group by the title column
 * @method LessonQuery groupByCreatedAt() Group by the created_at column
 * @method LessonQuery groupByUpdatedAt() Group by the updated_at column
 * @method LessonQuery groupBySortableRank() Group by the sortable_rank column
 *
 * @method LessonQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method LessonQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method LessonQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method LessonQuery leftJoinCourse($relationAlias = null) Adds a LEFT JOIN clause to the query using the Course relation
 * @method LessonQuery rightJoinCourse($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Course relation
 * @method LessonQuery innerJoinCourse($relationAlias = null) Adds a INNER JOIN clause to the query using the Course relation
 *
 * @method LessonQuery leftJoinUserLesson($relationAlias = null) Adds a LEFT JOIN clause to the query using the UserLesson relation
 * @method LessonQuery rightJoinUserLesson($relationAlias = null) Adds a RIGHT JOIN clause to the query using the UserLesson relation
 * @method LessonQuery innerJoinUserLesson($relationAlias = null) Adds a INNER JOIN clause to the query using the UserLesson relation
 *
 * @method LessonQuery leftJoinLessonQuiz($relationAlias = null) Adds a LEFT JOIN clause to the query using the LessonQuiz relation
 * @method LessonQuery rightJoinLessonQuiz($relationAlias = null) Adds a RIGHT JOIN clause to the query using the LessonQuiz relation
 * @method LessonQuery innerJoinLessonQuiz($relationAlias = null) Adds a INNER JOIN clause to the query using the LessonQuiz relation
 *
 * @method LessonQuery leftJoinLessonQuestion($relationAlias = null) Adds a LEFT JOIN clause to the query using the LessonQuestion relation
 * @method LessonQuery rightJoinLessonQuestion($relationAlias = null) Adds a RIGHT JOIN clause to the query using the LessonQuestion relation
 * @method LessonQuery innerJoinLessonQuestion($relationAlias = null) Adds a INNER JOIN clause to the query using the LessonQuestion relation
 *
 * @method LessonQuery leftJoinLessonAnswer($relationAlias = null) Adds a LEFT JOIN clause to the query using the LessonAnswer relation
 * @method LessonQuery rightJoinLessonAnswer($relationAlias = null) Adds a RIGHT JOIN clause to the query using the LessonAnswer relation
 * @method LessonQuery innerJoinLessonAnswer($relationAlias = null) Adds a INNER JOIN clause to the query using the LessonAnswer relation
 *
 * @method LessonQuery leftJoinContent($relationAlias = null) Adds a LEFT JOIN clause to the query using the Content relation
 * @method LessonQuery rightJoinContent($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Content relation
 * @method LessonQuery innerJoinContent($relationAlias = null) Adds a INNER JOIN clause to the query using the Content relation
 *
 * @method LessonQuery leftJoinTask($relationAlias = null) Adds a LEFT JOIN clause to the query using the Task relation
 * @method LessonQuery rightJoinTask($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Task relation
 * @method LessonQuery innerJoinTask($relationAlias = null) Adds a INNER JOIN clause to the query using the Task relation
 *
 * @method LessonQuery leftJoinUserTask($relationAlias = null) Adds a LEFT JOIN clause to the query using the UserTask relation
 * @method LessonQuery rightJoinUserTask($relationAlias = null) Adds a RIGHT JOIN clause to the query using the UserTask relation
 * @method LessonQuery innerJoinUserTask($relationAlias = null) Adds a INNER JOIN clause to the query using the UserTask relation
 *
 * @method LessonQuery leftJoinTextContent($relationAlias = null) Adds a LEFT JOIN clause to the query using the TextContent relation
 * @method LessonQuery rightJoinTextContent($relationAlias = null) Adds a RIGHT JOIN clause to the query using the TextContent relation
 * @method LessonQuery innerJoinTextContent($relationAlias = null) Adds a INNER JOIN clause to the query using the TextContent relation
 *
 * @method LessonQuery leftJoinUrlContent($relationAlias = null) Adds a LEFT JOIN clause to the query using the UrlContent relation
 * @method LessonQuery rightJoinUrlContent($relationAlias = null) Adds a RIGHT JOIN clause to the query using the UrlContent relation
 * @method LessonQuery innerJoinUrlContent($relationAlias = null) Adds a INNER JOIN clause to the query using the UrlContent relation
 *
 * @method LessonQuery leftJoinYoutubeContent($relationAlias = null) Adds a LEFT JOIN clause to the query using the YoutubeContent relation
 * @method LessonQuery rightJoinYoutubeContent($relationAlias = null) Adds a RIGHT JOIN clause to the query using the YoutubeContent relation
 * @method LessonQuery innerJoinYoutubeContent($relationAlias = null) Adds a INNER JOIN clause to the query using the YoutubeContent relation
 *
 * @method LessonQuery leftJoinSlideshareContent($relationAlias = null) Adds a LEFT JOIN clause to the query using the SlideshareContent relation
 * @method LessonQuery rightJoinSlideshareContent($relationAlias = null) Adds a RIGHT JOIN clause to the query using the SlideshareContent relation
 * @method LessonQuery innerJoinSlideshareContent($relationAlias = null) Adds a INNER JOIN clause to the query using the SlideshareContent relation
 *
 * @method Lesson findOne(PropelPDO $con = null) Return the first Lesson matching the query
 * @method Lesson findOneOrCreate(PropelPDO $con = null) Return the first Lesson matching the query, or a new Lesson object populated from the query conditions when no match is found
 *
 * @method Lesson findOneByCourseId(int $course_id) Return the first Lesson filtered by the course_id column
 * @method Lesson findOneByTitle(string $title) Return the first Lesson filtered by the title column
 * @method Lesson findOneByCreatedAt(string $created_at) Return the first Lesson filtered by the created_at column
 * @method Lesson findOneByUpdatedAt(string $updated_at) Return the first Lesson filtered by the updated_at column
 * @method Lesson findOneBySortableRank(int $sortable_rank) Return the first Lesson filtered by the sortable_rank column
 *
 * @method array findById(int $id) Return Lesson objects filtered by the id column
 * @method array findByCourseId(int $course_id) Return Lesson objects filtered by the course_id column
 * @method array findByTitle(string $title) Return Lesson objects filtered by the title column
 * @method array findByCreatedAt(string $created_at) Return Lesson objects filtered by the created_at column
 * @method array findByUpdatedAt(string $updated_at) Return Lesson objects filtered by the updated_at column
 * @method array findBySortableRank(int $sortable_rank) Return Lesson objects filtered by the sortable_rank column
 */
abstract class BaseLessonQuery extends ModelCriteria
{
    /**
     * Initializes internal state of BaseLessonQuery object.
     *
     * @param     string $dbName The dabase name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = 'Smirik\\CourseBundle\\Model\\Lesson', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new LessonQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     LessonQuery|Criteria $criteria Optional Criteria to build the query from
     *
     * @return LessonQuery
     */
    public static function create($modelAlias = null, $criteria = null)
    {
        if ($criteria instanceof LessonQuery) {
            return $criteria;
        }
        $query = new LessonQuery();
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
     * @return   Lesson|Lesson[]|mixed the result, formatted by the current formatter
     */
    public function findPk($key, $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = LessonPeer::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is alredy in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getConnection(LessonPeer::DATABASE_NAME, Propel::CONNECTION_READ);
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
     * @return   Lesson A model object, or null if the key is not found
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
     * @return   Lesson A model object, or null if the key is not found
     * @throws   PropelException
     */
    protected function findPkSimple($key, $con)
    {
        $sql = 'SELECT `ID`, `COURSE_ID`, `TITLE`, `CREATED_AT`, `UPDATED_AT`, `SORTABLE_RANK` FROM `lessons` WHERE `ID` = :p0';
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
            $obj = new Lesson();
            $obj->hydrate($row);
            LessonPeer::addInstanceToPool($obj, (string) $key);
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
     * @return Lesson|Lesson[]|mixed the result, formatted by the current formatter
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
     * @return PropelObjectCollection|Lesson[]|mixed the list of results, formatted by the current formatter
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
     * @return LessonQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(LessonPeer::ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return LessonQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(LessonPeer::ID, $keys, Criteria::IN);
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
     * @return LessonQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id) && null === $comparison) {
            $comparison = Criteria::IN;
        }

        return $this->addUsingAlias(LessonPeer::ID, $id, $comparison);
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
     * @return LessonQuery The current query, for fluid interface
     */
    public function filterByCourseId($courseId = null, $comparison = null)
    {
        if (is_array($courseId)) {
            $useMinMax = false;
            if (isset($courseId['min'])) {
                $this->addUsingAlias(LessonPeer::COURSE_ID, $courseId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($courseId['max'])) {
                $this->addUsingAlias(LessonPeer::COURSE_ID, $courseId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(LessonPeer::COURSE_ID, $courseId, $comparison);
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
     * @return LessonQuery The current query, for fluid interface
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

        return $this->addUsingAlias(LessonPeer::TITLE, $title, $comparison);
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
     * @return LessonQuery The current query, for fluid interface
     */
    public function filterByCreatedAt($createdAt = null, $comparison = null)
    {
        if (is_array($createdAt)) {
            $useMinMax = false;
            if (isset($createdAt['min'])) {
                $this->addUsingAlias(LessonPeer::CREATED_AT, $createdAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($createdAt['max'])) {
                $this->addUsingAlias(LessonPeer::CREATED_AT, $createdAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(LessonPeer::CREATED_AT, $createdAt, $comparison);
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
     * @return LessonQuery The current query, for fluid interface
     */
    public function filterByUpdatedAt($updatedAt = null, $comparison = null)
    {
        if (is_array($updatedAt)) {
            $useMinMax = false;
            if (isset($updatedAt['min'])) {
                $this->addUsingAlias(LessonPeer::UPDATED_AT, $updatedAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($updatedAt['max'])) {
                $this->addUsingAlias(LessonPeer::UPDATED_AT, $updatedAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(LessonPeer::UPDATED_AT, $updatedAt, $comparison);
    }

    /**
     * Filter the query on the sortable_rank column
     *
     * Example usage:
     * <code>
     * $query->filterBySortableRank(1234); // WHERE sortable_rank = 1234
     * $query->filterBySortableRank(array(12, 34)); // WHERE sortable_rank IN (12, 34)
     * $query->filterBySortableRank(array('min' => 12)); // WHERE sortable_rank > 12
     * </code>
     *
     * @param     mixed $sortableRank The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return LessonQuery The current query, for fluid interface
     */
    public function filterBySortableRank($sortableRank = null, $comparison = null)
    {
        if (is_array($sortableRank)) {
            $useMinMax = false;
            if (isset($sortableRank['min'])) {
                $this->addUsingAlias(LessonPeer::SORTABLE_RANK, $sortableRank['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($sortableRank['max'])) {
                $this->addUsingAlias(LessonPeer::SORTABLE_RANK, $sortableRank['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(LessonPeer::SORTABLE_RANK, $sortableRank, $comparison);
    }

    /**
     * Filter the query by a related Course object
     *
     * @param   Course|PropelObjectCollection $course The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return   LessonQuery The current query, for fluid interface
     * @throws   PropelException - if the provided filter is invalid.
     */
    public function filterByCourse($course, $comparison = null)
    {
        if ($course instanceof Course) {
            return $this
                ->addUsingAlias(LessonPeer::COURSE_ID, $course->getId(), $comparison);
        } elseif ($course instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(LessonPeer::COURSE_ID, $course->toKeyValue('PrimaryKey', 'Id'), $comparison);
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
     * @return LessonQuery The current query, for fluid interface
     */
    public function joinCourse($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
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
    public function useCourseQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinCourse($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Course', '\Smirik\CourseBundle\Model\CourseQuery');
    }

    /**
     * Filter the query by a related UserLesson object
     *
     * @param   UserLesson|PropelObjectCollection $userLesson  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return   LessonQuery The current query, for fluid interface
     * @throws   PropelException - if the provided filter is invalid.
     */
    public function filterByUserLesson($userLesson, $comparison = null)
    {
        if ($userLesson instanceof UserLesson) {
            return $this
                ->addUsingAlias(LessonPeer::ID, $userLesson->getLessonId(), $comparison);
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
     * @return LessonQuery The current query, for fluid interface
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
     * Filter the query by a related LessonQuiz object
     *
     * @param   LessonQuiz|PropelObjectCollection $lessonQuiz  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return   LessonQuery The current query, for fluid interface
     * @throws   PropelException - if the provided filter is invalid.
     */
    public function filterByLessonQuiz($lessonQuiz, $comparison = null)
    {
        if ($lessonQuiz instanceof LessonQuiz) {
            return $this
                ->addUsingAlias(LessonPeer::ID, $lessonQuiz->getLessonId(), $comparison);
        } elseif ($lessonQuiz instanceof PropelObjectCollection) {
            return $this
                ->useLessonQuizQuery()
                ->filterByPrimaryKeys($lessonQuiz->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByLessonQuiz() only accepts arguments of type LessonQuiz or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the LessonQuiz relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return LessonQuery The current query, for fluid interface
     */
    public function joinLessonQuiz($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('LessonQuiz');

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
            $this->addJoinObject($join, 'LessonQuiz');
        }

        return $this;
    }

    /**
     * Use the LessonQuiz relation LessonQuiz object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \Smirik\CourseBundle\Model\LessonQuizQuery A secondary query class using the current class as primary query
     */
    public function useLessonQuizQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinLessonQuiz($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'LessonQuiz', '\Smirik\CourseBundle\Model\LessonQuizQuery');
    }

    /**
     * Filter the query by a related LessonQuestion object
     *
     * @param   LessonQuestion|PropelObjectCollection $lessonQuestion  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return   LessonQuery The current query, for fluid interface
     * @throws   PropelException - if the provided filter is invalid.
     */
    public function filterByLessonQuestion($lessonQuestion, $comparison = null)
    {
        if ($lessonQuestion instanceof LessonQuestion) {
            return $this
                ->addUsingAlias(LessonPeer::ID, $lessonQuestion->getLessonId(), $comparison);
        } elseif ($lessonQuestion instanceof PropelObjectCollection) {
            return $this
                ->useLessonQuestionQuery()
                ->filterByPrimaryKeys($lessonQuestion->getPrimaryKeys())
                ->endUse();
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
     * @return LessonQuery The current query, for fluid interface
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
     * Filter the query by a related LessonAnswer object
     *
     * @param   LessonAnswer|PropelObjectCollection $lessonAnswer  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return   LessonQuery The current query, for fluid interface
     * @throws   PropelException - if the provided filter is invalid.
     */
    public function filterByLessonAnswer($lessonAnswer, $comparison = null)
    {
        if ($lessonAnswer instanceof LessonAnswer) {
            return $this
                ->addUsingAlias(LessonPeer::ID, $lessonAnswer->getLessonId(), $comparison);
        } elseif ($lessonAnswer instanceof PropelObjectCollection) {
            return $this
                ->useLessonAnswerQuery()
                ->filterByPrimaryKeys($lessonAnswer->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByLessonAnswer() only accepts arguments of type LessonAnswer or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the LessonAnswer relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return LessonQuery The current query, for fluid interface
     */
    public function joinLessonAnswer($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('LessonAnswer');

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
            $this->addJoinObject($join, 'LessonAnswer');
        }

        return $this;
    }

    /**
     * Use the LessonAnswer relation LessonAnswer object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \Smirik\CourseBundle\Model\LessonAnswerQuery A secondary query class using the current class as primary query
     */
    public function useLessonAnswerQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinLessonAnswer($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'LessonAnswer', '\Smirik\CourseBundle\Model\LessonAnswerQuery');
    }

    /**
     * Filter the query by a related Content object
     *
     * @param   Content|PropelObjectCollection $content  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return   LessonQuery The current query, for fluid interface
     * @throws   PropelException - if the provided filter is invalid.
     */
    public function filterByContent($content, $comparison = null)
    {
        if ($content instanceof Content) {
            return $this
                ->addUsingAlias(LessonPeer::ID, $content->getLessonId(), $comparison);
        } elseif ($content instanceof PropelObjectCollection) {
            return $this
                ->useContentQuery()
                ->filterByPrimaryKeys($content->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByContent() only accepts arguments of type Content or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Content relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return LessonQuery The current query, for fluid interface
     */
    public function joinContent($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Content');

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
            $this->addJoinObject($join, 'Content');
        }

        return $this;
    }

    /**
     * Use the Content relation Content object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \Smirik\CourseBundle\Model\ContentQuery A secondary query class using the current class as primary query
     */
    public function useContentQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinContent($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Content', '\Smirik\CourseBundle\Model\ContentQuery');
    }

    /**
     * Filter the query by a related Task object
     *
     * @param   Task|PropelObjectCollection $task  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return   LessonQuery The current query, for fluid interface
     * @throws   PropelException - if the provided filter is invalid.
     */
    public function filterByTask($task, $comparison = null)
    {
        if ($task instanceof Task) {
            return $this
                ->addUsingAlias(LessonPeer::ID, $task->getLessonId(), $comparison);
        } elseif ($task instanceof PropelObjectCollection) {
            return $this
                ->useTaskQuery()
                ->filterByPrimaryKeys($task->getPrimaryKeys())
                ->endUse();
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
     * @return LessonQuery The current query, for fluid interface
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
     * Filter the query by a related UserTask object
     *
     * @param   UserTask|PropelObjectCollection $userTask  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return   LessonQuery The current query, for fluid interface
     * @throws   PropelException - if the provided filter is invalid.
     */
    public function filterByUserTask($userTask, $comparison = null)
    {
        if ($userTask instanceof UserTask) {
            return $this
                ->addUsingAlias(LessonPeer::ID, $userTask->getLessonId(), $comparison);
        } elseif ($userTask instanceof PropelObjectCollection) {
            return $this
                ->useUserTaskQuery()
                ->filterByPrimaryKeys($userTask->getPrimaryKeys())
                ->endUse();
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
     * @return LessonQuery The current query, for fluid interface
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
     * Filter the query by a related TextContent object
     *
     * @param   TextContent|PropelObjectCollection $textContent  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return   LessonQuery The current query, for fluid interface
     * @throws   PropelException - if the provided filter is invalid.
     */
    public function filterByTextContent($textContent, $comparison = null)
    {
        if ($textContent instanceof TextContent) {
            return $this
                ->addUsingAlias(LessonPeer::ID, $textContent->getLessonId(), $comparison);
        } elseif ($textContent instanceof PropelObjectCollection) {
            return $this
                ->useTextContentQuery()
                ->filterByPrimaryKeys($textContent->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByTextContent() only accepts arguments of type TextContent or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the TextContent relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return LessonQuery The current query, for fluid interface
     */
    public function joinTextContent($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('TextContent');

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
            $this->addJoinObject($join, 'TextContent');
        }

        return $this;
    }

    /**
     * Use the TextContent relation TextContent object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \Smirik\CourseBundle\Model\TextContentQuery A secondary query class using the current class as primary query
     */
    public function useTextContentQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinTextContent($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'TextContent', '\Smirik\CourseBundle\Model\TextContentQuery');
    }

    /**
     * Filter the query by a related UrlContent object
     *
     * @param   UrlContent|PropelObjectCollection $urlContent  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return   LessonQuery The current query, for fluid interface
     * @throws   PropelException - if the provided filter is invalid.
     */
    public function filterByUrlContent($urlContent, $comparison = null)
    {
        if ($urlContent instanceof UrlContent) {
            return $this
                ->addUsingAlias(LessonPeer::ID, $urlContent->getLessonId(), $comparison);
        } elseif ($urlContent instanceof PropelObjectCollection) {
            return $this
                ->useUrlContentQuery()
                ->filterByPrimaryKeys($urlContent->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByUrlContent() only accepts arguments of type UrlContent or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the UrlContent relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return LessonQuery The current query, for fluid interface
     */
    public function joinUrlContent($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('UrlContent');

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
            $this->addJoinObject($join, 'UrlContent');
        }

        return $this;
    }

    /**
     * Use the UrlContent relation UrlContent object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \Smirik\CourseBundle\Model\UrlContentQuery A secondary query class using the current class as primary query
     */
    public function useUrlContentQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinUrlContent($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'UrlContent', '\Smirik\CourseBundle\Model\UrlContentQuery');
    }

    /**
     * Filter the query by a related YoutubeContent object
     *
     * @param   YoutubeContent|PropelObjectCollection $youtubeContent  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return   LessonQuery The current query, for fluid interface
     * @throws   PropelException - if the provided filter is invalid.
     */
    public function filterByYoutubeContent($youtubeContent, $comparison = null)
    {
        if ($youtubeContent instanceof YoutubeContent) {
            return $this
                ->addUsingAlias(LessonPeer::ID, $youtubeContent->getLessonId(), $comparison);
        } elseif ($youtubeContent instanceof PropelObjectCollection) {
            return $this
                ->useYoutubeContentQuery()
                ->filterByPrimaryKeys($youtubeContent->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByYoutubeContent() only accepts arguments of type YoutubeContent or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the YoutubeContent relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return LessonQuery The current query, for fluid interface
     */
    public function joinYoutubeContent($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('YoutubeContent');

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
            $this->addJoinObject($join, 'YoutubeContent');
        }

        return $this;
    }

    /**
     * Use the YoutubeContent relation YoutubeContent object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \Smirik\CourseBundle\Model\YoutubeContentQuery A secondary query class using the current class as primary query
     */
    public function useYoutubeContentQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinYoutubeContent($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'YoutubeContent', '\Smirik\CourseBundle\Model\YoutubeContentQuery');
    }

    /**
     * Filter the query by a related SlideshareContent object
     *
     * @param   SlideshareContent|PropelObjectCollection $slideshareContent  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return   LessonQuery The current query, for fluid interface
     * @throws   PropelException - if the provided filter is invalid.
     */
    public function filterBySlideshareContent($slideshareContent, $comparison = null)
    {
        if ($slideshareContent instanceof SlideshareContent) {
            return $this
                ->addUsingAlias(LessonPeer::ID, $slideshareContent->getLessonId(), $comparison);
        } elseif ($slideshareContent instanceof PropelObjectCollection) {
            return $this
                ->useSlideshareContentQuery()
                ->filterByPrimaryKeys($slideshareContent->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterBySlideshareContent() only accepts arguments of type SlideshareContent or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the SlideshareContent relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return LessonQuery The current query, for fluid interface
     */
    public function joinSlideshareContent($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('SlideshareContent');

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
            $this->addJoinObject($join, 'SlideshareContent');
        }

        return $this;
    }

    /**
     * Use the SlideshareContent relation SlideshareContent object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \Smirik\CourseBundle\Model\SlideshareContentQuery A secondary query class using the current class as primary query
     */
    public function useSlideshareContentQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinSlideshareContent($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'SlideshareContent', '\Smirik\CourseBundle\Model\SlideshareContentQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   Lesson $lesson Object to remove from the list of results
     *
     * @return LessonQuery The current query, for fluid interface
     */
    public function prune($lesson = null)
    {
        if ($lesson) {
            $this->addUsingAlias(LessonPeer::ID, $lesson->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    // timestampable behavior

    /**
     * Filter by the latest updated
     *
     * @param      int $nbDays Maximum age of the latest update in days
     *
     * @return     LessonQuery The current query, for fluid interface
     */
    public function recentlyUpdated($nbDays = 7)
    {
        return $this->addUsingAlias(LessonPeer::UPDATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by update date desc
     *
     * @return     LessonQuery The current query, for fluid interface
     */
    public function lastUpdatedFirst()
    {
        return $this->addDescendingOrderByColumn(LessonPeer::UPDATED_AT);
    }

    /**
     * Order by update date asc
     *
     * @return     LessonQuery The current query, for fluid interface
     */
    public function firstUpdatedFirst()
    {
        return $this->addAscendingOrderByColumn(LessonPeer::UPDATED_AT);
    }

    /**
     * Filter by the latest created
     *
     * @param      int $nbDays Maximum age of in days
     *
     * @return     LessonQuery The current query, for fluid interface
     */
    public function recentlyCreated($nbDays = 7)
    {
        return $this->addUsingAlias(LessonPeer::CREATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by create date desc
     *
     * @return     LessonQuery The current query, for fluid interface
     */
    public function lastCreatedFirst()
    {
        return $this->addDescendingOrderByColumn(LessonPeer::CREATED_AT);
    }

    /**
     * Order by create date asc
     *
     * @return     LessonQuery The current query, for fluid interface
     */
    public function firstCreatedFirst()
    {
        return $this->addAscendingOrderByColumn(LessonPeer::CREATED_AT);
    }
    // sortable behavior

    /**
     * Returns the objects in a certain list, from the list scope
     *
     * @param     int $scope		Scope to determine which objects node to return
     *
     * @return    LessonQuery The current query, for fluid interface
     */
    public function inList($scope = null)
    {
        return $this->addUsingAlias(LessonPeer::SCOPE_COL, $scope, Criteria::EQUAL);
    }

    /**
     * Filter the query based on a rank in the list
     *
     * @param     integer   $rank rank
     * @param     int $scope		Scope to determine which suite to consider
     *
     * @return    LessonQuery The current query, for fluid interface
     */
    public function filterByRank($rank, $scope = null)
    {
        return $this
            ->inList($scope)
            ->addUsingAlias(LessonPeer::RANK_COL, $rank, Criteria::EQUAL);
    }

    /**
     * Order the query based on the rank in the list.
     * Using the default $order, returns the item with the lowest rank first
     *
     * @param     string $order either Criteria::ASC (default) or Criteria::DESC
     *
     * @return    LessonQuery The current query, for fluid interface
     */
    public function orderByRank($order = Criteria::ASC)
    {
        $order = strtoupper($order);
        switch ($order) {
            case Criteria::ASC:
                return $this->addAscendingOrderByColumn($this->getAliasedColName(LessonPeer::RANK_COL));
                break;
            case Criteria::DESC:
                return $this->addDescendingOrderByColumn($this->getAliasedColName(LessonPeer::RANK_COL));
                break;
            default:
                throw new PropelException('LessonQuery::orderBy() only accepts "asc" or "desc" as argument');
        }
    }

    /**
     * Get an item from the list based on its rank
     *
     * @param     integer   $rank rank
     * @param     int $scope		Scope to determine which suite to consider
     * @param     PropelPDO $con optional connection
     *
     * @return    Lesson
     */
    public function findOneByRank($rank, $scope = null, PropelPDO $con = null)
    {
        return $this
            ->filterByRank($rank, $scope)
            ->findOne($con);
    }

    /**
     * Returns a list of objects
     *
     * @param      int $scope		Scope to determine which list to return
     * @param      PropelPDO $con	Connection to use.
     *
     * @return     mixed the list of results, formatted by the current formatter
     */
    public function findList($scope = null, $con = null)
    {
        return $this
            ->inList($scope)
            ->orderByRank()
            ->find($con);
    }

    /**
     * Get the highest rank
     *
     * @param      int $scope		Scope to determine which suite to consider
     * @param     PropelPDO optional connection
     *
     * @return    integer highest position
     */
    public function getMaxRank($scope = null, PropelPDO $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection(LessonPeer::DATABASE_NAME);
        }
        // shift the objects with a position lower than the one of object
        $this->addSelectColumn('MAX(' . LessonPeer::RANK_COL . ')');
        $this->add(LessonPeer::SCOPE_COL, $scope, Criteria::EQUAL);
        $stmt = $this->doSelect($con);

        return $stmt->fetchColumn();
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
    public function reorder(array $order, PropelPDO $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection(LessonPeer::DATABASE_NAME);
        }

        $con->beginTransaction();
        try {
            $ids = array_keys($order);
            $objects = $this->findPks($ids, $con);
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

}
