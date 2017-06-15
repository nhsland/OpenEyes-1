<?php

/**
 * Class TrialController
 */
class TrialController extends BaseModuleController
{
    /**
     * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
     * using two-column layout. See 'protected/views/layouts/column2.php'.
     */
    public $layout = '//layouts/main';

    /**
     * @return array action filters
     */
    public function filters()
    {
        return array(
            'accessControl',
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
                'actions' => array('index'),
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
                'actions' => array('permissions', 'addPermission', 'removePermission'),
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
     * Gets the data providers for each patient status
     * @param $model Trial The trial to get the patients for
     * @return array An array of data providers with one for each patient status
     */
    private function getPatientDataProviders($model)
    {
        return array(
            'shortlistedPatientDataProvider' => $this->getPatientDataProvider($model, TrialPatient::STATUS_SHORTLISTED),
            'acceptedPatientDataProvider' => $this->getPatientDataProvider($model, TrialPatient::STATUS_ACCEPTED),
            'rejectedPatientDataProvider' => $this->getPatientDataProvider($model, TrialPatient::STATUS_REJECTED),
        );
    }

    /**
     * Displays a particular model.
     * @param integer $id the ID of the model to be displayed
     */
    public function actionView($id)
    {
        $model = $this->loadModel($id);
        $report = new OETrial_ReportCohort();
        $params = array_merge(
            array(
                'model' => $model,
                'userPermission' => $model->getTrialAccess(Yii::app()->user->id),
                'report' => $report,
            ),
            $this->getPatientDataProviders($model)
        );
        $this->render('view', $params);
    }

    /**
     * Create a data provider for patients in the Trial
     * @param $model Trial The Trial model to find the patients for
     * @param $patient_status int The status of patients of
     * @return CActiveDataProvider The data provider of patients with the given status
     * @throws CException Thrown if the patient_status is invalid
     */
    public function getPatientDataProvider($model, $patient_status)
    {
        if (!array_key_exists($patient_status, TrialPatient::getAllowedStatusRange())) {
            throw new CException("Unknown Trial Patient status: $patient_status");
        }

        $patientDataProvider = new CActiveDataProvider('TrialPatient', array(
            'criteria' => array(
                'condition' => 'trial_id = :trialId AND patient_status = :patientStatus',
                'params' => array(
                    ':trialId' => $model->id,
                    ':patientStatus' => $patient_status,
                ),
            ),
            'pagination' => array(
                'pageSize' => 10,
            ),
        ));

        return $patientDataProvider;
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

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

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
     */
    public function actionUpdate($id)
    {
        $model = $this->loadModel($id);

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

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
            throw new Exception('Unable to create TrialPatient: ' . print_r($trialPatient->getErrors(), true));
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

        if ($trialPatient == null) {
            throw new CHttpException(400, "Patient $patient_id cannot be removed from Trial $id");
        }


        if (!$trialPatient->delete()) {
            throw new Exception('Unable to delete TrialPatient: ' . print_r($trialPatient->getErrors(), true));
        }
    }

    public function actionPermissions($id)
    {
        $model = Trial::model()->findByPk($id);

        $newPermission = new UserTrialPermission();

        $this->render('permissions', array(
            'model' => $model,
            'newPermission' => $newPermission,
        ));
    }


    public function actionAddPermission($id)
    {
        $permission = new UserTrialPermission();
        $permission->trial_id = $_POST['trial_id'];
        $permission->user_id = $_POST['user_id'];
        $permission->permission = $_POST['permission'];
        if (!$permission->save()) {
            var_dump($permission->getErrors());

            //throw new CHttpException(400);
            return;
        }

        $this->redirect(array('/OETrial/trial/permissions', 'id' => $id));
    }

    public function actionRemovePermission($id, $permission_id)
    {
        /* @var UserTrialPermission $permission */
        $permission = UserTrialPermission::model()->findByPk($permission_id);
        if ($permission->trial->id != $id) {
            throw new CHttpException(400);
        }

        $permission->delete();

        return "success";
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
}
