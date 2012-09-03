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
use Smirik\CourseBundle\Model\Lesson;
use Smirik\CourseBundle\Model\LessonQuiz;
use Smirik\CourseBundle\Model\LessonQuizPeer;
use Smirik\CourseBundle\Model\LessonQuizQuery;
use Smirik\QuizBundle\Model\Quiz;

/**
 * @method LessonQuizQuery orderById($order = Criteria::ASC) Order by the id column
 * @method LessonQuizQuery orderByLessonId($order = Criteria::ASC) Order by the lesson_id column
 * @method LessonQuizQuery orderByQuizId($order = Criteria::ASC) Order by the quiz_id column
 * @method LessonQuizQuery orderBySortableRank($order = Criteria::ASC) Order by the sortable_rank column
 *
 * @method LessonQuizQuery groupById() Group by the id column
 * @method LessonQuizQuery groupByLessonId() Group by the lesson_id column
 * @method LessonQuizQuery groupByQuizId() Group by the quiz_id column
 * @method LessonQuizQuery groupBySortableRank() Group by the sortable_rank column
 *
 * @method LessonQuizQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method LessonQuizQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method LessonQuizQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method LessonQuizQuery leftJoinLesson($relationAlias = null) Adds a LEFT JOIN clause to the query using the Lesson relation
 * @method LessonQuizQuery rightJoinLesson($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Lesson relation
 * @method LessonQuizQuery innerJoinLesson($relationAlias = null) Adds a INNER JOIN clause to the query using the Lesson relation
 *
 * @method LessonQuizQuery leftJoinQuiz($relationAlias = null) Adds a LEFT JOIN clause to the query using the Quiz relation
 * @method LessonQuizQuery rightJoinQuiz($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Quiz relation
 * @method LessonQuizQuery innerJoinQuiz($relationAlias = null) Adds a INNER JOIN clause to the query using the Quiz relation
 *
 * @method LessonQuiz findOne(PropelPDO $con = null) Return the first LessonQuiz matching the query
 * @method LessonQuiz findOneOrCreate(PropelPDO $con = null) Return the first LessonQuiz matching the query, or a new LessonQuiz object populated from the query conditions when no match is found
 *
 * @method LessonQuiz findOneByLessonId(int $lesson_id) Return the first LessonQuiz filtered by the lesson_id column
 * @method LessonQuiz findOneByQuizId(int $quiz_id) Return the first LessonQuiz filtered by the quiz_id column
 * @method LessonQuiz findOneBySortableRank(int $sortable_rank) Return the first LessonQuiz filtered by the sortable_rank column
 *
 * @method array findById(int $id) Return LessonQuiz objects filtered by the id column
 * @method array findByLessonId(int $lesson_id) Return LessonQuiz objects filtered by the lesson_id column
 * @method array findByQuizId(int $quiz_id) Return LessonQuiz objects filtered by the quiz_id column
 * @method array findBySortableRank(int $sortable_rank) Return LessonQuiz objects filtered by the sortable_rank column
 */
