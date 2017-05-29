<?php
/* @var $this TrialPatientController */
/* @var $model TrialPatient */

$this->breadcrumbs=array(
	'Trial Patients'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List TrialPatient', 'url'=>array('index')),
	array('label'=>'Create TrialPatient', 'url'=>array('create')),
	array('label'=>'View TrialPatient', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage TrialPatient', 'url'=>array('admin')),
);
?>

<h1>Update TrialPatient <?php echo $model->id; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>