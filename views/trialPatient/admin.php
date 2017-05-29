<?php
/* @var $this TrialPatientController */
/* @var $model TrialPatient */

$this->breadcrumbs=array(
	'Trial Patients'=>array('index'),
	'Manage',
);

$this->menu=array(
	array('label'=>'List TrialPatient', 'url'=>array('index')),
	array('label'=>'Create TrialPatient', 'url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#trial-patient-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Manage Trial Patients</h1>

<p>
You may optionally enter a comparison operator (<b>&lt;</b>, <b>&lt;=</b>, <b>&gt;</b>, <b>&gt;=</b>, <b>&lt;&gt;</b>
or <b>=</b>) at the beginning of each of your search values to specify how the comparison should be done.
</p>

<?php echo CHtml::link('Advanced Search','#',array('class'=>'search-button')); ?>
<div class="search-form" style="display:none">
<?php $this->renderPartial('_search',array(
	'model'=>$model,
)); ?>
</div><!-- search-form -->

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'trial-patient-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'id',
		'external_trial_identifier',
		'trial_id',
		'patient_id',
		'patient_status',
		'last_modified_user_id',
		/*
		'last_modified_date',
		'created_user_id',
		'created_date',
		*/
		array(
			'class'=>'CButtonColumn',
		),
	),
)); ?>
