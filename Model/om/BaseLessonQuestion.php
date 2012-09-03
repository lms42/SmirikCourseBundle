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
use FOS\UserBundle\Propel\User;
use FOS\UserBundle\Propel\UserQuery;
use Smirik\CourseBundle\Model\Lesson;
use Smirik\CourseBundle\Model\LessonAnswer;
use Smirik\CourseBundle\Model\LessonAnswerQuery;
use Smirik\CourseBundle\Model\LessonQuery;
use Smirik\CourseBundle\Model\LessonQuestion;
use Smirik\CourseBundle\Model\LessonQuestionPeer;
use Smirik\CourseBundle\Model\LessonQuestionQuery;

abstract class BaseLessonQuestion extends BaseObject implements Persistent
{
    /**
     * Peer class name
     */
    const PEER = 'Smirik\\CourseBundle\\Model\\LessonQuestionPeer';

    /**
     * The Peer class.
     * Instance provides a convenient way of calling static methods on a class
     * that calling code may not be able to identify.
     * @var        LessonQuestionPeer
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
     * The value for the user_id field.
     * @var        int
     */
    protected $user_id;

    /**
     * The value for the lesson_id field.
     * @var        int
     */
    protected $lesson_id;

    /**
     * The value for the title field.
     * @var        string
     */
    protected $title;

    /**
     * The value for the text field.
     * @var        string
     */
    protected $text;

    /**
     * The value for the is_visible field.
     * Note: this column has a database default value of: false
     * @var        boolean
     */
    protected $is_visible;

    /**
     * The value for the is_answered field.
     * Note: this column has a database default value of: false
     * @var        boolean
     */
    protected $is_answered;

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
     * @var        User
     */
    protected $aUser;

    /**
     * @var        Lesson
     */
    protected $aLesson;

    /**
     * @var        PropelObjectCollection|LessonAnswer[] Collection to store aggregation of LessonAnswer objects.
     */
    protected $collLessonAnswers;
    protected $collLessonAnswersPartial;

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
    protected $lessonAnswersScheduledForDeletion = null;

    /**
     * Applies default values to this object.
     * This method should be called from the object's constructor (or
     * equivalent initialization method).
     * @see        __construct()
     */
    public function applyDefaultValues()
    {
        $this->is_visible = false;
        $this->is_answered = false;
    }

    /**
     * Initializes internal state of BaseLessonQuestion object.
     * @see        applyDefaults()
     */
    public function __construct()
    {
        parent::__construct();
        $this->applyDefaultValues();
    }

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
     * Get the [user_id] column value.
     *
     * @return int
     */
    public function getUserId()
    {
        return $this->user_id;
    }

    /**
     * Get the [lesson_id] column value.
     *
     * @return int
     */
    public function getLessonId()
    {
        return $this->lesson_id;
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
     * Get the [text] column value.
     *
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * Get the [is_visible] column value.
     *
     * @return boolean
     */
    public function getIsVisible()
    {
        return $this->is_visible;
    }

    /**
     * Get the [is_answered] column value.
     *
     * @return boolean
     */
    public function getIsAnswered()
    {
        return $this->is_answered;
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
     * @return LessonQuestion The current object (for fluent API support)
     */
    public function setId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->id !== $v) {
            $this->id = $v;
            $this->modifiedColumns[] = LessonQuestionPeer::ID;
        }


        return $this;
    } // setId()

