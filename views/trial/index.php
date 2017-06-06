<?php
/* @var $this TrialController */
/* @var $dataProvider CActiveDataProvider */

?>
<h1 class="badge">Trials</h1>

<div class="row">
    <div class="large-9 column">

        <div class="box generic">

            <?php
            $dataProvided = $dataProvider->getData();
            $items_per_page = $dataProvider->getPagination()->getPageSize();
            $page_num = $dataProvider->getPagination()->getCurrentPage();
            $from = ($page_num * $items_per_page) + 1;
            $to = ($page_num + 1) * $items_per_page;
            $to = min($to, $dataProvider->totalItemCount);
            ?>
            <h2>
                My Trials: viewing <?php echo $from ?> - <?php echo $to ?>
                of <?php echo $dataProvider->totalItemCount ?>
            </h2>

            <table id="patient-grid" class="grid">
                <thead>
                <tr>
                    <?php foreach (array('Name', 'Date Created') as $i => $field) { ?>
                        <th id="patient-grid_c<?php echo $i; ?>">
                            <?php
                            $new_sort_dir = ($i == $sort_by) ? 1 - $sort_dir : 0;
                            echo CHtml::link(
                                $field,
                                Yii::app()->createUrl('/OETrial/trial/index', array('sort_by' => $i, 'sort_dir' => $new_sort_dir, 'page_num' => $page_num))
                            );
                            ?>
                        </th>
                    <?php } ?>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($dataProvided as $i => $result) { ?>
                    <tr id="r<?php echo $result->id ?>" class="clickable">
                        <td><?php echo $result->name ?></td>
                        <td><?php echo date('d/m/Y', strtotime($result->created_date)); ?></td>
                    </tr>
                <?php } ?>
                </tbody>
                <tfoot class="pagination-container">
                <tr>
                    <td colspan="7">
                        <?php
                        $this->widget('LinkPager', array(
                            'pages' => $dataProvider->getPagination(),
                            'maxButtonCount' => 15,
                            'cssFile' => false,
                            'selectedPageCssClass' => 'current',
                            'hiddenPageCssClass' => 'unavailable',
                            'htmlOptions' => array(
                                'class' => 'pagination',
                            ),
                        ));
                        ?>
                    </td>
                </tr>
                </tfoot>
            </table>
        </div><!--- /.box -->

    </div><!-- /.large-9.column -->
    <div class="large-3 column">
        <div class="box generic">
            <p><span class="highlight"><?php echo CHtml::link('Create a New Trial', array('create')) ?></span></p>
        </div>
    </div>
</div>


<script type="text/javascript">
    $('#patient-grid tr.clickable').click(function () {
        window.location.href = '<?php echo Yii::app()->createUrl('/OETrial/trial/view')?>/' + $(this).attr('id').match(/[0-9]+/);
        return false;
    });
</script>