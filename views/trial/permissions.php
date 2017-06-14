<?php
/* @var $this TrialController */
/* @var $model Trial
 * @var UserTrialPermission $newPermission
 */

?>

<h1 class="badge">Trial Sharing</h1>
<div class="row">
    <div class="large-9 column">

        <div class="box admin">
            <h1 class="text-center"><?php echo $model->name; ?></h1>
        </div>

        <table class="plain patient-data" id="currentAllergies">
            <thead>
            <tr>
                <th>User</th>
                <th>Permission</th>
                <th>Actions</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($model->userPermissions as $userPermission): ?>
                <tr data-permission-id="<?php echo $userPermission->id; ?>">
                    <td><?php echo $userPermission->user->getFullName(); ?></td>
                    <td><?php echo UserTrialPermission::getPermissionOptions()[$userPermission->permission]; ?></td>
                    <td>
                        <a href="#" rel="<?php echo $userPermission->id; ?>" class="small removePermission">
                            Remove
                        </a>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>

        <div id="add_permission">
            <?php
            $form = $this->beginWidget('FormLayout', array(
                'id' => 'add-permission',
                'enableAjaxValidation' => false,
                'htmlOptions' => array('class' => 'form add-data'),
                'action' => array('trial/addPermission', 'id' => $model->id),
                'layoutColumns' => array(
                    'label' => 3,
                    'field' => 9,
                ),
            )) ?>

            <div class="large-4 column end"><?php
                echo $form->error($newPermission, 'user_id');
                $this->widget('zii.widgets.jui.CJuiAutoComplete', array(
                    'name' => 'user_id',
                    'id' => 'autocomplete_user_id',
                    'source' => "js:function(request, response) {
                                    $.getJSON('/user/autoComplete', {
                                            term : request.term
                                    }, response);
                            }",
                    'options' => array(
                        'select' => "js:function(event, ui) {
                                    removeSelectedUser();
                                    addItem('selected_user_wrapper', ui);
                                    
                                    return false;
                    }",
                        'response' => 'js:function(event, ui){
                        if(ui.content.length === 0){
                            $("#no_user_result").show();
                        } else {
                            $("#no_user_result").hide();
                        }
                    }'
                    ),
                    'htmlOptions' => array(
                        'placeholder' => 'search Users',
                    ),

                )); ?>
            </div>
        </div>
        <div id="selected_user_wrapper" class="row field-row <?php echo !$newPermission->user_id ? 'hide' : '' ?>">
            <div class="large-offset-4 large-8 column selected_user end alert-box">
                <span class="name"><?php echo $newPermission->user_id ? $newPermission->user->getFullName() : '' ?></span>
                <a href="javascript:void(0)" class="remove right">remove</a>
            </div>
            <?php echo CHtml::hiddenField('user_id', $newPermission->user_id, array('class' => 'hidden_id')); ?>
        </div>
        <div id="no_gp_result" class="row field-row hide">
            <div class="large-offset-4 large-8 column selected_user end">No result</div>
        </div>


        <?php echo CHtml::dropDownList('permission', 'Select One...', UserTrialPermission::getPermissionOptions(), array('id' => 'permission')); ?>

        <div class="buttons">
            <img src="<?php echo Yii::app()->assetManager->createUrl('img/ajax-loader.gif') ?>"
                 class="add_allergy_loader" style="display: none;"/>
            <button class="secondary small btn_save_permission" type="submit">Add</button>
        </div>

        <?php echo CHtml::hiddenField('trial_id', $model->id, array('class' => 'hidden_id')); ?>
        <?php $this->endWidget() ?>
    </div><!-- /.large-9.column -->
</div>


<!-- Confirm deletion dialog -->
<div id="confirm_remove_permission_dialog" title="Confirm remove permission" style="display: none;">
    <div id="delete_permission">
        <!--<div class="alert-box alert with-icon">
            <strong>WARNING: This will remove the allergy from the patient record.</strong>
        </div>-->
        <p>
            <strong>Are you sure you want to proceed?</strong>
        </p>
        <div class="buttons">
            <input type="hidden" id="remove_permission_id" value="" />
            <button type="submit" class="warning small btn_remove_permission">Remove permission</button>
            <button type="submit" class="secondary small btn_cancel_remove_permission">Cancel</button>
            <img class="loader" src="<?php echo Yii::app()->assetManager->createUrl('img/ajax-loader.gif')?>" alt="loading..." style="display: none;" />
        </div>
    </div>
</div>


<script>
    function addItem(wrapper_id, ui) {
        var $wrapper = $('#' + wrapper_id);

        $wrapper.find('span.name').text(ui.item.label);
        $wrapper.show();
        $wrapper.find('.hidden_id').val(ui.item.id);
    }

    function removeSelectedUser() {
        $('#no_user_result').hide();
        $('.selected_user span.name').text('');
        $('#selected_user_wrapper').hide();
    }

    $(document).ready(function () {

        $('#selected_user_wrapper').on('click', '.remove', function () {
            removeSelectedUser();
        });

        $('button.btn_save_permission').click(function () {
            if ($('#user_id').val() == '') {
                new OpenEyes.UI.Dialog.Alert({
                    content: "Please select a user and a permission level"
                }).open();
                return false;
            }
            $('img.add_permission_loader').show();
            return true;
        });

    });

</script>