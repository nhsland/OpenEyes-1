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
            array('allow',  // allow all users to perform the 'index' action
                'actions' => array('index'),
                'users' => array('*'),
            ),
            array('allow',
                'actions' => array('view'),
                'expression' => 'Trial::canUserAccessTrial($user, Yii::app()->getRequest()->getQuery("id"), "view")',
            ),
            array('allow',
                'actions' => array('update'),
                'expression' => 'Trial::canUserAccessTrial($user, Yii::app()->getRequest()->getQuery("id"), "update")',
            ),
            array('allow',
                'actions' => array('manage'),
                'expression' => 'Trial::canUserAccessTrial($user, Yii::app()->getRequest()->getQuery("id"), "manage")',
            ),
            array('allow', // allow authenticated user to perform the 'create'  action
                'actions' => array('create'),
                'users' => array('@'),
            ),
            array('deny',  // deny all users
                'users' => array('*'),
            ),
        );
    }

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
        $params = array_merge(array('model' => $model), $this->getPatientDataProviders($model));
        $this->render('view', $params);
    }

    /**
     * Displays a particular model in manage mode.
     * @param integer $id the ID of the model to be displayed
     */
    public function actionManage($id)
    {
        $model = $this->loadModel($id);
        $params = array_merge(array('model' => $model), $this->getPatientDataProviders($model));
        $this->render('manage', $params);
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
        $dataProvider = new CActiveDataProvider('Trial', array(
            'criteria' => array(
                'condition' => 'owner_user_id = :userId',
                'params' => array(
                    ':userId' => Yii::app()->user->id,
                ),
            ),
        ));

        $this->render('index', array(
            'dataProvider' => $dataProvider,
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
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer $id the ID of the model to be loaded
     * @return Trial the loaded model
     * @throws CHttpException
     */
    public function loadModel($id)
    {
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
