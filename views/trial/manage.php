<?php

$this->breadcrumbs = array(
    'My Trials' => array('/OETrial/trial'),
    $model->name,
);
?>
IMMA EDITAN MAH TRYLE
<h1 class="badge">Trial</h1>
<div class="box content admin-content">

    <?php $this->widget('zii.widgets.CBreadcrumbs', array(
        'links' => $this->breadcrumbs,
    )); ?>

    <div class="large-10 column content admin large-centered">
        <div class="box admin">
            <h1 class="text-center"><?php echo $model->name; ?>
                <?php if (true or Yii::app()->user->checkAccess('/OETrial/trial/update', array('id' => $model->id))) {
                    echo Chtml::link('[edit]', array('/OETrial/trial/update', 'id' => $model->id));
                } ?></h1>

            <b><?php echo CHtml::encode($model->getAttributeLabel('description')); ?>:</b>
            <?php echo CHtml::encode($model->description); ?>
            <br/>

            <b><?php echo CHtml::encode($model->getAttributeLabel('owner_user_id')); ?>:</b>
            <?php echo CHtml::encode($model->ownerUser->getFullName()); ?>
            <br/>

            <b><?php echo CHtml::encode($model->getAttributeLabel('created_date')); ?>:</b>
            <?php echo CHtml::encode($model->created_date); ?>
            <br/>
        </div>
    </div>
</div>


<h2>Shortlisted Patients</h2>
<?php $this->widget('zii.widgets.CListView', array(
    'dataProvider' => $shortlistedPatientDataProvider,
    'itemView' => '/trialPatient/_view',
)); ?>

<h2>Accepted Patients</h2>
<?php $this->widget('zii.widgets.CListView', array(
    'dataProvider' => $acceptedPatientDataProvider,
    'itemView' => '/trialPatient/_view',
)); ?>

<h2>Rejected Patients</h2>
<?php $this->widget('zii.widgets.CListView', array(
    'dataProvider' => $rejectedPatientDataProvider,
    'itemView' => '/trialPatient/_view',
)); ?>
