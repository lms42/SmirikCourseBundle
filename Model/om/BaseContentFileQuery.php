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
use Smirik\CourseBundle\Model\ContentFile;
use Smirik\CourseBundle\Model\ContentFilePeer;
use Smirik\CourseBundle\Model\ContentFileQuery;

/**
 * @method ContentFileQuery orderById($order = Criteria::ASC) Order by the id column
 * @method ContentFileQuery orderByLessonContentId($order = Criteria::ASC) Order by the lesson_content_id column
 * @method ContentFileQuery orderByFile($order = Criteria::ASC) Order by the file column
 * @method ContentFileQuery orderByType($order = Criteria::ASC) Order by the type column
 * @method ContentFileQuery orderByTitle($order = Criteria::ASC) Order by the title column
 * @method ContentFileQuery orderByCaption($order = Criteria::ASC) Order by the caption column
 * @method ContentFileQuery orderByCreatedAt($order = Criteria::ASC) Order by the created_at column
 * @method ContentFileQuery orderByUpdatedAt($order = Criteria::ASC) Order by the updated_at column
 *
 * @method ContentFileQuery groupById() Group by the id column
 * @method ContentFileQuery groupByLessonContentId() Group by the lesson_content_id column
 * @method ContentFileQuery groupByFile() Group by the file column
 * @method ContentFileQuery groupByType() Group by the type column
 * @method ContentFileQuery groupByTitle() Group by the title column
 * @method ContentFileQuery groupByCaption() Group by the caption column
 * @method ContentFileQuery groupByCreatedAt() Group by the created_at column
 * @method ContentFileQuery groupByUpdatedAt() Group by the updated_at column
 *
 * @method ContentFileQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method ContentFileQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method ContentFileQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method ContentFileQuery leftJoinContent($relationAlias = null) Adds a LEFT JOIN clause to the query using the Content relation
 * @method ContentFileQuery rightJoinContent($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Content relation
 * @method ContentFileQuery innerJoinContent($relationAlias = null) Adds a INNER JOIN clause to the query using the Content relation
 *
 * @method ContentFile findOne(PropelPDO $con = null) Return the first ContentFile matching the query
 * @method ContentFile findOneOrCreate(PropelPDO $con = null) Return the first ContentFile matching the query, or a new ContentFile object populated from the query conditions when no match is found
 *
 * @method ContentFile findOneByLessonContentId(int $lesson_content_id) Return the first ContentFile filtered by the lesson_content_id column
 * @method ContentFile findOneByFile(string $file) Return the first ContentFile filtered by the file column
 * @method ContentFile findOneByType(string $type) Return the first ContentFile filtered by the type column
 * @method ContentFile findOneByTitle(string $title) Return the first ContentFile filtered by the title column
 * @method ContentFile findOneByCaption(string $caption) Return the first ContentFile filtered by the caption column
 * @method ContentFile findOneByCreatedAt(string $created_at) Return the first ContentFile filtered by the created_at column
 * @method ContentFile findOneByUpdatedAt(string $updated_at) Return the first ContentFile filtered by the updated_at column
 *
 * @method array findById(int $id) Return ContentFile objects filtered by the id column
 * @method array findByLessonContentId(int $lesson_content_id) Return ContentFile objects filtered by the lesson_content_id column
 * @method array findByFile(string $file) Return ContentFile objects filtered by the file column
 * @method array findByType(string $type) Return ContentFile objects filtered by the type column
 * @method array findByTitle(string $title) Return ContentFile objects filtered by the title column
 * @method array findByCaption(string $caption) Return ContentFile objects filtered by the caption column
 * @method array findByCreatedAt(string $created_at) Return ContentFile objects filtered by the created_at column
 * @method array findByUpdatedAt(string $updated_at) Return ContentFile objects filtered by the updated_at column
 */
