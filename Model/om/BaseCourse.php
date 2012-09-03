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
use Smirik\CourseBundle\Model\Course;
use Smirik\CourseBundle\Model\CoursePeer;
use Smirik\CourseBundle\Model\CourseQuery;
use Smirik\CourseBundle\Model\Lesson;
use Smirik\CourseBundle\Model\LessonQuery;
use Smirik\CourseBundle\Model\UserCourse;
use Smirik\CourseBundle\Model\UserCourseQuery;
use Smirik\CourseBundle\Model\UserLesson;
use Smirik\CourseBundle\Model\UserLessonQuery;

abstract class BaseCourse extends BaseObject implements Persistent
{
    /**
     * Peer class name
     */
    const PEER = 'Smirik\\CourseBundle\\Model\\CoursePeer';

    /**
     * The Peer class.
     * Instance provides a convenient way of calling static methods on a class
     * that calling code may not be able to identify.
     * @var        CoursePeer
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
     * The value for the pid field.
     * @var        int
     */
    protected $pid;

    /**
     * The value for the title field.
     * @var        string
     */
    protected $title;

    /**
     * The value for the description field.
     * @var        string
     */
    protected $description;

    /**
     * The value for the type field.
     * Note: this column has a database default value of: 1
     * @var        int
     */
    protected $type;

    /**
     * The value for the file field.
     * @var        string
     */
    protected $file;

    /**
     * The value for the is_public field.
     * @var        boolean
     */
    protected $is_public;

    /**
     * The value for the is_active field.
     * @var        boolean
     */
    protected $is_active;

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
     * @var        Course
     */
    protected $aCourseRelatedByPid;

    /**
     * @var        PropelObjectCollection|Course[] Collection to store aggregation of Course objects.
     */
    protected $collCoursesRelatedById;
    protected $collCoursesRelatedByIdPartial;

    /**
     * @var        PropelObjectCollection|Lesson[] Collection to store aggregation of Lesson objects.
     */
    protected $collLessons;
    protected $collLessonsPartial;

    /**
     * @var        PropelObjectCollection|UserCourse[] Collection to store aggregation of UserCourse objects.
     */
    protected $collUserCourses;
    protected $collUserCoursesPartial;

    /**
     * @var        PropelObjectCollection|UserLesson[] Collection to store aggregation of UserLesson objects.
     */
    protected $collUserLessons;
    protected $collUserLessonsPartial;

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
    protected $coursesRelatedByIdScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $lessonsScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $userCoursesScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $userLessonsScheduledForDeletion = null;

    /**
     * Applies default values to this object.
     * This method should be called from the object's constructor (or
     * equivalent initialization method).
     * @see        __construct()
     */
    public function applyDefaultValues()
    {
        $this->type = 1;
    }

    /**
     * Initializes internal state of BaseCourse object.
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
     * Get the [pid] column value.
     *
     * @return int
     */
    public function getPid()
    {
        return $this->pid;
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
     * Get the [description] column value.
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Get the [type] column value.
     *
     * @return int
     */
    public function getType()
    {
        return $this->type;
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
     * Get the [is_public] column value.
     *
     * @return boolean
     */
    public function getIsPublic()
    {
        return $this->is_public;
    }

    /**
     * Get the [is_active] column value.
     *
     * @return boolean
     */
    public function getIsActive()
    {
        return $this->is_active;
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
     * @return Course The current object (for fluent API support)
     */
    public function setId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->id !== $v) {
            $this->id = $v;
            $this->modifiedColumns[] = CoursePeer::ID;
        }


        return $this;
    } // setId()

    /**
     * Set the value of [pid] column.
     *
     * @param int $v new value
     * @return Course The current object (for fluent API support)
     */
    public function setPid($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->pid !== $v) {
            $this->pid = $v;
            $this->modifiedColumns[] = CoursePeer::PID;
        }

        if ($this->aCourseRelatedByPid !== null && $this->aCourseRelatedByPid->getId() !== $v) {
            $this->aCourseRelatedByPid = null;
        }


        return $this;
    } // setPid()

    /**
     * Set the value of [title] column.
     *
     * @param string $v new value
     * @return Course The current object (for fluent API support)
     */
    public function setTitle($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->title !== $v) {
            $this->title = $v;
            $this->modifiedColumns[] = CoursePeer::TITLE;
        }


