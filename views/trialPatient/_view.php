<?php
/* @var $this TrialPatientController */
/* @var $data TrialPatient */
?>

<div class="trialPatientContainer" id="<?php echo $data->id; ?>">

    <?php echo CHtml::link(CHtml::encode($data->patient->getFullName()), array('//patient/view', 'id' => $data->patient_id)); ?>
    <br/>

    <b><?php echo CHtml::encode($data->getAttributeLabel('external_trial_identifier')); ?>:</b>
    <?php echo CHtml::encode($data->external_trial_identifier); ?>
    <br/>

    <?php if ($data->patient_status == TrialPatient::STATUS_SHORTLISTED) {
        echo CHtml::link('Accept Patient',
            'javascript:void(0)',
            array(
                'onclick' => "changePatientStatus(this, $data->id, " . TrialPatient::STATUS_ACCEPTED . ")", 'class' => 'accept-patient-link'
            )
        );
    }

    if ($data->patient_status == TrialPatient::STATUS_SHORTLISTED || $data->patient_status == TrialPatient::STATUS_ACCEPTED) {
        echo CHtml::link('Reject Patient',
            'javascript:void(0)',
            array(
                'onclick' => "changePatientStatus(this, $data->id, " . TrialPatient::STATUS_REJECTED . ")", 'class' => 'accept-patient-link'
            )
        );
    }

    if ($data->patient_status == TrialPatient::STATUS_REJECTED) {
        echo CHtml::link('Shortlist Patient',
            'javascript:void(0)',
            array(
                'onclick' => "changePatientStatus(this, $data->id, " . TrialPatient::STATUS_SHORTLISTED . ")", 'class' => 'accept-patient-link'
            )
        );
    } ?>
</div>