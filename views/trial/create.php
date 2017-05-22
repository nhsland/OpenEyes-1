<?php
/* @var $this TrialController */
/* @var $model Trial */

$this->breadcrumbs = array(
    'Trials' => array('index'),
    'Create',
);

$this->menu = array(
    array('label' => 'List Trial', 'url' => array('index')),
    array('label' => 'Manage Trial', 'url' => array('admin')),
);
?>

    <h1>Create Trial</h1>

<?php $this->renderPartial('_form', array('model' => $model)); ?>