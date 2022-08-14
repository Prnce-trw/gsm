<?php 
include_once('../../conn.php');
include('../../function.php');
$fetchdata = new DB_con();
$branchID           = $_POST['branchID'];
$sizeID             = $_POST['sizeID'];
$FPID               = $_POST['fpID'];
$history            = $fetchdata->CheckPriceHistory($branchID, $sizeID, $FPID);
?>
<div id="modal_pricehistory" class="modal" data-modal="modal_pricehistory">
    <div class="modal-inner">
        <div class="modal-content">
            <div class="modal-header">
                <input type="hidden" name="" id="" class="modal_brand" value="">
                <a class="modal-close" data-modal-close="modal_pricehistory" data-id="" onclick="modal_close()" href="#">x</a>
            </div>
            <div class="modal-body">
                <table>
                    <thead>
                        <tr>
                            <th width="80" height="30">ลำดับ</th>
                            <th height="30">รายการ</th>
                            <th height="30">ผู้ทำรายการ</th>
                            <th width="80" height="30">ราคา</th>
                            <th height="30">วันที่ปรับราคา</th>
                            <!-- <th width="80" height="30">สถานะ</th> -->
                        </tr>
                    </thead>
                    <tbody>
                    <?php if (!is_array($history)) { foreach ($history as $key => $value) { ?>
                        <tr>
                            <td width="80" height="30"><?=$key+1?></td>
                            <td class="text-left">ถังแก๊สขนาด <?=$value['weightSize_id']?> กก.</td>
                            <td></td>
                            <td><?=$value['PB_itemPrice']?></td>
                            <td><?=$value['created_at']?></td>
                            <!-- <td><?=StatustoText($value['PB_active'])?></td> -->
                        </tr>
                    <?php } } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>