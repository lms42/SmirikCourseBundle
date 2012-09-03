<?php

namespace Smirik\CourseBundle\Model\om;

use \Criteria;
use \Exception;
use \ModelJoin;
use \PDO;
use \Propel;
use \PropelCollection;
use \PropelException;
use \PropelObjectCollection;
use \PropelPDO;
use Smirik\CourseBundle\Model\Content;
use Smirik\CourseBundle\Model\ContentQuery;
use Smirik\CourseBundle\Model\Lesson;
use Smirik\CourseBundle\Model\TextContent;
use Smirik\CourseBundle\Model\TextContentPeer;
use Smirik\CourseBundle\Model\TextContentQuery;

/**
 * @method TextContentQuery orderByText($order = Criteria::ASC) Order by the text column
 * @method TextContentQuery orderById($order = Criteria::ASC) Order by the id column
 * @method TextContentQuery orderByLessonId($order = Criteria::ASC) Order by the lesson_id column
 * @method TextContentQuery orderByTitle($order = Criteria::ASC) Order by the title column
 * @method TextContentQuery orderByDescription($order = Criteria::ASC) Order by the description column
 * @method TextContentQuery orderByCreatedAt($order = Criteria::ASC) Order by the created_at column
 * @method TextContentQuery orderByUpdatedAt($order = Criteria::ASC) Order by the updated_at column
 * @method TextContentQuery orderBySortableRank($order = Criteria::ASC) Order by the sortable_rank column
 *
 * @method TextContentQuery groupByText() Group by the text column
 * @method TextContentQuery groupById() Group by the id column
 * @method TextContentQuery groupByLessonId() Group by the lesson_id column
 * @method TextContentQuery groupByTitle() Group by the title column
 * @method TextContentQuery groupByDescription() Group by the description column
 * @method TextContentQuery groupByCreatedAt() Group by the created_at column
 * @method TextContentQuery groupByUpdatedAt() Group by the updated_at column
 * @method TextContentQuery groupBySortableRank() Group by the sortable_rank column
 *
 * @method TextContentQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method TextContentQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method TextContentQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method TextContentQuery leftJoinContent($relationAlias = null) Adds a LEFT JOIN clause to the query using the Content relation
 * @method TextContentQuery rightJoinContent($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Content relation
 * @method TextContentQuery innerJoinContent($relationAlias = null) Adds a INNER JOIN clause to the query using the Content relation
 *
 * @method TextContentQuery leftJoinLesson($relationAlias = null) Adds a LEFT JOIN clause to the query using the Lesson relation
 * @method TextContentQuery rightJoinLesson($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Lesson relation
 * @method TextContentQuery innerJoinLesson($relationAlias = null) Adds a INNER JOIN clause to the query using the Lesson relation
 *
 * @method TextContent findOne(PropelPDO $con = null) Return the first TextContent matching the query
 * @method TextContent findOneOrCreate(PropelPDO $con = null) Return the first TextContent matching the query, or a new TextContent object populated from the query conditions when no match is found
 *
 * @method TextContent findOneByText(string $text) Return the first TextContent filtered by the text column
 * @method TextContent findOneByLessonId(int $lesson_id) Return the first TextContent filtered by the lesson_id column
 * @method TextContent findOneByTitle(string $title) Return the first TextContent filtered by the title column
 * @method TextContent findOneByDescription(string $description) Return the first TextContent filtered by the description column
 * @method TextContent findOneByCreatedAt(string $created_at) Return the first TextContent filtered by the created_at column
 * @method TextContent findOneByUpdatedAt(string $updated_at) Return the first TextContent filtered by the updated_at column
 * @method TextContent findOneBySortableRank(int $sortable_rank) Return the first TextContent filtered by the sortable_rank column
 *
 * @method array findByText(string $text) Return TextContent objects filtered by the text column
 * @method array findById(int $id) Return TextContent objects filtered by the id column
 * @method array findByLessonId(int $lesson_id) Return TextContent objects filtered by the lesson_id column
 * @method array findByTitle(string $title) Return TextContent objects filtered by the title column
 * @method array findByDescription(string $description) Return TextContent objects filtered by the description column
 * @method array findByCreatedAt(string $created_at) Return TextContent objects filtered by the created_at column
 * @method array findByUpdatedAt(string $updated_at) Return TextContent objects filtered by the updated_at column
 * @method array findBySortableRank(int $sortable_rank) Return TextContent objects filtered by the sortable_rank column
 */
