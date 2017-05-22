<?php
/* @var $this TrialController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs = array(
    'Trials',
);

$this->menu = array(
    array('label' => 'Create Trial', 'url' => array('create')),
    array('label' => 'Manage Trial', 'url' => array('admin')),
);
?>
<h1 class="badge">Trial</h1>
<div class="box content admin-content">
    <div class="large-10 column content admin large-centered">

        <div class="box admin">
            <h1 class="text-center">Create Trial</h1>
            <h1>Trials</h1>

            <?php $this->widget('zii.widgets.CListView', array(
                'dataProvider' => $dataProvider,
                'itemView' => '_view',
            )); ?>
        </div>
    </div>
</div>