abstract class BaseLessonQuizQuery extends ModelCriteria
{
    /**
     * Initializes internal state of BaseLessonQuizQuery object.
     *
     * @param     string $dbName The dabase name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = 'Smirik\\CourseBundle\\Model\\LessonQuiz', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new LessonQuizQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     LessonQuizQuery|Criteria $criteria Optional Criteria to build the query from
     *
     * @return LessonQuizQuery
     */
    public static function create($modelAlias = null, $criteria = null)
    {
        if ($criteria instanceof LessonQuizQuery) {
            return $criteria;
        }
        $query = new LessonQuizQuery();
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
     * @return   LessonQuiz|LessonQuiz[]|mixed the result, formatted by the current formatter
     */
    public function findPk($key, $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = LessonQuizPeer::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is alredy in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getConnection(LessonQuizPeer::DATABASE_NAME, Propel::CONNECTION_READ);
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
     * @return   LessonQuiz A model object, or null if the key is not found
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
     * @return   LessonQuiz A model object, or null if the key is not found
     * @throws   PropelException
     */
    protected function findPkSimple($key, $con)
    {
        $sql = 'SELECT `ID`, `LESSON_ID`, `QUIZ_ID`, `SORTABLE_RANK` FROM `lessons_quizes` WHERE `ID` = :p0';
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
            $obj = new LessonQuiz();
            $obj->hydrate($row);
            LessonQuizPeer::addInstanceToPool($obj, (string) $key);
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
     * @return LessonQuiz|LessonQuiz[]|mixed the result, formatted by the current formatter
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
     * @return PropelObjectCollection|LessonQuiz[]|mixed the list of results, formatted by the current formatter
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
     * @return LessonQuizQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(LessonQuizPeer::ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return LessonQuizQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(LessonQuizPeer::ID, $keys, Criteria::IN);
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
     * @return LessonQuizQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id) && null === $comparison) {
            $comparison = Criteria::IN;
        }

        return $this->addUsingAlias(LessonQuizPeer::ID, $id, $comparison);
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
     * @return LessonQuizQuery The current query, for fluid interface
     */
    public function filterByLessonId($lessonId = null, $comparison = null)
    {
        if (is_array($lessonId)) {
            $useMinMax = false;
            if (isset($lessonId['min'])) {
                $this->addUsingAlias(LessonQuizPeer::LESSON_ID, $lessonId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($lessonId['max'])) {
                $this->addUsingAlias(LessonQuizPeer::LESSON_ID, $lessonId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(LessonQuizPeer::LESSON_ID, $lessonId, $comparison);
    }

    /**
     * Filter the query on the quiz_id column
     *
     * Example usage:
     * <code>
     * $query->filterByQuizId(1234); // WHERE quiz_id = 1234
     * $query->filterByQuizId(array(12, 34)); // WHERE quiz_id IN (12, 34)
     * $query->filterByQuizId(array('min' => 12)); // WHERE quiz_id > 12
     * </code>
     *
     * @see       filterByQuiz()
     *
     * @param     mixed $quizId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return LessonQuizQuery The current query, for fluid interface
     */
    public function filterByQuizId($quizId = null, $comparison = null)
    {
        if (is_array($quizId)) {
            $useMinMax = false;
            if (isset($quizId['min'])) {
                $this->addUsingAlias(LessonQuizPeer::QUIZ_ID, $quizId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($quizId['max'])) {
                $this->addUsingAlias(LessonQuizPeer::QUIZ_ID, $quizId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(LessonQuizPeer::QUIZ_ID, $quizId, $comparison);
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
     * @return LessonQuizQuery The current query, for fluid interface
     */
    public function filterBySortableRank($sortableRank = null, $comparison = null)
    {
        if (is_array($sortableRank)) {
            $useMinMax = false;
            if (isset($sortableRank['min'])) {
                $this->addUsingAlias(LessonQuizPeer::SORTABLE_RANK, $sortableRank['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($sortableRank['max'])) {
                $this->addUsingAlias(LessonQuizPeer::SORTABLE_RANK, $sortableRank['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(LessonQuizPeer::SORTABLE_RANK, $sortableRank, $comparison);
    }

    /**
     * Filter the query by a related Lesson object
     *
     * @param   Lesson|PropelObjectCollection $lesson The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return   LessonQuizQuery The current query, for fluid interface
     * @throws   PropelException - if the provided filter is invalid.
     */
    public function filterByLesson($lesson, $comparison = null)
    {
        if ($lesson instanceof Lesson) {
            return $this
                ->addUsingAlias(LessonQuizPeer::LESSON_ID, $lesson->getId(), $comparison);
        } elseif ($lesson instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(LessonQuizPeer::LESSON_ID, $lesson->toKeyValue('PrimaryKey', 'Id'), $comparison);
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
     * @return LessonQuizQuery The current query, for fluid interface
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
     * Filter the query by a related Quiz object
     *
     * @param   Quiz|PropelObjectCollection $quiz The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return   LessonQuizQuery The current query, for fluid interface
     * @throws   PropelException - if the provided filter is invalid.
     */
    public function filterByQuiz($quiz, $comparison = null)
    {
        if ($quiz instanceof Quiz) {
            return $this
                ->addUsingAlias(LessonQuizPeer::QUIZ_ID, $quiz->getId(), $comparison);
        } elseif ($quiz instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(LessonQuizPeer::QUIZ_ID, $quiz->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByQuiz() only accepts arguments of type Quiz or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Quiz relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return LessonQuizQuery The current query, for fluid interface
     */
    public function joinQuiz($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Quiz');

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
            $this->addJoinObject($join, 'Quiz');
        }

        return $this;
    }

    /**
     * Use the Quiz relation Quiz object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \Smirik\QuizBundle\Model\QuizQuery A secondary query class using the current class as primary query
     */
    public function useQuizQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinQuiz($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Quiz', '\Smirik\QuizBundle\Model\QuizQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   LessonQuiz $lessonQuiz Object to remove from the list of results
     *
     * @return LessonQuizQuery The current query, for fluid interface
     */
    public function prune($lessonQuiz = null)
    {
        if ($lessonQuiz) {
            $this->addUsingAlias(LessonQuizPeer::ID, $lessonQuiz->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    // sortable behavior

    /**
     * Returns the objects in a certain list, from the list scope
     *
     * @param     int $scope		Scope to determine which objects node to return
     *
     * @return    LessonQuizQuery The current query, for fluid interface
     */
    public function inList($scope = null)
    {
        return $this->addUsingAlias(LessonQuizPeer::SCOPE_COL, $scope, Criteria::EQUAL);
    }

    /**
     * Filter the query based on a rank in the list
     *
     * @param     integer   $rank rank
     * @param     int $scope		Scope to determine which suite to consider
     *
     * @return    LessonQuizQuery The current query, for fluid interface
     */
    public function filterByRank($rank, $scope = null)
    {
        return $this
            ->inList($scope)
            ->addUsingAlias(LessonQuizPeer::RANK_COL, $rank, Criteria::EQUAL);
    }

    /**
     * Order the query based on the rank in the list.
     * Using the default $order, returns the item with the lowest rank first
     *
     * @param     string $order either Criteria::ASC (default) or Criteria::DESC
     *
     * @return    LessonQuizQuery The current query, for fluid interface
     */
    public function orderByRank($order = Criteria::ASC)
    {
        $order = strtoupper($order);
        switch ($order) {
            case Criteria::ASC:
                return $this->addAscendingOrderByColumn($this->getAliasedColName(LessonQuizPeer::RANK_COL));
                break;
            case Criteria::DESC:
                return $this->addDescendingOrderByColumn($this->getAliasedColName(LessonQuizPeer::RANK_COL));
                break;
            default:
                throw new PropelException('LessonQuizQuery::orderBy() only accepts "asc" or "desc" as argument');
        }
    }

    /**
     * Get an item from the list based on its rank
     *
     * @param     integer   $rank rank
     * @param     int $scope		Scope to determine which suite to consider
     * @param     PropelPDO $con optional connection
     *
     * @return    LessonQuiz
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
            $con = Propel::getConnection(LessonQuizPeer::DATABASE_NAME);
        }
        // shift the objects with a position lower than the one of object
        $this->addSelectColumn('MAX(' . LessonQuizPeer::RANK_COL . ')');
        $this->add(LessonQuizPeer::SCOPE_COL, $scope, Criteria::EQUAL);
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
            $con = Propel::getConnection(LessonQuizPeer::DATABASE_NAME);
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
