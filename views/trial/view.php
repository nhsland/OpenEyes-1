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

<script type="application/javascript">
    function changePatientStatus(object, trial_patient_id, new_status) {
        $.ajax({
            url: '<?php echo Yii::app()->controller->createUrl('/OETrial/trialPatient/changeStatus'); ?>/' + trial_patient_id + '?new_status=' + new_status,
            type: 'GET',
            success: function (response) {
                $.fn.yiiListView.update('shortlistedPatientList');
                $.fn.yiiListView.update('acceptedPatientList');
                $.fn.yiiListView.update('rejectedPatientList');
            }
        });
    }

    function toggleSection(section, reference) {

        //make the collapse content to be shown or hidden
        var toggle_switch = $(section);
        $(reference).toggle(function () {
            if ($(reference).css('display') === 'none') {
                //change the button label to be 'Show'
                toggle_switch.html($(section).attr('data-show-label'));
            } else {
                //change the button label to be 'Hide'
                toggle_switch.html($(section).attr('data-hide-label'));
            }
        });

        // return false so the link isn't followed
        return false;
    }
</script>

<div>
    <button href="#collapse1" class="nav-toggle">Show</button>
</div>
<div id="collapse1" style="display:none">
    <p>Bla bla bla bla</p>
</div>

<h2>Shortlisted Patients</h2>
<?php $this->widget('zii.widgets.CListView', array(
    'id' => 'shortlistedPatientList',
    'dataProvider' => $shortlistedPatientDataProvider,
    'itemView' => '/trialPatient/_view',
)); ?>

<h2>Accepted Patients</h2>
<?php $this->widget('zii.widgets.CListView', array(
    'id' => 'acceptedPatientList',
    'dataProvider' => $acceptedPatientDataProvider,
    'itemView' => '/trialPatient/_view',
)); ?>

<h2>Rejected Patients</h2>
<?php $this->widget('zii.widgets.CListView', array(
    'id' => 'rejectedPatientList',
    'dataProvider' => $rejectedPatientDataProvider,
    'itemView' => '/trialPatient/_view',
)); ?>