    /**
     * Set the value of [user_id] column.
     *
     * @param int $v new value
     * @return LessonQuestion The current object (for fluent API support)
     */
    public function setUserId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->user_id !== $v) {
            $this->user_id = $v;
            $this->modifiedColumns[] = LessonQuestionPeer::USER_ID;
        }

        if ($this->aUser !== null && $this->aUser->getId() !== $v) {
            $this->aUser = null;
        }


        return $this;
    } // setUserId()

    /**
     * Set the value of [lesson_id] column.
     *
     * @param int $v new value
     * @return LessonQuestion The current object (for fluent API support)
     */
    public function setLessonId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->lesson_id !== $v) {
            $this->lesson_id = $v;
            $this->modifiedColumns[] = LessonQuestionPeer::LESSON_ID;
        }

        if ($this->aLesson !== null && $this->aLesson->getId() !== $v) {
            $this->aLesson = null;
        }


        return $this;
    } // setLessonId()

    /**
     * Set the value of [title] column.
     *
     * @param string $v new value
     * @return LessonQuestion The current object (for fluent API support)
     */
    public function setTitle($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->title !== $v) {
            $this->title = $v;
            $this->modifiedColumns[] = LessonQuestionPeer::TITLE;
        }


        return $this;
    } // setTitle()

    /**
     * Set the value of [text] column.
     *
     * @param string $v new value
     * @return LessonQuestion The current object (for fluent API support)
     */
    public function setText($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->text !== $v) {
            $this->text = $v;
            $this->modifiedColumns[] = LessonQuestionPeer::TEXT;
        }


        return $this;
    } // setText()

    /**
     * Sets the value of the [is_visible] column.
     * Non-boolean arguments are converted using the following rules:
     *   * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *   * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     * Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     *
     * @param boolean|integer|string $v The new value
     * @return LessonQuestion The current object (for fluent API support)
     */
    public function setIsVisible($v)
    {
        if ($v !== null) {
            if (is_string($v)) {
                $v = in_array(strtolower($v), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
            } else {
                $v = (boolean) $v;
            }
        }

        if ($this->is_visible !== $v) {
            $this->is_visible = $v;
            $this->modifiedColumns[] = LessonQuestionPeer::IS_VISIBLE;
        }


        return $this;
    } // setIsVisible()

    /**
     * Sets the value of the [is_answered] column.
     * Non-boolean arguments are converted using the following rules:
     *   * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *   * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     * Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     *
     * @param boolean|integer|string $v The new value
     * @return LessonQuestion The current object (for fluent API support)
     */
    public function setIsAnswered($v)
    {
        if ($v !== null) {
            if (is_string($v)) {
                $v = in_array(strtolower($v), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
            } else {
                $v = (boolean) $v;
            }
        }

        if ($this->is_answered !== $v) {
            $this->is_answered = $v;
            $this->modifiedColumns[] = LessonQuestionPeer::IS_ANSWERED;
        }


        return $this;
    } // setIsAnswered()

    /**
     * Sets the value of [created_at] column to a normalized version of the date/time value specified.
     *
     * @param mixed $v string, integer (timestamp), or DateTime value.
     *               Empty strings are treated as null.
     * @return LessonQuestion The current object (for fluent API support)
     */
    public function setCreatedAt($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->created_at !== null || $dt !== null) {
            $currentDateAsString = ($this->created_at !== null && $tmpDt = new DateTime($this->created_at)) ? $tmpDt->format('Y-m-d H:i:s') : null;
            $newDateAsString = $dt ? $dt->format('Y-m-d H:i:s') : null;
            if ($currentDateAsString !== $newDateAsString) {
                $this->created_at = $newDateAsString;
                $this->modifiedColumns[] = LessonQuestionPeer::CREATED_AT;
            }
        } // if either are not null


        return $this;
    } // setCreatedAt()

    /**
     * Sets the value of [updated_at] column to a normalized version of the date/time value specified.
     *
     * @param mixed $v string, integer (timestamp), or DateTime value.
     *               Empty strings are treated as null.
     * @return LessonQuestion The current object (for fluent API support)
     */
    public function setUpdatedAt($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->updated_at !== null || $dt !== null) {
            $currentDateAsString = ($this->updated_at !== null && $tmpDt = new DateTime($this->updated_at)) ? $tmpDt->format('Y-m-d H:i:s') : null;
            $newDateAsString = $dt ? $dt->format('Y-m-d H:i:s') : null;
            if ($currentDateAsString !== $newDateAsString) {
                $this->updated_at = $newDateAsString;
                $this->modifiedColumns[] = LessonQuestionPeer::UPDATED_AT;
            }
        } // if either are not null


        return $this;
    } // setUpdatedAt()

    /**
     * Set the value of [sortable_rank] column.
     *
     * @param int $v new value
     * @return LessonQuestion The current object (for fluent API support)
     */
    public function setSortableRank($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->sortable_rank !== $v) {
            $this->sortable_rank = $v;
            $this->modifiedColumns[] = LessonQuestionPeer::SORTABLE_RANK;
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
            if ($this->is_visible !== false) {
                return false;
            }

            if ($this->is_answered !== false) {
                return false;
            }

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
            $this->user_id = ($row[$startcol + 1] !== null) ? (int) $row[$startcol + 1] : null;
            $this->lesson_id = ($row[$startcol + 2] !== null) ? (int) $row[$startcol + 2] : null;
            $this->title = ($row[$startcol + 3] !== null) ? (string) $row[$startcol + 3] : null;
            $this->text = ($row[$startcol + 4] !== null) ? (string) $row[$startcol + 4] : null;
            $this->is_visible = ($row[$startcol + 5] !== null) ? (boolean) $row[$startcol + 5] : null;
            $this->is_answered = ($row[$startcol + 6] !== null) ? (boolean) $row[$startcol + 6] : null;
            $this->created_at = ($row[$startcol + 7] !== null) ? (string) $row[$startcol + 7] : null;
            $this->updated_at = ($row[$startcol + 8] !== null) ? (string) $row[$startcol + 8] : null;
            $this->sortable_rank = ($row[$startcol + 9] !== null) ? (int) $row[$startcol + 9] : null;
            $this->resetModified();

            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }

            return $startcol + 10; // 10 = LessonQuestionPeer::NUM_HYDRATE_COLUMNS.

        } catch (Exception $e) {
            throw new PropelException("Error populating LessonQuestion object", $e);
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

        if ($this->aUser !== null && $this->user_id !== $this->aUser->getId()) {
            $this->aUser = null;
        }
        if ($this->aLesson !== null && $this->lesson_id !== $this->aLesson->getId()) {
            $this->aLesson = null;
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
            $con = Propel::getConnection(LessonQuestionPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        // We don't need to alter the object instance pool; we're just modifying this instance
        // already in the pool.

        $stmt = LessonQuestionPeer::doSelectStmt($this->buildPkeyCriteria(), $con);
        $row = $stmt->fetch(PDO::FETCH_NUM);
        $stmt->closeCursor();
        if (!$row) {
            throw new PropelException('Cannot find matching row in the database to reload object values.');
        }
        $this->hydrate($row, 0, true); // rehydrate

        if ($deep) {  // also de-associate any related objects?

            $this->aUser = null;
            $this->aLesson = null;
            $this->collLessonAnswers = null;

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
            $con = Propel::getConnection(LessonQuestionPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        $con->beginTransaction();
        try {
            $deleteQuery = LessonQuestionQuery::create()
                ->filterByPrimaryKey($this->getPrimaryKey());
            $ret = $this->preDelete($con);
            // sortable behavior

            LessonQuestionPeer::shiftRank(-1, $this->getSortableRank() + 1, null, $this->getLessonId(), $con);
            LessonQuestionPeer::clearInstancePool();

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
            $con = Propel::getConnection(LessonQuestionPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
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
                if (!$this->isColumnModified(LessonQuestionPeer::CREATED_AT)) {
                    $this->setCreatedAt(time());
                }
                if (!$this->isColumnModified(LessonQuestionPeer::UPDATED_AT)) {
                    $this->setUpdatedAt(time());
                }
                // sortable behavior
                if (!$this->isColumnModified(LessonQuestionPeer::RANK_COL)) {
                    $this->setSortableRank(LessonQuestionQuery::create()->getMaxRank($this->getLessonId(), $con) + 1);
                }

            } else {
                $ret = $ret && $this->preUpdate($con);
                // timestampable behavior
                if ($this->isModified() && !$this->isColumnModified(LessonQuestionPeer::UPDATED_AT)) {
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
                LessonQuestionPeer::addInstanceToPool($this);
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

            if ($this->aUser !== null) {
                if ($this->aUser->isModified() || $this->aUser->isNew()) {
                    $affectedRows += $this->aUser->save($con);
                }
                $this->setUser($this->aUser);
            }

            if ($this->aLesson !== null) {
                if ($this->aLesson->isModified() || $this->aLesson->isNew()) {
                    $affectedRows += $this->aLesson->save($con);
                }
                $this->setLesson($this->aLesson);
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

        $this->modifiedColumns[] = LessonQuestionPeer::ID;
        if (null !== $this->id) {
            throw new PropelException('Cannot insert a value for auto-increment primary key (' . LessonQuestionPeer::ID . ')');
        }

         // check the columns in natural order for more readable SQL queries
        if ($this->isColumnModified(LessonQuestionPeer::ID)) {
            $modifiedColumns[':p' . $index++]  = '`ID`';
        }
        if ($this->isColumnModified(LessonQuestionPeer::USER_ID)) {
            $modifiedColumns[':p' . $index++]  = '`USER_ID`';
        }
        if ($this->isColumnModified(LessonQuestionPeer::LESSON_ID)) {
            $modifiedColumns[':p' . $index++]  = '`LESSON_ID`';
        }
        if ($this->isColumnModified(LessonQuestionPeer::TITLE)) {
            $modifiedColumns[':p' . $index++]  = '`TITLE`';
        }
        if ($this->isColumnModified(LessonQuestionPeer::TEXT)) {
            $modifiedColumns[':p' . $index++]  = '`TEXT`';
        }
        if ($this->isColumnModified(LessonQuestionPeer::IS_VISIBLE)) {
            $modifiedColumns[':p' . $index++]  = '`IS_VISIBLE`';
        }
        if ($this->isColumnModified(LessonQuestionPeer::IS_ANSWERED)) {
            $modifiedColumns[':p' . $index++]  = '`IS_ANSWERED`';
        }
        if ($this->isColumnModified(LessonQuestionPeer::CREATED_AT)) {
            $modifiedColumns[':p' . $index++]  = '`CREATED_AT`';
        }
        if ($this->isColumnModified(LessonQuestionPeer::UPDATED_AT)) {
            $modifiedColumns[':p' . $index++]  = '`UPDATED_AT`';
        }
        if ($this->isColumnModified(LessonQuestionPeer::SORTABLE_RANK)) {
            $modifiedColumns[':p' . $index++]  = '`SORTABLE_RANK`';
        }

        $sql = sprintf(
            'INSERT INTO `lessons_questions` (%s) VALUES (%s)',
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
                    case '`USER_ID`':
                        $stmt->bindValue($identifier, $this->user_id, PDO::PARAM_INT);
                        break;
                    case '`LESSON_ID`':
                        $stmt->bindValue($identifier, $this->lesson_id, PDO::PARAM_INT);
                        break;
                    case '`TITLE`':
                        $stmt->bindValue($identifier, $this->title, PDO::PARAM_STR);
                        break;
                    case '`TEXT`':
                        $stmt->bindValue($identifier, $this->text, PDO::PARAM_STR);
                        break;
                    case '`IS_VISIBLE`':
                        $stmt->bindValue($identifier, (int) $this->is_visible, PDO::PARAM_INT);
                        break;
                    case '`IS_ANSWERED`':
                        $stmt->bindValue($identifier, (int) $this->is_answered, PDO::PARAM_INT);
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

            if ($this->aUser !== null) {
                if (!$this->aUser->validate($columns)) {
                    $failureMap = array_merge($failureMap, $this->aUser->getValidationFailures());
                }
            }

            if ($this->aLesson !== null) {
                if (!$this->aLesson->validate($columns)) {
                    $failureMap = array_merge($failureMap, $this->aLesson->getValidationFailures());
                }
            }


            if (($retval = LessonQuestionPeer::doValidate($this, $columns)) !== true) {
                $failureMap = array_merge($failureMap, $retval);
            }


                if ($this->collLessonAnswers !== null) {
                    foreach ($this->collLessonAnswers as $referrerFK) {
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
        $pos = LessonQuestionPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
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
                return $this->getUserId();
                break;
            case 2:
                return $this->getLessonId();
                break;
            case 3:
                return $this->getTitle();
                break;
            case 4:
                return $this->getText();
                break;
            case 5:
                return $this->getIsVisible();
                break;
            case 6:
                return $this->getIsAnswered();
                break;
            case 7:
                return $this->getCreatedAt();
                break;
            case 8:
                return $this->getUpdatedAt();
                break;
            case 9:
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
        if (isset($alreadyDumpedObjects['LessonQuestion'][$this->getPrimaryKey()])) {
            return '*RECURSION*';
        }
        $alreadyDumpedObjects['LessonQuestion'][$this->getPrimaryKey()] = true;
        $keys = LessonQuestionPeer::getFieldNames($keyType);
        $result = array(
            $keys[0] => $this->getId(),
            $keys[1] => $this->getUserId(),
            $keys[2] => $this->getLessonId(),
            $keys[3] => $this->getTitle(),
            $keys[4] => $this->getText(),
            $keys[5] => $this->getIsVisible(),
            $keys[6] => $this->getIsAnswered(),
            $keys[7] => $this->getCreatedAt(),
            $keys[8] => $this->getUpdatedAt(),
            $keys[9] => $this->getSortableRank(),
        );
        if ($includeForeignObjects) {
            if (null !== $this->aUser) {
                $result['User'] = $this->aUser->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->aLesson) {
                $result['Lesson'] = $this->aLesson->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->collLessonAnswers) {
                $result['LessonAnswers'] = $this->collLessonAnswers->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
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
        $pos = LessonQuestionPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);

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
                $this->setUserId($value);
                break;
            case 2:
                $this->setLessonId($value);
                break;
            case 3:
                $this->setTitle($value);
                break;
            case 4:
                $this->setText($value);
                break;
            case 5:
                $this->setIsVisible($value);
                break;
            case 6:
                $this->setIsAnswered($value);
                break;
            case 7:
                $this->setCreatedAt($value);
                break;
            case 8:
                $this->setUpdatedAt($value);
                break;
            case 9:
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
        $keys = LessonQuestionPeer::getFieldNames($keyType);

        if (array_key_exists($keys[0], $arr)) $this->setId($arr[$keys[0]]);
        if (array_key_exists($keys[1], $arr)) $this->setUserId($arr[$keys[1]]);
        if (array_key_exists($keys[2], $arr)) $this->setLessonId($arr[$keys[2]]);
        if (array_key_exists($keys[3], $arr)) $this->setTitle($arr[$keys[3]]);
        if (array_key_exists($keys[4], $arr)) $this->setText($arr[$keys[4]]);
        if (array_key_exists($keys[5], $arr)) $this->setIsVisible($arr[$keys[5]]);
        if (array_key_exists($keys[6], $arr)) $this->setIsAnswered($arr[$keys[6]]);
        if (array_key_exists($keys[7], $arr)) $this->setCreatedAt($arr[$keys[7]]);
        if (array_key_exists($keys[8], $arr)) $this->setUpdatedAt($arr[$keys[8]]);
        if (array_key_exists($keys[9], $arr)) $this->setSortableRank($arr[$keys[9]]);
    }

    /**
     * Build a Criteria object containing the values of all modified columns in this object.
     *
     * @return Criteria The Criteria object containing all modified values.
     */
    public function buildCriteria()
    {
        $criteria = new Criteria(LessonQuestionPeer::DATABASE_NAME);

        if ($this->isColumnModified(LessonQuestionPeer::ID)) $criteria->add(LessonQuestionPeer::ID, $this->id);
        if ($this->isColumnModified(LessonQuestionPeer::USER_ID)) $criteria->add(LessonQuestionPeer::USER_ID, $this->user_id);
        if ($this->isColumnModified(LessonQuestionPeer::LESSON_ID)) $criteria->add(LessonQuestionPeer::LESSON_ID, $this->lesson_id);
        if ($this->isColumnModified(LessonQuestionPeer::TITLE)) $criteria->add(LessonQuestionPeer::TITLE, $this->title);
        if ($this->isColumnModified(LessonQuestionPeer::TEXT)) $criteria->add(LessonQuestionPeer::TEXT, $this->text);
        if ($this->isColumnModified(LessonQuestionPeer::IS_VISIBLE)) $criteria->add(LessonQuestionPeer::IS_VISIBLE, $this->is_visible);
        if ($this->isColumnModified(LessonQuestionPeer::IS_ANSWERED)) $criteria->add(LessonQuestionPeer::IS_ANSWERED, $this->is_answered);
        if ($this->isColumnModified(LessonQuestionPeer::CREATED_AT)) $criteria->add(LessonQuestionPeer::CREATED_AT, $this->created_at);
        if ($this->isColumnModified(LessonQuestionPeer::UPDATED_AT)) $criteria->add(LessonQuestionPeer::UPDATED_AT, $this->updated_at);
        if ($this->isColumnModified(LessonQuestionPeer::SORTABLE_RANK)) $criteria->add(LessonQuestionPeer::SORTABLE_RANK, $this->sortable_rank);

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
        $criteria = new Criteria(LessonQuestionPeer::DATABASE_NAME);
        $criteria->add(LessonQuestionPeer::ID, $this->id);

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
     * @param object $copyObj An object of LessonQuestion (or compatible) type.
     * @param boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @param boolean $makeNew Whether to reset autoincrement PKs and make the object new.
     * @throws PropelException
     */
    public function copyInto($copyObj, $deepCopy = false, $makeNew = true)
    {
        $copyObj->setUserId($this->getUserId());
        $copyObj->setLessonId($this->getLessonId());
        $copyObj->setTitle($this->getTitle());
        $copyObj->setText($this->getText());
        $copyObj->setIsVisible($this->getIsVisible());
        $copyObj->setIsAnswered($this->getIsAnswered());
        $copyObj->setCreatedAt($this->getCreatedAt());
        $copyObj->setUpdatedAt($this->getUpdatedAt());
        $copyObj->setSortableRank($this->getSortableRank());

        if ($deepCopy && !$this->startCopy) {
            // important: temporarily setNew(false) because this affects the behavior of
            // the getter/setter methods for fkey referrer objects.
            $copyObj->setNew(false);
            // store object hash to prevent cycle
            $this->startCopy = true;

            foreach ($this->getLessonAnswers() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addLessonAnswer($relObj->copy($deepCopy));
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
     * @return LessonQuestion Clone of current object.
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
     * @return LessonQuestionPeer
     */
    public function getPeer()
    {
        if (self::$peer === null) {
            self::$peer = new LessonQuestionPeer();
        }

        return self::$peer;
    }

    /**
     * Declares an association between this object and a User object.
     *
     * @param             User $v
     * @return LessonQuestion The current object (for fluent API support)
     * @throws PropelException
     */
    public function setUser(User $v = null)
    {
        if ($v === null) {
            $this->setUserId(NULL);
        } else {
            $this->setUserId($v->getId());
        }

        $this->aUser = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the User object, it will not be re-added.
        if ($v !== null) {
            $v->addLessonQuestion($this);
        }


        return $this;
    }


    /**
     * Get the associated User object
     *
     * @param PropelPDO $con Optional Connection object.
     * @return User The associated User object.
     * @throws PropelException
     */
    public function getUser(PropelPDO $con = null)
    {
        if ($this->aUser === null && ($this->user_id !== null)) {
            $this->aUser = UserQuery::create()->findPk($this->user_id, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aUser->addLessonQuestions($this);
             */
        }

        return $this->aUser;
    }

    /**
     * Declares an association between this object and a Lesson object.
     *
     * @param             Lesson $v
     * @return LessonQuestion The current object (for fluent API support)
     * @throws PropelException
     */
    public function setLesson(Lesson $v = null)
    {
        if ($v === null) {
            $this->setLessonId(NULL);
        } else {
            $this->setLessonId($v->getId());
        }

        $this->aLesson = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the Lesson object, it will not be re-added.
        if ($v !== null) {
            $v->addLessonQuestion($this);
        }


        return $this;
    }


    /**
     * Get the associated Lesson object
     *
     * @param PropelPDO $con Optional Connection object.
     * @return Lesson The associated Lesson object.
     * @throws PropelException
     */
    public function getLesson(PropelPDO $con = null)
    {
        if ($this->aLesson === null && ($this->lesson_id !== null)) {
            $this->aLesson = LessonQuery::create()->findPk($this->lesson_id, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aLesson->addLessonQuestions($this);
             */
        }

        return $this->aLesson;
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
        if ('LessonAnswer' == $relationName) {
            $this->initLessonAnswers();
        }
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
     * If this LessonQuestion is new, it will return
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
                    ->filterByLessonQuestion($this)
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
            $lessonAnswerRemoved->setLessonQuestion(null);
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
                    ->filterByLessonQuestion($this)
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
     * @return LessonQuestion The current object (for fluent API support)
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
        $lessonAnswer->setLessonQuestion($this);
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
            $lessonAnswer->setLessonQuestion(null);
        }
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this LessonQuestion is new, it will return
     * an empty collection; or if this LessonQuestion has previously
     * been saved, it will retrieve related LessonAnswers from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in LessonQuestion.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|LessonAnswer[] List of LessonAnswer objects
     */
    public function getLessonAnswersJoinLesson($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = LessonAnswerQuery::create(null, $criteria);
        $query->joinWith('Lesson', $join_behavior);

        return $this->getLessonAnswers($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this LessonQuestion is new, it will return
     * an empty collection; or if this LessonQuestion has previously
     * been saved, it will retrieve related LessonAnswers from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in LessonQuestion.
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
     * Clears the current object and sets all attributes to their default values
     */
    public function clear()
    {
        $this->id = null;
        $this->user_id = null;
        $this->lesson_id = null;
        $this->title = null;
        $this->text = null;
        $this->is_visible = null;
        $this->is_answered = null;
        $this->created_at = null;
        $this->updated_at = null;
        $this->sortable_rank = null;
        $this->alreadyInSave = false;
        $this->alreadyInValidation = false;
        $this->clearAllReferences();
        $this->applyDefaultValues();
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
            if ($this->collLessonAnswers) {
                foreach ($this->collLessonAnswers as $o) {
                    $o->clearAllReferences($deep);
                }
            }
        } // if ($deep)

        if ($this->collLessonAnswers instanceof PropelCollection) {
            $this->collLessonAnswers->clearIterator();
        }
        $this->collLessonAnswers = null;
        $this->aUser = null;
        $this->aLesson = null;
    }

    /**
     * return the string representation of this object
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->exportTo(LessonQuestionPeer::DEFAULT_STRING_FORMAT);
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
     * @return     LessonQuestion The current object (for fluent API support)
     */
    public function keepUpdateDateUnchanged()
    {
        $this->modifiedColumns[] = LessonQuestionPeer::UPDATED_AT;

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
     * @return    LessonQuestion
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
        return $this->lesson_id;
    }

    /**
     * Wrap the setter for scope value
     *
     * @param     int
     * @return    LessonQuestion
     */
    public function setScopeValue($v)
    {
        return $this->setLessonId($v);
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
        return $this->getSortableRank() == LessonQuestionQuery::create()->getMaxRank($this->getLessonId(), $con);
    }

    /**
     * Get the next item in the list, i.e. the one for which rank is immediately higher
     *
     * @param     PropelPDO  $con      optional connection
     *
     * @return    LessonQuestion
     */
    public function getNext(PropelPDO $con = null)
    {

        return LessonQuestionQuery::create()->findOneByRank($this->getSortableRank() + 1, $this->getLessonId(), $con);
    }

    /**
     * Get the previous item in the list, i.e. the one for which rank is immediately lower
     *
     * @param     PropelPDO  $con      optional connection
     *
     * @return    LessonQuestion
     */
    public function getPrevious(PropelPDO $con = null)
    {

        return LessonQuestionQuery::create()->findOneByRank($this->getSortableRank() - 1, $this->getLessonId(), $con);
    }

    /**
     * Insert at specified rank
     * The modifications are not persisted until the object is saved.
     *
     * @param     integer    $rank rank value
     * @param     PropelPDO  $con      optional connection
     *
     * @return    LessonQuestion the current object
     *
     * @throws    PropelException
     */
    public function insertAtRank($rank, PropelPDO $con = null)
    {
        if (null === $this->getLessonId()) {
            throw new PropelException('The scope must be defined before inserting an object in a suite');
        }
        $maxRank = LessonQuestionQuery::create()->getMaxRank($this->getLessonId(), $con);
        if ($rank < 1 || $rank > $maxRank + 1) {
            throw new PropelException('Invalid rank ' . $rank);
        }
        // move the object in the list, at the given rank
        $this->setSortableRank($rank);
        if ($rank != $maxRank + 1) {
            // Keep the list modification query for the save() transaction
            $this->sortableQueries []= array(
                'callable'  => array(self::PEER, 'shiftRank'),
                'arguments' => array(1, $rank, null, $this->getLessonId())
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
     * @return    LessonQuestion the current object
     *
     * @throws    PropelException
     */
    public function insertAtBottom(PropelPDO $con = null)
    {
        if (null === $this->getLessonId()) {
            throw new PropelException('The scope must be defined before inserting an object in a suite');
        }
        $this->setSortableRank(LessonQuestionQuery::create()->getMaxRank($this->getLessonId(), $con) + 1);

        return $this;
    }

    /**
     * Insert in the first rank
     * The modifications are not persisted until the object is saved.
     *
     * @return    LessonQuestion the current object
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
     * @return    LessonQuestion the current object
     *
     * @throws    PropelException
     */
    public function moveToRank($newRank, PropelPDO $con = null)
    {
        if ($this->isNew()) {
            throw new PropelException('New objects cannot be moved. Please use insertAtRank() instead');
        }
        if ($con === null) {
            $con = Propel::getConnection(LessonQuestionPeer::DATABASE_NAME);
        }
        if ($newRank < 1 || $newRank > LessonQuestionQuery::create()->getMaxRank($this->getLessonId(), $con)) {
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
            LessonQuestionPeer::shiftRank($delta, min($oldRank, $newRank), max($oldRank, $newRank), $this->getLessonId(), $con);

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
     * @param     LessonQuestion $object
     * @param     PropelPDO $con optional connection
     *
     * @return    LessonQuestion the current object
     *
     * @throws Exception if the database cannot execute the two updates
     */
    public function swapWith($object, PropelPDO $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection(LessonQuestionPeer::DATABASE_NAME);
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
     * @return    LessonQuestion the current object
     */
    public function moveUp(PropelPDO $con = null)
    {
        if ($this->isFirst()) {
            return $this;
        }
        if ($con === null) {
            $con = Propel::getConnection(LessonQuestionPeer::DATABASE_NAME);
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
     * @return    LessonQuestion the current object
     */
    public function moveDown(PropelPDO $con = null)
    {
        if ($this->isLast($con)) {
            return $this;
        }
        if ($con === null) {
            $con = Propel::getConnection(LessonQuestionPeer::DATABASE_NAME);
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
     * @return    LessonQuestion the current object
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
            $con = Propel::getConnection(LessonQuestionPeer::DATABASE_NAME);
        }
        $con->beginTransaction();
        try {
            $bottom = LessonQuestionQuery::create()->getMaxRank($this->getLessonId(), $con);
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
     * @return    LessonQuestion the current object
     */
    public function removeFromList()
    {
        // Keep the list modification query for the save() transaction
        $this->sortableQueries []= array(
            'callable'  => array(self::PEER, 'shiftRank'),
            'arguments' => array(-1, $this->getSortableRank() + 1, null, $this->getLessonId())
        );
        // remove the object from the list
        $this->setSortableRank(null);
        $this->setLessonId(null);

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
