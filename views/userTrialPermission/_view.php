<?php
/* @var TrialController $this */
/* @var UserTrialPermission $userPermission */
?>

<tr data-permission-id="<?php echo $data->id; ?>">
  <td><?php echo $data->user->getFullName(); ?></td>
  <td><?php echo UserTrialPermission::getPermissionOptions()[$data->permission]; ?></td>
  <td>
    <a href="#" rel="<?php echo $data->id; ?>" class="small removePermission">
      Remove
    </a>
  </td>
</tr>