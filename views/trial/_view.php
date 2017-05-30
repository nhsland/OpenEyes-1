<?php
/* @var $this TrialController */
/* @var $data Trial */
?>

<div class="view">

    <b><?php
        if (Trial::canUserAccessTrial(Yii::app()->user, $data->id, 'manage' )) {
            echo Chtml::link(CHtml::encode($data->name), array('manage', 'id' => $data->id));
        } else if (Trial::canUserAccessTrial(Yii::app()->user, $data->id, 'view' )) {
            echo CHtml::link(CHtml::encode($data->name), array('view', 'id' => $data->id));
        } else {
            echo CHtml::encode($data->name);
        }
        ?>
    </b>
    <br/>

    <?php echo CHtml::encode($data->description); ?>

</div>