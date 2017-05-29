<?php
/* @var $this TrialController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs = array(
    'My Trials',
);

?>
<h1 class="badge">Trial</h1>
<div class="box content admin-content">
    <div class="large-10 column content admin large-centered">
        <?php $this->widget('zii.widgets.CBreadcrumbs', array(
            'links' => $this->breadcrumbs,
        )); ?>
        <div class="box admin">
            <h1>My Trials</h1>

            <?php echo CHtml::link('Create a Trial', array('create')); ?>

            <?php $this->widget('zii.widgets.CListView', array(
                'dataProvider' => $dataProvider,
                'itemView' => '_view',
            )); ?>
        </div>
    </div>
</div>