        return $this;
    } // setTitle()

    /**
     * Set the value of [description] column.
     *
     * @param string $v new value
     * @return Course The current object (for fluent API support)
     */
    public function setDescription($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->description !== $v) {
            $this->description = $v;
            $this->modifiedColumns[] = CoursePeer::DESCRIPTION;
        }


        return $this;
    } // setDescription()

    /**
     * Set the value of [type] column.
     *
     * @param int $v new value
     * @return Course The current object (for fluent API support)
     */
    public function setType($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->type !== $v) {
            $this->type = $v;
            $this->modifiedColumns[] = CoursePeer::TYPE;
        }


        return $this;
    } // setType()

    /**
     * Set the value of [file] column.
     *
     * @param string $v new value
     * @return Course The current object (for fluent API support)
     */
    public function setFile($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->file !== $v) {
            $this->file = $v;
            $this->modifiedColumns[] = CoursePeer::FILE;
        }


        return $this;
    } // setFile()

    /**
     * Sets the value of the [is_public] column.
     * Non-boolean arguments are converted using the following rules:
     *   * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *   * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     * Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     *
     * @param boolean|integer|string $v The new value
     * @return Course The current object (for fluent API support)
     */
    public function setIsPublic($v)
    {
        if ($v !== null) {
            if (is_string($v)) {
                $v = in_array(strtolower($v), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
            } else {
                $v = (boolean) $v;
            }
        }

        if ($this->is_public !== $v) {
            $this->is_public = $v;
            $this->modifiedColumns[] = CoursePeer::IS_PUBLIC;
        }


        return $this;
    } // setIsPublic()

    /**
     * Sets the value of the [is_active] column.
     * Non-boolean arguments are converted using the following rules:
     *   * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *   * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     * Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     *
     * @param boolean|integer|string $v The new value
     * @return Course The current object (for fluent API support)
     */
    public function setIsActive($v)
    {
        if ($v !== null) {
            if (is_string($v)) {
                $v = in_array(strtolower($v), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
            } else {
                $v = (boolean) $v;
            }
        }

        if ($this->is_active !== $v) {
            $this->is_active = $v;
            $this->modifiedColumns[] = CoursePeer::IS_ACTIVE;
        }


        return $this;
    } // setIsActive()

    /**
     * Sets the value of [created_at] column to a normalized version of the date/time value specified.
     *
     * @param mixed $v string, integer (timestamp), or DateTime value.
     *               Empty strings are treated as null.
     * @return Course The current object (for fluent API support)
     */
    public function setCreatedAt($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->created_at !== null || $dt !== null) {
            $currentDateAsString = ($this->created_at !== null && $tmpDt = new DateTime($this->created_at)) ? $tmpDt->format('Y-m-d H:i:s') : null;
            $newDateAsString = $dt ? $dt->format('Y-m-d H:i:s') : null;
            if ($currentDateAsString !== $newDateAsString) {
                $this->created_at = $newDateAsString;
                $this->modifiedColumns[] = CoursePeer::CREATED_AT;
            }
        } // if either are not null


        return $this;
    } // setCreatedAt()

    /**
     * Sets the value of [updated_at] column to a normalized version of the date/time value specified.
     *
     * @param mixed $v string, integer (timestamp), or DateTime value.
     *               Empty strings are treated as null.
     * @return Course The current object (for fluent API support)
     */
    public function setUpdatedAt($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->updated_at !== null || $dt !== null) {
            $currentDateAsString = ($this->updated_at !== null && $tmpDt = new DateTime($this->updated_at)) ? $tmpDt->format('Y-m-d H:i:s') : null;
            $newDateAsString = $dt ? $dt->format('Y-m-d H:i:s') : null;
            if ($currentDateAsString !== $newDateAsString) {
                $this->updated_at = $newDateAsString;
                $this->modifiedColumns[] = CoursePeer::UPDATED_AT;
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
            if ($this->type !== 1) {
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
            $this->pid = ($row[$startcol + 1] !== null) ? (int) $row[$startcol + 1] : null;
            $this->title = ($row[$startcol + 2] !== null) ? (string) $row[$startcol + 2] : null;
            $this->description = ($row[$startcol + 3] !== null) ? (string) $row[$startcol + 3] : null;
            $this->type = ($row[$startcol + 4] !== null) ? (int) $row[$startcol + 4] : null;
            $this->file = ($row[$startcol + 5] !== null) ? (string) $row[$startcol + 5] : null;
            $this->is_public = ($row[$startcol + 6] !== null) ? (boolean) $row[$startcol + 6] : null;
            $this->is_active = ($row[$startcol + 7] !== null) ? (boolean) $row[$startcol + 7] : null;
            $this->created_at = ($row[$startcol + 8] !== null) ? (string) $row[$startcol + 8] : null;
            $this->updated_at = ($row[$startcol + 9] !== null) ? (string) $row[$startcol + 9] : null;
            $this->resetModified();

            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }

            return $startcol + 10; // 10 = CoursePeer::NUM_HYDRATE_COLUMNS.

        } catch (Exception $e) {
            throw new PropelException("Error populating Course object", $e);
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

        if ($this->aCourseRelatedByPid !== null && $this->pid !== $this->aCourseRelatedByPid->getId()) {
            $this->aCourseRelatedByPid = null;
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
            $con = Propel::getConnection(CoursePeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        // We don't need to alter the object instance pool; we're just modifying this instance
        // already in the pool.

        $stmt = CoursePeer::doSelectStmt($this->buildPkeyCriteria(), $con);
        $row = $stmt->fetch(PDO::FETCH_NUM);
        $stmt->closeCursor();
        if (!$row) {
            throw new PropelException('Cannot find matching row in the database to reload object values.');
        }
        $this->hydrate($row, 0, true); // rehydrate

        if ($deep) {  // also de-associate any related objects?

            $this->aCourseRelatedByPid = null;
            $this->collCoursesRelatedById = null;

            $this->collLessons = null;

            $this->collUserCourses = null;

            $this->collUserLessons = null;

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
            $con = Propel::getConnection(CoursePeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        $con->beginTransaction();
        try {
            $deleteQuery = CourseQuery::create()
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
            $con = Propel::getConnection(CoursePeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        $con->beginTransaction();
        $isInsert = $this->isNew();
        try {
            $ret = $this->preSave($con);
            if ($isInsert) {
                $ret = $ret && $this->preInsert($con);
                // timestampable behavior
                if (!$this->isColumnModified(CoursePeer::CREATED_AT)) {
                    $this->setCreatedAt(time());
                }
                if (!$this->isColumnModified(CoursePeer::UPDATED_AT)) {
                    $this->setUpdatedAt(time());
                }
            } else {
                $ret = $ret && $this->preUpdate($con);
                // timestampable behavior
                if ($this->isModified() && !$this->isColumnModified(CoursePeer::UPDATED_AT)) {
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
                CoursePeer::addInstanceToPool($this);
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

            if ($this->aCourseRelatedByPid !== null) {
                if ($this->aCourseRelatedByPid->isModified() || $this->aCourseRelatedByPid->isNew()) {
                    $affectedRows += $this->aCourseRelatedByPid->save($con);
                }
                $this->setCourseRelatedByPid($this->aCourseRelatedByPid);
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

            if ($this->coursesRelatedByIdScheduledForDeletion !== null) {
                if (!$this->coursesRelatedByIdScheduledForDeletion->isEmpty()) {
                    foreach ($this->coursesRelatedByIdScheduledForDeletion as $courseRelatedById) {
                        // need to save related object because we set the relation to null
                        $courseRelatedById->save($con);
                    }
                    $this->coursesRelatedByIdScheduledForDeletion = null;
                }
            }

            if ($this->collCoursesRelatedById !== null) {
                foreach ($this->collCoursesRelatedById as $referrerFK) {
                    if (!$referrerFK->isDeleted()) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->lessonsScheduledForDeletion !== null) {
                if (!$this->lessonsScheduledForDeletion->isEmpty()) {
                    foreach ($this->lessonsScheduledForDeletion as $lesson) {
                        // need to save related object because we set the relation to null
                        $lesson->save($con);
                    }
                    $this->lessonsScheduledForDeletion = null;
                }
            }

            if ($this->collLessons !== null) {
                foreach ($this->collLessons as $referrerFK) {
                    if (!$referrerFK->isDeleted()) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->userCoursesScheduledForDeletion !== null) {
                if (!$this->userCoursesScheduledForDeletion->isEmpty()) {
                    UserCourseQuery::create()
                        ->filterByPrimaryKeys($this->userCoursesScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->userCoursesScheduledForDeletion = null;
                }
            }

            if ($this->collUserCourses !== null) {
                foreach ($this->collUserCourses as $referrerFK) {
                    if (!$referrerFK->isDeleted()) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
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

        $this->modifiedColumns[] = CoursePeer::ID;
        if (null !== $this->id) {
            throw new PropelException('Cannot insert a value for auto-increment primary key (' . CoursePeer::ID . ')');
        }

         // check the columns in natural order for more readable SQL queries
        if ($this->isColumnModified(CoursePeer::ID)) {
            $modifiedColumns[':p' . $index++]  = '`ID`';
        }
        if ($this->isColumnModified(CoursePeer::PID)) {
            $modifiedColumns[':p' . $index++]  = '`PID`';
        }
        if ($this->isColumnModified(CoursePeer::TITLE)) {
            $modifiedColumns[':p' . $index++]  = '`TITLE`';
        }
        if ($this->isColumnModified(CoursePeer::DESCRIPTION)) {
            $modifiedColumns[':p' . $index++]  = '`DESCRIPTION`';
        }
        if ($this->isColumnModified(CoursePeer::TYPE)) {
            $modifiedColumns[':p' . $index++]  = '`TYPE`';
        }
        if ($this->isColumnModified(CoursePeer::FILE)) {
            $modifiedColumns[':p' . $index++]  = '`FILE`';
        }
        if ($this->isColumnModified(CoursePeer::IS_PUBLIC)) {
            $modifiedColumns[':p' . $index++]  = '`IS_PUBLIC`';
        }
        if ($this->isColumnModified(CoursePeer::IS_ACTIVE)) {
            $modifiedColumns[':p' . $index++]  = '`IS_ACTIVE`';
        }
        if ($this->isColumnModified(CoursePeer::CREATED_AT)) {
            $modifiedColumns[':p' . $index++]  = '`CREATED_AT`';
        }
        if ($this->isColumnModified(CoursePeer::UPDATED_AT)) {
            $modifiedColumns[':p' . $index++]  = '`UPDATED_AT`';
        }

        $sql = sprintf(
            'INSERT INTO `courses` (%s) VALUES (%s)',
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
                    case '`PID`':
                        $stmt->bindValue($identifier, $this->pid, PDO::PARAM_INT);
                        break;
                    case '`TITLE`':
                        $stmt->bindValue($identifier, $this->title, PDO::PARAM_STR);
                        break;
                    case '`DESCRIPTION`':
                        $stmt->bindValue($identifier, $this->description, PDO::PARAM_STR);
                        break;
                    case '`TYPE`':
                        $stmt->bindValue($identifier, $this->type, PDO::PARAM_INT);
                        break;
                    case '`FILE`':
                        $stmt->bindValue($identifier, $this->file, PDO::PARAM_STR);
                        break;
                    case '`IS_PUBLIC`':
                        $stmt->bindValue($identifier, (int) $this->is_public, PDO::PARAM_INT);
                        break;
                    case '`IS_ACTIVE`':
                        $stmt->bindValue($identifier, (int) $this->is_active, PDO::PARAM_INT);
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

            if ($this->aCourseRelatedByPid !== null) {
                if (!$this->aCourseRelatedByPid->validate($columns)) {
                    $failureMap = array_merge($failureMap, $this->aCourseRelatedByPid->getValidationFailures());
                }
            }


            if (($retval = CoursePeer::doValidate($this, $columns)) !== true) {
                $failureMap = array_merge($failureMap, $retval);
            }


                if ($this->collCoursesRelatedById !== null) {
                    foreach ($this->collCoursesRelatedById as $referrerFK) {
                        if (!$referrerFK->validate($columns)) {
                            $failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
                        }
                    }
                }

                if ($this->collLessons !== null) {
                    foreach ($this->collLessons as $referrerFK) {
                        if (!$referrerFK->validate($columns)) {
                            $failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
                        }
                    }
                }

                if ($this->collUserCourses !== null) {
                    foreach ($this->collUserCourses as $referrerFK) {
                        if (!$referrerFK->validate($columns)) {
                            $failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
                        }
                    }
                }

                if ($this->collUserLessons !== null) {
                    foreach ($this->collUserLessons as $referrerFK) {
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
        $pos = CoursePeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
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
                return $this->getPid();
                break;
            case 2:
                return $this->getTitle();
                break;
            case 3:
                return $this->getDescription();
                break;
            case 4:
                return $this->getType();
                break;
            case 5:
                return $this->getFile();
                break;
            case 6:
                return $this->getIsPublic();
                break;
            case 7:
                return $this->getIsActive();
                break;
            case 8:
                return $this->getCreatedAt();
                break;
            case 9:
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
        if (isset($alreadyDumpedObjects['Course'][$this->getPrimaryKey()])) {
            return '*RECURSION*';
        }
        $alreadyDumpedObjects['Course'][$this->getPrimaryKey()] = true;
        $keys = CoursePeer::getFieldNames($keyType);
        $result = array(
            $keys[0] => $this->getId(),
            $keys[1] => $this->getPid(),
            $keys[2] => $this->getTitle(),
            $keys[3] => $this->getDescription(),
            $keys[4] => $this->getType(),
            $keys[5] => $this->getFile(),
            $keys[6] => $this->getIsPublic(),
            $keys[7] => $this->getIsActive(),
            $keys[8] => $this->getCreatedAt(),
            $keys[9] => $this->getUpdatedAt(),
        );
        if ($includeForeignObjects) {
            if (null !== $this->aCourseRelatedByPid) {
                $result['CourseRelatedByPid'] = $this->aCourseRelatedByPid->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->collCoursesRelatedById) {
                $result['CoursesRelatedById'] = $this->collCoursesRelatedById->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collLessons) {
                $result['Lessons'] = $this->collLessons->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collUserCourses) {
                $result['UserCourses'] = $this->collUserCourses->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collUserLessons) {
                $result['UserLessons'] = $this->collUserLessons->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
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
        $pos = CoursePeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);

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
                $this->setPid($value);
                break;
            case 2:
                $this->setTitle($value);
                break;
            case 3:
                $this->setDescription($value);
                break;
            case 4:
                $this->setType($value);
                break;
            case 5:
                $this->setFile($value);
                break;
            case 6:
                $this->setIsPublic($value);
                break;
            case 7:
                $this->setIsActive($value);
                break;
            case 8:
                $this->setCreatedAt($value);
                break;
            case 9:
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
        $keys = CoursePeer::getFieldNames($keyType);

        if (array_key_exists($keys[0], $arr)) $this->setId($arr[$keys[0]]);
        if (array_key_exists($keys[1], $arr)) $this->setPid($arr[$keys[1]]);
        if (array_key_exists($keys[2], $arr)) $this->setTitle($arr[$keys[2]]);
        if (array_key_exists($keys[3], $arr)) $this->setDescription($arr[$keys[3]]);
        if (array_key_exists($keys[4], $arr)) $this->setType($arr[$keys[4]]);
        if (array_key_exists($keys[5], $arr)) $this->setFile($arr[$keys[5]]);
        if (array_key_exists($keys[6], $arr)) $this->setIsPublic($arr[$keys[6]]);
        if (array_key_exists($keys[7], $arr)) $this->setIsActive($arr[$keys[7]]);
        if (array_key_exists($keys[8], $arr)) $this->setCreatedAt($arr[$keys[8]]);
        if (array_key_exists($keys[9], $arr)) $this->setUpdatedAt($arr[$keys[9]]);
    }

    /**
     * Build a Criteria object containing the values of all modified columns in this object.
     *
     * @return Criteria The Criteria object containing all modified values.
     */
    public function buildCriteria()
    {
        $criteria = new Criteria(CoursePeer::DATABASE_NAME);

        if ($this->isColumnModified(CoursePeer::ID)) $criteria->add(CoursePeer::ID, $this->id);
        if ($this->isColumnModified(CoursePeer::PID)) $criteria->add(CoursePeer::PID, $this->pid);
        if ($this->isColumnModified(CoursePeer::TITLE)) $criteria->add(CoursePeer::TITLE, $this->title);
        if ($this->isColumnModified(CoursePeer::DESCRIPTION)) $criteria->add(CoursePeer::DESCRIPTION, $this->description);
        if ($this->isColumnModified(CoursePeer::TYPE)) $criteria->add(CoursePeer::TYPE, $this->type);
        if ($this->isColumnModified(CoursePeer::FILE)) $criteria->add(CoursePeer::FILE, $this->file);
        if ($this->isColumnModified(CoursePeer::IS_PUBLIC)) $criteria->add(CoursePeer::IS_PUBLIC, $this->is_public);
        if ($this->isColumnModified(CoursePeer::IS_ACTIVE)) $criteria->add(CoursePeer::IS_ACTIVE, $this->is_active);
        if ($this->isColumnModified(CoursePeer::CREATED_AT)) $criteria->add(CoursePeer::CREATED_AT, $this->created_at);
        if ($this->isColumnModified(CoursePeer::UPDATED_AT)) $criteria->add(CoursePeer::UPDATED_AT, $this->updated_at);

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
        $criteria = new Criteria(CoursePeer::DATABASE_NAME);
        $criteria->add(CoursePeer::ID, $this->id);

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
     * @param object $copyObj An object of Course (or compatible) type.
     * @param boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @param boolean $makeNew Whether to reset autoincrement PKs and make the object new.
     * @throws PropelException
     */
    public function copyInto($copyObj, $deepCopy = false, $makeNew = true)
    {
        $copyObj->setPid($this->getPid());
        $copyObj->setTitle($this->getTitle());
        $copyObj->setDescription($this->getDescription());
        $copyObj->setType($this->getType());
        $copyObj->setFile($this->getFile());
        $copyObj->setIsPublic($this->getIsPublic());
        $copyObj->setIsActive($this->getIsActive());
        $copyObj->setCreatedAt($this->getCreatedAt());
        $copyObj->setUpdatedAt($this->getUpdatedAt());

        if ($deepCopy && !$this->startCopy) {
            // important: temporarily setNew(false) because this affects the behavior of
            // the getter/setter methods for fkey referrer objects.
            $copyObj->setNew(false);
            // store object hash to prevent cycle
            $this->startCopy = true;

            foreach ($this->getCoursesRelatedById() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addCourseRelatedById($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getLessons() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addLesson($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getUserCourses() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addUserCourse($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getUserLessons() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addUserLesson($relObj->copy($deepCopy));
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
     * @return Course Clone of current object.
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
     * @return CoursePeer
     */
    public function getPeer()
    {
        if (self::$peer === null) {
            self::$peer = new CoursePeer();
        }

        return self::$peer;
    }

    /**
     * Declares an association between this object and a Course object.
     *
     * @param             Course $v
     * @return Course The current object (for fluent API support)
     * @throws PropelException
     */
    public function setCourseRelatedByPid(Course $v = null)
    {
        if ($v === null) {
            $this->setPid(NULL);
        } else {
            $this->setPid($v->getId());
        }

        $this->aCourseRelatedByPid = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the Course object, it will not be re-added.
        if ($v !== null) {
            $v->addCourseRelatedById($this);
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
    public function getCourseRelatedByPid(PropelPDO $con = null)
    {
        if ($this->aCourseRelatedByPid === null && ($this->pid !== null)) {
            $this->aCourseRelatedByPid = CourseQuery::create()->findPk($this->pid, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aCourseRelatedByPid->addCoursesRelatedById($this);
             */
        }

        return $this->aCourseRelatedByPid;
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
        if ('CourseRelatedById' == $relationName) {
            $this->initCoursesRelatedById();
        }
        if ('Lesson' == $relationName) {
            $this->initLessons();
        }
        if ('UserCourse' == $relationName) {
            $this->initUserCourses();
        }
        if ('UserLesson' == $relationName) {
            $this->initUserLessons();
        }
    }

    /**
     * Clears out the collCoursesRelatedById collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addCoursesRelatedById()
     */
    public function clearCoursesRelatedById()
    {
        $this->collCoursesRelatedById = null; // important to set this to null since that means it is uninitialized
        $this->collCoursesRelatedByIdPartial = null;
    }

    /**
     * reset is the collCoursesRelatedById collection loaded partially
     *
     * @return void
     */
    public function resetPartialCoursesRelatedById($v = true)
    {
        $this->collCoursesRelatedByIdPartial = $v;
    }

    /**
     * Initializes the collCoursesRelatedById collection.
     *
     * By default this just sets the collCoursesRelatedById collection to an empty array (like clearcollCoursesRelatedById());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initCoursesRelatedById($overrideExisting = true)
    {
        if (null !== $this->collCoursesRelatedById && !$overrideExisting) {
            return;
        }
        $this->collCoursesRelatedById = new PropelObjectCollection();
        $this->collCoursesRelatedById->setModel('Course');
    }

    /**
     * Gets an array of Course objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this Course is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|Course[] List of Course objects
     * @throws PropelException
     */
    public function getCoursesRelatedById($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collCoursesRelatedByIdPartial && !$this->isNew();
        if (null === $this->collCoursesRelatedById || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collCoursesRelatedById) {
                // return empty collection
                $this->initCoursesRelatedById();
            } else {
                $collCoursesRelatedById = CourseQuery::create(null, $criteria)
                    ->filterByCourseRelatedByPid($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collCoursesRelatedByIdPartial && count($collCoursesRelatedById)) {
                      $this->initCoursesRelatedById(false);

                      foreach($collCoursesRelatedById as $obj) {
                        if (false == $this->collCoursesRelatedById->contains($obj)) {
                          $this->collCoursesRelatedById->append($obj);
                        }
                      }

                      $this->collCoursesRelatedByIdPartial = true;
                    }

                    return $collCoursesRelatedById;
                }

                if($partial && $this->collCoursesRelatedById) {
                    foreach($this->collCoursesRelatedById as $obj) {
                        if($obj->isNew()) {
                            $collCoursesRelatedById[] = $obj;
                        }
                    }
                }

                $this->collCoursesRelatedById = $collCoursesRelatedById;
                $this->collCoursesRelatedByIdPartial = false;
            }
        }

        return $this->collCoursesRelatedById;
    }

    /**
     * Sets a collection of CourseRelatedById objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $coursesRelatedById A Propel collection.
     * @param PropelPDO $con Optional connection object
     */
    public function setCoursesRelatedById(PropelCollection $coursesRelatedById, PropelPDO $con = null)
    {
        $this->coursesRelatedByIdScheduledForDeletion = $this->getCoursesRelatedById(new Criteria(), $con)->diff($coursesRelatedById);

        foreach ($this->coursesRelatedByIdScheduledForDeletion as $courseRelatedByIdRemoved) {
            $courseRelatedByIdRemoved->setCourseRelatedByPid(null);
        }

        $this->collCoursesRelatedById = null;
        foreach ($coursesRelatedById as $courseRelatedById) {
            $this->addCourseRelatedById($courseRelatedById);
        }

        $this->collCoursesRelatedById = $coursesRelatedById;
        $this->collCoursesRelatedByIdPartial = false;
    }

    /**
     * Returns the number of related Course objects.
     *
     * @param Criteria $criteria
     * @param boolean $distinct
     * @param PropelPDO $con
     * @return int             Count of related Course objects.
     * @throws PropelException
     */
    public function countCoursesRelatedById(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collCoursesRelatedByIdPartial && !$this->isNew();
        if (null === $this->collCoursesRelatedById || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collCoursesRelatedById) {
                return 0;
            } else {
                if($partial && !$criteria) {
                    return count($this->getCoursesRelatedById());
                }
                $query = CourseQuery::create(null, $criteria);
                if ($distinct) {
                    $query->distinct();
                }

                return $query
                    ->filterByCourseRelatedByPid($this)
                    ->count($con);
            }
        } else {
            return count($this->collCoursesRelatedById);
        }
    }

    /**
     * Method called to associate a Course object to this object
     * through the Course foreign key attribute.
     *
     * @param    Course $l Course
     * @return Course The current object (for fluent API support)
     */
    public function addCourseRelatedById(Course $l)
    {
        if ($this->collCoursesRelatedById === null) {
            $this->initCoursesRelatedById();
            $this->collCoursesRelatedByIdPartial = true;
        }
        if (!in_array($l, $this->collCoursesRelatedById->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddCourseRelatedById($l);
        }

        return $this;
    }

    /**
     * @param	CourseRelatedById $courseRelatedById The courseRelatedById object to add.
     */
    protected function doAddCourseRelatedById($courseRelatedById)
    {
        $this->collCoursesRelatedById[]= $courseRelatedById;
        $courseRelatedById->setCourseRelatedByPid($this);
    }

    /**
     * @param	CourseRelatedById $courseRelatedById The courseRelatedById object to remove.
     */
    public function removeCourseRelatedById($courseRelatedById)
    {
        if ($this->getCoursesRelatedById()->contains($courseRelatedById)) {
            $this->collCoursesRelatedById->remove($this->collCoursesRelatedById->search($courseRelatedById));
            if (null === $this->coursesRelatedByIdScheduledForDeletion) {
                $this->coursesRelatedByIdScheduledForDeletion = clone $this->collCoursesRelatedById;
                $this->coursesRelatedByIdScheduledForDeletion->clear();
            }
            $this->coursesRelatedByIdScheduledForDeletion[]= $courseRelatedById;
            $courseRelatedById->setCourseRelatedByPid(null);
        }
    }

    /**
     * Clears out the collLessons collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addLessons()
     */
    public function clearLessons()
    {
        $this->collLessons = null; // important to set this to null since that means it is uninitialized
        $this->collLessonsPartial = null;
    }

    /**
     * reset is the collLessons collection loaded partially
     *
     * @return void
     */
    public function resetPartialLessons($v = true)
    {
        $this->collLessonsPartial = $v;
    }

    /**
     * Initializes the collLessons collection.
     *
     * By default this just sets the collLessons collection to an empty array (like clearcollLessons());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initLessons($overrideExisting = true)
    {
        if (null !== $this->collLessons && !$overrideExisting) {
            return;
        }
        $this->collLessons = new PropelObjectCollection();
        $this->collLessons->setModel('Lesson');
    }

    /**
     * Gets an array of Lesson objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this Course is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|Lesson[] List of Lesson objects
     * @throws PropelException
     */
    public function getLessons($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collLessonsPartial && !$this->isNew();
        if (null === $this->collLessons || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collLessons) {
                // return empty collection
                $this->initLessons();
            } else {
                $collLessons = LessonQuery::create(null, $criteria)
                    ->filterByCourse($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collLessonsPartial && count($collLessons)) {
                      $this->initLessons(false);

                      foreach($collLessons as $obj) {
                        if (false == $this->collLessons->contains($obj)) {
                          $this->collLessons->append($obj);
                        }
                      }

                      $this->collLessonsPartial = true;
                    }

                    return $collLessons;
                }

                if($partial && $this->collLessons) {
                    foreach($this->collLessons as $obj) {
                        if($obj->isNew()) {
                            $collLessons[] = $obj;
                        }
                    }
                }

                $this->collLessons = $collLessons;
                $this->collLessonsPartial = false;
            }
        }

        return $this->collLessons;
    }

    /**
     * Sets a collection of Lesson objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $lessons A Propel collection.
     * @param PropelPDO $con Optional connection object
     */
    public function setLessons(PropelCollection $lessons, PropelPDO $con = null)
    {
        $this->lessonsScheduledForDeletion = $this->getLessons(new Criteria(), $con)->diff($lessons);

        foreach ($this->lessonsScheduledForDeletion as $lessonRemoved) {
            $lessonRemoved->setCourse(null);
        }

        $this->collLessons = null;
        foreach ($lessons as $lesson) {
            $this->addLesson($lesson);
        }

        $this->collLessons = $lessons;
        $this->collLessonsPartial = false;
    }

    /**
     * Returns the number of related Lesson objects.
     *
     * @param Criteria $criteria
     * @param boolean $distinct
     * @param PropelPDO $con
     * @return int             Count of related Lesson objects.
     * @throws PropelException
     */
    public function countLessons(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collLessonsPartial && !$this->isNew();
        if (null === $this->collLessons || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collLessons) {
                return 0;
            } else {
                if($partial && !$criteria) {
                    return count($this->getLessons());
                }
                $query = LessonQuery::create(null, $criteria);
                if ($distinct) {
                    $query->distinct();
                }

                return $query
                    ->filterByCourse($this)
                    ->count($con);
            }
        } else {
            return count($this->collLessons);
        }
    }

    /**
     * Method called to associate a Lesson object to this object
     * through the Lesson foreign key attribute.
     *
     * @param    Lesson $l Lesson
     * @return Course The current object (for fluent API support)
     */
    public function addLesson(Lesson $l)
    {
        if ($this->collLessons === null) {
            $this->initLessons();
            $this->collLessonsPartial = true;
        }
        if (!in_array($l, $this->collLessons->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddLesson($l);
        }

        return $this;
    }

    /**
     * @param	Lesson $lesson The lesson object to add.
     */
    protected function doAddLesson($lesson)
    {
        $this->collLessons[]= $lesson;
        $lesson->setCourse($this);
    }

    /**
     * @param	Lesson $lesson The lesson object to remove.
     */
    public function removeLesson($lesson)
    {
        if ($this->getLessons()->contains($lesson)) {
            $this->collLessons->remove($this->collLessons->search($lesson));
            if (null === $this->lessonsScheduledForDeletion) {
                $this->lessonsScheduledForDeletion = clone $this->collLessons;
                $this->lessonsScheduledForDeletion->clear();
            }
            $this->lessonsScheduledForDeletion[]= $lesson;
            $lesson->setCourse(null);
        }
    }

    /**
     * Clears out the collUserCourses collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addUserCourses()
     */
    public function clearUserCourses()
    {
        $this->collUserCourses = null; // important to set this to null since that means it is uninitialized
        $this->collUserCoursesPartial = null;
    }

    /**
     * reset is the collUserCourses collection loaded partially
     *
     * @return void
     */
    public function resetPartialUserCourses($v = true)
    {
        $this->collUserCoursesPartial = $v;
    }

    /**
     * Initializes the collUserCourses collection.
     *
     * By default this just sets the collUserCourses collection to an empty array (like clearcollUserCourses());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initUserCourses($overrideExisting = true)
    {
        if (null !== $this->collUserCourses && !$overrideExisting) {
            return;
        }
        $this->collUserCourses = new PropelObjectCollection();
        $this->collUserCourses->setModel('UserCourse');
    }

    /**
     * Gets an array of UserCourse objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this Course is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|UserCourse[] List of UserCourse objects
     * @throws PropelException
     */
    public function getUserCourses($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collUserCoursesPartial && !$this->isNew();
        if (null === $this->collUserCourses || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collUserCourses) {
                // return empty collection
                $this->initUserCourses();
            } else {
                $collUserCourses = UserCourseQuery::create(null, $criteria)
                    ->filterByCourse($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collUserCoursesPartial && count($collUserCourses)) {
                      $this->initUserCourses(false);

                      foreach($collUserCourses as $obj) {
                        if (false == $this->collUserCourses->contains($obj)) {
                          $this->collUserCourses->append($obj);
                        }
                      }

                      $this->collUserCoursesPartial = true;
                    }

                    return $collUserCourses;
                }

                if($partial && $this->collUserCourses) {
                    foreach($this->collUserCourses as $obj) {
                        if($obj->isNew()) {
                            $collUserCourses[] = $obj;
                        }
                    }
                }

                $this->collUserCourses = $collUserCourses;
                $this->collUserCoursesPartial = false;
            }
        }

        return $this->collUserCourses;
    }

    /**
     * Sets a collection of UserCourse objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $userCourses A Propel collection.
     * @param PropelPDO $con Optional connection object
     */
    public function setUserCourses(PropelCollection $userCourses, PropelPDO $con = null)
    {
        $this->userCoursesScheduledForDeletion = $this->getUserCourses(new Criteria(), $con)->diff($userCourses);

        foreach ($this->userCoursesScheduledForDeletion as $userCourseRemoved) {
            $userCourseRemoved->setCourse(null);
        }

        $this->collUserCourses = null;
        foreach ($userCourses as $userCourse) {
            $this->addUserCourse($userCourse);
        }

        $this->collUserCourses = $userCourses;
        $this->collUserCoursesPartial = false;
    }

    /**
     * Returns the number of related UserCourse objects.
     *
     * @param Criteria $criteria
     * @param boolean $distinct
     * @param PropelPDO $con
     * @return int             Count of related UserCourse objects.
     * @throws PropelException
     */
    public function countUserCourses(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collUserCoursesPartial && !$this->isNew();
        if (null === $this->collUserCourses || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collUserCourses) {
                return 0;
            } else {
                if($partial && !$criteria) {
                    return count($this->getUserCourses());
                }
                $query = UserCourseQuery::create(null, $criteria);
                if ($distinct) {
                    $query->distinct();
                }

                return $query
                    ->filterByCourse($this)
                    ->count($con);
            }
        } else {
            return count($this->collUserCourses);
        }
    }

    /**
     * Method called to associate a UserCourse object to this object
     * through the UserCourse foreign key attribute.
     *
     * @param    UserCourse $l UserCourse
     * @return Course The current object (for fluent API support)
     */
    public function addUserCourse(UserCourse $l)
    {
        if ($this->collUserCourses === null) {
            $this->initUserCourses();
            $this->collUserCoursesPartial = true;
        }
        if (!in_array($l, $this->collUserCourses->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddUserCourse($l);
        }

        return $this;
    }

    /**
     * @param	UserCourse $userCourse The userCourse object to add.
     */
    protected function doAddUserCourse($userCourse)
    {
        $this->collUserCourses[]= $userCourse;
        $userCourse->setCourse($this);
    }

    /**
     * @param	UserCourse $userCourse The userCourse object to remove.
     */
    public function removeUserCourse($userCourse)
    {
        if ($this->getUserCourses()->contains($userCourse)) {
            $this->collUserCourses->remove($this->collUserCourses->search($userCourse));
            if (null === $this->userCoursesScheduledForDeletion) {
                $this->userCoursesScheduledForDeletion = clone $this->collUserCourses;
                $this->userCoursesScheduledForDeletion->clear();
            }
            $this->userCoursesScheduledForDeletion[]= $userCourse;
            $userCourse->setCourse(null);
        }
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Course is new, it will return
     * an empty collection; or if this Course has previously
     * been saved, it will retrieve related UserCourses from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Course.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|UserCourse[] List of UserCourse objects
     */
    public function getUserCoursesJoinUser($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = UserCourseQuery::create(null, $criteria);
        $query->joinWith('User', $join_behavior);

        return $this->getUserCourses($query, $con);
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
     * If this Course is new, it will return
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
                    ->filterByCourse($this)
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
            $userLessonRemoved->setCourse(null);
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
                    ->filterByCourse($this)
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
     * @return Course The current object (for fluent API support)
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
        $userLesson->setCourse($this);
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
            $userLesson->setCourse(null);
        }
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Course is new, it will return
     * an empty collection; or if this Course has previously
     * been saved, it will retrieve related UserLessons from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Course.
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
     * Otherwise if this Course is new, it will return
     * an empty collection; or if this Course has previously
     * been saved, it will retrieve related UserLessons from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Course.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|UserLesson[] List of UserLesson objects
     */
    public function getUserLessonsJoinLesson($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = UserLessonQuery::create(null, $criteria);
        $query->joinWith('Lesson', $join_behavior);

        return $this->getUserLessons($query, $con);
    }

    /**
     * Clears the current object and sets all attributes to their default values
     */
    public function clear()
    {
        $this->id = null;
        $this->pid = null;
        $this->title = null;
        $this->description = null;
        $this->type = null;
        $this->file = null;
        $this->is_public = null;
        $this->is_active = null;
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
            if ($this->collCoursesRelatedById) {
                foreach ($this->collCoursesRelatedById as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collLessons) {
                foreach ($this->collLessons as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collUserCourses) {
                foreach ($this->collUserCourses as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collUserLessons) {
                foreach ($this->collUserLessons as $o) {
                    $o->clearAllReferences($deep);
                }
            }
        } // if ($deep)

        if ($this->collCoursesRelatedById instanceof PropelCollection) {
            $this->collCoursesRelatedById->clearIterator();
        }
        $this->collCoursesRelatedById = null;
        if ($this->collLessons instanceof PropelCollection) {
            $this->collLessons->clearIterator();
        }
        $this->collLessons = null;
        if ($this->collUserCourses instanceof PropelCollection) {
            $this->collUserCourses->clearIterator();
        }
        $this->collUserCourses = null;
        if ($this->collUserLessons instanceof PropelCollection) {
            $this->collUserLessons->clearIterator();
        }
        $this->collUserLessons = null;
        $this->aCourseRelatedByPid = null;
    }

    /**
     * return the string representation of this object
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->exportTo(CoursePeer::DEFAULT_STRING_FORMAT);
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
     * @return     Course The current object (for fluent API support)
     */
    public function keepUpdateDateUnchanged()
    {
        $this->modifiedColumns[] = CoursePeer::UPDATED_AT;

        return $this;
    }

}
