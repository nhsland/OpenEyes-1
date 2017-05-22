<?php
/* @var $this TrialController */
/* @var $model Trial */
/* @var $form CActiveForm */
?>

<div class="form">

    <?php $form = $this->beginWidget('CActiveForm', array(
        'id' => 'trial-form',
        // Please note: When you enable ajax validation, make sure the corresponding
        // controller action is handling ajax validation correctly.
        // There is a call to performAjaxValidation() commented in generated controller code.
        // See class documentation of CActiveForm for details on this.
        'enableAjaxValidation' => false,
    )); ?>

    <p class="note">Fields with <span class="required">*</span> are required.</p>

    <?php echo $form->errorSummary($model); ?>

    <div class="row">
        <?php echo $form->labelEx($model, 'name'); ?>
        <?php echo $form->textField($model, 'name', array('size' => 60, 'maxlength' => 64)); ?>
        <?php echo $form->error($model, 'name'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model, 'description'); ?>
        <?php echo $form->textField($model, 'description', array('size' => 60, 'maxlength' => 64)); ?>
        <?php echo $form->error($model, 'description'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model, 'owner_user_id'); ?>
        <?php echo $form->textField($model, 'owner_user_id', array('size' => 10, 'maxlength' => 10)); ?>
        <?php echo $form->error($model, 'owner_user_id'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model, 'status'); ?>
        <?php echo $form->dropDownList($model, 'status', $model->getStatusOptions()); ?>
        <?php echo $form->error($model, 'status'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model, 'last_modified_date'); ?>
        <?php echo $form->textField($model, 'last_modified_date'); ?>
        <?php echo $form->error($model, 'last_modified_date'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model, 'last_modified_user_id'); ?>
        <?php echo $form->textField($model, 'last_modified_user_id', array('size' => 10, 'maxlength' => 10)); ?>
        <?php echo $form->error($model, 'last_modified_user_id'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model, 'created_user_id'); ?>
        <?php echo $form->textField($model, 'created_user_id', array('size' => 10, 'maxlength' => 10)); ?>
        <?php echo $form->error($model, 'created_user_id'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model, 'created_date'); ?>
        <?php echo $form->textField($model, 'created_date'); ?>
        <?php echo $form->error($model, 'created_date'); ?>
    </div>

    <div class="row buttons">
        <?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
    </div>

    <?php $this->endWidget(); ?>

</div><!-- form -->