<?php

/**
 * Class TrialController
 */
class TrialController extends BaseModuleController
{
    const RETURN_CODE_OK = '0';
    const RETURN_CODE_CANT_OPEN_SHORTLISTED_TRIAL = '1';
    const RETURN_CODE_USER_PERMISSION_ALREADY_EXISTS = '2';

    /**
     * @return array action filters
     */
    public function filters()
    {
        return array(
            'accessControl',
            'ajaxOnly + getTrialList'
        );
    }

    /**
     * Specifies the access control rules.
     * This method is used by the 'accessControl' filter.
     * @return array access control rules
     */
    public function accessRules()
    {
        return array(
            array(
                'allow',  // allow authenticated users to perform the 'index' action
                'actions' => array('index', 'getTrialList'),
                'users' => array('@'),
            ),
            array(
                'allow',
                'actions' => array('view'),
                'expression' => 'Trial::checkTrialAccess($user, Yii::app()->getRequest()->getQuery("id"), UserTrialPermission::PERMISSION_VIEW)',
            ),
            array(
                'allow',
                'actions' => array('update', 'addPatient', 'removePatient'),
                'expression' => 'Trial::checkTrialAccess($user, Yii::app()->getRequest()->getQuery("id"), UserTrialPermission::PERMISSION_EDIT)',
            ),
            array(
                'allow',
                'actions' => array('permissions', 'addPermission', 'removePermission', 'transitionState'),
                'expression' => 'Trial::checkTrialAccess($user, Yii::app()->getRequest()->getQuery("id"), UserTrialPermission::PERMISSION_MANAGE)',
            ),
            array(
                'allow', // allow authenticated user to perform the 'create'  action
                'actions' => array('create'),
                'users' => array('@'),
            ),
            array(
                'deny',  // deny all users
                'users' => array('*'),
            ),
        );
    }

    /**
     * Displays a particular model.
     * @param integer $id the ID of the model to be displayed
     * @throws CException Thrown if an error occurs when loading the data providers
     */
    public function actionView($id)
    {
        $model = $this->loadModel($id);
        $report = new OETrial_ReportCohort();

        $this->render('view', array(
            'model' => $model,
            'userPermission' => $model->getTrialAccess(Yii::app()->user->id),
            'report' => $report,
            'dataProviders' => $model->getPatientDataProviders(),
        ));
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer $id the ID of the model to be loaded
     * @return Trial the loaded model
     * @throws CHttpException
     */
    public function loadModel($id)
    {
        /* @var Trial $model */
        $model = Trial::model()->findByPk($id);
        if ($model === null) {
            throw new CHttpException(404, 'The requested page does not exist.');
        }

        return $model;
    }


    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate()
    {
        $model = new Trial;
        $model->status = Trial::STATUS_OPEN;
        $model->owner_user_id = Yii::app()->user->id;

        if (isset($_POST['Trial'])) {
            $model->attributes = $_POST['Trial'];
            if ($model->save()) {
                $this->redirect(array('view', 'id' => $model->id));
            }
        }

        $this->render('create', array(
            'model' => $model,
        ));
    }

    /**
     * Updates a particular model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id the ID of the model to be updated
     * @throws CHttpException Thrown if the model cannot be loaded
     */
    public function actionUpdate($id)
    {
        $model = $this->loadModel($id);

        if (isset($_POST['Trial'])) {
            $model->attributes = $_POST['Trial'];
            if ($model->save()) {
                $this->redirect(array('view', 'id' => $model->id));
            }
        }

        $this->render('update', array(
            'model' => $model,
        ));
    }

    /**
     * Deletes a particular model.
     * If deletion is successful, the browser will be redirected to the 'admin' page.
     * @param integer $id the ID of the model to be deleted
     * @throws CHttpException Thrown if the model cannot be loaded
     * @throws CDbException Thrown if the model cannot be loaded
     */
    public function actionDelete($id)
    {
        $this->loadModel($id)->delete();

        // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
        if (!isset($_GET['ajax'])) {
            $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
        }
    }

    /**
     * Lists all models.
     */
    public function actionIndex()
    {
        $condition = 'trial_type = :trialType AND (
                    owner_user_id = :userId
                    OR EXISTS (
                        SELECT * FROM user_trial_permission utp WHERE utp.user_id = :userId AND utp.trial_id = t.id
                    ))';

        $interventionTrialDataProvider = new CActiveDataProvider('Trial', array(
            'criteria' => array(
                'condition' => $condition,
                'params' => array(
                    ':userId' => Yii::app()->user->id,
                    ':trialType' => Trial::TRIAL_TYPE_INTERVENTION,
                ),
            ),
        ));

        $nonInterventionTrialDataProvider = new CActiveDataProvider('Trial', array(
            'criteria' => array(
                'condition' => $condition,
                'params' => array(
                    ':userId' => Yii::app()->user->id,
                    ':trialType' => Trial::TRIAL_TYPE_NON_INTERVENTION,
                ),
            ),
        ));

        $this->render('index', array(
            'interventionTrialDataProvider' => $interventionTrialDataProvider,
            'nonInterventionTrialDataProvider' => $nonInterventionTrialDataProvider,
            'sort_by' => (integer)Yii::app()->request->getParam('sort_by', null),
            'sort_dir' => (integer)Yii::app()->request->getParam('sort_dir', null),
        ));
    }

    /**
     * Manages all models.
     */
    public function actionAdmin()
    {
        $model = new Trial('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['Trial'])) {
            $model->attributes = $_GET['Trial'];
        }

        $this->render('admin', array(
            'model' => $model,
        ));
    }

