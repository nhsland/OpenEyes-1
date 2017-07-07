<?php
/* @var TrialController $this */
/* @var UserTrialPermission $data */
?>

<tr data-permission-id="<?php echo $data->id; ?>">
  <td><?php echo $data->user->getFullName(); ?></td>
  <td><?php echo UserTrialPermission::getPermissionOptions()[$data->permission]; ?></td>
  <td>
      <?php if ($data->user_id !== Yii::app()->user->id): ?>
        <a href="#" rel="<?php echo $data->id; ?>" class="small removePermission">
          Remove
        </a>
      <?php endif; ?>
  </td>
</tr>