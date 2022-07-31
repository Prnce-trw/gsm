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
                                <td><?=$value['weightSize_id']?></td>
                            <?php } ?>
                        </tr>
                        <tr>
                            <?php foreach ($dataSizeRelate as $key => $value) { ?>
                            <td>
                                <select name="" class="pickitem weightSize_<?=$value['order_by_no']?>" id="" data-Cytype="Adv" data-brand="<?=$_GET['modal_id'];?>" data-size="<?=$value['order_by_no']?>">
                                    <?php for ($n=0; $n <=20 ; $n++) { ?>
                                        <option value="<?php echo $n; ?>" <?php echo $n == 0 ? 'selected':'' ?>><?php echo $n; ?></option>
                                    <?php } ?>
                                </select>
                            </td>
                            <?php } ?>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>