<?php
/* @var $this TrialPatientController */
/* @var $model TrialPatient */

$this->breadcrumbs=array(
	'Trial Patients'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List TrialPatient', 'url'=>array('index')),
	array('label'=>'Manage TrialPatient', 'url'=>array('admin')),
);
?>

<h1>Create TrialPatient</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>