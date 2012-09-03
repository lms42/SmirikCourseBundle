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
use \PropelDateTime;
use \PropelException;
use \PropelPDO;
use FOS\UserBundle\Propel\User;
use FOS\UserBundle\Propel\UserQuery;
use Smirik\CourseBundle\Model\Course;
use Smirik\CourseBundle\Model\CourseQuery;
use Smirik\CourseBundle\Model\Lesson;
use Smirik\CourseBundle\Model\LessonQuery;
use Smirik\CourseBundle\Model\UserLesson;
use Smirik\CourseBundle\Model\UserLessonPeer;
use Smirik\CourseBundle\Model\UserLessonQuery;

abstract class BaseUserLesson extends BaseObject implements Persistent
{
    /**
     * Peer class name
     */
    const PEER = 'Smirik\\CourseBundle\\Model\\UserLessonPeer';

    /**
     * The Peer class.
     * Instance provides a convenient way of calling static methods on a class
     * that calling code may not be able to identify.
     * @var        UserLessonPeer
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
     * The value for the course_id field.
     * @var        int
     */
    protected $course_id;

    /**
     * The value for the lesson_id field.
     * @var        int
     */
    protected $lesson_id;

    /**
     * The value for the started_at field.
     * @var        string
     */
    protected $started_at;

    /**
     * The value for the stopped_at field.
     * @var        string
     */
    protected $stopped_at;

    /**
     * The value for the is_passed field.
     * Note: this column has a database default value of: false
     * @var        boolean
     */
    protected $is_passed;

    /**
     * The value for the is_closed field.
     * Note: this column has a database default value of: false
     * @var        boolean
     */
    protected $is_closed;

    /**
     * The value for the updated_at field.
     * @var        string
     */
    protected $updated_at;

    /**
     * @var        User
     */
    protected $aUser;

    /**
     * @var        Lesson
     */
    protected $aLesson;

