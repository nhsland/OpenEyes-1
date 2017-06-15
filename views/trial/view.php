<?php
/* @var $this TrialController */
/* @var $model Trial
 * @var $canUpdateTrial boolean
 * @var $shortlistedPatientDataProvider CActiveDataProvider
 * @var $acceptedPatientDataProvider CActiveDataProvider
 * @var $rejectedPatientDataProvider CActiveDataProvider
 */

?>

<h1 class="badge">Trial</h1>
<div class="row">
  <div class="large-9 column">

    <div class="box admin">
      <h1 class="text-center"><?php echo $model->name; ?>
          <?php if ($canUpdateTrial) {
              echo Chtml::link('[edit]', array('/OETrial/trial/update', 'id' => $model->id));
          } ?></h1>
      <div class="box-info"><?php echo $model->getTrialTypeOptions()[$model->trial_type]; ?></div>

        <?php if (strlen($model->description) > 0): ?>
          <b><?php echo CHtml::encode($model->getAttributeLabel('description')); ?>:</b>
            <?php echo CHtml::encode($model->description); ?>
          <br/>
        <?php endif; ?>

      <b><?php echo CHtml::encode($model->getAttributeLabel('created_date')); ?>:</b>
        <?php echo CHtml::encode($model->created_date); ?>
      <br/>

      <hr/>
      <h2>Shortlisted Patients</h2>
        <?php $this->widget('zii.widgets.CListView', array(
            'id' => 'shortlistedPatientList',
            'dataProvider' => $shortlistedPatientDataProvider,
            'itemView' => '/trialPatient/_view',
        )); ?>
      <hr/>
      <h2>Accepted Patients</h2>
        <?php $this->widget('zii.widgets.CListView', array(
            'id' => 'acceptedPatientList',
            'dataProvider' => $acceptedPatientDataProvider,
            'itemView' => '/trialPatient/_view',
        )); ?>
      <hr/>
      <h2>Rejected Patients</h2>
        <?php $this->widget('zii.widgets.CListView', array(
            'id' => 'rejectedPatientList',
            'dataProvider' => $rejectedPatientDataProvider,
            'itemView' => '/trialPatient/_view',
        )); ?>

    </div>


  </div><!-- /.large-9.column -->
  <div class="large-3 column">
    <div class="box generic">
      <p>
        <span class="highlight"><?php echo CHtml::link('Search for patients to add',
                Yii::app()->createUrl('/OECaseSearch/caseSearch', array('trial_id' => $model->id))); ?></span>
      </p>
        <?php echo CHtml::beginForm($this->createUrl('report/downloadReport')); ?>
      <p>


          <span class="highlight">
              <?php echo CHtml::hiddenField('report-name', 'Cohort'); ?>
              <?php echo CHtml::hiddenField('trialID', $model->id); ?>
              <?php echo CHtml::linkButton('Download Report'); ?>
          </span>

      </p>
        <?php echo CHtml::endForm(); ?>
    </div>
  </div>

</div>

<?php
$assetPath = Yii::app()->getAssetManager()->publish(Yii::getPathOfAlias('application.assets'), false, -1);
Yii::app()->getClientScript()->registerScriptFile($assetPath . '/js/toggle-section.js');
?>

<script type="application/javascript">

  function changePatientStatus(object, trial_patient_id, new_status) {
    $.ajax({
      url: '<?php echo Yii::app()->controller->createUrl('/OETrial/trialPatient/changeStatus'); ?>/' + trial_patient_id + '?new_status=' + new_status,
      type: 'GET',
      success: function (response) {
        if (response == '<?php echo TrialPatient::STATUS_CHANGE_CODE_OK; ?>') {
          $.fn.yiiListView.update('shortlistedPatientList');
          $.fn.yiiListView.update('acceptedPatientList');
          $.fn.yiiListView.update('rejectedPatientList');
        } else if (response == '<?php echo TrialPatient::STATUS_CHANGE_CODE_ALREADY_IN_INTERVENTION; ?>') {
          new OpenEyes.UI.Dialog.Alert({
            content: "You can't accept this patient into your Trial because the patient has already been accepted into another Intervention trial."
          }).open();
        } else {
          alert("Unknown response code: " + response_code);
        }
      },
      error: function (response) {
        new OpenEyes.UI.Dialog.Alert({
          content: "Sorry, an internal error occurred and we were unable to change the patient status.\n\nPlease contact support for assistance."
        }).open();
      }
    });
  }

  function editExternalTrialIdentifier(trial_patient_id) {
    $("#ext-trial-id-form-" + trial_patient_id).show();
    $("#ext-trial-id-" + trial_patient_id).hide();
    $('#ext-trial-id-link-' + trial_patient_id).hide();
  }

  function saveExternalTrialIdentifier(trial_patient_id) {
    var external_id = $('#trial-patient-ext-id-' + trial_patient_id).val();
    $.ajax({
      url: '<?php echo Yii::app()->controller->createUrl('/OETrial/trialPatient/updateExternalId'); ?>',
      data: {id: trial_patient_id, new_external_id: external_id},
      type: 'GET',
      success: function (response) {
        $("#ext-trial-id-form-" + trial_patient_id).hide();

        var id_label = $("#ext-trial-id-" + trial_patient_id);
        id_label.html(external_id);
        id_label.show();

        $('#ext-trial-id-link-' + trial_patient_id).show();
      },
      error: function (response) {
        new OpenEyes.UI.Dialog.Alert({
          content: "Sorry, an internal error occurred and we were unable to change the external trail identifier.\n\nPlease contact support for assistance."
        }).open();
      }
    });
  }
</script>
