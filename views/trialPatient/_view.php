<?php
/* @var $this TrialPatientController */
/* @var $data TrialPatient */
?>

<div class="view">

    <?php echo CHtml::link(CHtml::encode($data->patient->getFullName()), array('//patient/view', 'id' => $data->patient_id)); ?>
    <br/>

    <b><?php echo CHtml::encode($data->getAttributeLabel('external_trial_identifier')); ?>:</b>
    <?php echo CHtml::encode($data->external_trial_identifier); ?>
    <br/>

</div>