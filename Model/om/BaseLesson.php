<?php

namespace Smirik\CourseBundle\Model\om;

use \BaseObject;
use \BasePeer;
use \Criteria;
use \DateTime;
use \Exception;
use \PDO;
use \Persistent;
use \Propel;
use \PropelCollection;
use \PropelDateTime;
use \PropelException;
use \PropelObjectCollection;
use \PropelPDO;
use Smirik\CourseBundle\Model\Content;
use Smirik\CourseBundle\Model\ContentQuery;
use Smirik\CourseBundle\Model\Course;
use Smirik\CourseBundle\Model\CourseQuery;
use Smirik\CourseBundle\Model\Lesson;
use Smirik\CourseBundle\Model\LessonAnswer;
use Smirik\CourseBundle\Model\LessonAnswerQuery;
use Smirik\CourseBundle\Model\LessonPeer;
use Smirik\CourseBundle\Model\LessonQuery;
use Smirik\CourseBundle\Model\LessonQuestion;
use Smirik\CourseBundle\Model\LessonQuestionQuery;
use Smirik\CourseBundle\Model\LessonQuiz;
use Smirik\CourseBundle\Model\LessonQuizQuery;
use Smirik\CourseBundle\Model\SlideshareContent;
use Smirik\CourseBundle\Model\SlideshareContentQuery;
use Smirik\CourseBundle\Model\Task;
use Smirik\CourseBundle\Model\TaskQuery;
use Smirik\CourseBundle\Model\TextContent;
use Smirik\CourseBundle\Model\TextContentQuery;
use Smirik\CourseBundle\Model\UrlContent;
use Smirik\CourseBundle\Model\UrlContentQuery;
use Smirik\CourseBundle\Model\UserLesson;
use Smirik\CourseBundle\Model\UserLessonQuery;
use Smirik\CourseBundle\Model\UserTask;
use Smirik\CourseBundle\Model\UserTaskQuery;
use Smirik\CourseBundle\Model\YoutubeContent;
use Smirik\CourseBundle\Model\YoutubeContentQuery;

abstract class BaseLesson extends BaseObject implements Persistent
{
    /**
     * Peer class name
     */
    const PEER = 'Smirik\\CourseBundle\\Model\\LessonPeer';

    /**
     * The Peer class.
     * Instance provides a convenient way of calling static methods on a class
     * that calling code may not be able to identify.
     * @var        LessonPeer
     */
    protected static $peer;

    /**
     * The flag var to prevent infinit loop in deep copy
     * @var       boolean
     */
    protected $startCopy = false;

    /**
     * The value for the id field.
     * @var        int
     */
    protected $id;

    /**
     * The value for the course_id field.
     * @var        int
     */
    protected $course_id;

    /**
     * The value for the title field.
     * @var        string
     */
    protected $title;

    /**
     * The value for the created_at field.
     * @var        string
     */
    protected $created_at;

    /**
     * The value for the updated_at field.
     * @var        string
     */
    protected $updated_at;

    /**
     * The value for the sortable_rank field.
     * @var        int
     */
    protected $sortable_rank;

    /**
     * @var        Course
     */
    protected $aCourse;

    /**
     * @var        PropelObjectCollection|UserLesson[] Collection to store aggregation of UserLesson objects.
     */
    protected $collUserLessons;
    protected $collUserLessonsPartial;

    /**
     * @var        PropelObjectCollection|LessonQuiz[] Collection to store aggregation of LessonQuiz objects.
     */
    protected $collLessonquizzes;
    protected $collLessonquizzesPartial;

    /**
     * @var        PropelObjectCollection|LessonQuestion[] Collection to store aggregation of LessonQuestion objects.
     */
    protected $collLessonQuestions;
    protected $collLessonQuestionsPartial;

    /**
     * @var        PropelObjectCollection|LessonAnswer[] Collection to store aggregation of LessonAnswer objects.
     */
    protected $collLessonAnswers;
    protected $collLessonAnswersPartial;

    /**
     * @var        PropelObjectCollection|Content[] Collection to store aggregation of Content objects.
     */
    protected $collContents;
    protected $collContentsPartial;

    /**
     * @var        PropelObjectCollection|Task[] Collection to store aggregation of Task objects.
     */
    protected $collTasks;
    protected $collTasksPartial;

    /**
     * @var        PropelObjectCollection|UserTask[] Collection to store aggregation of UserTask objects.
     */
    protected $collUserTasks;
    protected $collUserTasksPartial;

    /**
     * @var        PropelObjectCollection|TextContent[] Collection to store aggregation of TextContent objects.
     */
    protected $collTextContents;
    protected $collTextContentsPartial;

    /**
     * @var        PropelObjectCollection|UrlContent[] Collection to store aggregation of UrlContent objects.
     */
    protected $collUrlContents;
    protected $collUrlContentsPartial;

    /**
     * @var        PropelObjectCollection|YoutubeContent[] Collection to store aggregation of YoutubeContent objects.
     */
    protected $collYoutubeContents;
    protected $collYoutubeContentsPartial;

    /**
     * @var        PropelObjectCollection|SlideshareContent[] Collection to store aggregation of SlideshareContent objects.
     */
    protected $collSlideshareContents;
    protected $collSlideshareContentsPartial;

    /**
     * Flag to prevent endless save loop, if this object is referenced
     * by another object which falls in this transaction.
     * @var        boolean
     */
    protected $alreadyInSave = false;

    /**
     * Flag to prevent endless validation loop, if this object is referenced
     * by another object which falls in this transaction.
     * @var        boolean
     */
    protected $alreadyInValidation = false;

    // sortable behavior

    /**
     * Queries to be executed in the save transaction
     * @var        array
     */
    protected $sortableQueries = array();

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $userLessonsScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $lessonquizzesScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $lessonQuestionsScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $lessonAnswersScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $contentsScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $tasksScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $userTasksScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $textContentsScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $urlContentsScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $youtubeContentsScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $slideshareContentsScheduledForDeletion = null;

    /**
     * Get the [id] column value.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get the [course_id] column value.
     *
     * @return int
     */
    public function getCourseId()
    {
        return $this->course_id;
    }

    /**
     * Get the [title] column value.
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Get the [optionally formatted] temporal [created_at] column value.
     *
     *
     * @param string $format The date/time format string (either date()-style or strftime()-style).
     *				 If format is null, then the raw DateTime object will be returned.
     * @return mixed Formatted date/time value as string or DateTime object (if format is null), null if column is null, and 0 if column value is 0000-00-00 00:00:00
     * @throws PropelException - if unable to parse/validate the date/time value.
     */
    public function getCreatedAt($format = null)
    {
        if ($this->created_at === null) {
            return null;
        }

        if ($this->created_at === '0000-00-00 00:00:00') {
            // while technically this is not a default value of null,
            // this seems to be closest in meaning.
            return null;
        } else {
            try {
                $dt = new DateTime($this->created_at);
            } catch (Exception $x) {
                throw new PropelException("Internally stored date/time/timestamp value could not be converted to DateTime: " . var_export($this->created_at, true), $x);
            }
        }

        if ($format === null) {
            // Because propel.useDateTimeClass is true, we return a DateTime object.
            return $dt;
        } elseif (strpos($format, '%') !== false) {
            return strftime($format, $dt->format('U'));
        } else {
            return $dt->format($format);
        }
    }

    /**
     * Get the [optionally formatted] temporal [updated_at] column value.
     *
     *
     * @param string $format The date/time format string (either date()-style or strftime()-style).
     *				 If format is null, then the raw DateTime object will be returned.
     * @return mixed Formatted date/time value as string or DateTime object (if format is null), null if column is null, and 0 if column value is 0000-00-00 00:00:00
     * @throws PropelException - if unable to parse/validate the date/time value.
     */
    public function getUpdatedAt($format = null)
    {
        if ($this->updated_at === null) {
            return null;
        }

        if ($this->updated_at === '0000-00-00 00:00:00') {
            // while technically this is not a default value of null,
            // this seems to be closest in meaning.
            return null;
        } else {
            try {
                $dt = new DateTime($this->updated_at);
            } catch (Exception $x) {
                throw new PropelException("Internally stored date/time/timestamp value could not be converted to DateTime: " . var_export($this->updated_at, true), $x);
            }
        }

        if ($format === null) {
            // Because propel.useDateTimeClass is true, we return a DateTime object.
            return $dt;
        } elseif (strpos($format, '%') !== false) {
            return strftime($format, $dt->format('U'));
        } else {
            return $dt->format($format);
        }
    }

    /**
     * Get the [sortable_rank] column value.
     *
     * @return int
     */
    public function getSortableRank()
    {
        return $this->sortable_rank;
    }

