<?php
/* @var $this TrialPatientController */
/* @var $data TrialPatient */
?>

<div class="result box generic">


    <h3 class="box-title">
        <?php echo CHtml::link(
            $data->patient->contact->last_name . ', ' . $data->patient->contact->first_name . ($data->patient->is_deceased ? ' (Deceased)' : ''),
            array('/patient/view', 'id' => $data->patient->id),
            array('target' => '_blank')
        ); ?>
    </h3>

    <div class="row data-row">
        <div class="large-12 column">
            <?php
            echo $data->patient->getGenderString() . ' ' . '(' . $data->patient->getAge() . ') ';
            if ($data->patient->ethnic_group) {
                echo $data->patient->getEthnicGroupString();
            }
            ?>
        </div>
    </div>

    <b><?php echo CHtml::encode($data->getAttributeLabel('external_trial_identifier')); ?>:</b>
    <span id="ext-trial-id-<?php echo $data->id; ?>"><?php echo CHtml::encode($data->external_trial_identifier); ?></span>
    <a id="ext-trial-id-link-<?php echo $data->id; ?>" href="javascript:void(0)"
       onclick="editExternalTrialIdentifier(<?php echo $data->id; ?>)">edit</a>

    <div id="ext-trial-id-form-<?php echo $data->id; ?>" style="display:none">
        <input id="trial-patient-ext-id-<?php echo $data->id; ?>" type="text" display="inline"
               value="<?php echo $data->external_trial_identifier; ?>" width="50"/>
        <a id="ext-trial-id-save-<?php echo $data->id; ?>" href="javascript:void(0)" display="inline"
           onclick="saveExternalTrialIdentifier(<?php echo $data->id; ?>)">save</a>
    </div>

    <?php $this->widget('PatientDiagnosesAndMedicationsWidget',
        array(
            'patient' => $data->patient,
        )
    ); ?>

    <?php if ($data->patient_status == TrialPatient::STATUS_SHORTLISTED): ?>
        <button onclick="changePatientStatus(this, <?php echo $data->id; ?>, <?php echo TrialPatient::STATUS_ACCEPTED; ?>)"
                class="accept-patient-link">Accept Patient
        </button>
    <?php endif; ?>

    <?php if ($data->patient_status == TrialPatient::STATUS_SHORTLISTED || $data->patient_status == TrialPatient::STATUS_ACCEPTED): ?>
        <button onclick="changePatientStatus(this, <?php echo $data->id; ?>, <?php echo TrialPatient::STATUS_REJECTED; ?>)"
                class="accept-patient-link">Reject Patient
        </button>
    <?php endif; ?>

    <?php if ($data->patient_status == TrialPatient::STATUS_REJECTED): ?>
        <button onclick="changePatientStatus(this, <?php echo $data->id; ?>, <?php echo TrialPatient::STATUS_SHORTLISTED; ?>)"
                class="accept-patient-link">Shortlist Patient
        </button>
    <?php endif; ?>
</div>

