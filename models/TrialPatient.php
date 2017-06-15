<?php

/**
 * This is the model class for table "trial_patient".
 *
 * The followings are the available columns in table 'trial_patient':
 * @property integer $id
 * @property string $external_trial_identifier
 * @property integer $trial_id
 * @property integer $patient_id
 * @property integer $patient_status
 * @property integer $last_modified_user_id
 * @property string $last_modified_date
 * @property integer $created_user_id
 * @property string $created_date
 *
 * The followings are the available model relations:
 * @property Patient $patient
 * @property User $createdUser
 * @property User $lastModifiedUser
 * @property Trial $trial
 */
class TrialPatient extends BaseActiveRecordVersioned
{
    /**
     * The status when the patient has been just added to a Trial, but hasn't been accepted or rejected yet
     */
    const STATUS_SHORTLISTED = 0;

    /**
     * The status when the patient has been accepted into the Trial
     */
    const STATUS_ACCEPTED = 1;

    /**
     * The status when the patient hsa been rejected from the Trial
     */
    const STATUS_REJECTED = 2;

    const STATUS_CHANGE_CODE_OK = "0";
    const STATUS_CHANGE_CODE_ALREADY_IN_INTERVENTION = "1";


    /**
     * Gets an array of the available statues of a patient
     * @return array The array of statues
     */
    public static function getAllowedStatusRange()
    {
        return array(
            self::STATUS_SHORTLISTED,
            self::STATUS_ACCEPTED,
            self::STATUS_REJECTED,
        );
    }

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'trial_patient';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        return array(
            array('trial_id, patient_id, patient_status', 'required'),
            array('trial_id', 'numerical', 'integerOnly' => true),
            array('external_trial_identifier', 'length', 'max' => 64),
            array('patient_id, patient_status', 'length', 'max' => 10),
            array('patient_status', 'in', 'range' => self::getAllowedStatusRange()),
            // The trial_id and the patient_id must be unique together
            array(
                'trial_id',
                'unique',
                'criteria' => array(
                    'condition' => '`patient_id`=:patientId',
                    'params' => array(
                        ':patientId' => $this->patient_id,
                    ),
                ),
            ),
            array('last_modified_date, created_date', 'safe'),
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
            'patient' => array(self::BELONGS_TO, 'Patient', 'patient_id'),
            'createdUser' => array(self::BELONGS_TO, 'User', 'created_user_id'),
            'lastModifiedUser' => array(self::BELONGS_TO, 'User', 'last_modified_user_id'),
            'trial' => array(self::BELONGS_TO, 'Trial', 'trial_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'external_trial_identifier' => 'Trial Identifier',
            'trial_id' => 'Trial',
            'patient_id' => 'Patient',
            'patient_status' => 'Patient Status',
            'last_modified_user_id' => 'Last Modified User',
            'last_modified_date' => 'Last Modified Date',
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

        $criteria->compare('id', $this->id);
        $criteria->compare('external_trial_identifier', $this->external_trial_identifier, true);
        $criteria->compare('trial_id', $this->trial_id);
        $criteria->compare('patient_id', $this->patient_id, true);
        $criteria->compare('patient_status', $this->patient_status, true);
        $criteria->compare('last_modified_user_id', $this->last_modified_user_id, true);
        $criteria->compare('last_modified_date', $this->last_modified_date, true);
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
     * @return TrialPatient the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public static function isPatientInTrial($patient_id, $trial_id)
    {
        return TrialPatient::model()->exists(
            'patient_id = :patientId AND trial_id = :trialId',
            array(
                ':patientId' => $patient_id,
                ':trialId' => $trial_id,
            )
        );
    }

    public static function checkTrialPatientAccess($user, $trial_patient_id, $permission)
    {
        /* @var TrialPatient $model */
        $model = TrialPatient::model()->findByPk($trial_patient_id);

        return Trial::checkTrialAccess($user, $model->trial_id, $permission);
    }
}
