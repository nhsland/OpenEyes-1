<?php
/* @var $this TrialController */
/* @var $data Trial */
?>

<div class="view">

    <b><?php echo CHtml::link(CHtml::encode($data->name), array('view', 'id' => $data->id)); ?></b>
    <br/>
    <?php echo CHtml::encode($data->description); ?>
</div>