    /**
     * Set the value of [id] column.
     *
     * @param int $v new value
     * @return Lesson The current object (for fluent API support)
     */
    public function setId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->id !== $v) {
            $this->id = $v;
            $this->modifiedColumns[] = LessonPeer::ID;
        }


        return $this;
    } // setId()

    /**
     * Set the value of [course_id] column.
     *
     * @param int $v new value
     * @return Lesson The current object (for fluent API support)
     */
    public function setCourseId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->course_id !== $v) {
            $this->course_id = $v;
            $this->modifiedColumns[] = LessonPeer::COURSE_ID;
        }

        if ($this->aCourse !== null && $this->aCourse->getId() !== $v) {
            $this->aCourse = null;
        }


        return $this;
    } // setCourseId()

    /**
     * Set the value of [title] column.
     *
     * @param string $v new value
     * @return Lesson The current object (for fluent API support)
     */
    public function setTitle($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->title !== $v) {
            $this->title = $v;
            $this->modifiedColumns[] = LessonPeer::TITLE;
        }


        return $this;
    } // setTitle()

    /**
     * Sets the value of [created_at] column to a normalized version of the date/time value specified.
     *
     * @param mixed $v string, integer (timestamp), or DateTime value.
     *               Empty strings are treated as null.
     * @return Lesson The current object (for fluent API support)
     */
    public function setCreatedAt($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->created_at !== null || $dt !== null) {
            $currentDateAsString = ($this->created_at !== null && $tmpDt = new DateTime($this->created_at)) ? $tmpDt->format('Y-m-d H:i:s') : null;
            $newDateAsString = $dt ? $dt->format('Y-m-d H:i:s') : null;
            if ($currentDateAsString !== $newDateAsString) {
                $this->created_at = $newDateAsString;
                $this->modifiedColumns[] = LessonPeer::CREATED_AT;
            }
        } // if either are not null


        return $this;
    } // setCreatedAt()

    /**
     * Sets the value of [updated_at] column to a normalized version of the date/time value specified.
     *
     * @param mixed $v string, integer (timestamp), or DateTime value.
     *               Empty strings are treated as null.
     * @return Lesson The current object (for fluent API support)
     */
    public function setUpdatedAt($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->updated_at !== null || $dt !== null) {
            $currentDateAsString = ($this->updated_at !== null && $tmpDt = new DateTime($this->updated_at)) ? $tmpDt->format('Y-m-d H:i:s') : null;
            $newDateAsString = $dt ? $dt->format('Y-m-d H:i:s') : null;
            if ($currentDateAsString !== $newDateAsString) {
                $this->updated_at = $newDateAsString;
                $this->modifiedColumns[] = LessonPeer::UPDATED_AT;
            }
        } // if either are not null


        return $this;
    } // setUpdatedAt()

    /**
     * Set the value of [sortable_rank] column.
     *
     * @param int $v new value
     * @return Lesson The current object (for fluent API support)
     */
    public function setSortableRank($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->sortable_rank !== $v) {
            $this->sortable_rank = $v;
            $this->modifiedColumns[] = LessonPeer::SORTABLE_RANK;
        }


        return $this;
    } // setSortableRank()

    /**
     * Indicates whether the columns in this object are only set to default values.
     *
     * This method can be used in conjunction with isModified() to indicate whether an object is both
     * modified _and_ has some values set which are non-default.
     *
     * @return boolean Whether the columns in this object are only been set with default values.
     */
    public function hasOnlyDefaultValues()
    {
        // otherwise, everything was equal, so return true
        return true;
    } // hasOnlyDefaultValues()

    /**
     * Hydrates (populates) the object variables with values from the database resultset.
     *
     * An offset (0-based "start column") is specified so that objects can be hydrated
     * with a subset of the columns in the resultset rows.  This is needed, for example,
     * for results of JOIN queries where the resultset row includes columns from two or
     * more tables.
     *
     * @param array $row The row returned by PDOStatement->fetch(PDO::FETCH_NUM)
     * @param int $startcol 0-based offset column which indicates which restultset column to start with.
     * @param boolean $rehydrate Whether this object is being re-hydrated from the database.
     * @return int             next starting column
     * @throws PropelException - Any caught Exception will be rewrapped as a PropelException.
     */
    public function hydrate($row, $startcol = 0, $rehydrate = false)
    {
        try {

            $this->id = ($row[$startcol + 0] !== null) ? (int) $row[$startcol + 0] : null;
            $this->course_id = ($row[$startcol + 1] !== null) ? (int) $row[$startcol + 1] : null;
            $this->title = ($row[$startcol + 2] !== null) ? (string) $row[$startcol + 2] : null;
            $this->created_at = ($row[$startcol + 3] !== null) ? (string) $row[$startcol + 3] : null;
            $this->updated_at = ($row[$startcol + 4] !== null) ? (string) $row[$startcol + 4] : null;
            $this->sortable_rank = ($row[$startcol + 5] !== null) ? (int) $row[$startcol + 5] : null;
            $this->resetModified();

            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }

            return $startcol + 6; // 6 = LessonPeer::NUM_HYDRATE_COLUMNS.

        } catch (Exception $e) {
            throw new PropelException("Error populating Lesson object", $e);
        }
    }

    /**
     * Checks and repairs the internal consistency of the object.
     *
     * This method is executed after an already-instantiated object is re-hydrated
     * from the database.  It exists to check any foreign keys to make sure that
     * the objects related to the current object are correct based on foreign key.
     *
     * You can override this method in the stub class, but you should always invoke
     * the base method from the overridden method (i.e. parent::ensureConsistency()),
     * in case your model changes.
     *
     * @throws PropelException
     */
    public function ensureConsistency()
    {

        if ($this->aCourse !== null && $this->course_id !== $this->aCourse->getId()) {
            $this->aCourse = null;
        }
    } // ensureConsistency

    /**
     * Reloads this object from datastore based on primary key and (optionally) resets all associated objects.
     *
     * This will only work if the object has been saved and has a valid primary key set.
     *
     * @param boolean $deep (optional) Whether to also de-associated any related objects.
     * @param PropelPDO $con (optional) The PropelPDO connection to use.
     * @return void
     * @throws PropelException - if this object is deleted, unsaved or doesn't have pk match in db
     */
    public function reload($deep = false, PropelPDO $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("Cannot reload a deleted object.");
        }

        if ($this->isNew()) {
            throw new PropelException("Cannot reload an unsaved object.");
        }

        if ($con === null) {
            $con = Propel::getConnection(LessonPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        // We don't need to alter the object instance pool; we're just modifying this instance
        // already in the pool.

        $stmt = LessonPeer::doSelectStmt($this->buildPkeyCriteria(), $con);
        $row = $stmt->fetch(PDO::FETCH_NUM);
        $stmt->closeCursor();
        if (!$row) {
            throw new PropelException('Cannot find matching row in the database to reload object values.');
        }
        $this->hydrate($row, 0, true); // rehydrate

        if ($deep) {  // also de-associate any related objects?

            $this->aCourse = null;
            $this->collUserLessons = null;

            $this->collLessonquizzes = null;

            $this->collLessonQuestions = null;

            $this->collLessonAnswers = null;

            $this->collContents = null;

            $this->collTasks = null;

            $this->collUserTasks = null;

            $this->collTextContents = null;

            $this->collUrlContents = null;

            $this->collYoutubeContents = null;

            $this->collSlideshareContents = null;

        } // if (deep)
    }

    /**
     * Removes this object from datastore and sets delete attribute.
     *
     * @param PropelPDO $con
     * @return void
     * @throws PropelException
     * @throws Exception
     * @see        BaseObject::setDeleted()
     * @see        BaseObject::isDeleted()
     */
    public function delete(PropelPDO $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("This object has already been deleted.");
        }

        if ($con === null) {
            $con = Propel::getConnection(LessonPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        $con->beginTransaction();
        try {
            $deleteQuery = LessonQuery::create()
                ->filterByPrimaryKey($this->getPrimaryKey());
            $ret = $this->preDelete($con);
            // sortable behavior

            LessonPeer::shiftRank(-1, $this->getSortableRank() + 1, null, $this->getCourseId(), $con);
            LessonPeer::clearInstancePool();

            if ($ret) {
                $deleteQuery->delete($con);
                $this->postDelete($con);
                $con->commit();
                $this->setDeleted(true);
            } else {
                $con->commit();
            }
        } catch (Exception $e) {
            $con->rollBack();
            throw $e;
        }
    }

    /**
     * Persists this object to the database.
     *
     * If the object is new, it inserts it; otherwise an update is performed.
     * All modified related objects will also be persisted in the doSave()
     * method.  This method wraps all precipitate database operations in a
     * single transaction.
     *
     * @param PropelPDO $con
     * @return int             The number of rows affected by this insert/update and any referring fk objects' save() operations.
     * @throws PropelException
     * @throws Exception
     * @see        doSave()
     */
    public function save(PropelPDO $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("You cannot save an object that has been deleted.");
        }

        if ($con === null) {
            $con = Propel::getConnection(LessonPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        $con->beginTransaction();
        $isInsert = $this->isNew();
        try {
            $ret = $this->preSave($con);
            // sortable behavior
            $this->processSortableQueries($con);
            if ($isInsert) {
                $ret = $ret && $this->preInsert($con);
                // timestampable behavior
                if (!$this->isColumnModified(LessonPeer::CREATED_AT)) {
                    $this->setCreatedAt(time());
                }
                if (!$this->isColumnModified(LessonPeer::UPDATED_AT)) {
                    $this->setUpdatedAt(time());
                }
                // sortable behavior
                if (!$this->isColumnModified(LessonPeer::RANK_COL)) {
                    $this->setSortableRank(LessonQuery::create()->getMaxRank($this->getCourseId(), $con) + 1);
                }

            } else {
                $ret = $ret && $this->preUpdate($con);
                // timestampable behavior
                if ($this->isModified() && !$this->isColumnModified(LessonPeer::UPDATED_AT)) {
                    $this->setUpdatedAt(time());
                }
            }
            if ($ret) {
                $affectedRows = $this->doSave($con);
                if ($isInsert) {
                    $this->postInsert($con);
                } else {
                    $this->postUpdate($con);
                }
                $this->postSave($con);
                LessonPeer::addInstanceToPool($this);
            } else {
                $affectedRows = 0;
            }
            $con->commit();

            return $affectedRows;
        } catch (Exception $e) {
            $con->rollBack();
            throw $e;
        }
    }

    /**
     * Performs the work of inserting or updating the row in the database.
     *
     * If the object is new, it inserts it; otherwise an update is performed.
     * All related objects are also updated in this method.
     *
     * @param PropelPDO $con
     * @return int             The number of rows affected by this insert/update and any referring fk objects' save() operations.
     * @throws PropelException
     * @see        save()
     */
    protected function doSave(PropelPDO $con)
    {
        $affectedRows = 0; // initialize var to track total num of affected rows
        if (!$this->alreadyInSave) {
            $this->alreadyInSave = true;

            // We call the save method on the following object(s) if they
            // were passed to this object by their coresponding set
            // method.  This object relates to these object(s) by a
            // foreign key reference.

            if ($this->aCourse !== null) {
                if ($this->aCourse->isModified() || $this->aCourse->isNew()) {
                    $affectedRows += $this->aCourse->save($con);
                }
                $this->setCourse($this->aCourse);
            }

            if ($this->isNew() || $this->isModified()) {
                // persist changes
                if ($this->isNew()) {
                    $this->doInsert($con);
                } else {
                    $this->doUpdate($con);
                }
                $affectedRows += 1;
                $this->resetModified();
            }

            if ($this->userLessonsScheduledForDeletion !== null) {
                if (!$this->userLessonsScheduledForDeletion->isEmpty()) {
                    UserLessonQuery::create()
                        ->filterByPrimaryKeys($this->userLessonsScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->userLessonsScheduledForDeletion = null;
                }
            }

            if ($this->collUserLessons !== null) {
                foreach ($this->collUserLessons as $referrerFK) {
                    if (!$referrerFK->isDeleted()) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->lessonquizzesScheduledForDeletion !== null) {
                if (!$this->lessonquizzesScheduledForDeletion->isEmpty()) {
                    LessonQuizQuery::create()
                        ->filterByPrimaryKeys($this->lessonquizzesScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->lessonquizzesScheduledForDeletion = null;
                }
            }

            if ($this->collLessonquizzes !== null) {
                foreach ($this->collLessonquizzes as $referrerFK) {
                    if (!$referrerFK->isDeleted()) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->lessonQuestionsScheduledForDeletion !== null) {
                if (!$this->lessonQuestionsScheduledForDeletion->isEmpty()) {
                    LessonQuestionQuery::create()
                        ->filterByPrimaryKeys($this->lessonQuestionsScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->lessonQuestionsScheduledForDeletion = null;
                }
            }

            if ($this->collLessonQuestions !== null) {
                foreach ($this->collLessonQuestions as $referrerFK) {
                    if (!$referrerFK->isDeleted()) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->lessonAnswersScheduledForDeletion !== null) {
                if (!$this->lessonAnswersScheduledForDeletion->isEmpty()) {
                    LessonAnswerQuery::create()
                        ->filterByPrimaryKeys($this->lessonAnswersScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->lessonAnswersScheduledForDeletion = null;
                }
            }

            if ($this->collLessonAnswers !== null) {
                foreach ($this->collLessonAnswers as $referrerFK) {
                    if (!$referrerFK->isDeleted()) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->contentsScheduledForDeletion !== null) {
                if (!$this->contentsScheduledForDeletion->isEmpty()) {
                    foreach ($this->contentsScheduledForDeletion as $content) {
                        // need to save related object because we set the relation to null
                        $content->save($con);
                    }
                    $this->contentsScheduledForDeletion = null;
                }
            }

            if ($this->collContents !== null) {
                foreach ($this->collContents as $referrerFK) {
                    if (!$referrerFK->isDeleted()) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->tasksScheduledForDeletion !== null) {
                if (!$this->tasksScheduledForDeletion->isEmpty()) {
                    TaskQuery::create()
                        ->filterByPrimaryKeys($this->tasksScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->tasksScheduledForDeletion = null;
                }
            }

            if ($this->collTasks !== null) {
                foreach ($this->collTasks as $referrerFK) {
                    if (!$referrerFK->isDeleted()) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->userTasksScheduledForDeletion !== null) {
                if (!$this->userTasksScheduledForDeletion->isEmpty()) {
                    UserTaskQuery::create()
                        ->filterByPrimaryKeys($this->userTasksScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->userTasksScheduledForDeletion = null;
                }
            }

            if ($this->collUserTasks !== null) {
                foreach ($this->collUserTasks as $referrerFK) {
                    if (!$referrerFK->isDeleted()) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->textContentsScheduledForDeletion !== null) {
                if (!$this->textContentsScheduledForDeletion->isEmpty()) {
                    foreach ($this->textContentsScheduledForDeletion as $textContent) {
                        // need to save related object because we set the relation to null
                        $textContent->save($con);
                    }
                    $this->textContentsScheduledForDeletion = null;
                }
            }

            if ($this->collTextContents !== null) {
                foreach ($this->collTextContents as $referrerFK) {
                    if (!$referrerFK->isDeleted()) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->urlContentsScheduledForDeletion !== null) {
                if (!$this->urlContentsScheduledForDeletion->isEmpty()) {
                    foreach ($this->urlContentsScheduledForDeletion as $urlContent) {
                        // need to save related object because we set the relation to null
                        $urlContent->save($con);
                    }
                    $this->urlContentsScheduledForDeletion = null;
                }
            }

            if ($this->collUrlContents !== null) {
                foreach ($this->collUrlContents as $referrerFK) {
                    if (!$referrerFK->isDeleted()) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->youtubeContentsScheduledForDeletion !== null) {
                if (!$this->youtubeContentsScheduledForDeletion->isEmpty()) {
                    foreach ($this->youtubeContentsScheduledForDeletion as $youtubeContent) {
                        // need to save related object because we set the relation to null
                        $youtubeContent->save($con);
                    }
                    $this->youtubeContentsScheduledForDeletion = null;
                }
            }

            if ($this->collYoutubeContents !== null) {
                foreach ($this->collYoutubeContents as $referrerFK) {
                    if (!$referrerFK->isDeleted()) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->slideshareContentsScheduledForDeletion !== null) {
                if (!$this->slideshareContentsScheduledForDeletion->isEmpty()) {
                    foreach ($this->slideshareContentsScheduledForDeletion as $slideshareContent) {
                        // need to save related object because we set the relation to null
                        $slideshareContent->save($con);
                    }
                    $this->slideshareContentsScheduledForDeletion = null;
                }
            }

            if ($this->collSlideshareContents !== null) {
                foreach ($this->collSlideshareContents as $referrerFK) {
                    if (!$referrerFK->isDeleted()) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            $this->alreadyInSave = false;

        }

        return $affectedRows;
    } // doSave()

    /**
     * Insert the row in the database.
     *
     * @param PropelPDO $con
     *
     * @throws PropelException
     * @see        doSave()
     */
    protected function doInsert(PropelPDO $con)
    {
        $modifiedColumns = array();
        $index = 0;

        $this->modifiedColumns[] = LessonPeer::ID;
        if (null !== $this->id) {
            throw new PropelException('Cannot insert a value for auto-increment primary key (' . LessonPeer::ID . ')');
        }

         // check the columns in natural order for more readable SQL queries
        if ($this->isColumnModified(LessonPeer::ID)) {
            $modifiedColumns[':p' . $index++]  = '`ID`';
        }
        if ($this->isColumnModified(LessonPeer::COURSE_ID)) {
            $modifiedColumns[':p' . $index++]  = '`COURSE_ID`';
        }
        if ($this->isColumnModified(LessonPeer::TITLE)) {
            $modifiedColumns[':p' . $index++]  = '`TITLE`';
        }
        if ($this->isColumnModified(LessonPeer::CREATED_AT)) {
            $modifiedColumns[':p' . $index++]  = '`CREATED_AT`';
        }
        if ($this->isColumnModified(LessonPeer::UPDATED_AT)) {
            $modifiedColumns[':p' . $index++]  = '`UPDATED_AT`';
        }
        if ($this->isColumnModified(LessonPeer::SORTABLE_RANK)) {
            $modifiedColumns[':p' . $index++]  = '`SORTABLE_RANK`';
        }

        $sql = sprintf(
            'INSERT INTO `lessons` (%s) VALUES (%s)',
            implode(', ', $modifiedColumns),
            implode(', ', array_keys($modifiedColumns))
        );

        try {
            $stmt = $con->prepare($sql);
            foreach ($modifiedColumns as $identifier => $columnName) {
                switch ($columnName) {
                    case '`ID`':
                        $stmt->bindValue($identifier, $this->id, PDO::PARAM_INT);
                        break;
                    case '`COURSE_ID`':
                        $stmt->bindValue($identifier, $this->course_id, PDO::PARAM_INT);
                        break;
                    case '`TITLE`':
                        $stmt->bindValue($identifier, $this->title, PDO::PARAM_STR);
                        break;
                    case '`CREATED_AT`':
                        $stmt->bindValue($identifier, $this->created_at, PDO::PARAM_STR);
                        break;
                    case '`UPDATED_AT`':
                        $stmt->bindValue($identifier, $this->updated_at, PDO::PARAM_STR);
                        break;
                    case '`SORTABLE_RANK`':
                        $stmt->bindValue($identifier, $this->sortable_rank, PDO::PARAM_INT);
                        break;
                }
            }
            $stmt->execute();
        } catch (Exception $e) {
            Propel::log($e->getMessage(), Propel::LOG_ERR);
            throw new PropelException(sprintf('Unable to execute INSERT statement [%s]', $sql), $e);
        }

        try {
            $pk = $con->lastInsertId();
        } catch (Exception $e) {
            throw new PropelException('Unable to get autoincrement id.', $e);
        }
        $this->setId($pk);

        $this->setNew(false);
    }

    /**
     * Update the row in the database.
     *
     * @param PropelPDO $con
     *
     * @see        doSave()
     */
    protected function doUpdate(PropelPDO $con)
    {
        $selectCriteria = $this->buildPkeyCriteria();
        $valuesCriteria = $this->buildCriteria();
        BasePeer::doUpdate($selectCriteria, $valuesCriteria, $con);
    }

    /**
     * Array of ValidationFailed objects.
     * @var        array ValidationFailed[]
     */
    protected $validationFailures = array();

    /**
     * Gets any ValidationFailed objects that resulted from last call to validate().
     *
     *
     * @return array ValidationFailed[]
     * @see        validate()
     */
    public function getValidationFailures()
    {
        return $this->validationFailures;
    }

    /**
     * Validates the objects modified field values and all objects related to this table.
     *
     * If $columns is either a column name or an array of column names
     * only those columns are validated.
     *
     * @param mixed $columns Column name or an array of column names.
     * @return boolean Whether all columns pass validation.
     * @see        doValidate()
     * @see        getValidationFailures()
     */
    public function validate($columns = null)
    {
        $res = $this->doValidate($columns);
        if ($res === true) {
            $this->validationFailures = array();

            return true;
        } else {
            $this->validationFailures = $res;

            return false;
        }
    }

    /**
     * This function performs the validation work for complex object models.
     *
     * In addition to checking the current object, all related objects will
     * also be validated.  If all pass then <code>true</code> is returned; otherwise
     * an aggreagated array of ValidationFailed objects will be returned.
     *
     * @param array $columns Array of column names to validate.
     * @return mixed <code>true</code> if all validations pass; array of <code>ValidationFailed</code> objets otherwise.
     */
    protected function doValidate($columns = null)
    {
        if (!$this->alreadyInValidation) {
            $this->alreadyInValidation = true;
            $retval = null;

            $failureMap = array();


            // We call the validate method on the following object(s) if they
            // were passed to this object by their coresponding set
            // method.  This object relates to these object(s) by a
            // foreign key reference.

            if ($this->aCourse !== null) {
                if (!$this->aCourse->validate($columns)) {
                    $failureMap = array_merge($failureMap, $this->aCourse->getValidationFailures());
                }
            }


            if (($retval = LessonPeer::doValidate($this, $columns)) !== true) {
                $failureMap = array_merge($failureMap, $retval);
            }


                if ($this->collUserLessons !== null) {
                    foreach ($this->collUserLessons as $referrerFK) {
                        if (!$referrerFK->validate($columns)) {
                            $failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
                        }
                    }
                }

                if ($this->collLessonquizzes !== null) {
                    foreach ($this->collLessonquizzes as $referrerFK) {
                        if (!$referrerFK->validate($columns)) {
                            $failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
                        }
                    }
                }

                if ($this->collLessonQuestions !== null) {
                    foreach ($this->collLessonQuestions as $referrerFK) {
                        if (!$referrerFK->validate($columns)) {
                            $failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
                        }
                    }
                }

                if ($this->collLessonAnswers !== null) {
                    foreach ($this->collLessonAnswers as $referrerFK) {
                        if (!$referrerFK->validate($columns)) {
                            $failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
                        }
                    }
                }

                if ($this->collContents !== null) {
                    foreach ($this->collContents as $referrerFK) {
                        if (!$referrerFK->validate($columns)) {
                            $failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
                        }
                    }
                }

                if ($this->collTasks !== null) {
                    foreach ($this->collTasks as $referrerFK) {
                        if (!$referrerFK->validate($columns)) {
                            $failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
                        }
                    }
                }

                if ($this->collUserTasks !== null) {
                    foreach ($this->collUserTasks as $referrerFK) {
                        if (!$referrerFK->validate($columns)) {
                            $failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
                        }
                    }
                }

                if ($this->collTextContents !== null) {
                    foreach ($this->collTextContents as $referrerFK) {
                        if (!$referrerFK->validate($columns)) {
                            $failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
                        }
                    }
                }

                if ($this->collUrlContents !== null) {
                    foreach ($this->collUrlContents as $referrerFK) {
                        if (!$referrerFK->validate($columns)) {
                            $failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
                        }
                    }
                }

                if ($this->collYoutubeContents !== null) {
                    foreach ($this->collYoutubeContents as $referrerFK) {
                        if (!$referrerFK->validate($columns)) {
                            $failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
                        }
                    }
                }

                if ($this->collSlideshareContents !== null) {
                    foreach ($this->collSlideshareContents as $referrerFK) {
                        if (!$referrerFK->validate($columns)) {
                            $failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
                        }
                    }
                }


            $this->alreadyInValidation = false;
        }

        return (!empty($failureMap) ? $failureMap : true);
    }

    /**
     * Retrieves a field from the object by name passed in as a string.
     *
     * @param string $name name
     * @param string $type The type of fieldname the $name is of:
     *               one of the class type constants BasePeer::TYPE_PHPNAME, BasePeer::TYPE_STUDLYPHPNAME
     *               BasePeer::TYPE_COLNAME, BasePeer::TYPE_FIELDNAME, BasePeer::TYPE_NUM.
     *               Defaults to BasePeer::TYPE_PHPNAME
     * @return mixed Value of field.
     */
    public function getByName($name, $type = BasePeer::TYPE_PHPNAME)
    {
        $pos = LessonPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
        $field = $this->getByPosition($pos);

        return $field;
    }

    /**
     * Retrieves a field from the object by Position as specified in the xml schema.
     * Zero-based.
     *
     * @param int $pos position in xml schema
     * @return mixed Value of field at $pos
     */
    public function getByPosition($pos)
    {
        switch ($pos) {
            case 0:
                return $this->getId();
                break;
            case 1:
                return $this->getCourseId();
                break;
            case 2:
                return $this->getTitle();
                break;
            case 3:
                return $this->getCreatedAt();
                break;
            case 4:
                return $this->getUpdatedAt();
                break;
            case 5:
                return $this->getSortableRank();
                break;
            default:
                return null;
                break;
        } // switch()
    }

    /**
     * Exports the object as an array.
     *
     * You can specify the key type of the array by passing one of the class
     * type constants.
     *
     * @param     string  $keyType (optional) One of the class type constants BasePeer::TYPE_PHPNAME, BasePeer::TYPE_STUDLYPHPNAME,
     *                    BasePeer::TYPE_COLNAME, BasePeer::TYPE_FIELDNAME, BasePeer::TYPE_NUM.
     *                    Defaults to BasePeer::TYPE_PHPNAME.
     * @param     boolean $includeLazyLoadColumns (optional) Whether to include lazy loaded columns. Defaults to true.
     * @param     array $alreadyDumpedObjects List of objects to skip to avoid recursion
     * @param     boolean $includeForeignObjects (optional) Whether to include hydrated related objects. Default to FALSE.
     *
     * @return array an associative array containing the field names (as keys) and field values
     */
    public function toArray($keyType = BasePeer::TYPE_PHPNAME, $includeLazyLoadColumns = true, $alreadyDumpedObjects = array(), $includeForeignObjects = false)
    {
        if (isset($alreadyDumpedObjects['Lesson'][$this->getPrimaryKey()])) {
            return '*RECURSION*';
        }
        $alreadyDumpedObjects['Lesson'][$this->getPrimaryKey()] = true;
        $keys = LessonPeer::getFieldNames($keyType);
        $result = array(
            $keys[0] => $this->getId(),
            $keys[1] => $this->getCourseId(),
            $keys[2] => $this->getTitle(),
            $keys[3] => $this->getCreatedAt(),
            $keys[4] => $this->getUpdatedAt(),
            $keys[5] => $this->getSortableRank(),
        );
        if ($includeForeignObjects) {
            if (null !== $this->aCourse) {
                $result['Course'] = $this->aCourse->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->collUserLessons) {
                $result['UserLessons'] = $this->collUserLessons->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collLessonquizzes) {
                $result['Lessonquizzes'] = $this->collLessonquizzes->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collLessonQuestions) {
                $result['LessonQuestions'] = $this->collLessonQuestions->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collLessonAnswers) {
                $result['LessonAnswers'] = $this->collLessonAnswers->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collContents) {
                $result['Contents'] = $this->collContents->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collTasks) {
                $result['Tasks'] = $this->collTasks->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collUserTasks) {
                $result['UserTasks'] = $this->collUserTasks->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collTextContents) {
                $result['TextContents'] = $this->collTextContents->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collUrlContents) {
                $result['UrlContents'] = $this->collUrlContents->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collYoutubeContents) {
                $result['YoutubeContents'] = $this->collYoutubeContents->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collSlideshareContents) {
                $result['SlideshareContents'] = $this->collSlideshareContents->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
        }

        return $result;
    }

    /**
     * Sets a field from the object by name passed in as a string.
     *
     * @param string $name peer name
     * @param mixed $value field value
     * @param string $type The type of fieldname the $name is of:
     *                     one of the class type constants BasePeer::TYPE_PHPNAME, BasePeer::TYPE_STUDLYPHPNAME
     *                     BasePeer::TYPE_COLNAME, BasePeer::TYPE_FIELDNAME, BasePeer::TYPE_NUM.
     *                     Defaults to BasePeer::TYPE_PHPNAME
     * @return void
     */
    public function setByName($name, $value, $type = BasePeer::TYPE_PHPNAME)
    {
        $pos = LessonPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);

        $this->setByPosition($pos, $value);
    }

    /**
     * Sets a field from the object by Position as specified in the xml schema.
     * Zero-based.
     *
     * @param int $pos position in xml schema
     * @param mixed $value field value
     * @return void
     */
    public function setByPosition($pos, $value)
    {
        switch ($pos) {
            case 0:
                $this->setId($value);
                break;
            case 1:
                $this->setCourseId($value);
                break;
            case 2:
                $this->setTitle($value);
                break;
            case 3:
                $this->setCreatedAt($value);
                break;
            case 4:
                $this->setUpdatedAt($value);
                break;
            case 5:
                $this->setSortableRank($value);
                break;
        } // switch()
    }

    /**
     * Populates the object using an array.
     *
     * This is particularly useful when populating an object from one of the
     * request arrays (e.g. $_POST).  This method goes through the column
     * names, checking to see whether a matching key exists in populated
     * array. If so the setByName() method is called for that column.
     *
     * You can specify the key type of the array by additionally passing one
     * of the class type constants BasePeer::TYPE_PHPNAME, BasePeer::TYPE_STUDLYPHPNAME,
     * BasePeer::TYPE_COLNAME, BasePeer::TYPE_FIELDNAME, BasePeer::TYPE_NUM.
     * The default key type is the column's BasePeer::TYPE_PHPNAME
     *
     * @param array  $arr     An array to populate the object from.
     * @param string $keyType The type of keys the array uses.
     * @return void
     */
    public function fromArray($arr, $keyType = BasePeer::TYPE_PHPNAME)
    {
        $keys = LessonPeer::getFieldNames($keyType);

        if (array_key_exists($keys[0], $arr)) $this->setId($arr[$keys[0]]);
        if (array_key_exists($keys[1], $arr)) $this->setCourseId($arr[$keys[1]]);
        if (array_key_exists($keys[2], $arr)) $this->setTitle($arr[$keys[2]]);
        if (array_key_exists($keys[3], $arr)) $this->setCreatedAt($arr[$keys[3]]);
        if (array_key_exists($keys[4], $arr)) $this->setUpdatedAt($arr[$keys[4]]);
        if (array_key_exists($keys[5], $arr)) $this->setSortableRank($arr[$keys[5]]);
    }

    /**
     * Build a Criteria object containing the values of all modified columns in this object.
     *
     * @return Criteria The Criteria object containing all modified values.
     */
    public function buildCriteria()
    {
        $criteria = new Criteria(LessonPeer::DATABASE_NAME);

        if ($this->isColumnModified(LessonPeer::ID)) $criteria->add(LessonPeer::ID, $this->id);
        if ($this->isColumnModified(LessonPeer::COURSE_ID)) $criteria->add(LessonPeer::COURSE_ID, $this->course_id);
        if ($this->isColumnModified(LessonPeer::TITLE)) $criteria->add(LessonPeer::TITLE, $this->title);
        if ($this->isColumnModified(LessonPeer::CREATED_AT)) $criteria->add(LessonPeer::CREATED_AT, $this->created_at);
        if ($this->isColumnModified(LessonPeer::UPDATED_AT)) $criteria->add(LessonPeer::UPDATED_AT, $this->updated_at);
        if ($this->isColumnModified(LessonPeer::SORTABLE_RANK)) $criteria->add(LessonPeer::SORTABLE_RANK, $this->sortable_rank);

        return $criteria;
    }

    /**
     * Builds a Criteria object containing the primary key for this object.
     *
     * Unlike buildCriteria() this method includes the primary key values regardless
     * of whether or not they have been modified.
     *
     * @return Criteria The Criteria object containing value(s) for primary key(s).
     */
    public function buildPkeyCriteria()
    {
        $criteria = new Criteria(LessonPeer::DATABASE_NAME);
        $criteria->add(LessonPeer::ID, $this->id);

        return $criteria;
    }

    /**
     * Returns the primary key for this object (row).
     * @return int
     */
    public function getPrimaryKey()
    {
        return $this->getId();
    }

    /**
     * Generic method to set the primary key (id column).
     *
     * @param  int $key Primary key.
     * @return void
     */
    public function setPrimaryKey($key)
    {
        $this->setId($key);
    }

    /**
     * Returns true if the primary key for this object is null.
     * @return boolean
     */
    public function isPrimaryKeyNull()
    {

        return null === $this->getId();
    }

    /**
     * Sets contents of passed object to values from current object.
     *
     * If desired, this method can also make copies of all associated (fkey referrers)
     * objects.
     *
     * @param object $copyObj An object of Lesson (or compatible) type.
     * @param boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @param boolean $makeNew Whether to reset autoincrement PKs and make the object new.
     * @throws PropelException
     */
    public function copyInto($copyObj, $deepCopy = false, $makeNew = true)
    {
        $copyObj->setCourseId($this->getCourseId());
        $copyObj->setTitle($this->getTitle());
        $copyObj->setCreatedAt($this->getCreatedAt());
        $copyObj->setUpdatedAt($this->getUpdatedAt());
        $copyObj->setSortableRank($this->getSortableRank());

        if ($deepCopy && !$this->startCopy) {
            // important: temporarily setNew(false) because this affects the behavior of
            // the getter/setter methods for fkey referrer objects.
            $copyObj->setNew(false);
            // store object hash to prevent cycle
            $this->startCopy = true;

            foreach ($this->getUserLessons() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addUserLesson($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getLessonquizzes() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addLessonQuiz($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getLessonQuestions() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addLessonQuestion($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getLessonAnswers() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addLessonAnswer($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getContents() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addContent($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getTasks() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addTask($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getUserTasks() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addUserTask($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getTextContents() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addTextContent($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getUrlContents() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addUrlContent($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getYoutubeContents() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addYoutubeContent($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getSlideshareContents() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addSlideshareContent($relObj->copy($deepCopy));
                }
            }

            //unflag object copy
            $this->startCopy = false;
        } // if ($deepCopy)

        if ($makeNew) {
            $copyObj->setNew(true);
            $copyObj->setId(NULL); // this is a auto-increment column, so set to default value
        }
    }

    /**
     * Makes a copy of this object that will be inserted as a new row in table when saved.
     * It creates a new object filling in the simple attributes, but skipping any primary
     * keys that are defined for the table.
     *
     * If desired, this method can also make copies of all associated (fkey referrers)
     * objects.
     *
     * @param boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @return Lesson Clone of current object.
     * @throws PropelException
     */
    public function copy($deepCopy = false)
    {
        // we use get_class(), because this might be a subclass
        $clazz = get_class($this);
        $copyObj = new $clazz();
        $this->copyInto($copyObj, $deepCopy);

        return $copyObj;
    }

    /**
     * Returns a peer instance associated with this om.
     *
     * Since Peer classes are not to have any instance attributes, this method returns the
     * same instance for all member of this class. The method could therefore
     * be static, but this would prevent one from overriding the behavior.
     *
     * @return LessonPeer
     */
    public function getPeer()
    {
        if (self::$peer === null) {
            self::$peer = new LessonPeer();
        }

        return self::$peer;
    }

    /**
     * Declares an association between this object and a Course object.
     *
     * @param             Course $v
     * @return Lesson The current object (for fluent API support)
     * @throws PropelException
     */
    public function setCourse(Course $v = null)
    {
        if ($v === null) {
            $this->setCourseId(NULL);
        } else {
            $this->setCourseId($v->getId());
        }

        $this->aCourse = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the Course object, it will not be re-added.
        if ($v !== null) {
            $v->addLesson($this);
        }


        return $this;
    }


    /**
     * Get the associated Course object
     *
     * @param PropelPDO $con Optional Connection object.
     * @return Course The associated Course object.
     * @throws PropelException
     */
    public function getCourse(PropelPDO $con = null)
    {
        if ($this->aCourse === null && ($this->course_id !== null)) {
            $this->aCourse = CourseQuery::create()->findPk($this->course_id, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aCourse->addLessons($this);
             */
        }

        return $this->aCourse;
    }


    /**
     * Initializes a collection based on the name of a relation.
     * Avoids crafting an 'init[$relationName]s' method name
     * that wouldn't work when StandardEnglishPluralizer is used.
     *
     * @param string $relationName The name of the relation to initialize
     * @return void
     */
    public function initRelation($relationName)
    {
        if ('UserLesson' == $relationName) {
            $this->initUserLessons();
        }
        if ('LessonQuiz' == $relationName) {
            $this->initLessonquizzes();
        }
        if ('LessonQuestion' == $relationName) {
            $this->initLessonQuestions();
        }
        if ('LessonAnswer' == $relationName) {
            $this->initLessonAnswers();
        }
        if ('Content' == $relationName) {
            $this->initContents();
        }
        if ('Task' == $relationName) {
            $this->initTasks();
        }
        if ('UserTask' == $relationName) {
            $this->initUserTasks();
        }
        if ('TextContent' == $relationName) {
            $this->initTextContents();
        }
        if ('UrlContent' == $relationName) {
            $this->initUrlContents();
        }
        if ('YoutubeContent' == $relationName) {
            $this->initYoutubeContents();
        }
        if ('SlideshareContent' == $relationName) {
            $this->initSlideshareContents();
        }
    }

    /**
     * Clears out the collUserLessons collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addUserLessons()
     */
    public function clearUserLessons()
    {
        $this->collUserLessons = null; // important to set this to null since that means it is uninitialized
        $this->collUserLessonsPartial = null;
    }

    /**
     * reset is the collUserLessons collection loaded partially
     *
     * @return void
     */
    public function resetPartialUserLessons($v = true)
    {
        $this->collUserLessonsPartial = $v;
    }

    /**
     * Initializes the collUserLessons collection.
     *
     * By default this just sets the collUserLessons collection to an empty array (like clearcollUserLessons());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initUserLessons($overrideExisting = true)
    {
        if (null !== $this->collUserLessons && !$overrideExisting) {
            return;
        }
        $this->collUserLessons = new PropelObjectCollection();
        $this->collUserLessons->setModel('UserLesson');
    }

    /**
     * Gets an array of UserLesson objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this Lesson is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|UserLesson[] List of UserLesson objects
     * @throws PropelException
     */
    public function getUserLessons($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collUserLessonsPartial && !$this->isNew();
        if (null === $this->collUserLessons || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collUserLessons) {
                // return empty collection
                $this->initUserLessons();
            } else {
                $collUserLessons = UserLessonQuery::create(null, $criteria)
                    ->filterByLesson($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collUserLessonsPartial && count($collUserLessons)) {
                      $this->initUserLessons(false);

                      foreach($collUserLessons as $obj) {
                        if (false == $this->collUserLessons->contains($obj)) {
                          $this->collUserLessons->append($obj);
                        }
                      }

                      $this->collUserLessonsPartial = true;
                    }

                    return $collUserLessons;
                }

                if($partial && $this->collUserLessons) {
                    foreach($this->collUserLessons as $obj) {
                        if($obj->isNew()) {
                            $collUserLessons[] = $obj;
                        }
                    }
                }

                $this->collUserLessons = $collUserLessons;
                $this->collUserLessonsPartial = false;
            }
        }

        return $this->collUserLessons;
    }

    /**
     * Sets a collection of UserLesson objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $userLessons A Propel collection.
     * @param PropelPDO $con Optional connection object
     */
    public function setUserLessons(PropelCollection $userLessons, PropelPDO $con = null)
    {
        $this->userLessonsScheduledForDeletion = $this->getUserLessons(new Criteria(), $con)->diff($userLessons);

        foreach ($this->userLessonsScheduledForDeletion as $userLessonRemoved) {
            $userLessonRemoved->setLesson(null);
        }

        $this->collUserLessons = null;
        foreach ($userLessons as $userLesson) {
            $this->addUserLesson($userLesson);
        }

        $this->collUserLessons = $userLessons;
        $this->collUserLessonsPartial = false;
    }

    /**
     * Returns the number of related UserLesson objects.
     *
     * @param Criteria $criteria
     * @param boolean $distinct
     * @param PropelPDO $con
     * @return int             Count of related UserLesson objects.
     * @throws PropelException
     */
    public function countUserLessons(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collUserLessonsPartial && !$this->isNew();
        if (null === $this->collUserLessons || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collUserLessons) {
                return 0;
            } else {
                if($partial && !$criteria) {
                    return count($this->getUserLessons());
                }
                $query = UserLessonQuery::create(null, $criteria);
                if ($distinct) {
                    $query->distinct();
                }

                return $query
                    ->filterByLesson($this)
                    ->count($con);
            }
        } else {
            return count($this->collUserLessons);
        }
    }

    /**
     * Method called to associate a UserLesson object to this object
     * through the UserLesson foreign key attribute.
     *
     * @param    UserLesson $l UserLesson
     * @return Lesson The current object (for fluent API support)
     */
    public function addUserLesson(UserLesson $l)
    {
        if ($this->collUserLessons === null) {
            $this->initUserLessons();
            $this->collUserLessonsPartial = true;
        }
        if (!in_array($l, $this->collUserLessons->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddUserLesson($l);
        }

        return $this;
    }

    /**
     * @param	UserLesson $userLesson The userLesson object to add.
     */
    protected function doAddUserLesson($userLesson)
    {
        $this->collUserLessons[]= $userLesson;
        $userLesson->setLesson($this);
    }

    /**
     * @param	UserLesson $userLesson The userLesson object to remove.
     */
    public function removeUserLesson($userLesson)
    {
        if ($this->getUserLessons()->contains($userLesson)) {
            $this->collUserLessons->remove($this->collUserLessons->search($userLesson));
            if (null === $this->userLessonsScheduledForDeletion) {
                $this->userLessonsScheduledForDeletion = clone $this->collUserLessons;
                $this->userLessonsScheduledForDeletion->clear();
            }
            $this->userLessonsScheduledForDeletion[]= $userLesson;
            $userLesson->setLesson(null);
        }
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Lesson is new, it will return
     * an empty collection; or if this Lesson has previously
     * been saved, it will retrieve related UserLessons from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Lesson.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|UserLesson[] List of UserLesson objects
     */
    public function getUserLessonsJoinUser($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = UserLessonQuery::create(null, $criteria);
        $query->joinWith('User', $join_behavior);

        return $this->getUserLessons($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Lesson is new, it will return
     * an empty collection; or if this Lesson has previously
     * been saved, it will retrieve related UserLessons from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Lesson.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|UserLesson[] List of UserLesson objects
     */
    public function getUserLessonsJoinCourse($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = UserLessonQuery::create(null, $criteria);
        $query->joinWith('Course', $join_behavior);

        return $this->getUserLessons($query, $con);
    }

    /**
     * Clears out the collLessonquizzes collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addLessonquizzes()
     */
    public function clearLessonquizzes()
    {
        $this->collLessonquizzes = null; // important to set this to null since that means it is uninitialized
        $this->collLessonquizzesPartial = null;
    }

    /**
     * reset is the collLessonquizzes collection loaded partially
     *
     * @return void
     */
    public function resetPartialLessonquizzes($v = true)
    {
        $this->collLessonquizzesPartial = $v;
    }

    /**
     * Initializes the collLessonquizzes collection.
     *
     * By default this just sets the collLessonquizzes collection to an empty array (like clearcollLessonquizzes());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initLessonquizzes($overrideExisting = true)
    {
        if (null !== $this->collLessonquizzes && !$overrideExisting) {
            return;
        }
        $this->collLessonquizzes = new PropelObjectCollection();
        $this->collLessonquizzes->setModel('LessonQuiz');
    }

    /**
     * Gets an array of LessonQuiz objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this Lesson is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|LessonQuiz[] List of LessonQuiz objects
     * @throws PropelException
     */
    public function getLessonquizzes($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collLessonquizzesPartial && !$this->isNew();
        if (null === $this->collLessonquizzes || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collLessonquizzes) {
                // return empty collection
                $this->initLessonquizzes();
            } else {
                $collLessonquizzes = LessonQuizQuery::create(null, $criteria)
                    ->filterByLesson($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collLessonquizzesPartial && count($collLessonquizzes)) {
                      $this->initLessonquizzes(false);

                      foreach($collLessonquizzes as $obj) {
                        if (false == $this->collLessonquizzes->contains($obj)) {
                          $this->collLessonquizzes->append($obj);
                        }
                      }

                      $this->collLessonquizzesPartial = true;
                    }

                    return $collLessonquizzes;
                }

                if($partial && $this->collLessonquizzes) {
                    foreach($this->collLessonquizzes as $obj) {
                        if($obj->isNew()) {
                            $collLessonquizzes[] = $obj;
                        }
                    }
                }

                $this->collLessonquizzes = $collLessonquizzes;
                $this->collLessonquizzesPartial = false;
            }
        }

        return $this->collLessonquizzes;
    }

    /**
     * Sets a collection of LessonQuiz objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $lessonquizzes A Propel collection.
     * @param PropelPDO $con Optional connection object
     */
    public function setLessonquizzes(PropelCollection $lessonquizzes, PropelPDO $con = null)
    {
        $this->lessonquizzesScheduledForDeletion = $this->getLessonquizzes(new Criteria(), $con)->diff($lessonquizzes);

        foreach ($this->lessonquizzesScheduledForDeletion as $lessonQuizRemoved) {
            $lessonQuizRemoved->setLesson(null);
        }

        $this->collLessonquizzes = null;
        foreach ($lessonquizzes as $lessonQuiz) {
            $this->addLessonQuiz($lessonQuiz);
        }

        $this->collLessonquizzes = $lessonquizzes;
        $this->collLessonquizzesPartial = false;
    }

    /**
     * Returns the number of related LessonQuiz objects.
     *
     * @param Criteria $criteria
     * @param boolean $distinct
     * @param PropelPDO $con
     * @return int             Count of related LessonQuiz objects.
     * @throws PropelException
     */
    public function countLessonquizzes(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collLessonquizzesPartial && !$this->isNew();
        if (null === $this->collLessonquizzes || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collLessonquizzes) {
                return 0;
            } else {
                if($partial && !$criteria) {
                    return count($this->getLessonquizzes());
                }
                $query = LessonQuizQuery::create(null, $criteria);
                if ($distinct) {
                    $query->distinct();
                }

                return $query
                    ->filterByLesson($this)
                    ->count($con);
            }
        } else {
            return count($this->collLessonquizzes);
        }
    }

    /**
     * Method called to associate a LessonQuiz object to this object
     * through the LessonQuiz foreign key attribute.
     *
     * @param    LessonQuiz $l LessonQuiz
     * @return Lesson The current object (for fluent API support)
     */
    public function addLessonQuiz(LessonQuiz $l)
    {
        if ($this->collLessonquizzes === null) {
            $this->initLessonquizzes();
            $this->collLessonquizzesPartial = true;
        }
        if (!in_array($l, $this->collLessonquizzes->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddLessonQuiz($l);
        }

        return $this;
    }

    /**
     * @param	LessonQuiz $lessonQuiz The lessonQuiz object to add.
     */
    protected function doAddLessonQuiz($lessonQuiz)
    {
        $this->collLessonquizzes[]= $lessonQuiz;
        $lessonQuiz->setLesson($this);
    }

    /**
     * @param	LessonQuiz $lessonQuiz The lessonQuiz object to remove.
     */
    public function removeLessonQuiz($lessonQuiz)
    {
        if ($this->getLessonquizzes()->contains($lessonQuiz)) {
            $this->collLessonquizzes->remove($this->collLessonquizzes->search($lessonQuiz));
            if (null === $this->lessonquizzesScheduledForDeletion) {
                $this->lessonquizzesScheduledForDeletion = clone $this->collLessonquizzes;
                $this->lessonquizzesScheduledForDeletion->clear();
            }
            $this->lessonquizzesScheduledForDeletion[]= $lessonQuiz;
            $lessonQuiz->setLesson(null);
        }
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Lesson is new, it will return
     * an empty collection; or if this Lesson has previously
     * been saved, it will retrieve related Lessonquizzes from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Lesson.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|LessonQuiz[] List of LessonQuiz objects
     */
    public function getLessonquizzesJoinQuiz($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = LessonQuizQuery::create(null, $criteria);
        $query->joinWith('Quiz', $join_behavior);

        return $this->getLessonquizzes($query, $con);
    }

    /**
     * Clears out the collLessonQuestions collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addLessonQuestions()
     */
    public function clearLessonQuestions()
    {
        $this->collLessonQuestions = null; // important to set this to null since that means it is uninitialized
        $this->collLessonQuestionsPartial = null;
    }

    /**
     * reset is the collLessonQuestions collection loaded partially
     *
     * @return void
     */
    public function resetPartialLessonQuestions($v = true)
    {
        $this->collLessonQuestionsPartial = $v;
    }

    /**
     * Initializes the collLessonQuestions collection.
     *
     * By default this just sets the collLessonQuestions collection to an empty array (like clearcollLessonQuestions());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initLessonQuestions($overrideExisting = true)
    {
        if (null !== $this->collLessonQuestions && !$overrideExisting) {
            return;
        }
        $this->collLessonQuestions = new PropelObjectCollection();
        $this->collLessonQuestions->setModel('LessonQuestion');
    }

    /**
     * Gets an array of LessonQuestion objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this Lesson is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|LessonQuestion[] List of LessonQuestion objects
     * @throws PropelException
     */
    public function getLessonQuestions($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collLessonQuestionsPartial && !$this->isNew();
        if (null === $this->collLessonQuestions || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collLessonQuestions) {
                // return empty collection
                $this->initLessonQuestions();
            } else {
                $collLessonQuestions = LessonQuestionQuery::create(null, $criteria)
                    ->filterByLesson($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collLessonQuestionsPartial && count($collLessonQuestions)) {
                      $this->initLessonQuestions(false);

                      foreach($collLessonQuestions as $obj) {
                        if (false == $this->collLessonQuestions->contains($obj)) {
                          $this->collLessonQuestions->append($obj);
                        }
                      }

                      $this->collLessonQuestionsPartial = true;
                    }

                    return $collLessonQuestions;
                }

                if($partial && $this->collLessonQuestions) {
                    foreach($this->collLessonQuestions as $obj) {
                        if($obj->isNew()) {
                            $collLessonQuestions[] = $obj;
                        }
                    }
                }

                $this->collLessonQuestions = $collLessonQuestions;
                $this->collLessonQuestionsPartial = false;
            }
        }

        return $this->collLessonQuestions;
    }

    /**
     * Sets a collection of LessonQuestion objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $lessonQuestions A Propel collection.
     * @param PropelPDO $con Optional connection object
     */
    public function setLessonQuestions(PropelCollection $lessonQuestions, PropelPDO $con = null)
    {
        $this->lessonQuestionsScheduledForDeletion = $this->getLessonQuestions(new Criteria(), $con)->diff($lessonQuestions);

        foreach ($this->lessonQuestionsScheduledForDeletion as $lessonQuestionRemoved) {
            $lessonQuestionRemoved->setLesson(null);
        }

        $this->collLessonQuestions = null;
        foreach ($lessonQuestions as $lessonQuestion) {
            $this->addLessonQuestion($lessonQuestion);
        }

        $this->collLessonQuestions = $lessonQuestions;
        $this->collLessonQuestionsPartial = false;
    }

    /**
     * Returns the number of related LessonQuestion objects.
     *
     * @param Criteria $criteria
     * @param boolean $distinct
     * @param PropelPDO $con
     * @return int             Count of related LessonQuestion objects.
     * @throws PropelException
     */
    public function countLessonQuestions(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collLessonQuestionsPartial && !$this->isNew();
        if (null === $this->collLessonQuestions || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collLessonQuestions) {
                return 0;
            } else {
                if($partial && !$criteria) {
                    return count($this->getLessonQuestions());
                }
                $query = LessonQuestionQuery::create(null, $criteria);
                if ($distinct) {
                    $query->distinct();
                }

                return $query
                    ->filterByLesson($this)
                    ->count($con);
            }
        } else {
            return count($this->collLessonQuestions);
        }
    }

    /**
     * Method called to associate a LessonQuestion object to this object
     * through the LessonQuestion foreign key attribute.
     *
     * @param    LessonQuestion $l LessonQuestion
     * @return Lesson The current object (for fluent API support)
     */
    public function addLessonQuestion(LessonQuestion $l)
    {
        if ($this->collLessonQuestions === null) {
            $this->initLessonQuestions();
            $this->collLessonQuestionsPartial = true;
        }
        if (!in_array($l, $this->collLessonQuestions->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddLessonQuestion($l);
        }

        return $this;
    }

    /**
     * @param	LessonQuestion $lessonQuestion The lessonQuestion object to add.
     */
    protected function doAddLessonQuestion($lessonQuestion)
    {
        $this->collLessonQuestions[]= $lessonQuestion;
        $lessonQuestion->setLesson($this);
    }

    /**
     * @param	LessonQuestion $lessonQuestion The lessonQuestion object to remove.
     */
    public function removeLessonQuestion($lessonQuestion)
    {
        if ($this->getLessonQuestions()->contains($lessonQuestion)) {
            $this->collLessonQuestions->remove($this->collLessonQuestions->search($lessonQuestion));
            if (null === $this->lessonQuestionsScheduledForDeletion) {
                $this->lessonQuestionsScheduledForDeletion = clone $this->collLessonQuestions;
                $this->lessonQuestionsScheduledForDeletion->clear();
            }
            $this->lessonQuestionsScheduledForDeletion[]= $lessonQuestion;
            $lessonQuestion->setLesson(null);
        }
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Lesson is new, it will return
     * an empty collection; or if this Lesson has previously
     * been saved, it will retrieve related LessonQuestions from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Lesson.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|LessonQuestion[] List of LessonQuestion objects
     */
    public function getLessonQuestionsJoinUser($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = LessonQuestionQuery::create(null, $criteria);
        $query->joinWith('User', $join_behavior);

        return $this->getLessonQuestions($query, $con);
    }

    /**
     * Clears out the collLessonAnswers collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addLessonAnswers()
     */
    public function clearLessonAnswers()
    {
        $this->collLessonAnswers = null; // important to set this to null since that means it is uninitialized
        $this->collLessonAnswersPartial = null;
    }

    /**
     * reset is the collLessonAnswers collection loaded partially
     *
     * @return void
     */
    public function resetPartialLessonAnswers($v = true)
    {
        $this->collLessonAnswersPartial = $v;
    }

    /**
     * Initializes the collLessonAnswers collection.
     *
     * By default this just sets the collLessonAnswers collection to an empty array (like clearcollLessonAnswers());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initLessonAnswers($overrideExisting = true)
    {
        if (null !== $this->collLessonAnswers && !$overrideExisting) {
            return;
        }
        $this->collLessonAnswers = new PropelObjectCollection();
        $this->collLessonAnswers->setModel('LessonAnswer');
    }

    /**
     * Gets an array of LessonAnswer objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this Lesson is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|LessonAnswer[] List of LessonAnswer objects
     * @throws PropelException
     */
    public function getLessonAnswers($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collLessonAnswersPartial && !$this->isNew();
        if (null === $this->collLessonAnswers || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collLessonAnswers) {
                // return empty collection
                $this->initLessonAnswers();
            } else {
                $collLessonAnswers = LessonAnswerQuery::create(null, $criteria)
                    ->filterByLesson($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collLessonAnswersPartial && count($collLessonAnswers)) {
                      $this->initLessonAnswers(false);

                      foreach($collLessonAnswers as $obj) {
                        if (false == $this->collLessonAnswers->contains($obj)) {
                          $this->collLessonAnswers->append($obj);
                        }
                      }

                      $this->collLessonAnswersPartial = true;
                    }

                    return $collLessonAnswers;
                }

                if($partial && $this->collLessonAnswers) {
                    foreach($this->collLessonAnswers as $obj) {
                        if($obj->isNew()) {
                            $collLessonAnswers[] = $obj;
                        }
                    }
                }

                $this->collLessonAnswers = $collLessonAnswers;
                $this->collLessonAnswersPartial = false;
            }
        }

        return $this->collLessonAnswers;
    }

    /**
     * Sets a collection of LessonAnswer objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $lessonAnswers A Propel collection.
     * @param PropelPDO $con Optional connection object
     */
    public function setLessonAnswers(PropelCollection $lessonAnswers, PropelPDO $con = null)
    {
        $this->lessonAnswersScheduledForDeletion = $this->getLessonAnswers(new Criteria(), $con)->diff($lessonAnswers);

        foreach ($this->lessonAnswersScheduledForDeletion as $lessonAnswerRemoved) {
            $lessonAnswerRemoved->setLesson(null);
        }

        $this->collLessonAnswers = null;
        foreach ($lessonAnswers as $lessonAnswer) {
            $this->addLessonAnswer($lessonAnswer);
        }

        $this->collLessonAnswers = $lessonAnswers;
        $this->collLessonAnswersPartial = false;
    }

    /**
     * Returns the number of related LessonAnswer objects.
     *
     * @param Criteria $criteria
     * @param boolean $distinct
     * @param PropelPDO $con
     * @return int             Count of related LessonAnswer objects.
     * @throws PropelException
     */
    public function countLessonAnswers(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collLessonAnswersPartial && !$this->isNew();
        if (null === $this->collLessonAnswers || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collLessonAnswers) {
                return 0;
            } else {
                if($partial && !$criteria) {
                    return count($this->getLessonAnswers());
                }
                $query = LessonAnswerQuery::create(null, $criteria);
                if ($distinct) {
                    $query->distinct();
                }

                return $query
                    ->filterByLesson($this)
                    ->count($con);
            }
        } else {
            return count($this->collLessonAnswers);
        }
    }

    /**
     * Method called to associate a LessonAnswer object to this object
     * through the LessonAnswer foreign key attribute.
     *
     * @param    LessonAnswer $l LessonAnswer
     * @return Lesson The current object (for fluent API support)
     */
    public function addLessonAnswer(LessonAnswer $l)
    {
        if ($this->collLessonAnswers === null) {
            $this->initLessonAnswers();
            $this->collLessonAnswersPartial = true;
        }
        if (!in_array($l, $this->collLessonAnswers->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddLessonAnswer($l);
        }

        return $this;
    }

    /**
     * @param	LessonAnswer $lessonAnswer The lessonAnswer object to add.
     */
    protected function doAddLessonAnswer($lessonAnswer)
    {
        $this->collLessonAnswers[]= $lessonAnswer;
        $lessonAnswer->setLesson($this);
    }

    /**
     * @param	LessonAnswer $lessonAnswer The lessonAnswer object to remove.
     */
    public function removeLessonAnswer($lessonAnswer)
    {
        if ($this->getLessonAnswers()->contains($lessonAnswer)) {
            $this->collLessonAnswers->remove($this->collLessonAnswers->search($lessonAnswer));
            if (null === $this->lessonAnswersScheduledForDeletion) {
                $this->lessonAnswersScheduledForDeletion = clone $this->collLessonAnswers;
                $this->lessonAnswersScheduledForDeletion->clear();
            }
            $this->lessonAnswersScheduledForDeletion[]= $lessonAnswer;
            $lessonAnswer->setLesson(null);
        }
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Lesson is new, it will return
     * an empty collection; or if this Lesson has previously
     * been saved, it will retrieve related LessonAnswers from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Lesson.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|LessonAnswer[] List of LessonAnswer objects
     */
    public function getLessonAnswersJoinLessonQuestion($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = LessonAnswerQuery::create(null, $criteria);
        $query->joinWith('LessonQuestion', $join_behavior);

        return $this->getLessonAnswers($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Lesson is new, it will return
     * an empty collection; or if this Lesson has previously
     * been saved, it will retrieve related LessonAnswers from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Lesson.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|LessonAnswer[] List of LessonAnswer objects
     */
    public function getLessonAnswersJoinUser($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = LessonAnswerQuery::create(null, $criteria);
        $query->joinWith('User', $join_behavior);

        return $this->getLessonAnswers($query, $con);
    }

    /**
     * Clears out the collContents collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addContents()
     */
    public function clearContents()
    {
        $this->collContents = null; // important to set this to null since that means it is uninitialized
        $this->collContentsPartial = null;
    }

    /**
     * reset is the collContents collection loaded partially
     *
     * @return void
     */
    public function resetPartialContents($v = true)
    {
        $this->collContentsPartial = $v;
    }

    /**
     * Initializes the collContents collection.
     *
     * By default this just sets the collContents collection to an empty array (like clearcollContents());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initContents($overrideExisting = true)
    {
        if (null !== $this->collContents && !$overrideExisting) {
            return;
        }
        $this->collContents = new PropelObjectCollection();
        $this->collContents->setModel('Content');
    }

    /**
     * Gets an array of Content objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this Lesson is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|Content[] List of Content objects
     * @throws PropelException
     */
    public function getContents($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collContentsPartial && !$this->isNew();
        if (null === $this->collContents || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collContents) {
                // return empty collection
                $this->initContents();
            } else {
                $collContents = ContentQuery::create(null, $criteria)
                    ->filterByLesson($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collContentsPartial && count($collContents)) {
                      $this->initContents(false);

                      foreach($collContents as $obj) {
                        if (false == $this->collContents->contains($obj)) {
                          $this->collContents->append($obj);
                        }
                      }

                      $this->collContentsPartial = true;
                    }

                    return $collContents;
                }

                if($partial && $this->collContents) {
                    foreach($this->collContents as $obj) {
                        if($obj->isNew()) {
                            $collContents[] = $obj;
                        }
                    }
                }

                $this->collContents = $collContents;
                $this->collContentsPartial = false;
            }
        }

        return $this->collContents;
    }

    /**
     * Sets a collection of Content objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $contents A Propel collection.
     * @param PropelPDO $con Optional connection object
     */
    public function setContents(PropelCollection $contents, PropelPDO $con = null)
    {
        $this->contentsScheduledForDeletion = $this->getContents(new Criteria(), $con)->diff($contents);

        foreach ($this->contentsScheduledForDeletion as $contentRemoved) {
            $contentRemoved->setLesson(null);
        }

        $this->collContents = null;
        foreach ($contents as $content) {
            $this->addContent($content);
        }

        $this->collContents = $contents;
        $this->collContentsPartial = false;
    }

    /**
     * Returns the number of related Content objects.
     *
     * @param Criteria $criteria
     * @param boolean $distinct
     * @param PropelPDO $con
     * @return int             Count of related Content objects.
     * @throws PropelException
     */
    public function countContents(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collContentsPartial && !$this->isNew();
        if (null === $this->collContents || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collContents) {
                return 0;
            } else {
                if($partial && !$criteria) {
                    return count($this->getContents());
                }
                $query = ContentQuery::create(null, $criteria);
                if ($distinct) {
                    $query->distinct();
                }

                return $query
                    ->filterByLesson($this)
                    ->count($con);
            }
        } else {
            return count($this->collContents);
        }
    }

    /**
     * Method called to associate a Content object to this object
     * through the Content foreign key attribute.
     *
     * @param    Content $l Content
     * @return Lesson The current object (for fluent API support)
     */
    public function addContent(Content $l)
    {
        if ($this->collContents === null) {
            $this->initContents();
            $this->collContentsPartial = true;
        }
        if (!in_array($l, $this->collContents->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddContent($l);
        }

        return $this;
    }

    /**
     * @param	Content $content The content object to add.
     */
    protected function doAddContent($content)
    {
        $this->collContents[]= $content;
        $content->setLesson($this);
    }

    /**
     * @param	Content $content The content object to remove.
     */
    public function removeContent($content)
    {
        if ($this->getContents()->contains($content)) {
            $this->collContents->remove($this->collContents->search($content));
            if (null === $this->contentsScheduledForDeletion) {
                $this->contentsScheduledForDeletion = clone $this->collContents;
                $this->contentsScheduledForDeletion->clear();
            }
            $this->contentsScheduledForDeletion[]= $content;
            $content->setLesson(null);
        }
    }

    /**
     * Clears out the collTasks collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addTasks()
     */
    public function clearTasks()
    {
        $this->collTasks = null; // important to set this to null since that means it is uninitialized
        $this->collTasksPartial = null;
    }

    /**
     * reset is the collTasks collection loaded partially
     *
     * @return void
     */
    public function resetPartialTasks($v = true)
    {
        $this->collTasksPartial = $v;
    }

    /**
     * Initializes the collTasks collection.
     *
     * By default this just sets the collTasks collection to an empty array (like clearcollTasks());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initTasks($overrideExisting = true)
    {
        if (null !== $this->collTasks && !$overrideExisting) {
            return;
        }
        $this->collTasks = new PropelObjectCollection();
        $this->collTasks->setModel('Task');
    }

    /**
     * Gets an array of Task objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this Lesson is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|Task[] List of Task objects
     * @throws PropelException
     */
    public function getTasks($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collTasksPartial && !$this->isNew();
        if (null === $this->collTasks || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collTasks) {
                // return empty collection
                $this->initTasks();
            } else {
                $collTasks = TaskQuery::create(null, $criteria)
                    ->filterByLesson($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collTasksPartial && count($collTasks)) {
                      $this->initTasks(false);

                      foreach($collTasks as $obj) {
                        if (false == $this->collTasks->contains($obj)) {
                          $this->collTasks->append($obj);
                        }
                      }

                      $this->collTasksPartial = true;
                    }

                    return $collTasks;
                }

                if($partial && $this->collTasks) {
                    foreach($this->collTasks as $obj) {
                        if($obj->isNew()) {
                            $collTasks[] = $obj;
                        }
                    }
                }

                $this->collTasks = $collTasks;
                $this->collTasksPartial = false;
            }
        }

        return $this->collTasks;
    }

    /**
     * Sets a collection of Task objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $tasks A Propel collection.
     * @param PropelPDO $con Optional connection object
     */
    public function setTasks(PropelCollection $tasks, PropelPDO $con = null)
    {
        $this->tasksScheduledForDeletion = $this->getTasks(new Criteria(), $con)->diff($tasks);

        foreach ($this->tasksScheduledForDeletion as $taskRemoved) {
            $taskRemoved->setLesson(null);
        }

        $this->collTasks = null;
        foreach ($tasks as $task) {
            $this->addTask($task);
        }

        $this->collTasks = $tasks;
        $this->collTasksPartial = false;
    }

    /**
     * Returns the number of related Task objects.
     *
     * @param Criteria $criteria
     * @param boolean $distinct
     * @param PropelPDO $con
     * @return int             Count of related Task objects.
     * @throws PropelException
     */
    public function countTasks(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collTasksPartial && !$this->isNew();
        if (null === $this->collTasks || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collTasks) {
                return 0;
            } else {
                if($partial && !$criteria) {
                    return count($this->getTasks());
                }
                $query = TaskQuery::create(null, $criteria);
                if ($distinct) {
                    $query->distinct();
                }

                return $query
                    ->filterByLesson($this)
                    ->count($con);
            }
        } else {
            return count($this->collTasks);
        }
    }

    /**
     * Method called to associate a Task object to this object
     * through the Task foreign key attribute.
     *
     * @param    Task $l Task
     * @return Lesson The current object (for fluent API support)
     */
    public function addTask(Task $l)
    {
        if ($this->collTasks === null) {
            $this->initTasks();
            $this->collTasksPartial = true;
        }
        if (!in_array($l, $this->collTasks->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddTask($l);
        }

        return $this;
    }

    /**
     * @param	Task $task The task object to add.
     */
    protected function doAddTask($task)
    {
        $this->collTasks[]= $task;
        $task->setLesson($this);
    }

    /**
     * @param	Task $task The task object to remove.
     */
    public function removeTask($task)
    {
        if ($this->getTasks()->contains($task)) {
            $this->collTasks->remove($this->collTasks->search($task));
            if (null === $this->tasksScheduledForDeletion) {
                $this->tasksScheduledForDeletion = clone $this->collTasks;
                $this->tasksScheduledForDeletion->clear();
            }
            $this->tasksScheduledForDeletion[]= $task;
            $task->setLesson(null);
        }
    }

    /**
     * Clears out the collUserTasks collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addUserTasks()
     */
    public function clearUserTasks()
    {
        $this->collUserTasks = null; // important to set this to null since that means it is uninitialized
        $this->collUserTasksPartial = null;
    }

    /**
     * reset is the collUserTasks collection loaded partially
     *
     * @return void
     */
    public function resetPartialUserTasks($v = true)
    {
        $this->collUserTasksPartial = $v;
    }

    /**
     * Initializes the collUserTasks collection.
     *
     * By default this just sets the collUserTasks collection to an empty array (like clearcollUserTasks());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initUserTasks($overrideExisting = true)
    {
        if (null !== $this->collUserTasks && !$overrideExisting) {
            return;
        }
        $this->collUserTasks = new PropelObjectCollection();
        $this->collUserTasks->setModel('UserTask');
    }

    /**
     * Gets an array of UserTask objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this Lesson is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|UserTask[] List of UserTask objects
     * @throws PropelException
     */
    public function getUserTasks($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collUserTasksPartial && !$this->isNew();
        if (null === $this->collUserTasks || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collUserTasks) {
                // return empty collection
                $this->initUserTasks();
            } else {
                $collUserTasks = UserTaskQuery::create(null, $criteria)
                    ->filterByLesson($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collUserTasksPartial && count($collUserTasks)) {
                      $this->initUserTasks(false);

                      foreach($collUserTasks as $obj) {
                        if (false == $this->collUserTasks->contains($obj)) {
                          $this->collUserTasks->append($obj);
                        }
                      }

                      $this->collUserTasksPartial = true;
                    }

                    return $collUserTasks;
                }

                if($partial && $this->collUserTasks) {
                    foreach($this->collUserTasks as $obj) {
                        if($obj->isNew()) {
                            $collUserTasks[] = $obj;
                        }
                    }
                }

                $this->collUserTasks = $collUserTasks;
                $this->collUserTasksPartial = false;
            }
        }

        return $this->collUserTasks;
    }

    /**
     * Sets a collection of UserTask objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $userTasks A Propel collection.
     * @param PropelPDO $con Optional connection object
     */
    public function setUserTasks(PropelCollection $userTasks, PropelPDO $con = null)
    {
        $this->userTasksScheduledForDeletion = $this->getUserTasks(new Criteria(), $con)->diff($userTasks);

        foreach ($this->userTasksScheduledForDeletion as $userTaskRemoved) {
            $userTaskRemoved->setLesson(null);
        }

        $this->collUserTasks = null;
        foreach ($userTasks as $userTask) {
            $this->addUserTask($userTask);
        }

        $this->collUserTasks = $userTasks;
        $this->collUserTasksPartial = false;
    }

    /**
     * Returns the number of related UserTask objects.
     *
     * @param Criteria $criteria
     * @param boolean $distinct
     * @param PropelPDO $con
     * @return int             Count of related UserTask objects.
     * @throws PropelException
     */
    public function countUserTasks(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collUserTasksPartial && !$this->isNew();
        if (null === $this->collUserTasks || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collUserTasks) {
                return 0;
            } else {
                if($partial && !$criteria) {
                    return count($this->getUserTasks());
                }
                $query = UserTaskQuery::create(null, $criteria);
                if ($distinct) {
                    $query->distinct();
                }

                return $query
                    ->filterByLesson($this)
                    ->count($con);
            }
        } else {
            return count($this->collUserTasks);
        }
    }

    /**
     * Method called to associate a UserTask object to this object
     * through the UserTask foreign key attribute.
     *
     * @param    UserTask $l UserTask
     * @return Lesson The current object (for fluent API support)
     */
    public function addUserTask(UserTask $l)
    {
        if ($this->collUserTasks === null) {
            $this->initUserTasks();
            $this->collUserTasksPartial = true;
        }
        if (!in_array($l, $this->collUserTasks->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddUserTask($l);
        }

        return $this;
    }

    /**
     * @param	UserTask $userTask The userTask object to add.
     */
    protected function doAddUserTask($userTask)
    {
        $this->collUserTasks[]= $userTask;
        $userTask->setLesson($this);
    }

    /**
     * @param	UserTask $userTask The userTask object to remove.
     */
    public function removeUserTask($userTask)
    {
        if ($this->getUserTasks()->contains($userTask)) {
            $this->collUserTasks->remove($this->collUserTasks->search($userTask));
            if (null === $this->userTasksScheduledForDeletion) {
                $this->userTasksScheduledForDeletion = clone $this->collUserTasks;
                $this->userTasksScheduledForDeletion->clear();
            }
            $this->userTasksScheduledForDeletion[]= $userTask;
            $userTask->setLesson(null);
        }
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Lesson is new, it will return
     * an empty collection; or if this Lesson has previously
     * been saved, it will retrieve related UserTasks from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Lesson.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|UserTask[] List of UserTask objects
     */
    public function getUserTasksJoinUser($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = UserTaskQuery::create(null, $criteria);
        $query->joinWith('User', $join_behavior);

        return $this->getUserTasks($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Lesson is new, it will return
     * an empty collection; or if this Lesson has previously
     * been saved, it will retrieve related UserTasks from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Lesson.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|UserTask[] List of UserTask objects
     */
    public function getUserTasksJoinTask($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = UserTaskQuery::create(null, $criteria);
        $query->joinWith('Task', $join_behavior);

        return $this->getUserTasks($query, $con);
    }

    /**
     * Clears out the collTextContents collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addTextContents()
     */
    public function clearTextContents()
    {
        $this->collTextContents = null; // important to set this to null since that means it is uninitialized
        $this->collTextContentsPartial = null;
    }

    /**
     * reset is the collTextContents collection loaded partially
     *
     * @return void
     */
    public function resetPartialTextContents($v = true)
    {
        $this->collTextContentsPartial = $v;
    }

    /**
     * Initializes the collTextContents collection.
     *
     * By default this just sets the collTextContents collection to an empty array (like clearcollTextContents());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initTextContents($overrideExisting = true)
    {
        if (null !== $this->collTextContents && !$overrideExisting) {
            return;
        }
        $this->collTextContents = new PropelObjectCollection();
        $this->collTextContents->setModel('TextContent');
    }

    /**
     * Gets an array of TextContent objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this Lesson is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|TextContent[] List of TextContent objects
     * @throws PropelException
     */
    public function getTextContents($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collTextContentsPartial && !$this->isNew();
        if (null === $this->collTextContents || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collTextContents) {
                // return empty collection
                $this->initTextContents();
            } else {
                $collTextContents = TextContentQuery::create(null, $criteria)
                    ->filterByLesson($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collTextContentsPartial && count($collTextContents)) {
                      $this->initTextContents(false);

                      foreach($collTextContents as $obj) {
                        if (false == $this->collTextContents->contains($obj)) {
                          $this->collTextContents->append($obj);
                        }
                      }

                      $this->collTextContentsPartial = true;
                    }

                    return $collTextContents;
                }

                if($partial && $this->collTextContents) {
                    foreach($this->collTextContents as $obj) {
                        if($obj->isNew()) {
                            $collTextContents[] = $obj;
                        }
                    }
                }

                $this->collTextContents = $collTextContents;
                $this->collTextContentsPartial = false;
            }
        }

        return $this->collTextContents;
    }

    /**
     * Sets a collection of TextContent objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $textContents A Propel collection.
     * @param PropelPDO $con Optional connection object
     */
    public function setTextContents(PropelCollection $textContents, PropelPDO $con = null)
    {
        $this->textContentsScheduledForDeletion = $this->getTextContents(new Criteria(), $con)->diff($textContents);

        foreach ($this->textContentsScheduledForDeletion as $textContentRemoved) {
            $textContentRemoved->setLesson(null);
        }

        $this->collTextContents = null;
        foreach ($textContents as $textContent) {
            $this->addTextContent($textContent);
        }

        $this->collTextContents = $textContents;
        $this->collTextContentsPartial = false;
    }

    /**
     * Returns the number of related TextContent objects.
     *
     * @param Criteria $criteria
     * @param boolean $distinct
     * @param PropelPDO $con
     * @return int             Count of related TextContent objects.
     * @throws PropelException
     */
    public function countTextContents(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collTextContentsPartial && !$this->isNew();
        if (null === $this->collTextContents || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collTextContents) {
                return 0;
            } else {
                if($partial && !$criteria) {
                    return count($this->getTextContents());
                }
                $query = TextContentQuery::create(null, $criteria);
                if ($distinct) {
                    $query->distinct();
                }

                return $query
                    ->filterByLesson($this)
                    ->count($con);
            }
        } else {
            return count($this->collTextContents);
        }
    }

    /**
     * Method called to associate a TextContent object to this object
     * through the TextContent foreign key attribute.
     *
     * @param    TextContent $l TextContent
     * @return Lesson The current object (for fluent API support)
     */
    public function addTextContent(TextContent $l)
    {
        if ($this->collTextContents === null) {
            $this->initTextContents();
            $this->collTextContentsPartial = true;
        }
        if (!in_array($l, $this->collTextContents->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddTextContent($l);
        }

        return $this;
    }

    /**
     * @param	TextContent $textContent The textContent object to add.
     */
    protected function doAddTextContent($textContent)
    {
        $this->collTextContents[]= $textContent;
        $textContent->setLesson($this);
    }

    /**
     * @param	TextContent $textContent The textContent object to remove.
     */
    public function removeTextContent($textContent)
    {
        if ($this->getTextContents()->contains($textContent)) {
            $this->collTextContents->remove($this->collTextContents->search($textContent));
            if (null === $this->textContentsScheduledForDeletion) {
                $this->textContentsScheduledForDeletion = clone $this->collTextContents;
                $this->textContentsScheduledForDeletion->clear();
            }
            $this->textContentsScheduledForDeletion[]= $textContent;
            $textContent->setLesson(null);
        }
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Lesson is new, it will return
     * an empty collection; or if this Lesson has previously
     * been saved, it will retrieve related TextContents from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Lesson.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|TextContent[] List of TextContent objects
     */
    public function getTextContentsJoinContent($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = TextContentQuery::create(null, $criteria);
        $query->joinWith('Content', $join_behavior);

        return $this->getTextContents($query, $con);
    }

    /**
     * Clears out the collUrlContents collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addUrlContents()
     */
    public function clearUrlContents()
    {
        $this->collUrlContents = null; // important to set this to null since that means it is uninitialized
        $this->collUrlContentsPartial = null;
    }

    /**
     * reset is the collUrlContents collection loaded partially
     *
     * @return void
     */
    public function resetPartialUrlContents($v = true)
    {
        $this->collUrlContentsPartial = $v;
    }

    /**
     * Initializes the collUrlContents collection.
     *
     * By default this just sets the collUrlContents collection to an empty array (like clearcollUrlContents());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initUrlContents($overrideExisting = true)
    {
        if (null !== $this->collUrlContents && !$overrideExisting) {
            return;
        }
        $this->collUrlContents = new PropelObjectCollection();
        $this->collUrlContents->setModel('UrlContent');
    }

    /**
     * Gets an array of UrlContent objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this Lesson is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|UrlContent[] List of UrlContent objects
     * @throws PropelException
     */
    public function getUrlContents($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collUrlContentsPartial && !$this->isNew();
        if (null === $this->collUrlContents || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collUrlContents) {
                // return empty collection
                $this->initUrlContents();
            } else {
                $collUrlContents = UrlContentQuery::create(null, $criteria)
                    ->filterByLesson($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collUrlContentsPartial && count($collUrlContents)) {
                      $this->initUrlContents(false);

                      foreach($collUrlContents as $obj) {
                        if (false == $this->collUrlContents->contains($obj)) {
                          $this->collUrlContents->append($obj);
                        }
                      }

                      $this->collUrlContentsPartial = true;
                    }

                    return $collUrlContents;
                }

                if($partial && $this->collUrlContents) {
                    foreach($this->collUrlContents as $obj) {
                        if($obj->isNew()) {
                            $collUrlContents[] = $obj;
                        }
                    }
                }

                $this->collUrlContents = $collUrlContents;
                $this->collUrlContentsPartial = false;
            }
        }

        return $this->collUrlContents;
    }

    /**
     * Sets a collection of UrlContent objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $urlContents A Propel collection.
     * @param PropelPDO $con Optional connection object
     */
    public function setUrlContents(PropelCollection $urlContents, PropelPDO $con = null)
    {
        $this->urlContentsScheduledForDeletion = $this->getUrlContents(new Criteria(), $con)->diff($urlContents);

        foreach ($this->urlContentsScheduledForDeletion as $urlContentRemoved) {
            $urlContentRemoved->setLesson(null);
        }

        $this->collUrlContents = null;
        foreach ($urlContents as $urlContent) {
            $this->addUrlContent($urlContent);
        }

        $this->collUrlContents = $urlContents;
        $this->collUrlContentsPartial = false;
    }

    /**
     * Returns the number of related UrlContent objects.
     *
     * @param Criteria $criteria
     * @param boolean $distinct
     * @param PropelPDO $con
     * @return int             Count of related UrlContent objects.
     * @throws PropelException
     */
    public function countUrlContents(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collUrlContentsPartial && !$this->isNew();
        if (null === $this->collUrlContents || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collUrlContents) {
                return 0;
            } else {
                if($partial && !$criteria) {
                    return count($this->getUrlContents());
                }
                $query = UrlContentQuery::create(null, $criteria);
                if ($distinct) {
                    $query->distinct();
                }

                return $query
                    ->filterByLesson($this)
                    ->count($con);
            }
        } else {
            return count($this->collUrlContents);
        }
    }

    /**
     * Method called to associate a UrlContent object to this object
     * through the UrlContent foreign key attribute.
     *
     * @param    UrlContent $l UrlContent
     * @return Lesson The current object (for fluent API support)
     */
    public function addUrlContent(UrlContent $l)
    {
        if ($this->collUrlContents === null) {
            $this->initUrlContents();
            $this->collUrlContentsPartial = true;
        }
        if (!in_array($l, $this->collUrlContents->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddUrlContent($l);
        }

        return $this;
    }

    /**
     * @param	UrlContent $urlContent The urlContent object to add.
     */
    protected function doAddUrlContent($urlContent)
    {
        $this->collUrlContents[]= $urlContent;
        $urlContent->setLesson($this);
    }

    /**
     * @param	UrlContent $urlContent The urlContent object to remove.
     */
    public function removeUrlContent($urlContent)
    {
        if ($this->getUrlContents()->contains($urlContent)) {
            $this->collUrlContents->remove($this->collUrlContents->search($urlContent));
            if (null === $this->urlContentsScheduledForDeletion) {
                $this->urlContentsScheduledForDeletion = clone $this->collUrlContents;
                $this->urlContentsScheduledForDeletion->clear();
            }
            $this->urlContentsScheduledForDeletion[]= $urlContent;
            $urlContent->setLesson(null);
        }
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Lesson is new, it will return
     * an empty collection; or if this Lesson has previously
     * been saved, it will retrieve related UrlContents from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Lesson.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|UrlContent[] List of UrlContent objects
     */
    public function getUrlContentsJoinContent($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = UrlContentQuery::create(null, $criteria);
        $query->joinWith('Content', $join_behavior);

        return $this->getUrlContents($query, $con);
    }

    /**
     * Clears out the collYoutubeContents collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addYoutubeContents()
     */
    public function clearYoutubeContents()
    {
        $this->collYoutubeContents = null; // important to set this to null since that means it is uninitialized
        $this->collYoutubeContentsPartial = null;
    }

    /**
     * reset is the collYoutubeContents collection loaded partially
     *
     * @return void
     */
    public function resetPartialYoutubeContents($v = true)
    {
        $this->collYoutubeContentsPartial = $v;
    }

    /**
     * Initializes the collYoutubeContents collection.
     *
     * By default this just sets the collYoutubeContents collection to an empty array (like clearcollYoutubeContents());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initYoutubeContents($overrideExisting = true)
    {
        if (null !== $this->collYoutubeContents && !$overrideExisting) {
            return;
        }
        $this->collYoutubeContents = new PropelObjectCollection();
        $this->collYoutubeContents->setModel('YoutubeContent');
    }

    /**
     * Gets an array of YoutubeContent objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this Lesson is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|YoutubeContent[] List of YoutubeContent objects
     * @throws PropelException
     */
    public function getYoutubeContents($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collYoutubeContentsPartial && !$this->isNew();
        if (null === $this->collYoutubeContents || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collYoutubeContents) {
                // return empty collection
                $this->initYoutubeContents();
            } else {
                $collYoutubeContents = YoutubeContentQuery::create(null, $criteria)
                    ->filterByLesson($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collYoutubeContentsPartial && count($collYoutubeContents)) {
                      $this->initYoutubeContents(false);

                      foreach($collYoutubeContents as $obj) {
                        if (false == $this->collYoutubeContents->contains($obj)) {
                          $this->collYoutubeContents->append($obj);
                        }
                      }

                      $this->collYoutubeContentsPartial = true;
                    }

                    return $collYoutubeContents;
                }

                if($partial && $this->collYoutubeContents) {
                    foreach($this->collYoutubeContents as $obj) {
                        if($obj->isNew()) {
                            $collYoutubeContents[] = $obj;
                        }
                    }
                }

                $this->collYoutubeContents = $collYoutubeContents;
                $this->collYoutubeContentsPartial = false;
            }
        }

        return $this->collYoutubeContents;
    }

    /**
     * Sets a collection of YoutubeContent objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $youtubeContents A Propel collection.
     * @param PropelPDO $con Optional connection object
     */
    public function setYoutubeContents(PropelCollection $youtubeContents, PropelPDO $con = null)
    {
        $this->youtubeContentsScheduledForDeletion = $this->getYoutubeContents(new Criteria(), $con)->diff($youtubeContents);

        foreach ($this->youtubeContentsScheduledForDeletion as $youtubeContentRemoved) {
            $youtubeContentRemoved->setLesson(null);
        }

        $this->collYoutubeContents = null;
        foreach ($youtubeContents as $youtubeContent) {
            $this->addYoutubeContent($youtubeContent);
        }

        $this->collYoutubeContents = $youtubeContents;
        $this->collYoutubeContentsPartial = false;
    }

    /**
     * Returns the number of related YoutubeContent objects.
     *
     * @param Criteria $criteria
     * @param boolean $distinct
     * @param PropelPDO $con
     * @return int             Count of related YoutubeContent objects.
     * @throws PropelException
     */
    public function countYoutubeContents(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collYoutubeContentsPartial && !$this->isNew();
        if (null === $this->collYoutubeContents || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collYoutubeContents) {
                return 0;
            } else {
                if($partial && !$criteria) {
                    return count($this->getYoutubeContents());
                }
                $query = YoutubeContentQuery::create(null, $criteria);
                if ($distinct) {
                    $query->distinct();
                }

                return $query
                    ->filterByLesson($this)
                    ->count($con);
            }
        } else {
            return count($this->collYoutubeContents);
        }
    }

    /**
     * Method called to associate a YoutubeContent object to this object
     * through the YoutubeContent foreign key attribute.
     *
     * @param    YoutubeContent $l YoutubeContent
     * @return Lesson The current object (for fluent API support)
     */
    public function addYoutubeContent(YoutubeContent $l)
    {
        if ($this->collYoutubeContents === null) {
            $this->initYoutubeContents();
            $this->collYoutubeContentsPartial = true;
        }
        if (!in_array($l, $this->collYoutubeContents->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddYoutubeContent($l);
        }

        return $this;
    }

    /**
     * @param	YoutubeContent $youtubeContent The youtubeContent object to add.
     */
    protected function doAddYoutubeContent($youtubeContent)
    {
        $this->collYoutubeContents[]= $youtubeContent;
        $youtubeContent->setLesson($this);
    }

    /**
     * @param	YoutubeContent $youtubeContent The youtubeContent object to remove.
     */
    public function removeYoutubeContent($youtubeContent)
    {
        if ($this->getYoutubeContents()->contains($youtubeContent)) {
            $this->collYoutubeContents->remove($this->collYoutubeContents->search($youtubeContent));
            if (null === $this->youtubeContentsScheduledForDeletion) {
                $this->youtubeContentsScheduledForDeletion = clone $this->collYoutubeContents;
                $this->youtubeContentsScheduledForDeletion->clear();
            }
            $this->youtubeContentsScheduledForDeletion[]= $youtubeContent;
            $youtubeContent->setLesson(null);
        }
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Lesson is new, it will return
     * an empty collection; or if this Lesson has previously
     * been saved, it will retrieve related YoutubeContents from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Lesson.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|YoutubeContent[] List of YoutubeContent objects
     */
    public function getYoutubeContentsJoinContent($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = YoutubeContentQuery::create(null, $criteria);
        $query->joinWith('Content', $join_behavior);

        return $this->getYoutubeContents($query, $con);
    }

    /**
     * Clears out the collSlideshareContents collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addSlideshareContents()
     */
    public function clearSlideshareContents()
    {
        $this->collSlideshareContents = null; // important to set this to null since that means it is uninitialized
        $this->collSlideshareContentsPartial = null;
    }

    /**
     * reset is the collSlideshareContents collection loaded partially
     *
     * @return void
     */
    public function resetPartialSlideshareContents($v = true)
    {
        $this->collSlideshareContentsPartial = $v;
    }

    /**
     * Initializes the collSlideshareContents collection.
     *
     * By default this just sets the collSlideshareContents collection to an empty array (like clearcollSlideshareContents());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initSlideshareContents($overrideExisting = true)
    {
        if (null !== $this->collSlideshareContents && !$overrideExisting) {
            return;
        }
        $this->collSlideshareContents = new PropelObjectCollection();
        $this->collSlideshareContents->setModel('SlideshareContent');
    }

    /**
     * Gets an array of SlideshareContent objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this Lesson is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|SlideshareContent[] List of SlideshareContent objects
     * @throws PropelException
     */
    public function getSlideshareContents($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collSlideshareContentsPartial && !$this->isNew();
        if (null === $this->collSlideshareContents || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collSlideshareContents) {
                // return empty collection
                $this->initSlideshareContents();
            } else {
                $collSlideshareContents = SlideshareContentQuery::create(null, $criteria)
                    ->filterByLesson($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collSlideshareContentsPartial && count($collSlideshareContents)) {
                      $this->initSlideshareContents(false);

                      foreach($collSlideshareContents as $obj) {
                        if (false == $this->collSlideshareContents->contains($obj)) {
                          $this->collSlideshareContents->append($obj);
                        }
                      }

                      $this->collSlideshareContentsPartial = true;
                    }

                    return $collSlideshareContents;
                }

                if($partial && $this->collSlideshareContents) {
                    foreach($this->collSlideshareContents as $obj) {
                        if($obj->isNew()) {
                            $collSlideshareContents[] = $obj;
                        }
                    }
                }

                $this->collSlideshareContents = $collSlideshareContents;
                $this->collSlideshareContentsPartial = false;
            }
        }

        return $this->collSlideshareContents;
    }

    /**
     * Sets a collection of SlideshareContent objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $slideshareContents A Propel collection.
     * @param PropelPDO $con Optional connection object
     */
    public function setSlideshareContents(PropelCollection $slideshareContents, PropelPDO $con = null)
    {
        $this->slideshareContentsScheduledForDeletion = $this->getSlideshareContents(new Criteria(), $con)->diff($slideshareContents);

        foreach ($this->slideshareContentsScheduledForDeletion as $slideshareContentRemoved) {
            $slideshareContentRemoved->setLesson(null);
        }

        $this->collSlideshareContents = null;
        foreach ($slideshareContents as $slideshareContent) {
            $this->addSlideshareContent($slideshareContent);
        }

        $this->collSlideshareContents = $slideshareContents;
        $this->collSlideshareContentsPartial = false;
    }

    /**
     * Returns the number of related SlideshareContent objects.
     *
     * @param Criteria $criteria
     * @param boolean $distinct
     * @param PropelPDO $con
     * @return int             Count of related SlideshareContent objects.
     * @throws PropelException
     */
    public function countSlideshareContents(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collSlideshareContentsPartial && !$this->isNew();
        if (null === $this->collSlideshareContents || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collSlideshareContents) {
                return 0;
            } else {
                if($partial && !$criteria) {
                    return count($this->getSlideshareContents());
                }
                $query = SlideshareContentQuery::create(null, $criteria);
                if ($distinct) {
                    $query->distinct();
                }

                return $query
                    ->filterByLesson($this)
                    ->count($con);
            }
        } else {
            return count($this->collSlideshareContents);
        }
    }

    /**
     * Method called to associate a SlideshareContent object to this object
     * through the SlideshareContent foreign key attribute.
     *
     * @param    SlideshareContent $l SlideshareContent
     * @return Lesson The current object (for fluent API support)
     */
    public function addSlideshareContent(SlideshareContent $l)
    {
        if ($this->collSlideshareContents === null) {
            $this->initSlideshareContents();
            $this->collSlideshareContentsPartial = true;
        }
        if (!in_array($l, $this->collSlideshareContents->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddSlideshareContent($l);
        }

        return $this;
    }

    /**
     * @param	SlideshareContent $slideshareContent The slideshareContent object to add.
     */
    protected function doAddSlideshareContent($slideshareContent)
    {
        $this->collSlideshareContents[]= $slideshareContent;
        $slideshareContent->setLesson($this);
    }

    /**
     * @param	SlideshareContent $slideshareContent The slideshareContent object to remove.
     */
    public function removeSlideshareContent($slideshareContent)
    {
        if ($this->getSlideshareContents()->contains($slideshareContent)) {
            $this->collSlideshareContents->remove($this->collSlideshareContents->search($slideshareContent));
            if (null === $this->slideshareContentsScheduledForDeletion) {
                $this->slideshareContentsScheduledForDeletion = clone $this->collSlideshareContents;
                $this->slideshareContentsScheduledForDeletion->clear();
            }
            $this->slideshareContentsScheduledForDeletion[]= $slideshareContent;
            $slideshareContent->setLesson(null);
        }
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Lesson is new, it will return
     * an empty collection; or if this Lesson has previously
     * been saved, it will retrieve related SlideshareContents from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Lesson.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|SlideshareContent[] List of SlideshareContent objects
     */
    public function getSlideshareContentsJoinContent($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = SlideshareContentQuery::create(null, $criteria);
        $query->joinWith('Content', $join_behavior);

        return $this->getSlideshareContents($query, $con);
    }

    /**
     * Clears the current object and sets all attributes to their default values
     */
    public function clear()
    {
        $this->id = null;
        $this->course_id = null;
        $this->title = null;
        $this->created_at = null;
        $this->updated_at = null;
        $this->sortable_rank = null;
        $this->alreadyInSave = false;
        $this->alreadyInValidation = false;
        $this->clearAllReferences();
        $this->resetModified();
        $this->setNew(true);
        $this->setDeleted(false);
    }

    /**
     * Resets all references to other model objects or collections of model objects.
     *
     * This method is a user-space workaround for PHP's inability to garbage collect
     * objects with circular references (even in PHP 5.3). This is currently necessary
     * when using Propel in certain daemon or large-volumne/high-memory operations.
     *
     * @param boolean $deep Whether to also clear the references on all referrer objects.
     */
    public function clearAllReferences($deep = false)
    {
        if ($deep) {
            if ($this->collUserLessons) {
                foreach ($this->collUserLessons as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collLessonquizzes) {
                foreach ($this->collLessonquizzes as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collLessonQuestions) {
                foreach ($this->collLessonQuestions as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collLessonAnswers) {
                foreach ($this->collLessonAnswers as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collContents) {
                foreach ($this->collContents as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collTasks) {
                foreach ($this->collTasks as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collUserTasks) {
                foreach ($this->collUserTasks as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collTextContents) {
                foreach ($this->collTextContents as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collUrlContents) {
                foreach ($this->collUrlContents as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collYoutubeContents) {
                foreach ($this->collYoutubeContents as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collSlideshareContents) {
                foreach ($this->collSlideshareContents as $o) {
                    $o->clearAllReferences($deep);
                }
            }
        } // if ($deep)

        if ($this->collUserLessons instanceof PropelCollection) {
            $this->collUserLessons->clearIterator();
        }
        $this->collUserLessons = null;
        if ($this->collLessonquizzes instanceof PropelCollection) {
            $this->collLessonquizzes->clearIterator();
        }
        $this->collLessonquizzes = null;
        if ($this->collLessonQuestions instanceof PropelCollection) {
            $this->collLessonQuestions->clearIterator();
        }
        $this->collLessonQuestions = null;
        if ($this->collLessonAnswers instanceof PropelCollection) {
            $this->collLessonAnswers->clearIterator();
        }
        $this->collLessonAnswers = null;
        if ($this->collContents instanceof PropelCollection) {
            $this->collContents->clearIterator();
        }
        $this->collContents = null;
        if ($this->collTasks instanceof PropelCollection) {
            $this->collTasks->clearIterator();
        }
        $this->collTasks = null;
        if ($this->collUserTasks instanceof PropelCollection) {
            $this->collUserTasks->clearIterator();
        }
        $this->collUserTasks = null;
        if ($this->collTextContents instanceof PropelCollection) {
            $this->collTextContents->clearIterator();
        }
        $this->collTextContents = null;
        if ($this->collUrlContents instanceof PropelCollection) {
            $this->collUrlContents->clearIterator();
        }
        $this->collUrlContents = null;
        if ($this->collYoutubeContents instanceof PropelCollection) {
            $this->collYoutubeContents->clearIterator();
        }
        $this->collYoutubeContents = null;
        if ($this->collSlideshareContents instanceof PropelCollection) {
            $this->collSlideshareContents->clearIterator();
        }
        $this->collSlideshareContents = null;
        $this->aCourse = null;
    }

    /**
     * return the string representation of this object
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->exportTo(LessonPeer::DEFAULT_STRING_FORMAT);
    }

    /**
     * return true is the object is in saving state
     *
     * @return boolean
     */
    public function isAlreadyInSave()
    {
        return $this->alreadyInSave;
    }

    // timestampable behavior

    /**
     * Mark the current object so that the update date doesn't get updated during next save
     *
     * @return     Lesson The current object (for fluent API support)
     */
    public function keepUpdateDateUnchanged()
    {
        $this->modifiedColumns[] = LessonPeer::UPDATED_AT;

        return $this;
    }

    // sortable behavior

    /**
     * Wrap the getter for rank value
     *
     * @return    int
     */
    public function getRank()
    {
        return $this->sortable_rank;
    }

    /**
     * Wrap the setter for rank value
     *
     * @param     int
     * @return    Lesson
     */
    public function setRank($v)
    {
        return $this->setSortableRank($v);
    }

    /**
     * Wrap the getter for scope value
     *
     * @return    int
     */
    public function getScopeValue()
    {
        return $this->course_id;
    }

    /**
     * Wrap the setter for scope value
     *
     * @param     int
     * @return    Lesson
     */
    public function setScopeValue($v)
    {
        return $this->setCourseId($v);
    }

    /**
     * Check if the object is first in the list, i.e. if it has 1 for rank
     *
     * @return    boolean
     */
    public function isFirst()
    {
        return $this->getSortableRank() == 1;
    }

    /**
     * Check if the object is last in the list, i.e. if its rank is the highest rank
     *
     * @param     PropelPDO  $con      optional connection
     *
     * @return    boolean
     */
    public function isLast(PropelPDO $con = null)
    {
        return $this->getSortableRank() == LessonQuery::create()->getMaxRank($this->getCourseId(), $con);
    }

    /**
     * Get the next item in the list, i.e. the one for which rank is immediately higher
     *
     * @param     PropelPDO  $con      optional connection
     *
     * @return    Lesson
     */
    public function getNext(PropelPDO $con = null)
    {

        return LessonQuery::create()->findOneByRank($this->getSortableRank() + 1, $this->getCourseId(), $con);
    }

    /**
     * Get the previous item in the list, i.e. the one for which rank is immediately lower
     *
     * @param     PropelPDO  $con      optional connection
     *
     * @return    Lesson
     */
    public function getPrevious(PropelPDO $con = null)
    {

        return LessonQuery::create()->findOneByRank($this->getSortableRank() - 1, $this->getCourseId(), $con);
    }

    /**
     * Insert at specified rank
     * The modifications are not persisted until the object is saved.
     *
     * @param     integer    $rank rank value
     * @param     PropelPDO  $con      optional connection
     *
     * @return    Lesson the current object
     *
     * @throws    PropelException
     */
    public function insertAtRank($rank, PropelPDO $con = null)
    {
        if (null === $this->getCourseId()) {
            throw new PropelException('The scope must be defined before inserting an object in a suite');
        }
        $maxRank = LessonQuery::create()->getMaxRank($this->getCourseId(), $con);
        if ($rank < 1 || $rank > $maxRank + 1) {
            throw new PropelException('Invalid rank ' . $rank);
        }
        // move the object in the list, at the given rank
        $this->setSortableRank($rank);
        if ($rank != $maxRank + 1) {
            // Keep the list modification query for the save() transaction
            $this->sortableQueries []= array(
                'callable'  => array(self::PEER, 'shiftRank'),
                'arguments' => array(1, $rank, null, $this->getCourseId())
            );
        }

        return $this;
    }

    /**
     * Insert in the last rank
     * The modifications are not persisted until the object is saved.
     *
     * @param PropelPDO $con optional connection
     *
     * @return    Lesson the current object
     *
     * @throws    PropelException
     */
    public function insertAtBottom(PropelPDO $con = null)
    {
        if (null === $this->getCourseId()) {
            throw new PropelException('The scope must be defined before inserting an object in a suite');
        }
        $this->setSortableRank(LessonQuery::create()->getMaxRank($this->getCourseId(), $con) + 1);

        return $this;
    }

    /**
     * Insert in the first rank
     * The modifications are not persisted until the object is saved.
     *
     * @return    Lesson the current object
     */
    public function insertAtTop()
    {
        return $this->insertAtRank(1);
    }

    /**
     * Move the object to a new rank, and shifts the rank
     * Of the objects inbetween the old and new rank accordingly
     *
     * @param     integer   $newRank rank value
     * @param     PropelPDO $con optional connection
     *
     * @return    Lesson the current object
     *
     * @throws    PropelException
     */
    public function moveToRank($newRank, PropelPDO $con = null)
    {
        if ($this->isNew()) {
            throw new PropelException('New objects cannot be moved. Please use insertAtRank() instead');
        }
        if ($con === null) {
            $con = Propel::getConnection(LessonPeer::DATABASE_NAME);
        }
        if ($newRank < 1 || $newRank > LessonQuery::create()->getMaxRank($this->getCourseId(), $con)) {
            throw new PropelException('Invalid rank ' . $newRank);
        }

        $oldRank = $this->getSortableRank();
        if ($oldRank == $newRank) {
            return $this;
        }

        $con->beginTransaction();
        try {
            // shift the objects between the old and the new rank
            $delta = ($oldRank < $newRank) ? -1 : 1;
            LessonPeer::shiftRank($delta, min($oldRank, $newRank), max($oldRank, $newRank), $this->getCourseId(), $con);

            // move the object to its new rank
            $this->setSortableRank($newRank);
            $this->save($con);

            $con->commit();

            return $this;
        } catch (Exception $e) {
            $con->rollback();
            throw $e;
        }
    }

    /**
     * Exchange the rank of the object with the one passed as argument, and saves both objects
     *
     * @param     Lesson $object
     * @param     PropelPDO $con optional connection
     *
     * @return    Lesson the current object
     *
     * @throws Exception if the database cannot execute the two updates
     */
    public function swapWith($object, PropelPDO $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection(LessonPeer::DATABASE_NAME);
        }
        $con->beginTransaction();
        try {
            $oldRank = $this->getSortableRank();
            $newRank = $object->getSortableRank();
            $this->setSortableRank($newRank);
            $this->save($con);
            $object->setSortableRank($oldRank);
            $object->save($con);
            $con->commit();

            return $this;
        } catch (Exception $e) {
            $con->rollback();
            throw $e;
        }
    }

    /**
     * Move the object higher in the list, i.e. exchanges its rank with the one of the previous object
     *
     * @param     PropelPDO $con optional connection
     *
     * @return    Lesson the current object
     */
    public function moveUp(PropelPDO $con = null)
    {
        if ($this->isFirst()) {
            return $this;
        }
        if ($con === null) {
            $con = Propel::getConnection(LessonPeer::DATABASE_NAME);
        }
        $con->beginTransaction();
        try {
            $prev = $this->getPrevious($con);
            $this->swapWith($prev, $con);
            $con->commit();

            return $this;
        } catch (Exception $e) {
            $con->rollback();
            throw $e;
        }
    }

    /**
     * Move the object higher in the list, i.e. exchanges its rank with the one of the next object
     *
     * @param     PropelPDO $con optional connection
     *
     * @return    Lesson the current object
     */
    public function moveDown(PropelPDO $con = null)
    {
        if ($this->isLast($con)) {
            return $this;
        }
        if ($con === null) {
            $con = Propel::getConnection(LessonPeer::DATABASE_NAME);
        }
        $con->beginTransaction();
        try {
            $next = $this->getNext($con);
            $this->swapWith($next, $con);
            $con->commit();

            return $this;
        } catch (Exception $e) {
            $con->rollback();
            throw $e;
        }
    }

    /**
     * Move the object to the top of the list
     *
     * @param     PropelPDO $con optional connection
     *
     * @return    Lesson the current object
     */
    public function moveToTop(PropelPDO $con = null)
    {
        if ($this->isFirst()) {
            return $this;
        }

        return $this->moveToRank(1, $con);
    }

    /**
     * Move the object to the bottom of the list
     *
     * @param     PropelPDO $con optional connection
     *
     * @return integer the old object's rank
     */
    public function moveToBottom(PropelPDO $con = null)
    {
        if ($this->isLast($con)) {
            return false;
        }
        if ($con === null) {
            $con = Propel::getConnection(LessonPeer::DATABASE_NAME);
        }
        $con->beginTransaction();
        try {
            $bottom = LessonQuery::create()->getMaxRank($this->getCourseId(), $con);
            $res = $this->moveToRank($bottom, $con);
            $con->commit();

            return $res;
        } catch (Exception $e) {
            $con->rollback();
            throw $e;
        }
    }

    /**
     * Removes the current object from the list.
     * The modifications are not persisted until the object is saved.
     *
     * @return    Lesson the current object
     */
    public function removeFromList()
    {
        // Keep the list modification query for the save() transaction
        $this->sortableQueries []= array(
            'callable'  => array(self::PEER, 'shiftRank'),
            'arguments' => array(-1, $this->getSortableRank() + 1, null, $this->getCourseId())
        );
        // remove the object from the list
        $this->setSortableRank(null);
        $this->setCourseId(null);

        return $this;
    }

    /**
     * Execute queries that were saved to be run inside the save transaction
     */
    protected function processSortableQueries($con)
    {
        foreach ($this->sortableQueries as $query) {
            $query['arguments'][]= $con;
            call_user_func_array($query['callable'], $query['arguments']);
        }
        $this->sortableQueries = array();
    }

}
