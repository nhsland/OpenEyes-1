<?php
/* @var $this PatientController */
?>
<section class="box patient-info js-toggle-container">
  <h3 class="box-title">Trials:</h3>
  <a href="#" class="toggle-trigger toggle-hide js-toggle">
		<span class="icon-showhide">
			Show/hide this section
		</span>
  </a>
    <a href="#" class="toggle-trigger toggle-hide js-toggle">
      <span class="icon-showhide">
        Show/hide this section
      </span>
    </a>
  </header>

  <div class="js-toggle-body">

    <table class="plain patient-data">
      <thead>
      <tr>
        <th>Trial</th>
        <th>Control Status</th>
        <th>Trial Status</th>
        <th>Trial Type</th>
        <th>Date Started</th>
        <th>Date Ended</th>
      </tr>
      </thead>
      <tbody>
      <?php foreach ($this->patient->trials as $trialPatient): ?>
        <tr>
          <td><?php
              if (Trial::checkTrialAccess(Yii::app()->user, $trialPatient->trial_id,
                  UserTrialPermission::PERMISSION_VIEW)
              ) {
                  echo Chtml::link(CHtml::encode($trialPatient->trial->name),
                      Yii::app()->controller->createUrl('/OETrial/trial/view', array('id' => $trialPatient->trial_id)));
              } else {
                  echo CHtml::encode($trialPatient->trial->name);
              }
              ?>
          </td>
          <td><?php echo 'TODO'; //TODO; ?></td>
          <td><?php echo $trialPatient->getStatusForDisplay(); ?></td>
          <td><?php echo $trialPatient->trial->getTypeString(); ?></td>
          <td><?php echo $trialPatient->trial->getCreatedDateForDisplay(); ?></td>
          <td><?php echo $trialPatient->trial->getClosedDateForDisplay(); ?></td>
        </tr>
      <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</section>
