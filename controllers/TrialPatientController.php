<?php

/**
 * Class TrialPatientController
 */
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
            array(
                'allow',
                'actions' => array('changeStatus', 'updateExternalId'),
                'expression' => 'TrialPatient::checkTrialPatientAccess($user, Yii::app()->getRequest()->getQuery("id"), ' . UserTrialPermission::PERMISSION_EDIT . ')',
            ),
            array(
                'deny',  // deny all users
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
        if ($model === null) {
            throw new CHttpException(404, 'The requested page does not exist.');
        }

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

    /**
     * Changes the status of a patient in a trial to a given value
     * @param $id integer The id of the TrialPatient to change the status for
     * @param $new_status integer The new status of the TrialPatient
     * @throws CHttpException Thrown the model cannot be saved
     */
    public function actionChangeStatus($id, $new_status)
    {
        /** @var TrialPatient $model */
        $model = TrialPatient::model()->findByPk($id);

        if ($new_status == TrialPatient::STATUS_ACCEPTED &&
            $model->trial->trial_type == Trial::TRIAL_TYPE_INTERVENTION &&
            $model->patient->isCurrentlyInInterventionTrial()
        ) {
            echo TrialPatient::STATUS_CHANGE_CODE_ALREADY_IN_INTERVENTION;
            return;
        }

        $model->patient_status = $new_status;
        if (!$model->save()) {
            throw new CHttpException(400, 'An error occurred when saving the model: ' . print_r($model->getErrors()));
        }

        echo TrialPatient::STATUS_CHANGE_CODE_OK;
    }

    public function actionUpdateExternalId($id, $new_external_id)
    {
        $model = TrialPatient::model()->findByPk($id);
        if ($model == null) {
            throw new HttpException(404);
        }

        $model->external_trial_identifier = $new_external_id;

        if (!$model->save()) {
            throw new CHttpException(400, 'An error occurred when saving the model: ' . print_r($model->getErrors()));
        }
    }
}
