<?php
/* @var $this TrialController */
/* @var $model Trial */

$this->breadcrumbs = array(
    'Trials' => array('index'),
    $model->name,
);

$this->menu = array(
    array('label' => 'List Trial', 'url' => array('index')),
    array('label' => 'Create Trial', 'url' => array('create')),
    array('label' => 'Update Trial', 'url' => array('update', 'id' => $model->id)),
    array('label' => 'Delete Trial', 'url' => '#', 'linkOptions' => array('submit' => array('delete', 'id' => $model->id), 'confirm' => 'Are you sure you want to delete this item?')),
    array('label' => 'Manage Trial', 'url' => array('admin')),
);
?>

<h1>View Trial #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
    'data' => $model,
    'attributes' => array(
        'id',
        'name',
        'description',
        'owner_user_id',
        'last_modified_date',
        'last_modified_user_id',
        'created_user_id',
        'created_date',
    ),
)); ?>