abstract class BaseContentFileQuery extends ModelCriteria
{
    /**
     * Initializes internal state of BaseContentFileQuery object.
     *
     * @param     string $dbName The dabase name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = 'Smirik\\CourseBundle\\Model\\ContentFile', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ContentFileQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     ContentFileQuery|Criteria $criteria Optional Criteria to build the query from
     *
     * @return ContentFileQuery
     */
    public static function create($modelAlias = null, $criteria = null)
    {
        if ($criteria instanceof ContentFileQuery) {
            return $criteria;
        }
        $query = new ContentFileQuery();
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
     * @return   ContentFile|ContentFile[]|mixed the result, formatted by the current formatter
     */
    public function findPk($key, $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = ContentFilePeer::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is alredy in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getConnection(ContentFilePeer::DATABASE_NAME, Propel::CONNECTION_READ);
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
     * @return   ContentFile A model object, or null if the key is not found
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
     * @return   ContentFile A model object, or null if the key is not found
     * @throws   PropelException
     */
    protected function findPkSimple($key, $con)
    {
        $sql = 'SELECT `ID`, `LESSON_CONTENT_ID`, `FILE`, `TYPE`, `TITLE`, `CAPTION`, `CREATED_AT`, `UPDATED_AT` FROM `lessons_content_files` WHERE `ID` = :p0';
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
            $obj = new ContentFile();
            $obj->hydrate($row);
            ContentFilePeer::addInstanceToPool($obj, (string) $key);
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
     * @return ContentFile|ContentFile[]|mixed the result, formatted by the current formatter
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
     * @return PropelObjectCollection|ContentFile[]|mixed the list of results, formatted by the current formatter
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
     * @return ContentFileQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(ContentFilePeer::ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return ContentFileQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(ContentFilePeer::ID, $keys, Criteria::IN);
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
     * @return ContentFileQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id) && null === $comparison) {
            $comparison = Criteria::IN;
        }

        return $this->addUsingAlias(ContentFilePeer::ID, $id, $comparison);
    }

    /**
     * Filter the query on the lesson_content_id column
     *
     * Example usage:
     * <code>
     * $query->filterByLessonContentId(1234); // WHERE lesson_content_id = 1234
     * $query->filterByLessonContentId(array(12, 34)); // WHERE lesson_content_id IN (12, 34)
     * $query->filterByLessonContentId(array('min' => 12)); // WHERE lesson_content_id > 12
     * </code>
     *
     * @see       filterByContent()
     *
     * @param     mixed $lessonContentId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ContentFileQuery The current query, for fluid interface
     */
    public function filterByLessonContentId($lessonContentId = null, $comparison = null)
    {
        if (is_array($lessonContentId)) {
            $useMinMax = false;
            if (isset($lessonContentId['min'])) {
                $this->addUsingAlias(ContentFilePeer::LESSON_CONTENT_ID, $lessonContentId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($lessonContentId['max'])) {
                $this->addUsingAlias(ContentFilePeer::LESSON_CONTENT_ID, $lessonContentId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ContentFilePeer::LESSON_CONTENT_ID, $lessonContentId, $comparison);
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
     * @return ContentFileQuery The current query, for fluid interface
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

        return $this->addUsingAlias(ContentFilePeer::FILE, $file, $comparison);
    }

    /**
     * Filter the query on the type column
     *
     * Example usage:
     * <code>
     * $query->filterByType('fooValue');   // WHERE type = 'fooValue'
     * $query->filterByType('%fooValue%'); // WHERE type LIKE '%fooValue%'
     * </code>
     *
     * @param     string $type The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ContentFileQuery The current query, for fluid interface
     */
    public function filterByType($type = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($type)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $type)) {
                $type = str_replace('*', '%', $type);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(ContentFilePeer::TYPE, $type, $comparison);
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
     * @return ContentFileQuery The current query, for fluid interface
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

        return $this->addUsingAlias(ContentFilePeer::TITLE, $title, $comparison);
    }

    /**
     * Filter the query on the caption column
     *
     * Example usage:
     * <code>
     * $query->filterByCaption('fooValue');   // WHERE caption = 'fooValue'
     * $query->filterByCaption('%fooValue%'); // WHERE caption LIKE '%fooValue%'
     * </code>
     *
     * @param     string $caption The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ContentFileQuery The current query, for fluid interface
     */
    public function filterByCaption($caption = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($caption)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $caption)) {
                $caption = str_replace('*', '%', $caption);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(ContentFilePeer::CAPTION, $caption, $comparison);
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
     * @return ContentFileQuery The current query, for fluid interface
     */
    public function filterByCreatedAt($createdAt = null, $comparison = null)
    {
        if (is_array($createdAt)) {
            $useMinMax = false;
            if (isset($createdAt['min'])) {
                $this->addUsingAlias(ContentFilePeer::CREATED_AT, $createdAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($createdAt['max'])) {
                $this->addUsingAlias(ContentFilePeer::CREATED_AT, $createdAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ContentFilePeer::CREATED_AT, $createdAt, $comparison);
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
     * @return ContentFileQuery The current query, for fluid interface
     */
    public function filterByUpdatedAt($updatedAt = null, $comparison = null)
    {
        if (is_array($updatedAt)) {
            $useMinMax = false;
            if (isset($updatedAt['min'])) {
                $this->addUsingAlias(ContentFilePeer::UPDATED_AT, $updatedAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($updatedAt['max'])) {
                $this->addUsingAlias(ContentFilePeer::UPDATED_AT, $updatedAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ContentFilePeer::UPDATED_AT, $updatedAt, $comparison);
    }

    /**
     * Filter the query by a related Content object
     *
     * @param   Content|PropelObjectCollection $content The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return   ContentFileQuery The current query, for fluid interface
     * @throws   PropelException - if the provided filter is invalid.
     */
    public function filterByContent($content, $comparison = null)
    {
        if ($content instanceof Content) {
            return $this
                ->addUsingAlias(ContentFilePeer::LESSON_CONTENT_ID, $content->getId(), $comparison);
        } elseif ($content instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(ContentFilePeer::LESSON_CONTENT_ID, $content->toKeyValue('PrimaryKey', 'Id'), $comparison);
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
     * @return ContentFileQuery The current query, for fluid interface
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
     * Exclude object from result
     *
     * @param   ContentFile $contentFile Object to remove from the list of results
     *
     * @return ContentFileQuery The current query, for fluid interface
     */
    public function prune($contentFile = null)
    {
        if ($contentFile) {
            $this->addUsingAlias(ContentFilePeer::ID, $contentFile->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    // timestampable behavior

    /**
     * Filter by the latest updated
     *
     * @param      int $nbDays Maximum age of the latest update in days
     *
     * @return     ContentFileQuery The current query, for fluid interface
     */
    public function recentlyUpdated($nbDays = 7)
    {
        return $this->addUsingAlias(ContentFilePeer::UPDATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by update date desc
     *
     * @return     ContentFileQuery The current query, for fluid interface
     */
    public function lastUpdatedFirst()
    {
        return $this->addDescendingOrderByColumn(ContentFilePeer::UPDATED_AT);
    }

    /**
     * Order by update date asc
     *
     * @return     ContentFileQuery The current query, for fluid interface
     */
    public function firstUpdatedFirst()
    {
        return $this->addAscendingOrderByColumn(ContentFilePeer::UPDATED_AT);
    }

    /**
     * Filter by the latest created
     *
     * @param      int $nbDays Maximum age of in days
     *
     * @return     ContentFileQuery The current query, for fluid interface
     */
    public function recentlyCreated($nbDays = 7)
    {
        return $this->addUsingAlias(ContentFilePeer::CREATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by create date desc
     *
     * @return     ContentFileQuery The current query, for fluid interface
     */
    public function lastCreatedFirst()
    {
        return $this->addDescendingOrderByColumn(ContentFilePeer::CREATED_AT);
    }

    /**
     * Order by create date asc
     *
     * @return     ContentFileQuery The current query, for fluid interface
     */
    public function firstCreatedFirst()
    {
        return $this->addAscendingOrderByColumn(ContentFilePeer::CREATED_AT);
    }
}
