<?php
/* @var $this TrialPatientController */
/* @var $model TrialPatient */

$this->breadcrumbs=array(
	'Trial Patients'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List TrialPatient', 'url'=>array('index')),
	array('label'=>'Create TrialPatient', 'url'=>array('create')),
	array('label'=>'Update TrialPatient', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete TrialPatient', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage TrialPatient', 'url'=>array('admin')),
);
?>

<h1>View TrialPatient #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'external_trial_identifier',
		'trial_id',
		'patient_id',
		'patient_status',
		'last_modified_user_id',
		'last_modified_date',
		'created_user_id',
		'created_date',
	),
)); ?>
