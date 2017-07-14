<?php
/* @var TrialController $this */
/* @var UserTrialPermission $data */

$canManageTrial = Trial::checkTrialAccess(Yii::app()->user, $data->trial_id, UserTrialPermission::PERMISSION_MANAGE);
?>

<tr data-permission-id="<?php echo $data->id; ?>">
  <td>
      <?php echo CHtml::encode($data->user->getFullName()); ?>

  </td>
  <td>
      <?php echo CHtml::encode($data->role); ?>
  </td>
  <td>
      <?php echo UserTrialPermission::getPermissionOptions()[$data->permission]; ?></td>
    <?php if ($canManageTrial): ?>
      <td>
          <?php if ($data->user_id !== Yii::app()->user->id): ?>
            <a href="#" rel="<?php echo $data->id; ?>" class="small removePermission">
              Remove
            </a>
          <?php endif; ?>
      </td>
    <?php endif; ?>
</tr>