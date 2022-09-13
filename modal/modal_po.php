<?php 
include_once('../conn.php');
$fetchdata = new DB_con();
$dataBrand = $fetchdata->fetchdataBrand();
$dataSizeRelate = $fetchdata->fetchdataSizeRelate($_GET['modal_id']); ?>
<div id="add_data_Modal" class="modal" data-modal="add_data_modal">
    <div class="modal-inner">
        <div class="modal-content">
            <div class="modal-header">
                <input type="hidden" name="" id="temp_" class="modal_brand" value="<?php echo $_GET['modal_id'];?>">
                <a class="modal-close" data-modal-close="add_data_modal" data-id="<?php echo $_GET['modal_id'];?>" onclick="modal_close()" href="#">x</a>
            </div>
            <div class="modal-body">
                <table>
                    <tbody>
                        <tr>
                            <td rowspan="2">ขนาด</td>
                            <?php foreach ($dataSizeRelate as $key => $value) { ?>
                                <td><?=$value['rn_itemweight_weightSize_id']?></td>
                            <?php } ?>
                        </tr>
                        <tr>
                            <?php foreach ($dataSizeRelate as $key => $value) { ?>
                            <td>
                                <input type="number" min="0" style="width: 50px;" class="form-control pickitem weightSize_<?=$value['rn_itemweight_weight_NoID']?>" 
                                id="input_<?=$_GET['modal_id']?>_<?=$value['rn_itemweight_weight_NoID']?>_Adv" 
                                data-Cytype="Adv" data-brand="<?=$_GET['modal_id'];?>" data-weight="<?=$value['rn_itemweight_wightSize']?>" data-sizeid="<?=$value['rn_itemweight_weight_NoID']?>">
                            </td>
                            <?php } ?>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>