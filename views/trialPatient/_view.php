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

    <?php echo CHtml::link('Accept Patient', array('trialPatient/accept', 'id' => $data->id)); ?>

    <?php /* echo CHtml::button('Accept Patient 2', array('class' => 'acceptPatientLink')); */?>
    <?php echo CHtml::link('Accept Patient 2', 'javascript:void(0)', array('onclick'=> "acceptPatient(this, $data->id)", 'class' => 'accept-patient-link')); ?>
</div>