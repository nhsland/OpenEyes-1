<?php
/* @var $this TrialController */
/* @var $model Trial */

$this->breadcrumbs = array(
    'Trials' => array('index'),
    $model->name => array('view', 'id' => $model->id),
    'Update',
);

$this->menu = array(
    array('label' => 'List Trial', 'url' => array('index')),
    array('label' => 'Create Trial', 'url' => array('create')),
    array('label' => 'View Trial', 'url' => array('view', 'id' => $model->id)),
    array('label' => 'Manage Trial', 'url' => array('admin')),
);
?>

    <h1>Update Trial <?php echo $model->id; ?></h1>

<?php $this->renderPartial('_form', array('model' => $model)); ?>