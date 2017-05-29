<?php
/* @var $this TrialPatientController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Trial Patients',
);

$this->menu=array(
	array('label'=>'Create TrialPatient', 'url'=>array('create')),
	array('label'=>'Manage TrialPatient', 'url'=>array('admin')),
);
?>

<h1>Trial Patients</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
