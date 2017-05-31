<?php
/* @var $this TrialController */
/* @var $model Trial
 * @var $canUpdateTrial boolean
 * @var $shortlistedPatientDataProvider CActiveDataProvider
 * @var $acceptedPatientDataProvider CActiveDataProvider
 * @var $rejectedPatientDataProvider CActiveDataProvider
 */

$this->breadcrumbs = array(
    'My Trials' => array('/OETrial/trial'),
    $model->name,
);
?>

<h1 class="badge">Trial</h1>
<div class="box content admin-content">

    <?php $this->widget('zii.widgets.CBreadcrumbs', array(
        'links' => $this->breadcrumbs,
    )); ?>

    <div class="large-10 column content admin large-centered">
        <div class="box admin">
            <h1 class="text-center"><?php echo $model->name; ?>
                <?php if ($canUpdateTrial) {
                    echo Chtml::link('[edit]', array('/OETrial/trial/update', 'id' => $model->id));
                } ?></h1>

            <b><?php echo CHtml::encode($model->getAttributeLabel('description')); ?>:</b>
            <?php echo CHtml::encode($model->description); ?>
            <br/>

            <b><?php echo CHtml::encode($model->getAttributeLabel('owner_user_id')); ?>:</b>
            <?php echo CHtml::encode($model->ownerUser->getFullName()); ?>
            <br/>

            <b><?php echo CHtml::encode($model->getAttributeLabel('created_date')); ?>:</b>
            <?php echo CHtml::encode($model->created_date); ?>
            <br/>
        </div>
    </div>
</div>

<?php /*
Yii::app()->clientScript->registerScript('acceptPatient', "
$('.acceptPatientLink').click(function() {
  var id = $(this).parent('.trialPatientContainer').attr('id');
  $.ajax({
    url: '" . Yii::app()->controller->createUrl('/OETrial/trialPatient/accept') . "/' + id,
    type: 'GET',
    success: function(response) {
      $.fn.yiiListView.update('shortlistedPatientList');
    }
  });
});");*/
?>

<script type="application/javascript">
function acceptPatient(object, trial_patient_id) {
  $.ajax({
    url: '<?php echo Yii::app()->controller->createUrl('/OETrial/trialPatient/accept'); ?>/' + trial_patient_id,
    type: 'GET',
    success: function(response) {
      $.fn.yiiListView.update('shortlistedPatientList');
    }
  });
}
</script>

<h2>Shortlisted Patients</h2>
<?php $this->widget('zii.widgets.CListView', array(
    'id' => 'shortlistedPatientList',
    'dataProvider' => $shortlistedPatientDataProvider,
    'itemView' => '/trialPatient/_view',
)); ?>

<h2>Accepted Patients</h2>
<?php $this->widget('zii.widgets.CListView', array(
    'dataProvider' => $acceptedPatientDataProvider,
    'itemView' => '/trialPatient/_view',
)); ?>

<h2>Rejected Patients</h2>
<?php $this->widget('zii.widgets.CListView', array(
    'dataProvider' => $rejectedPatientDataProvider,
    'itemView' => '/trialPatient/_view',
)); ?>