    /**
     * @var        Course
     */
    protected $aCourse;

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
     * Applies default values to this object.
     * This method should be called from the object's constructor (or
     * equivalent initialization method).
     * @see        __construct()
     */
    public function applyDefaultValues()
    {
        $this->is_passed = false;
        $this->is_closed = false;
    }

    /**
     * Initializes internal state of BaseUserLesson object.
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
     * Get the [course_id] column value.
     *
     * @return int
     */
    public function getCourseId()
    {
        return $this->course_id;
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
     * Get the [optionally formatted] temporal [started_at] column value.
     *
     *
     * @param string $format The date/time format string (either date()-style or strftime()-style).
     *				 If format is null, then the raw DateTime object will be returned.
     * @return mixed Formatted date/time value as string or DateTime object (if format is null), null if column is null, and 0 if column value is 0000-00-00 00:00:00
     * @throws PropelException - if unable to parse/validate the date/time value.
     */
    public function getStartedAt($format = null)
    {
        if ($this->started_at === null) {
            return null;
        }

        if ($this->started_at === '0000-00-00 00:00:00') {
            // while technically this is not a default value of null,
            // this seems to be closest in meaning.
            return null;
        } else {
            try {
                $dt = new DateTime($this->started_at);
            } catch (Exception $x) {
                throw new PropelException("Internally stored date/time/timestamp value could not be converted to DateTime: " . var_export($this->started_at, true), $x);
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
     * Get the [optionally formatted] temporal [stopped_at] column value.
     *
     *
     * @param string $format The date/time format string (either date()-style or strftime()-style).
     *				 If format is null, then the raw DateTime object will be returned.
     * @return mixed Formatted date/time value as string or DateTime object (if format is null), null if column is null, and 0 if column value is 0000-00-00 00:00:00
     * @throws PropelException - if unable to parse/validate the date/time value.
     */
    public function getStoppedAt($format = null)
    {
        if ($this->stopped_at === null) {
            return null;
        }

        if ($this->stopped_at === '0000-00-00 00:00:00') {
            // while technically this is not a default value of null,
            // this seems to be closest in meaning.
            return null;
        } else {
            try {
                $dt = new DateTime($this->stopped_at);
            } catch (Exception $x) {
                throw new PropelException("Internally stored date/time/timestamp value could not be converted to DateTime: " . var_export($this->stopped_at, true), $x);
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
     * Get the [is_passed] column value.
     *
     * @return boolean
     */
    public function getIsPassed()
    {
        return $this->is_passed;
    }

    /**
     * Get the [is_closed] column value.
     *
     * @return boolean
     */
    public function getIsClosed()
    {
        return $this->is_closed;
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
     * @return UserLesson The current object (for fluent API support)
     */
    public function setId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->id !== $v) {
            $this->id = $v;
            $this->modifiedColumns[] = UserLessonPeer::ID;
        }


        return $this;
    } // setId()

    /**
     * Set the value of [user_id] column.
     *
     * @param int $v new value
     * @return UserLesson The current object (for fluent API support)
     */
    public function setUserId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->user_id !== $v) {
            $this->user_id = $v;
            $this->modifiedColumns[] = UserLessonPeer::USER_ID;
        }

        if ($this->aUser !== null && $this->aUser->getId() !== $v) {
            $this->aUser = null;
        }


        return $this;
    } // setUserId()

    /**
     * Set the value of [course_id] column.
     *
     * @param int $v new value
     * @return UserLesson The current object (for fluent API support)
     */
    public function setCourseId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->course_id !== $v) {
            $this->course_id = $v;
            $this->modifiedColumns[] = UserLessonPeer::COURSE_ID;
        }

        if ($this->aCourse !== null && $this->aCourse->getId() !== $v) {
            $this->aCourse = null;
        }


        return $this;
    } // setCourseId()

    /**
     * Set the value of [lesson_id] column.
     *
     * @param int $v new value
     * @return UserLesson The current object (for fluent API support)
     */
    public function setLessonId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->lesson_id !== $v) {
            $this->lesson_id = $v;
            $this->modifiedColumns[] = UserLessonPeer::LESSON_ID;
        }

        if ($this->aLesson !== null && $this->aLesson->getId() !== $v) {
            $this->aLesson = null;
        }


        return $this;
    } // setLessonId()

    /**
     * Sets the value of [started_at] column to a normalized version of the date/time value specified.
     *
     * @param mixed $v string, integer (timestamp), or DateTime value.
     *               Empty strings are treated as null.
     * @return UserLesson The current object (for fluent API support)
     */
    public function setStartedAt($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->started_at !== null || $dt !== null) {
            $currentDateAsString = ($this->started_at !== null && $tmpDt = new DateTime($this->started_at)) ? $tmpDt->format('Y-m-d H:i:s') : null;
            $newDateAsString = $dt ? $dt->format('Y-m-d H:i:s') : null;
            if ($currentDateAsString !== $newDateAsString) {
                $this->started_at = $newDateAsString;
                $this->modifiedColumns[] = UserLessonPeer::STARTED_AT;
            }
        } // if either are not null


        return $this;
    } // setStartedAt()

    /**
     * Sets the value of [stopped_at] column to a normalized version of the date/time value specified.
     *
     * @param mixed $v string, integer (timestamp), or DateTime value.
     *               Empty strings are treated as null.
     * @return UserLesson The current object (for fluent API support)
     */
    public function setStoppedAt($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->stopped_at !== null || $dt !== null) {
            $currentDateAsString = ($this->stopped_at !== null && $tmpDt = new DateTime($this->stopped_at)) ? $tmpDt->format('Y-m-d H:i:s') : null;
            $newDateAsString = $dt ? $dt->format('Y-m-d H:i:s') : null;
            if ($currentDateAsString !== $newDateAsString) {
                $this->stopped_at = $newDateAsString;
                $this->modifiedColumns[] = UserLessonPeer::STOPPED_AT;
            }
        } // if either are not null


        return $this;
    } // setStoppedAt()

    /**
     * Sets the value of the [is_passed] column.
     * Non-boolean arguments are converted using the following rules:
     *   * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *   * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     * Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     *
     * @param boolean|integer|string $v The new value
     * @return UserLesson The current object (for fluent API support)
     */
    public function setIsPassed($v)
    {
        if ($v !== null) {
            if (is_string($v)) {
                $v = in_array(strtolower($v), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
            } else {
                $v = (boolean) $v;
            }
        }

        if ($this->is_passed !== $v) {
            $this->is_passed = $v;
            $this->modifiedColumns[] = UserLessonPeer::IS_PASSED;
        }


        return $this;
    } // setIsPassed()

    /**
     * Sets the value of the [is_closed] column.
     * Non-boolean arguments are converted using the following rules:
     *   * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *   * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     * Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     *
     * @param boolean|integer|string $v The new value
     * @return UserLesson The current object (for fluent API support)
     */
    public function setIsClosed($v)
    {
        if ($v !== null) {
            if (is_string($v)) {
                $v = in_array(strtolower($v), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
            } else {
                $v = (boolean) $v;
            }
        }

        if ($this->is_closed !== $v) {
            $this->is_closed = $v;
            $this->modifiedColumns[] = UserLessonPeer::IS_CLOSED;
        }


        return $this;
    } // setIsClosed()

    /**
     * Sets the value of [updated_at] column to a normalized version of the date/time value specified.
     *
     * @param mixed $v string, integer (timestamp), or DateTime value.
     *               Empty strings are treated as null.
     * @return UserLesson The current object (for fluent API support)
     */
    public function setUpdatedAt($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->updated_at !== null || $dt !== null) {
            $currentDateAsString = ($this->updated_at !== null && $tmpDt = new DateTime($this->updated_at)) ? $tmpDt->format('Y-m-d H:i:s') : null;
            $newDateAsString = $dt ? $dt->format('Y-m-d H:i:s') : null;
            if ($currentDateAsString !== $newDateAsString) {
                $this->updated_at = $newDateAsString;
                $this->modifiedColumns[] = UserLessonPeer::UPDATED_AT;
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
            if ($this->is_passed !== false) {
                return false;
            }

            if ($this->is_closed !== false) {
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
            $this->course_id = ($row[$startcol + 2] !== null) ? (int) $row[$startcol + 2] : null;
            $this->lesson_id = ($row[$startcol + 3] !== null) ? (int) $row[$startcol + 3] : null;
            $this->started_at = ($row[$startcol + 4] !== null) ? (string) $row[$startcol + 4] : null;
            $this->stopped_at = ($row[$startcol + 5] !== null) ? (string) $row[$startcol + 5] : null;
            $this->is_passed = ($row[$startcol + 6] !== null) ? (boolean) $row[$startcol + 6] : null;
            $this->is_closed = ($row[$startcol + 7] !== null) ? (boolean) $row[$startcol + 7] : null;
            $this->updated_at = ($row[$startcol + 8] !== null) ? (string) $row[$startcol + 8] : null;
            $this->resetModified();

            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }

            return $startcol + 9; // 9 = UserLessonPeer::NUM_HYDRATE_COLUMNS.

        } catch (Exception $e) {
            throw new PropelException("Error populating UserLesson object", $e);
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
        if ($this->aCourse !== null && $this->course_id !== $this->aCourse->getId()) {
            $this->aCourse = null;
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
            $con = Propel::getConnection(UserLessonPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        // We don't need to alter the object instance pool; we're just modifying this instance
        // already in the pool.

        $stmt = UserLessonPeer::doSelectStmt($this->buildPkeyCriteria(), $con);
        $row = $stmt->fetch(PDO::FETCH_NUM);
        $stmt->closeCursor();
        if (!$row) {
            throw new PropelException('Cannot find matching row in the database to reload object values.');
        }
        $this->hydrate($row, 0, true); // rehydrate

        if ($deep) {  // also de-associate any related objects?

            $this->aUser = null;
            $this->aLesson = null;
            $this->aCourse = null;
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
            $con = Propel::getConnection(UserLessonPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        $con->beginTransaction();
        try {
            $deleteQuery = UserLessonQuery::create()
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
            $con = Propel::getConnection(UserLessonPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        $con->beginTransaction();
        $isInsert = $this->isNew();
        try {
            $ret = $this->preSave($con);
            if ($isInsert) {
                $ret = $ret && $this->preInsert($con);
                // timestampable behavior
                if (!$this->isColumnModified(UserLessonPeer::STARTED_AT)) {
                    $this->setStartedAt(time());
                }
                if (!$this->isColumnModified(UserLessonPeer::UPDATED_AT)) {
                    $this->setUpdatedAt(time());
                }
            } else {
                $ret = $ret && $this->preUpdate($con);
                // timestampable behavior
                if ($this->isModified() && !$this->isColumnModified(UserLessonPeer::UPDATED_AT)) {
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
                UserLessonPeer::addInstanceToPool($this);
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

        $this->modifiedColumns[] = UserLessonPeer::ID;
        if (null !== $this->id) {
            throw new PropelException('Cannot insert a value for auto-increment primary key (' . UserLessonPeer::ID . ')');
        }

         // check the columns in natural order for more readable SQL queries
        if ($this->isColumnModified(UserLessonPeer::ID)) {
            $modifiedColumns[':p' . $index++]  = '`ID`';
        }
        if ($this->isColumnModified(UserLessonPeer::USER_ID)) {
            $modifiedColumns[':p' . $index++]  = '`USER_ID`';
        }
        if ($this->isColumnModified(UserLessonPeer::COURSE_ID)) {
            $modifiedColumns[':p' . $index++]  = '`COURSE_ID`';
        }
        if ($this->isColumnModified(UserLessonPeer::LESSON_ID)) {
            $modifiedColumns[':p' . $index++]  = '`LESSON_ID`';
        }
        if ($this->isColumnModified(UserLessonPeer::STARTED_AT)) {
            $modifiedColumns[':p' . $index++]  = '`STARTED_AT`';
        }
        if ($this->isColumnModified(UserLessonPeer::STOPPED_AT)) {
            $modifiedColumns[':p' . $index++]  = '`STOPPED_AT`';
        }
        if ($this->isColumnModified(UserLessonPeer::IS_PASSED)) {
            $modifiedColumns[':p' . $index++]  = '`IS_PASSED`';
        }
        if ($this->isColumnModified(UserLessonPeer::IS_CLOSED)) {
            $modifiedColumns[':p' . $index++]  = '`IS_CLOSED`';
        }
        if ($this->isColumnModified(UserLessonPeer::UPDATED_AT)) {
            $modifiedColumns[':p' . $index++]  = '`UPDATED_AT`';
        }

        $sql = sprintf(
            'INSERT INTO `users_lessons` (%s) VALUES (%s)',
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
                    case '`COURSE_ID`':
                        $stmt->bindValue($identifier, $this->course_id, PDO::PARAM_INT);
                        break;
                    case '`LESSON_ID`':
                        $stmt->bindValue($identifier, $this->lesson_id, PDO::PARAM_INT);
                        break;
                    case '`STARTED_AT`':
                        $stmt->bindValue($identifier, $this->started_at, PDO::PARAM_STR);
                        break;
                    case '`STOPPED_AT`':
                        $stmt->bindValue($identifier, $this->stopped_at, PDO::PARAM_STR);
                        break;
                    case '`IS_PASSED`':
                        $stmt->bindValue($identifier, (int) $this->is_passed, PDO::PARAM_INT);
                        break;
                    case '`IS_CLOSED`':
                        $stmt->bindValue($identifier, (int) $this->is_closed, PDO::PARAM_INT);
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

            if ($this->aCourse !== null) {
                if (!$this->aCourse->validate($columns)) {
                    $failureMap = array_merge($failureMap, $this->aCourse->getValidationFailures());
                }
            }


            if (($retval = UserLessonPeer::doValidate($this, $columns)) !== true) {
                $failureMap = array_merge($failureMap, $retval);
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
        $pos = UserLessonPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
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
                return $this->getCourseId();
                break;
            case 3:
                return $this->getLessonId();
                break;
            case 4:
                return $this->getStartedAt();
                break;
            case 5:
                return $this->getStoppedAt();
                break;
            case 6:
                return $this->getIsPassed();
                break;
            case 7:
                return $this->getIsClosed();
                break;
            case 8:
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
        if (isset($alreadyDumpedObjects['UserLesson'][$this->getPrimaryKey()])) {
            return '*RECURSION*';
        }
        $alreadyDumpedObjects['UserLesson'][$this->getPrimaryKey()] = true;
        $keys = UserLessonPeer::getFieldNames($keyType);
        $result = array(
            $keys[0] => $this->getId(),
            $keys[1] => $this->getUserId(),
            $keys[2] => $this->getCourseId(),
            $keys[3] => $this->getLessonId(),
            $keys[4] => $this->getStartedAt(),
            $keys[5] => $this->getStoppedAt(),
            $keys[6] => $this->getIsPassed(),
            $keys[7] => $this->getIsClosed(),
            $keys[8] => $this->getUpdatedAt(),
        );
        if ($includeForeignObjects) {
            if (null !== $this->aUser) {
                $result['User'] = $this->aUser->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->aLesson) {
                $result['Lesson'] = $this->aLesson->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->aCourse) {
                $result['Course'] = $this->aCourse->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
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
        $pos = UserLessonPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);

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
                $this->setCourseId($value);
                break;
            case 3:
                $this->setLessonId($value);
                break;
            case 4:
                $this->setStartedAt($value);
                break;
            case 5:
                $this->setStoppedAt($value);
                break;
            case 6:
                $this->setIsPassed($value);
                break;
            case 7:
                $this->setIsClosed($value);
                break;
            case 8:
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
        $keys = UserLessonPeer::getFieldNames($keyType);

        if (array_key_exists($keys[0], $arr)) $this->setId($arr[$keys[0]]);
        if (array_key_exists($keys[1], $arr)) $this->setUserId($arr[$keys[1]]);
        if (array_key_exists($keys[2], $arr)) $this->setCourseId($arr[$keys[2]]);
        if (array_key_exists($keys[3], $arr)) $this->setLessonId($arr[$keys[3]]);
        if (array_key_exists($keys[4], $arr)) $this->setStartedAt($arr[$keys[4]]);
        if (array_key_exists($keys[5], $arr)) $this->setStoppedAt($arr[$keys[5]]);
        if (array_key_exists($keys[6], $arr)) $this->setIsPassed($arr[$keys[6]]);
        if (array_key_exists($keys[7], $arr)) $this->setIsClosed($arr[$keys[7]]);
        if (array_key_exists($keys[8], $arr)) $this->setUpdatedAt($arr[$keys[8]]);
    }

    /**
     * Build a Criteria object containing the values of all modified columns in this object.
     *
     * @return Criteria The Criteria object containing all modified values.
     */
    public function buildCriteria()
    {
        $criteria = new Criteria(UserLessonPeer::DATABASE_NAME);

        if ($this->isColumnModified(UserLessonPeer::ID)) $criteria->add(UserLessonPeer::ID, $this->id);
        if ($this->isColumnModified(UserLessonPeer::USER_ID)) $criteria->add(UserLessonPeer::USER_ID, $this->user_id);
        if ($this->isColumnModified(UserLessonPeer::COURSE_ID)) $criteria->add(UserLessonPeer::COURSE_ID, $this->course_id);
        if ($this->isColumnModified(UserLessonPeer::LESSON_ID)) $criteria->add(UserLessonPeer::LESSON_ID, $this->lesson_id);
        if ($this->isColumnModified(UserLessonPeer::STARTED_AT)) $criteria->add(UserLessonPeer::STARTED_AT, $this->started_at);
        if ($this->isColumnModified(UserLessonPeer::STOPPED_AT)) $criteria->add(UserLessonPeer::STOPPED_AT, $this->stopped_at);
        if ($this->isColumnModified(UserLessonPeer::IS_PASSED)) $criteria->add(UserLessonPeer::IS_PASSED, $this->is_passed);
        if ($this->isColumnModified(UserLessonPeer::IS_CLOSED)) $criteria->add(UserLessonPeer::IS_CLOSED, $this->is_closed);
        if ($this->isColumnModified(UserLessonPeer::UPDATED_AT)) $criteria->add(UserLessonPeer::UPDATED_AT, $this->updated_at);

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
        $criteria = new Criteria(UserLessonPeer::DATABASE_NAME);
        $criteria->add(UserLessonPeer::ID, $this->id);

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
     * @param object $copyObj An object of UserLesson (or compatible) type.
     * @param boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @param boolean $makeNew Whether to reset autoincrement PKs and make the object new.
     * @throws PropelException
     */
    public function copyInto($copyObj, $deepCopy = false, $makeNew = true)
    {
        $copyObj->setUserId($this->getUserId());
        $copyObj->setCourseId($this->getCourseId());
        $copyObj->setLessonId($this->getLessonId());
        $copyObj->setStartedAt($this->getStartedAt());
        $copyObj->setStoppedAt($this->getStoppedAt());
        $copyObj->setIsPassed($this->getIsPassed());
        $copyObj->setIsClosed($this->getIsClosed());
        $copyObj->setUpdatedAt($this->getUpdatedAt());

        if ($deepCopy && !$this->startCopy) {
            // important: temporarily setNew(false) because this affects the behavior of
            // the getter/setter methods for fkey referrer objects.
            $copyObj->setNew(false);
            // store object hash to prevent cycle
            $this->startCopy = true;

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
     * @return UserLesson Clone of current object.
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
     * @return UserLessonPeer
     */
    public function getPeer()
    {
        if (self::$peer === null) {
            self::$peer = new UserLessonPeer();
        }

        return self::$peer;
    }

    /**
     * Declares an association between this object and a User object.
     *
     * @param             User $v
     * @return UserLesson The current object (for fluent API support)
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
            $v->addUserLesson($this);
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
                $this->aUser->addUserLessons($this);
             */
        }

        return $this->aUser;
    }

    /**
     * Declares an association between this object and a Lesson object.
     *
     * @param             Lesson $v
     * @return UserLesson The current object (for fluent API support)
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
            $v->addUserLesson($this);
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
                $this->aLesson->addUserLessons($this);
             */
        }

        return $this->aLesson;
    }

    /**
     * Declares an association between this object and a Course object.
     *
     * @param             Course $v
     * @return UserLesson The current object (for fluent API support)
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
            $v->addUserLesson($this);
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
                $this->aCourse->addUserLessons($this);
             */
        }

        return $this->aCourse;
    }

    /**
     * Clears the current object and sets all attributes to their default values
     */
    public function clear()
    {
        $this->id = null;
        $this->user_id = null;
        $this->course_id = null;
        $this->lesson_id = null;
        $this->started_at = null;
        $this->stopped_at = null;
        $this->is_passed = null;
        $this->is_closed = null;
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
        } // if ($deep)

        $this->aUser = null;
        $this->aLesson = null;
        $this->aCourse = null;
    }

    /**
     * return the string representation of this object
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->exportTo(UserLessonPeer::DEFAULT_STRING_FORMAT);
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
     * @return     UserLesson The current object (for fluent API support)
     */
    public function keepUpdateDateUnchanged()
    {
        $this->modifiedColumns[] = UserLessonPeer::UPDATED_AT;

        return $this;
    }

}
