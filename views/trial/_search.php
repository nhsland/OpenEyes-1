<?php
/* @var $this TrialController */
/* @var $model Trial */
/* @var $form CActiveForm */
?>

<div class="wide form">

    <?php $form = $this->beginWidget('CActiveForm', array(
        'action' => Yii::app()->createUrl($this->route),
        'method' => 'get',
    )); ?>

  <div class="row">
      <?php echo $form->label($model, 'id'); ?>
      <?php echo $form->textField($model, 'id', array('size' => 10, 'maxlength' => 10)); ?>
  </div>

  <div class="row">
      <?php echo $form->label($model, 'name'); ?>
      <?php echo $form->textField($model, 'name', array('size' => 60, 'maxlength' => 64)); ?>
  </div>

  <div class="row">
      <?php echo $form->label($model, 'description'); ?>
      <?php echo $form->textField($model, 'description', array('size' => 60, 'maxlength' => 64)); ?>
  </div>

  <div class="row">
      <?php echo $form->label($model, 'owner_user_id'); ?>
      <?php echo $form->textField($model, 'owner_user_id', array('size' => 10, 'maxlength' => 10)); ?>
  </div>

  <div class="row">
      <?php echo $form->label($model, 'last_modified_date'); ?>
      <?php echo $form->textField($model, 'last_modified_date'); ?>
  </div>

  <div class="row">
      <?php echo $form->label($model, 'last_modified_user_id'); ?>
      <?php echo $form->textField($model, 'last_modified_user_id', array('size' => 10, 'maxlength' => 10)); ?>
  </div>

  <div class="row">
      <?php echo $form->label($model, 'created_user_id'); ?>
      <?php echo $form->textField($model, 'created_user_id', array('size' => 10, 'maxlength' => 10)); ?>
  </div>

  <div class="row">
      <?php echo $form->label($model, 'created_date'); ?>
      <?php echo $form->textField($model, 'created_date'); ?>
  </div>

  <div class="row buttons">
      <?php echo CHtml::submitButton('Search'); ?>
  </div>

    <?php $this->endWidget(); ?>

</div><!-- search-form -->