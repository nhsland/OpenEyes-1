<?php

class TrialPatientController extends BaseModuleController
{
    /**
     * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
     * using two-column layout. See 'protected/views/layouts/column2.php'.
     */
    public $layout = '//layouts/column2';

    /**
     * @return array action filters
     */
    public function filters()
    {
        return array(
            'accessControl', // perform access control for CRUD operations
            'ajaxOnly + accept',
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
                'actions' => array('accept'),
                'users' => array('*'),
            ),
            array('allow',  // allow all users to perform the 'index' action
                'actions' => array('reject'),
                'users' => array('*'),
            ),
            array('allow',  // allow all users to perform the 'index' action
                'actions' => array('shortlist'),
                'users' => array('*'),
            ),
            array('deny',  // deny all users
                'users' => array('*'),
            ),
        );
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer $id the ID of the model to be loaded
     * @return TrialPatient the loaded model
     * @throws CHttpException
     */
    public function loadModel($id)
    {
        $model = TrialPatient::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param TrialPatient $model the model to be validated
     */
    protected function performAjaxValidation($model)
    {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'trial-patient-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

    public function actionAccept($id)
    {
        $model = TrialPatient::model()->findByPk($id);

        $model->patient_status = TrialPatient::STATUS_ACCEPTED;
        $model->save();
    }
}
