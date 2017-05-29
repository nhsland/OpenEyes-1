<?php
/* @var $this TrialPatientController */
/* @var $model TrialPatient */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'trial-patient-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'external_trial_identifier'); ?>
		<?php echo $form->textField($model,'external_trial_identifier',array('size'=>60,'maxlength'=>64)); ?>
		<?php echo $form->error($model,'external_trial_identifier'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'trial_id'); ?>
		<?php echo $form->textField($model,'trial_id'); ?>
		<?php echo $form->error($model,'trial_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'patient_id'); ?>
		<?php echo $form->textField($model,'patient_id',array('size'=>10,'maxlength'=>10)); ?>
		<?php echo $form->error($model,'patient_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'patient_status'); ?>
		<?php echo $form->textField($model,'patient_status',array('size'=>10,'maxlength'=>10)); ?>
		<?php echo $form->error($model,'patient_status'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'last_modified_user_id'); ?>
		<?php echo $form->textField($model,'last_modified_user_id',array('size'=>10,'maxlength'=>10)); ?>
		<?php echo $form->error($model,'last_modified_user_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'last_modified_date'); ?>
		<?php echo $form->textField($model,'last_modified_date'); ?>
		<?php echo $form->error($model,'last_modified_date'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'created_user_id'); ?>
		<?php echo $form->textField($model,'created_user_id',array('size'=>10,'maxlength'=>10)); ?>
		<?php echo $form->error($model,'created_user_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'created_date'); ?>
		<?php echo $form->textField($model,'created_date'); ?>
		<?php echo $form->error($model,'created_date'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->