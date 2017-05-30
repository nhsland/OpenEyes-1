<?php

/**
 * This is the model class for table "trial".
 *
 * The followings are the available columns in table 'trial':
 * @property integer $id
 * @property string $name
 * @property string $description
 * @property integer $owner_user_id
 * @property integer $status
 * @property string $last_modified_date
 * @property string $last_modified_user_id
 * @property integer $created_user_id
 * @property string $created_date
 *
 * The followings are the available model relations:
 * @property User $ownerUser
 * @property User $createdUser
 * @property User $lastModifiedUser
 * @property TrialPatient[] $trialPatients
 */
class Trial extends BaseActiveRecordVersioned
{
    /**
     * The status when the Trial is first created
     */
    const STATUS_OPEN = 0;

    /**
     * The status when the Trial has begun (can only be moved here once all patients have accepted or rejected)
     */
    const STATUS_IN_PROGRESS = 1;

    /**
     * The status when the Trial has been completed and closed (can only be moved here from STATUS_IN_PROGRESS)
     */
    const STATUS_CLOSED = 2;

    /**
     * The status when the Trial has been closed prematurely
     */
    const STATUS_CANCELLED = 3;

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'trial';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('name, owner_user_id, status', 'required'),
            array('name, description', 'length', 'max' => 64),
            array('owner_user_id, last_modified_user_id, created_user_id, status', 'length', 'max' => 10),
            array('status', 'in', 'range' => self::getAllowedStatusRange()),
            array('last_modified_date, created_date', 'safe'),

            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, name, description, owner_user_id, status, last_modified_date, last_modified_user_id, created_user_id, created_date', 'safe', 'on' => 'search'),
        );
    }

    /**
     * Returns an array of all of the allowable values of "status"
     * @return array The list of statuses
     */
    public function getAllowedStatusRange()
    {
        return array(
            self::STATUS_OPEN,
            self::STATUS_IN_PROGRESS,
            self::STATUS_CLOSED,
            self::STATUS_CANCELLED,
        );
    }

    /**
     * Returns an array withs keys of the allowable values of status and values of the label for that status
     * @return array The array of status id/label key/value pairs
     */
    public function getStatusOptions()
    {
        return array(
            self::STATUS_OPEN => 'Open"',
            self::STATUS_IN_PROGRESS => 'In Progress',
            self::STATUS_CLOSED => 'Closed',
            self::STATUS_CANCELLED => 'Cancelled',
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'ownerUser' => array(self::BELONGS_TO, 'User', 'owner_user_id'),
            'createdUser' => array(self::BELONGS_TO, 'User', 'created_user_id'),
            'lastModifiedUser' => array(self::BELONGS_TO, 'User', 'last_modified_user_id'),
            'trialPatients' => array(self::HAS_MANY, 'TrialPatient', 'trial_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'name' => 'Name',
            'description' => 'Description',
            'owner_user_id' => 'Owner User',
            'status' => 'Status',
            'last_modified_date' => 'Last Modified Date',
            'last_modified_user_id' => 'Last Modified User',
            'created_user_id' => 'Created User',
            'created_date' => 'Created Date',
        );
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     *
     * Typical usecase:
     * - Initialize the model fields with values from filter form.
     * - Execute this method to get CActiveDataProvider instance which will filter
     * models according to data in model fields.
     * - Pass data provider to CGridView, CListView or any similar widget.
     *
     * @return CActiveDataProvider the data provider that can return the models
     * based on the search/filter conditions.
     */
    public function search()
    {
        // @todo Please modify the following code to remove attributes that should not be searched.

        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id, true);
        $criteria->compare('name', $this->name, true);
        $criteria->compare('description', $this->description, true);
        $criteria->compare('owner_user_id', $this->owner_user_id, true);
        $criteria->compare('status', $this->status, true);
        $criteria->compare('last_modified_date', $this->last_modified_date, true);
        $criteria->compare('last_modified_user_id', $this->last_modified_user_id, true);
        $criteria->compare('created_user_id', $this->created_user_id, true);
        $criteria->compare('created_date', $this->created_date, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return Trial the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    /**
     * Returns whether or not the given user can access the given trial using the given action
     * @param $user User The user to check access for
     * @param $trial_id int The ID of the trial
     * @param $action string The ID of the controller action
     * @return bool True if access is permitted, otherwise false
     */
    public static function canUserAccessTrial($user, $trial_id, $action)
    {
        $model = Trial::model()->findByPk($trial_id);
        if ($model === null) {
            return false;
        }

        return $model->owner_user_id == $user->id;
    }

}
