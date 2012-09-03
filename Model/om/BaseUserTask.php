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
use Smirik\CourseBundle\Model\LessonQuery;
use Smirik\CourseBundle\Model\Task;
use Smirik\CourseBundle\Model\TaskQuery;
use Smirik\CourseBundle\Model\UserTask;
use Smirik\CourseBundle\Model\UserTaskPeer;
use Smirik\CourseBundle\Model\UserTaskQuery;
use Smirik\CourseBundle\Model\UserTaskReview;
use Smirik\CourseBundle\Model\UserTaskReviewQuery;

abstract class BaseUserTask extends BaseObject implements Persistent
{
    /**
     * Peer class name
     */
    const PEER = 'Smirik\\CourseBundle\\Model\\UserTaskPeer';

    /**
     * The Peer class.
     * Instance provides a convenient way of calling static methods on a class
     * that calling code may not be able to identify.
     * @var        UserTaskPeer
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
     * The value for the lesson_id field.
     * @var        int
     */
    protected $lesson_id;

    /**
     * The value for the task_id field.
     * @var        int
     */
    protected $task_id;

    /**
     * The value for the user_id field.
     * @var        int
     */
    protected $user_id;

    /**
     * The value for the text field.
     * @var        string
     */
    protected $text;

    /**
     * The value for the url field.
     * @var        string
     */
    protected $url;

    /**
     * The value for the file field.
     * @var        string
     */
    protected $file;

    /**
     * The value for the status field.
     * Note: this column has a database default value of: 0
     * @var        int
     */
    protected $status;

    /**
     * The value for the mark field.
     * @var        int
     */
    protected $mark;

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
     * @var        Lesson
     */
    protected $aLesson;

    /**
     * @var        User
     */
    protected $aUser;

    /**
     * @var        Task
     */
    protected $aTask;

    /**
     * @var        PropelObjectCollection|UserTaskReview[] Collection to store aggregation of UserTaskReview objects.
     */
    protected $collUserTaskReviews;
    protected $collUserTaskReviewsPartial;

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

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $userTaskReviewsScheduledForDeletion = null;

    /**
     * Applies default values to this object.
     * This method should be called from the object's constructor (or
     * equivalent initialization method).
     * @see        __construct()
     */
    public function applyDefaultValues()
    {
        $this->status = 0;
    }

    /**
     * Initializes internal state of BaseUserTask object.
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
     * Get the [lesson_id] column value.
     *
     * @return int
     */
    public function getLessonId()
    {
        return $this->lesson_id;
    }

    /**
     * Get the [task_id] column value.
     *
     * @return int
     */
    public function getTaskId()
    {
        return $this->task_id;
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
     * Get the [text] column value.
     *
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * Get the [url] column value.
     *
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Get the [file] column value.
     *
     * @return string
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * Get the [status] column value.
     *
     * @return int
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Get the [mark] column value.
     *
     * @return int
     */
    public function getMark()
    {
        return $this->mark;
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
     * Set the value of [id] column.
     *
     * @param int $v new value
     * @return UserTask The current object (for fluent API support)
     */
    public function setId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->id !== $v) {
            $this->id = $v;
            $this->modifiedColumns[] = UserTaskPeer::ID;
        }


        return $this;
    } // setId()