    /**
     * Adds a patient to the trial
     *
     * @param $id integer The ID of the Trial to add to
     * @param $patient_id integer THe ID of the patient to add
     * @param $patient_status integer The initial trial status for the patient (default to shortlisted)
     * @throws Exception Thrown if an error occurs when saving the TrialPatient record
     */
    public function actionAddPatient($id, $patient_id, $patient_status = TrialPatient::STATUS_SHORTLISTED)
    {
        $trial = Trial::model()->findByPk($id);
        $patient = Patient::model()->findByPk($patient_id);

        $trialPatient = new TrialPatient();
        $trialPatient->trial_id = $trial->id;
        $trialPatient->patient_id = $patient->id;
        $trialPatient->patient_status = $patient_status;

        if (!$trialPatient->save()) {
            throw new CHttpException(400, 'Unable to create TrialPatient: ' . print_r($trialPatient->getErrors(), true));
        }
    }

    /**
     * @param $id integer The id of the trial to remove
     * @param $patient_id integer The id of the patient to remove
     * @throws CHttpException Raised when the record cannot be found
     * @throws Exception Raised when an error occurs when removing the record
     */
    public function actionRemovePatient($id, $patient_id)
    {
        $trialPatient = TrialPatient::model()->find(
            'patient_id = :patientId AND trial_id = :trialId',
            array(
                ':patientId' => $patient_id,
                ':trialId' => $id,
            )
        );

        if ($trialPatient === null) {
            throw new CHttpException(400, "Patient $patient_id cannot be removed from Trial $id");
        }


        if (!$trialPatient->delete()) {
            throw new CHttpException(400, 'Unable to delete TrialPatient: ' . print_r($trialPatient->getErrors(), true));
        }
    }

    /**
     * Displays the permissions screen
     *
     * @param integer $id The ID of the Trial
     */
    public function actionPermissions($id)
    {
        $model = Trial::model()->findByPk($id);

        $permissionDataProvider = new CActiveDataProvider('UserTrialPermission', array(
            'criteria' => array(
                'condition' => 'trial_id = :trialId',
                'params' => array(
                    ':trialId' => $model->id,
                ),
                'order' => 'permission DESC',
            ),
        ));

        $newPermission = new UserTrialPermission();

        $this->render('permissions', array(
            'model' => $model,
            'newPermission' => $newPermission,
            'permissionDataProvider' => $permissionDataProvider,
        ));
    }

    /**
     * Creates a new Trial Permission using values in $_POST
     *
     * @param integer $id The Trial ID
     * @throws CHttpException Thrown if the permission couldn't be saved
     */
    public function actionAddPermission($id, $user_id, $permission)
    {
        if (UserTrialPermission::model()->exists(
            'trial_id = :trialId AND user_id = :userId',
            array(
                ':trialId' => $id,
                ':userId' => $user_id,
            ))
        ) {
            echo self::RETURN_CODE_USER_PERMISSION_ALREADY_EXISTS;

            return;
        }

        $userPermission = new UserTrialPermission();
        $userPermission->trial_id = $id;
        $userPermission->user_id = $user_id;
        $userPermission->permission = $permission;

        if (!$userPermission->save()) {
            throw new CHttpException(400,
                'Unable to create UserTrialPermission: ' . print_r($permission->getErrors(), true));
        }

        echo self::RETURN_CODE_OK;
    }

    /**
     * Removes a UserTrialPermission
     *
     * @param integer $id The ID of the trial
     * @param integer $permission_id The ID of the permission to remove
     * @return string 'success' if the permission is removed successfully
     * @throws CHttpException Thrown if the permission cannot be found
     * @throws CDbException Thrown if the permission cannot be deleted
     */
    public function actionRemovePermission($id, $permission_id)
    {
        /* @var UserTrialPermission $permission */
        $permission = UserTrialPermission::model()->findByPk($permission_id);
        if ($permission->trial->id !== $id) {
            throw new CHttpException(400);
        }

        $permission->delete();

        return 'success';
    }

    /**
     * Performs the AJAX validation.
     * @param Trial $model the model to be validated
     */
    protected function performAjaxValidation($model)
    {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'trial-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

    public function actionTransitionState($id, $new_state)
    {
        /* @var Trial $model */
        $model = Trial::model()->findByPk($id);

        if ($new_state == Trial::STATUS_OPEN && $model->hasShortlistedPatients()) {
            echo self::RETURN_CODE_CANT_OPEN_SHORTLISTED_TRIAL;

            return;
        }

        if ($new_state == Trial::STATUS_CLOSED || $new_state == Trial::STATUS_CANCELLED) {
            $model->closed_date = date('Y-m-d 00:00:00');
        } else {
            $model->closed_date = null;
        }

        $model->status = $new_state;
        if (!$model->save()) {
            throw new CHttpException(403, 'An error occurred when attempting to change the status');
        }

        echo self::RETURN_CODE_OK;
    }

    public function actionGetTrialList($trialID, $type)
    {
        $model = new PreviousTrialParameter();
        $model->id = $trialID;
        $trialModels = Trial::model()->findAll('trial_type=:type', array(':type' => $type));
        $trials = CHtml::listData($trialModels, 'id', 'name');
        $dropDown = CHtml::activeDropDownList($model, "[$trialID]trial", $trials, array('empty' => 'Any'));

        echo '<div class="large-3 column trial-list">' . $dropDown . '</div>';
    }
}