abstract class BaseTextContentQuery extends ContentQuery
{
    /**
     * Initializes internal state of BaseTextContentQuery object.
     *
     * @param     string $dbName The dabase name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = 'Smirik\\CourseBundle\\Model\\TextContent', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new TextContentQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     TextContentQuery|Criteria $criteria Optional Criteria to build the query from
     *
     * @return TextContentQuery
     */
    public static function create($modelAlias = null, $criteria = null)
    {
        if ($criteria instanceof TextContentQuery) {
            return $criteria;
        }
        $query = new TextContentQuery();
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
     * @return   TextContent|TextContent[]|mixed the result, formatted by the current formatter
     */
    public function findPk($key, $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = TextContentPeer::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is alredy in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getConnection(TextContentPeer::DATABASE_NAME, Propel::CONNECTION_READ);
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
     * @return   TextContent A model object, or null if the key is not found
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
     * @return   TextContent A model object, or null if the key is not found
     * @throws   PropelException
     */
    protected function findPkSimple($key, $con)
    {
        $sql = 'SELECT `TEXT`, `ID`, `LESSON_ID`, `TITLE`, `DESCRIPTION`, `CREATED_AT`, `UPDATED_AT`, `SORTABLE_RANK` FROM `lessons_content_texts` WHERE `ID` = :p0';
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
            $obj = new TextContent();
            $obj->hydrate($row);
            TextContentPeer::addInstanceToPool($obj, (string) $key);
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
     * @return TextContent|TextContent[]|mixed the result, formatted by the current formatter
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
     * @return PropelObjectCollection|TextContent[]|mixed the list of results, formatted by the current formatter
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
     * @return TextContentQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(TextContentPeer::ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return TextContentQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(TextContentPeer::ID, $keys, Criteria::IN);
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
     * @return TextContentQuery The current query, for fluid interface
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

        return $this->addUsingAlias(TextContentPeer::TEXT, $text, $comparison);
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
     * @see       filterByContent()
     *
     * @param     mixed $id The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return TextContentQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id) && null === $comparison) {
            $comparison = Criteria::IN;
        }

        return $this->addUsingAlias(TextContentPeer::ID, $id, $comparison);
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
     * @return TextContentQuery The current query, for fluid interface
     */
    public function filterByLessonId($lessonId = null, $comparison = null)
    {
        if (is_array($lessonId)) {
            $useMinMax = false;
            if (isset($lessonId['min'])) {
                $this->addUsingAlias(TextContentPeer::LESSON_ID, $lessonId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($lessonId['max'])) {
                $this->addUsingAlias(TextContentPeer::LESSON_ID, $lessonId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(TextContentPeer::LESSON_ID, $lessonId, $comparison);
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
     * @return TextContentQuery The current query, for fluid interface
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

        return $this->addUsingAlias(TextContentPeer::TITLE, $title, $comparison);
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
     * @return TextContentQuery The current query, for fluid interface
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

        return $this->addUsingAlias(TextContentPeer::DESCRIPTION, $description, $comparison);
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
     * @return TextContentQuery The current query, for fluid interface
     */
    public function filterByCreatedAt($createdAt = null, $comparison = null)
    {
        if (is_array($createdAt)) {
            $useMinMax = false;
            if (isset($createdAt['min'])) {
                $this->addUsingAlias(TextContentPeer::CREATED_AT, $createdAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($createdAt['max'])) {
                $this->addUsingAlias(TextContentPeer::CREATED_AT, $createdAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(TextContentPeer::CREATED_AT, $createdAt, $comparison);
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
     * @return TextContentQuery The current query, for fluid interface
     */
    public function filterByUpdatedAt($updatedAt = null, $comparison = null)
    {
        if (is_array($updatedAt)) {
            $useMinMax = false;
            if (isset($updatedAt['min'])) {
                $this->addUsingAlias(TextContentPeer::UPDATED_AT, $updatedAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($updatedAt['max'])) {
                $this->addUsingAlias(TextContentPeer::UPDATED_AT, $updatedAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(TextContentPeer::UPDATED_AT, $updatedAt, $comparison);
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
     * @return TextContentQuery The current query, for fluid interface
     */
    public function filterBySortableRank($sortableRank = null, $comparison = null)
    {
        if (is_array($sortableRank)) {
            $useMinMax = false;
            if (isset($sortableRank['min'])) {
                $this->addUsingAlias(TextContentPeer::SORTABLE_RANK, $sortableRank['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($sortableRank['max'])) {
                $this->addUsingAlias(TextContentPeer::SORTABLE_RANK, $sortableRank['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(TextContentPeer::SORTABLE_RANK, $sortableRank, $comparison);
    }

    /**
     * Filter the query by a related Content object
     *
     * @param   Content|PropelObjectCollection $content The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return   TextContentQuery The current query, for fluid interface
     * @throws   PropelException - if the provided filter is invalid.
     */
    public function filterByContent($content, $comparison = null)
    {
        if ($content instanceof Content) {
            return $this
                ->addUsingAlias(TextContentPeer::ID, $content->getId(), $comparison);
        } elseif ($content instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(TextContentPeer::ID, $content->toKeyValue('PrimaryKey', 'Id'), $comparison);
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
     * @return TextContentQuery The current query, for fluid interface
     */
    public function joinContent($relationAlias = null, $joinType = Criteria::INNER_JOIN)
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
    public function useContentQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinContent($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Content', '\Smirik\CourseBundle\Model\ContentQuery');
    }

    /**
     * Filter the query by a related Lesson object
     *
     * @param   Lesson|PropelObjectCollection $lesson The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return   TextContentQuery The current query, for fluid interface
     * @throws   PropelException - if the provided filter is invalid.
     */
    public function filterByLesson($lesson, $comparison = null)
    {
        if ($lesson instanceof Lesson) {
            return $this
                ->addUsingAlias(TextContentPeer::LESSON_ID, $lesson->getId(), $comparison);
        } elseif ($lesson instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(TextContentPeer::LESSON_ID, $lesson->toKeyValue('PrimaryKey', 'Id'), $comparison);
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
     * @return TextContentQuery The current query, for fluid interface
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
     * Exclude object from result
     *
     * @param   TextContent $textContent Object to remove from the list of results
     *
     * @return TextContentQuery The current query, for fluid interface
     */
    public function prune($textContent = null)
    {
        if ($textContent) {
            $this->addUsingAlias(TextContentPeer::ID, $textContent->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    // timestampable behavior

    /**
     * Filter by the latest updated
     *
     * @param      int $nbDays Maximum age of the latest update in days
     *
     * @return     TextContentQuery The current query, for fluid interface
     */
    public function recentlyUpdated($nbDays = 7)
    {
        return $this->addUsingAlias(TextContentPeer::UPDATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by update date desc
     *
     * @return     TextContentQuery The current query, for fluid interface
     */
    public function lastUpdatedFirst()
    {
        return $this->addDescendingOrderByColumn(TextContentPeer::UPDATED_AT);
    }

    /**
     * Order by update date asc
     *
     * @return     TextContentQuery The current query, for fluid interface
     */
    public function firstUpdatedFirst()
    {
        return $this->addAscendingOrderByColumn(TextContentPeer::UPDATED_AT);
    }

    /**
     * Filter by the latest created
     *
     * @param      int $nbDays Maximum age of in days
     *
     * @return     TextContentQuery The current query, for fluid interface
     */
    public function recentlyCreated($nbDays = 7)
    {
        return $this->addUsingAlias(TextContentPeer::CREATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by create date desc
     *
     * @return     TextContentQuery The current query, for fluid interface
     */
    public function lastCreatedFirst()
    {
        return $this->addDescendingOrderByColumn(TextContentPeer::CREATED_AT);
    }

    /**
     * Order by create date asc
     *
     * @return     TextContentQuery The current query, for fluid interface
     */
    public function firstCreatedFirst()
    {
        return $this->addAscendingOrderByColumn(TextContentPeer::CREATED_AT);
    }
    // sortable behavior

    /**
     * Returns the objects in a certain list, from the list scope
     *
     * @param     int $scope		Scope to determine which objects node to return
     *
     * @return    TextContentQuery The current query, for fluid interface
     */
    public function inList($scope = null)
    {
        return $this->addUsingAlias(TextContentPeer::SCOPE_COL, $scope, Criteria::EQUAL);
    }

    /**
     * Filter the query based on a rank in the list
     *
     * @param     integer   $rank rank
     * @param     int $scope		Scope to determine which suite to consider
     *
     * @return    TextContentQuery The current query, for fluid interface
     */
    public function filterByRank($rank, $scope = null)
    {
        return $this
            ->inList($scope)
            ->addUsingAlias(TextContentPeer::RANK_COL, $rank, Criteria::EQUAL);
    }

    /**
     * Order the query based on the rank in the list.
     * Using the default $order, returns the item with the lowest rank first
     *
     * @param     string $order either Criteria::ASC (default) or Criteria::DESC
     *
     * @return    TextContentQuery The current query, for fluid interface
     */
    public function orderByRank($order = Criteria::ASC)
    {
        $order = strtoupper($order);
        switch ($order) {
            case Criteria::ASC:
                return $this->addAscendingOrderByColumn($this->getAliasedColName(TextContentPeer::RANK_COL));
                break;
            case Criteria::DESC:
                return $this->addDescendingOrderByColumn($this->getAliasedColName(TextContentPeer::RANK_COL));
                break;
            default:
                throw new PropelException('TextContentQuery::orderBy() only accepts "asc" or "desc" as argument');
        }
    }

    /**
     * Get an item from the list based on its rank
     *
     * @param     integer   $rank rank
     * @param     int $scope		Scope to determine which suite to consider
     * @param     PropelPDO $con optional connection
     *
     * @return    TextContent
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
            $con = Propel::getConnection(TextContentPeer::DATABASE_NAME);
        }
        // shift the objects with a position lower than the one of object
        $this->addSelectColumn('MAX(' . TextContentPeer::RANK_COL . ')');
        $this->add(TextContentPeer::SCOPE_COL, $scope, Criteria::EQUAL);
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
            $con = Propel::getConnection(TextContentPeer::DATABASE_NAME);
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
