<?php
/* @var TrialController $this */
/* @var CActiveDataProvider $dataProvider */
/* @var string $title */

?>

<h2><?php echo $title; ?></h2>
<table id="patient-grid" class="grid">
    <thead>
    <tr>
        <th></th>
        <th>Name</th>
        <th>Gender</th>
        <th>Age</th>
        <th>Ethnicity</th>
        <th>External Reference</th>
        <th>Treatment Type</th>
        <th>Diagnoses/Medications</th>
        <th>Actions</th>
    </tr>
    </thead>
    <tbody>
    <?php $this->widget('zii.widgets.CListView', array(
        'id' => 'shortlistedPatientList',
        'dataProvider' => $dataProvider,
        'itemView' => '/trialPatient/_view',
    )); ?>
    </tbody>
</table>