    /**
     * Set the value of [lesson_id] column.
     *
     * @param int $v new value
     * @return UserTask The current object (for fluent API support)
     */
    public function setLessonId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->lesson_id !== $v) {
            $this->lesson_id = $v;
            $this->modifiedColumns[] = UserTaskPeer::LESSON_ID;
        }

        if ($this->aLesson !== null && $this->aLesson->getId() !== $v) {
            $this->aLesson = null;
        }


        return $this;
    } // setLessonId()

    /**
     * Set the value of [task_id] column.
     *
     * @param int $v new value
     * @return UserTask The current object (for fluent API support)
     */
    public function setTaskId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->task_id !== $v) {
            $this->task_id = $v;
            $this->modifiedColumns[] = UserTaskPeer::TASK_ID;
        }

        if ($this->aTask !== null && $this->aTask->getId() !== $v) {
            $this->aTask = null;
        }


        return $this;
    } // setTaskId()

    /**
     * Set the value of [user_id] column.
     *
     * @param int $v new value
     * @return UserTask The current object (for fluent API support)
     */
    public function setUserId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->user_id !== $v) {
            $this->user_id = $v;
            $this->modifiedColumns[] = UserTaskPeer::USER_ID;
        }

        if ($this->aUser !== null && $this->aUser->getId() !== $v) {
            $this->aUser = null;
        }


        return $this;
    } // setUserId()

    /**
     * Set the value of [text] column.
     *
     * @param string $v new value
     * @return UserTask The current object (for fluent API support)
     */
    public function setText($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->text !== $v) {
            $this->text = $v;
            $this->modifiedColumns[] = UserTaskPeer::TEXT;
        }


        return $this;
    } // setText()

    /**
     * Set the value of [url] column.
     *
     * @param string $v new value
     * @return UserTask The current object (for fluent API support)
     */
    public function setUrl($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->url !== $v) {
            $this->url = $v;
            $this->modifiedColumns[] = UserTaskPeer::URL;
        }


        return $this;
    } // setUrl()

    /**
     * Set the value of [file] column.
     *
     * @param string $v new value
     * @return UserTask The current object (for fluent API support)
     */
    public function setFile($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->file !== $v) {
            $this->file = $v;
            $this->modifiedColumns[] = UserTaskPeer::FILE;
        }


        return $this;
    } // setFile()

    /**
     * Set the value of [status] column.
     *
     * @param int $v new value
     * @return UserTask The current object (for fluent API support)
     */
    public function setStatus($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->status !== $v) {
            $this->status = $v;
            $this->modifiedColumns[] = UserTaskPeer::STATUS;
        }


        return $this;
    } // setStatus()

    /**
     * Set the value of [mark] column.
     *
     * @param int $v new value
     * @return UserTask The current object (for fluent API support)
     */
    public function setMark($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->mark !== $v) {
            $this->mark = $v;
            $this->modifiedColumns[] = UserTaskPeer::MARK;
        }


        return $this;
    } // setMark()

    /**
     * Sets the value of [created_at] column to a normalized version of the date/time value specified.
     *
     * @param mixed $v string, integer (timestamp), or DateTime value.
     *               Empty strings are treated as null.
     * @return UserTask The current object (for fluent API support)
     */
    public function setCreatedAt($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->created_at !== null || $dt !== null) {
            $currentDateAsString = ($this->created_at !== null && $tmpDt = new DateTime($this->created_at)) ? $tmpDt->format('Y-m-d H:i:s') : null;
            $newDateAsString = $dt ? $dt->format('Y-m-d H:i:s') : null;
            if ($currentDateAsString !== $newDateAsString) {
                $this->created_at = $newDateAsString;
                $this->modifiedColumns[] = UserTaskPeer::CREATED_AT;
            }
        } // if either are not null


        return $this;
    } // setCreatedAt()

    /**
     * Sets the value of [updated_at] column to a normalized version of the date/time value specified.
     *
     * @param mixed $v string, integer (timestamp), or DateTime value.
     *               Empty strings are treated as null.
     * @return UserTask The current object (for fluent API support)
     */
    public function setUpdatedAt($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->updated_at !== null || $dt !== null) {
            $currentDateAsString = ($this->updated_at !== null && $tmpDt = new DateTime($this->updated_at)) ? $tmpDt->format('Y-m-d H:i:s') : null;
            $newDateAsString = $dt ? $dt->format('Y-m-d H:i:s') : null;
            if ($currentDateAsString !== $newDateAsString) {
                $this->updated_at = $newDateAsString;
                $this->modifiedColumns[] = UserTaskPeer::UPDATED_AT;
            }
        } // if either are not null


        return $this;
    } // setUpdatedAt()

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
            if ($this->status !== 0) {
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
            $this->lesson_id = ($row[$startcol + 1] !== null) ? (int) $row[$startcol + 1] : null;
            $this->task_id = ($row[$startcol + 2] !== null) ? (int) $row[$startcol + 2] : null;
            $this->user_id = ($row[$startcol + 3] !== null) ? (int) $row[$startcol + 3] : null;
            $this->text = ($row[$startcol + 4] !== null) ? (string) $row[$startcol + 4] : null;
            $this->url = ($row[$startcol + 5] !== null) ? (string) $row[$startcol + 5] : null;
            $this->file = ($row[$startcol + 6] !== null) ? (string) $row[$startcol + 6] : null;
            $this->status = ($row[$startcol + 7] !== null) ? (int) $row[$startcol + 7] : null;
            $this->mark = ($row[$startcol + 8] !== null) ? (int) $row[$startcol + 8] : null;
            $this->created_at = ($row[$startcol + 9] !== null) ? (string) $row[$startcol + 9] : null;
            $this->updated_at = ($row[$startcol + 10] !== null) ? (string) $row[$startcol + 10] : null;
            $this->resetModified();

            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }

            return $startcol + 11; // 11 = UserTaskPeer::NUM_HYDRATE_COLUMNS.

        } catch (Exception $e) {
            throw new PropelException("Error populating UserTask object", $e);
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

        if ($this->aLesson !== null && $this->lesson_id !== $this->aLesson->getId()) {
            $this->aLesson = null;
        }
        if ($this->aTask !== null && $this->task_id !== $this->aTask->getId()) {
            $this->aTask = null;
        }
        if ($this->aUser !== null && $this->user_id !== $this->aUser->getId()) {
            $this->aUser = null;
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
            $con = Propel::getConnection(UserTaskPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        // We don't need to alter the object instance pool; we're just modifying this instance
        // already in the pool.

        $stmt = UserTaskPeer::doSelectStmt($this->buildPkeyCriteria(), $con);
        $row = $stmt->fetch(PDO::FETCH_NUM);
        $stmt->closeCursor();
        if (!$row) {
            throw new PropelException('Cannot find matching row in the database to reload object values.');
        }
        $this->hydrate($row, 0, true); // rehydrate

        if ($deep) {  // also de-associate any related objects?

            $this->aLesson = null;
            $this->aUser = null;
            $this->aTask = null;
            $this->collUserTaskReviews = null;

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
            $con = Propel::getConnection(UserTaskPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        $con->beginTransaction();
        try {
            $deleteQuery = UserTaskQuery::create()
                ->filterByPrimaryKey($this->getPrimaryKey());
            $ret = $this->preDelete($con);
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
            $con = Propel::getConnection(UserTaskPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        $con->beginTransaction();
        $isInsert = $this->isNew();
        try {
            $ret = $this->preSave($con);
            if ($isInsert) {
                $ret = $ret && $this->preInsert($con);
                // timestampable behavior
                if (!$this->isColumnModified(UserTaskPeer::CREATED_AT)) {
                    $this->setCreatedAt(time());
                }
                if (!$this->isColumnModified(UserTaskPeer::UPDATED_AT)) {
                    $this->setUpdatedAt(time());
                }
            } else {
                $ret = $ret && $this->preUpdate($con);
                // timestampable behavior
                if ($this->isModified() && !$this->isColumnModified(UserTaskPeer::UPDATED_AT)) {
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
                UserTaskPeer::addInstanceToPool($this);
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

            if ($this->aLesson !== null) {
                if ($this->aLesson->isModified() || $this->aLesson->isNew()) {
                    $affectedRows += $this->aLesson->save($con);
                }
                $this->setLesson($this->aLesson);
            }

            if ($this->aUser !== null) {
                if ($this->aUser->isModified() || $this->aUser->isNew()) {
                    $affectedRows += $this->aUser->save($con);
                }
                $this->setUser($this->aUser);
            }

            if ($this->aTask !== null) {
                if ($this->aTask->isModified() || $this->aTask->isNew()) {
                    $affectedRows += $this->aTask->save($con);
                }
                $this->setTask($this->aTask);
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

            if ($this->userTaskReviewsScheduledForDeletion !== null) {
                if (!$this->userTaskReviewsScheduledForDeletion->isEmpty()) {
                    UserTaskReviewQuery::create()
                        ->filterByPrimaryKeys($this->userTaskReviewsScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->userTaskReviewsScheduledForDeletion = null;
                }
            }

            if ($this->collUserTaskReviews !== null) {
                foreach ($this->collUserTaskReviews as $referrerFK) {
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

        $this->modifiedColumns[] = UserTaskPeer::ID;
        if (null !== $this->id) {
            throw new PropelException('Cannot insert a value for auto-increment primary key (' . UserTaskPeer::ID . ')');
        }

         // check the columns in natural order for more readable SQL queries
        if ($this->isColumnModified(UserTaskPeer::ID)) {
            $modifiedColumns[':p' . $index++]  = '`ID`';
        }
        if ($this->isColumnModified(UserTaskPeer::LESSON_ID)) {
            $modifiedColumns[':p' . $index++]  = '`LESSON_ID`';
        }
        if ($this->isColumnModified(UserTaskPeer::TASK_ID)) {
            $modifiedColumns[':p' . $index++]  = '`TASK_ID`';
        }
        if ($this->isColumnModified(UserTaskPeer::USER_ID)) {
            $modifiedColumns[':p' . $index++]  = '`USER_ID`';
        }
        if ($this->isColumnModified(UserTaskPeer::TEXT)) {
            $modifiedColumns[':p' . $index++]  = '`TEXT`';
        }
        if ($this->isColumnModified(UserTaskPeer::URL)) {
            $modifiedColumns[':p' . $index++]  = '`URL`';
        }
        if ($this->isColumnModified(UserTaskPeer::FILE)) {
            $modifiedColumns[':p' . $index++]  = '`FILE`';
        }
        if ($this->isColumnModified(UserTaskPeer::STATUS)) {
            $modifiedColumns[':p' . $index++]  = '`STATUS`';
        }
        if ($this->isColumnModified(UserTaskPeer::MARK)) {
            $modifiedColumns[':p' . $index++]  = '`MARK`';
        }
        if ($this->isColumnModified(UserTaskPeer::CREATED_AT)) {
            $modifiedColumns[':p' . $index++]  = '`CREATED_AT`';
        }
        if ($this->isColumnModified(UserTaskPeer::UPDATED_AT)) {
            $modifiedColumns[':p' . $index++]  = '`UPDATED_AT`';
        }

        $sql = sprintf(
            'INSERT INTO `users_tasks` (%s) VALUES (%s)',
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
                    case '`LESSON_ID`':
                        $stmt->bindValue($identifier, $this->lesson_id, PDO::PARAM_INT);
                        break;
                    case '`TASK_ID`':
                        $stmt->bindValue($identifier, $this->task_id, PDO::PARAM_INT);
                        break;
                    case '`USER_ID`':
                        $stmt->bindValue($identifier, $this->user_id, PDO::PARAM_INT);
                        break;
                    case '`TEXT`':
                        $stmt->bindValue($identifier, $this->text, PDO::PARAM_STR);
                        break;
                    case '`URL`':
                        $stmt->bindValue($identifier, $this->url, PDO::PARAM_STR);
                        break;
                    case '`FILE`':
                        $stmt->bindValue($identifier, $this->file, PDO::PARAM_STR);
                        break;
                    case '`STATUS`':
                        $stmt->bindValue($identifier, $this->status, PDO::PARAM_INT);
                        break;
                    case '`MARK`':
                        $stmt->bindValue($identifier, $this->mark, PDO::PARAM_INT);
                        break;
                    case '`CREATED_AT`':
                        $stmt->bindValue($identifier, $this->created_at, PDO::PARAM_STR);
                        break;
                    case '`UPDATED_AT`':
                        $stmt->bindValue($identifier, $this->updated_at, PDO::PARAM_STR);
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

            if ($this->aLesson !== null) {
                if (!$this->aLesson->validate($columns)) {
                    $failureMap = array_merge($failureMap, $this->aLesson->getValidationFailures());
                }
            }

            if ($this->aUser !== null) {
                if (!$this->aUser->validate($columns)) {
                    $failureMap = array_merge($failureMap, $this->aUser->getValidationFailures());
                }
            }

            if ($this->aTask !== null) {
                if (!$this->aTask->validate($columns)) {
                    $failureMap = array_merge($failureMap, $this->aTask->getValidationFailures());
                }
            }


            if (($retval = UserTaskPeer::doValidate($this, $columns)) !== true) {
                $failureMap = array_merge($failureMap, $retval);
            }


                if ($this->collUserTaskReviews !== null) {
                    foreach ($this->collUserTaskReviews as $referrerFK) {
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
        $pos = UserTaskPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
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
                return $this->getLessonId();
                break;
            case 2:
                return $this->getTaskId();
                break;
            case 3:
                return $this->getUserId();
                break;
            case 4:
                return $this->getText();
                break;
            case 5:
                return $this->getUrl();
                break;
            case 6:
                return $this->getFile();
                break;
            case 7:
                return $this->getStatus();
                break;
            case 8:
                return $this->getMark();
                break;
            case 9:
                return $this->getCreatedAt();
                break;
            case 10:
                return $this->getUpdatedAt();
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
        if (isset($alreadyDumpedObjects['UserTask'][$this->getPrimaryKey()])) {
            return '*RECURSION*';
        }
        $alreadyDumpedObjects['UserTask'][$this->getPrimaryKey()] = true;
        $keys = UserTaskPeer::getFieldNames($keyType);
        $result = array(
            $keys[0] => $this->getId(),
            $keys[1] => $this->getLessonId(),
            $keys[2] => $this->getTaskId(),
            $keys[3] => $this->getUserId(),
            $keys[4] => $this->getText(),
            $keys[5] => $this->getUrl(),
            $keys[6] => $this->getFile(),
            $keys[7] => $this->getStatus(),
            $keys[8] => $this->getMark(),
            $keys[9] => $this->getCreatedAt(),
            $keys[10] => $this->getUpdatedAt(),
        );
        if ($includeForeignObjects) {
            if (null !== $this->aLesson) {
                $result['Lesson'] = $this->aLesson->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->aUser) {
                $result['User'] = $this->aUser->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->aTask) {
                $result['Task'] = $this->aTask->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->collUserTaskReviews) {
                $result['UserTaskReviews'] = $this->collUserTaskReviews->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
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
        $pos = UserTaskPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);

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
                $this->setLessonId($value);
                break;
            case 2:
                $this->setTaskId($value);
                break;
            case 3:
                $this->setUserId($value);
                break;
            case 4:
                $this->setText($value);
                break;
            case 5:
                $this->setUrl($value);
                break;
            case 6:
                $this->setFile($value);
                break;
            case 7:
                $this->setStatus($value);
                break;
            case 8:
                $this->setMark($value);
                break;
            case 9:
                $this->setCreatedAt($value);
                break;
            case 10:
                $this->setUpdatedAt($value);
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
        $keys = UserTaskPeer::getFieldNames($keyType);

        if (array_key_exists($keys[0], $arr)) $this->setId($arr[$keys[0]]);
        if (array_key_exists($keys[1], $arr)) $this->setLessonId($arr[$keys[1]]);
        if (array_key_exists($keys[2], $arr)) $this->setTaskId($arr[$keys[2]]);
        if (array_key_exists($keys[3], $arr)) $this->setUserId($arr[$keys[3]]);
        if (array_key_exists($keys[4], $arr)) $this->setText($arr[$keys[4]]);
        if (array_key_exists($keys[5], $arr)) $this->setUrl($arr[$keys[5]]);
        if (array_key_exists($keys[6], $arr)) $this->setFile($arr[$keys[6]]);
        if (array_key_exists($keys[7], $arr)) $this->setStatus($arr[$keys[7]]);
        if (array_key_exists($keys[8], $arr)) $this->setMark($arr[$keys[8]]);
        if (array_key_exists($keys[9], $arr)) $this->setCreatedAt($arr[$keys[9]]);
        if (array_key_exists($keys[10], $arr)) $this->setUpdatedAt($arr[$keys[10]]);
    }

    /**
     * Build a Criteria object containing the values of all modified columns in this object.
     *
     * @return Criteria The Criteria object containing all modified values.
     */
    public function buildCriteria()
    {
        $criteria = new Criteria(UserTaskPeer::DATABASE_NAME);

        if ($this->isColumnModified(UserTaskPeer::ID)) $criteria->add(UserTaskPeer::ID, $this->id);
        if ($this->isColumnModified(UserTaskPeer::LESSON_ID)) $criteria->add(UserTaskPeer::LESSON_ID, $this->lesson_id);
        if ($this->isColumnModified(UserTaskPeer::TASK_ID)) $criteria->add(UserTaskPeer::TASK_ID, $this->task_id);
        if ($this->isColumnModified(UserTaskPeer::USER_ID)) $criteria->add(UserTaskPeer::USER_ID, $this->user_id);
        if ($this->isColumnModified(UserTaskPeer::TEXT)) $criteria->add(UserTaskPeer::TEXT, $this->text);
        if ($this->isColumnModified(UserTaskPeer::URL)) $criteria->add(UserTaskPeer::URL, $this->url);
        if ($this->isColumnModified(UserTaskPeer::FILE)) $criteria->add(UserTaskPeer::FILE, $this->file);
        if ($this->isColumnModified(UserTaskPeer::STATUS)) $criteria->add(UserTaskPeer::STATUS, $this->status);
        if ($this->isColumnModified(UserTaskPeer::MARK)) $criteria->add(UserTaskPeer::MARK, $this->mark);
        if ($this->isColumnModified(UserTaskPeer::CREATED_AT)) $criteria->add(UserTaskPeer::CREATED_AT, $this->created_at);
        if ($this->isColumnModified(UserTaskPeer::UPDATED_AT)) $criteria->add(UserTaskPeer::UPDATED_AT, $this->updated_at);

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
        $criteria = new Criteria(UserTaskPeer::DATABASE_NAME);
        $criteria->add(UserTaskPeer::ID, $this->id);

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
     * @param object $copyObj An object of UserTask (or compatible) type.
     * @param boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @param boolean $makeNew Whether to reset autoincrement PKs and make the object new.
     * @throws PropelException
     */
    public function copyInto($copyObj, $deepCopy = false, $makeNew = true)
    {
        $copyObj->setLessonId($this->getLessonId());
        $copyObj->setTaskId($this->getTaskId());
        $copyObj->setUserId($this->getUserId());
        $copyObj->setText($this->getText());
        $copyObj->setUrl($this->getUrl());
        $copyObj->setFile($this->getFile());
        $copyObj->setStatus($this->getStatus());
        $copyObj->setMark($this->getMark());
        $copyObj->setCreatedAt($this->getCreatedAt());
        $copyObj->setUpdatedAt($this->getUpdatedAt());

        if ($deepCopy && !$this->startCopy) {
            // important: temporarily setNew(false) because this affects the behavior of
            // the getter/setter methods for fkey referrer objects.
            $copyObj->setNew(false);
            // store object hash to prevent cycle
            $this->startCopy = true;

            foreach ($this->getUserTaskReviews() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addUserTaskReview($relObj->copy($deepCopy));
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
     * @return UserTask Clone of current object.
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
     * @return UserTaskPeer
     */
    public function getPeer()
    {
        if (self::$peer === null) {
            self::$peer = new UserTaskPeer();
        }

        return self::$peer;
    }

    /**
     * Declares an association between this object and a Lesson object.
     *
     * @param             Lesson $v
     * @return UserTask The current object (for fluent API support)
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
            $v->addUserTask($this);
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
                $this->aLesson->addUserTasks($this);
             */
        }

        return $this->aLesson;
    }

    /**
     * Declares an association between this object and a User object.
     *
     * @param             User $v
     * @return UserTask The current object (for fluent API support)
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
            $v->addUserTask($this);
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
                $this->aUser->addUserTasks($this);
             */
        }

        return $this->aUser;
    }

    /**
     * Declares an association between this object and a Task object.
     *
     * @param             Task $v
     * @return UserTask The current object (for fluent API support)
     * @throws PropelException
     */
    public function setTask(Task $v = null)
    {
        if ($v === null) {
            $this->setTaskId(NULL);
        } else {
            $this->setTaskId($v->getId());
        }

        $this->aTask = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the Task object, it will not be re-added.
        if ($v !== null) {
            $v->addUserTask($this);
        }


        return $this;
    }


    /**
     * Get the associated Task object
     *
     * @param PropelPDO $con Optional Connection object.
     * @return Task The associated Task object.
     * @throws PropelException
     */
    public function getTask(PropelPDO $con = null)
    {
        if ($this->aTask === null && ($this->task_id !== null)) {
            $this->aTask = TaskQuery::create()->findPk($this->task_id, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aTask->addUserTasks($this);
             */
        }

        return $this->aTask;
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
        if ('UserTaskReview' == $relationName) {
            $this->initUserTaskReviews();
        }
    }

    /**
     * Clears out the collUserTaskReviews collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addUserTaskReviews()
     */
    public function clearUserTaskReviews()
    {
        $this->collUserTaskReviews = null; // important to set this to null since that means it is uninitialized
        $this->collUserTaskReviewsPartial = null;
    }

    /**
     * reset is the collUserTaskReviews collection loaded partially
     *
     * @return void
     */
    public function resetPartialUserTaskReviews($v = true)
    {
        $this->collUserTaskReviewsPartial = $v;
    }

    /**
     * Initializes the collUserTaskReviews collection.
     *
     * By default this just sets the collUserTaskReviews collection to an empty array (like clearcollUserTaskReviews());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initUserTaskReviews($overrideExisting = true)
    {
        if (null !== $this->collUserTaskReviews && !$overrideExisting) {
            return;
        }
        $this->collUserTaskReviews = new PropelObjectCollection();
        $this->collUserTaskReviews->setModel('UserTaskReview');
    }

    /**
     * Gets an array of UserTaskReview objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this UserTask is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|UserTaskReview[] List of UserTaskReview objects
     * @throws PropelException
     */
    public function getUserTaskReviews($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collUserTaskReviewsPartial && !$this->isNew();
        if (null === $this->collUserTaskReviews || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collUserTaskReviews) {
                // return empty collection
                $this->initUserTaskReviews();
            } else {
                $collUserTaskReviews = UserTaskReviewQuery::create(null, $criteria)
                    ->filterByUserTask($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collUserTaskReviewsPartial && count($collUserTaskReviews)) {
                      $this->initUserTaskReviews(false);

                      foreach($collUserTaskReviews as $obj) {
                        if (false == $this->collUserTaskReviews->contains($obj)) {
                          $this->collUserTaskReviews->append($obj);
                        }
                      }

                      $this->collUserTaskReviewsPartial = true;
                    }

                    return $collUserTaskReviews;
                }

                if($partial && $this->collUserTaskReviews) {
                    foreach($this->collUserTaskReviews as $obj) {
                        if($obj->isNew()) {
                            $collUserTaskReviews[] = $obj;
                        }
                    }
                }

                $this->collUserTaskReviews = $collUserTaskReviews;
                $this->collUserTaskReviewsPartial = false;
            }
        }

        return $this->collUserTaskReviews;
    }

    /**
     * Sets a collection of UserTaskReview objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $userTaskReviews A Propel collection.
     * @param PropelPDO $con Optional connection object
     */
    public function setUserTaskReviews(PropelCollection $userTaskReviews, PropelPDO $con = null)
    {
        $this->userTaskReviewsScheduledForDeletion = $this->getUserTaskReviews(new Criteria(), $con)->diff($userTaskReviews);

        foreach ($this->userTaskReviewsScheduledForDeletion as $userTaskReviewRemoved) {
            $userTaskReviewRemoved->setUserTask(null);
        }

        $this->collUserTaskReviews = null;
        foreach ($userTaskReviews as $userTaskReview) {
            $this->addUserTaskReview($userTaskReview);
        }

        $this->collUserTaskReviews = $userTaskReviews;
        $this->collUserTaskReviewsPartial = false;
    }

    /**
     * Returns the number of related UserTaskReview objects.
     *
     * @param Criteria $criteria
     * @param boolean $distinct
     * @param PropelPDO $con
     * @return int             Count of related UserTaskReview objects.
     * @throws PropelException
     */
    public function countUserTaskReviews(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collUserTaskReviewsPartial && !$this->isNew();
        if (null === $this->collUserTaskReviews || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collUserTaskReviews) {
                return 0;
            } else {
                if($partial && !$criteria) {
                    return count($this->getUserTaskReviews());
                }
                $query = UserTaskReviewQuery::create(null, $criteria);
                if ($distinct) {
                    $query->distinct();
                }

                return $query
                    ->filterByUserTask($this)
                    ->count($con);
            }
        } else {
            return count($this->collUserTaskReviews);
        }
    }

    /**
     * Method called to associate a UserTaskReview object to this object
     * through the UserTaskReview foreign key attribute.
     *
     * @param    UserTaskReview $l UserTaskReview
     * @return UserTask The current object (for fluent API support)
     */
    public function addUserTaskReview(UserTaskReview $l)
    {
        if ($this->collUserTaskReviews === null) {
            $this->initUserTaskReviews();
            $this->collUserTaskReviewsPartial = true;
        }
        if (!in_array($l, $this->collUserTaskReviews->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddUserTaskReview($l);
        }

        return $this;
    }

    /**
     * @param	UserTaskReview $userTaskReview The userTaskReview object to add.
     */
    protected function doAddUserTaskReview($userTaskReview)
    {
        $this->collUserTaskReviews[]= $userTaskReview;
        $userTaskReview->setUserTask($this);
    }

    /**
     * @param	UserTaskReview $userTaskReview The userTaskReview object to remove.
     */
    public function removeUserTaskReview($userTaskReview)
    {
        if ($this->getUserTaskReviews()->contains($userTaskReview)) {
            $this->collUserTaskReviews->remove($this->collUserTaskReviews->search($userTaskReview));
            if (null === $this->userTaskReviewsScheduledForDeletion) {
                $this->userTaskReviewsScheduledForDeletion = clone $this->collUserTaskReviews;
                $this->userTaskReviewsScheduledForDeletion->clear();
            }
            $this->userTaskReviewsScheduledForDeletion[]= $userTaskReview;
            $userTaskReview->setUserTask(null);
        }
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this UserTask is new, it will return
     * an empty collection; or if this UserTask has previously
     * been saved, it will retrieve related UserTaskReviews from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in UserTask.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|UserTaskReview[] List of UserTaskReview objects
     */
    public function getUserTaskReviewsJoinUser($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = UserTaskReviewQuery::create(null, $criteria);
        $query->joinWith('User', $join_behavior);

        return $this->getUserTaskReviews($query, $con);
    }

    /**
     * Clears the current object and sets all attributes to their default values
     */
    public function clear()
    {
        $this->id = null;
        $this->lesson_id = null;
        $this->task_id = null;
        $this->user_id = null;
        $this->text = null;
        $this->url = null;
        $this->file = null;
        $this->status = null;
        $this->mark = null;
        $this->created_at = null;
        $this->updated_at = null;
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
            if ($this->collUserTaskReviews) {
                foreach ($this->collUserTaskReviews as $o) {
                    $o->clearAllReferences($deep);
                }
            }
        } // if ($deep)

        if ($this->collUserTaskReviews instanceof PropelCollection) {
            $this->collUserTaskReviews->clearIterator();
        }
        $this->collUserTaskReviews = null;
        $this->aLesson = null;
        $this->aUser = null;
        $this->aTask = null;
    }

    /**
     * return the string representation of this object
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->exportTo(UserTaskPeer::DEFAULT_STRING_FORMAT);
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
     * @return     UserTask The current object (for fluent API support)
     */
    public function keepUpdateDateUnchanged()
    {
        $this->modifiedColumns[] = UserTaskPeer::UPDATED_AT;

        return $this;
    }

}
