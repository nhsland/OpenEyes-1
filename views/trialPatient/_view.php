<?php
/* @var $this TrialPatientController */
/* @var $data TrialPatient */
/* @var $userPermission integer */

$isInAnotherInterventionTrial = $data->patient->isCurrentlyInInterventionTrial($data->trial_id);
$canEditPatient = Trial::checkTrialAccess(Yii::app()->user, $data->trial_id, UserTrialPermission::PERMISSION_EDIT);
?>
<div class="box generic">
  <div class="row">
    <div class="large-10 column">
      <div class="box">
        <h3 class="box-title">
            <?php echo CHtml::link(
                $data->patient->contact->last_name . ', ' . $data->patient->contact->first_name . ($data->patient->is_deceased ? ' (Deceased)' : ''),
                array('/patient/view', 'id' => $data->patient->id),
                array('target' => '_blank')
            ); ?>
        </h3>

          <?php if ($isInAnotherInterventionTrial) { ?>
            <div class="alert-box alert with-icon">
              This patient is already in an Intervention trial
            </div>
          <?php } ?>

          <?php
          echo $data->patient->getGenderString() . ' ' . '(' . $data->patient->getAge() . ') ';
          if ($data->patient->ethnic_group) {
              echo $data->patient->getEthnicGroupString();
          }
          ?>
        <br/>

          <?php echo CHtml::encode($data->getAttributeLabel('external_trial_identifier')); ?>:
        <span
            id="ext-trial-id-<?php echo $data->id; ?>"><?php echo CHtml::encode($data->external_trial_identifier); ?></span>
          <?php if ($canEditPatient && $data->trial->status != Trial::STATUS_CANCELLED): ?>
            <a id="ext-trial-id-link-<?php echo $data->id; ?>" href="javascript:void(0)"
               onclick="editExternalTrialIdentifier(<?php echo $data->id; ?>)">edit</a>

            <div id="ext-trial-id-form-<?php echo $data->id; ?>" style="display:none">
              <input id="trial-patient-ext-id-<?php echo $data->id; ?>" type="text"
                     value="<?php echo $data->external_trial_identifier; ?>" width="50"/>
              <a id="ext-trial-id-save-<?php echo $data->id; ?>" href="javascript:void(0)"
                 onclick="saveExternalTrialIdentifier(<?php echo $data->id; ?>)">save</a>
            </div>
          <?php endif; ?>
        <br/>

          <?php $this->widget('PatientDiagnosesAndMedicationsWidget',
              array(
                  'patient' => $data->patient,
              )
          ); ?>
      </div>
    </div>

      <?php if ($canEditPatient && $data->trial->status == Trial::STATUS_OPEN && $data->trial->status == Trial::STATUS_IN_PROGRESS): ?>
        <div class="large-2 column">
          <div class="box">
              <?php if ($data->patient_status == TrialPatient::STATUS_SHORTLISTED && !$isInAnotherInterventionTrial): ?>
                <a href="javascript:void(0)"
                   onclick="changePatientStatus(this, <?php echo $data->id; ?>, <?php echo TrialPatient::STATUS_ACCEPTED; ?>)"
                   class="accept-patient-link">Accept into Trial
                </a>
                <br/>
              <?php endif; ?>

              <?php if ($data->patient_status == TrialPatient::STATUS_SHORTLISTED || $data->patient_status == TrialPatient::STATUS_ACCEPTED): ?>
                <a href="javascript:void(0)"
                   onclick="changePatientStatus(this, <?php echo $data->id; ?>, <?php echo TrialPatient::STATUS_REJECTED; ?>)"
                   class="accept-patient-link">Reject from Trial
                </a>
              <?php endif; ?>

              <?php if ($data->patient_status == TrialPatient::STATUS_REJECTED): ?>
                <a href="javascript:void(0)"
                   onclick="changePatientStatus(this, <?php echo $data->id; ?>, <?php echo TrialPatient::STATUS_SHORTLISTED; ?>)"
                   class="accept-patient-link">Send to Shortlist
                </a>
              <?php endif; ?>
          </div>
        </div>
      <?php endif; ?>
  </div>
</div>
