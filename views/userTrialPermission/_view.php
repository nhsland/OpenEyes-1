<?php
/* @var TrialController $this */
/* @var UserTrialPermission $userPermission */
?>

<tr data-permission-id="<?php echo $userPermission->id; ?>">
  <td><?php echo $userPermission->user->getFullName(); ?></td>
  <td><?php echo UserTrialPermission::getPermissionOptions()[$userPermission->permission]; ?></td>
  <td>
    <a href="#" rel="<?php echo $userPermission->id; ?>" class="small removePermission">
      Remove
    </a>
  </td>
</tr>