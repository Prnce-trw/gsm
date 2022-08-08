<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/select2.min.css">
    <link rel="stylesheet" href="css/custom.css">
    <link rel="stylesheet" href="css/jquery-ui.min.css">
    <title>GSM</title>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
</head>

<body>
    <section id="purchase">
        <form action="controller/PRController.php" method="POST">
            <input type="hidden" name="parameter" id="" value="PreOrderReCeipt">
            <input type="hidden" name="POID" id="" value="<?=$_GET['id']?>">
            <div class="container">
                <?php
                include_once('conn.php');
                include('function.php');
                $fetchdata  = new DB_con();
                $dataFP     = $fetchdata->fetchdataFP();
                $sql        = $fetchdata->editPO($_GET['id']);
                $row        = mysqli_fetch_array($sql);
                $itemPO     = $fetchdata->editCylinderPO($_GET['id']); 
                $totalSum   = $fetchdata->SumAmountItem($_GET['id']);
                $totalItem  = mysqli_fetch_array($totalSum);
                $dataBrand  = $fetchdata->fetchdataBrand();
                $dataSize   = $fetchdata->fetchdataSize();
                $dataBS     = $fetchdata->fetchdataBS();
                ?>
                <div class="row">
                    <div class="col-50">
                        <label class="label-titile">เอกสารใบกำกับ</label>
                        <input type="text" class="form-control" name="RefDO" placeholder="หมายเลขเอกสารใบกำกับ..." value="<?=$row['head_pr_doc_ref']?>">
                    </div>
                </div>
                <div class="row">
                    <div class="col-50">
                        <label for="filling" class="label-titile">โรงบรรจุ</label>
                        <select class="gas_filling js-example-basic-single" name="gas_filling" id="gas_filling" style="width: 150px;">
                            <option selected disabled>เลือกโรงบรรจุ...</option>
                            <?php foreach ($dataFP as $key => $value) { ?>
                                <option value="<?=$value['FP_ID']; ?>" <?php echo $row['head_po_fillstation'] == $value['FP_ID'] ? 'selected':'' ?>><?=$value['FP_Name']; ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="col-50">
                        <label class="label-titile">หมายเลขเอกสาร</label> <?=$_GET['id']?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-50">
                        <label class="label-titile">รอบ</label>
                        <select name="round" id="" class="js-example-basic-single" style="width: 150px;">
                            <option selected disabled>เลือกรอบขนส่ง...</option>
                            <?php for ($i=1; $i <= 5; $i++) { ?>
                            <option value="<?php echo $i; ?>" <?php echo $row['head_po_round'] == $i ? 'selected':'' ?>>
                                <?php echo $i; ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="col-50">
                        <label class="label-titile">วันที่</label>
                        <input type="text" class="form-control datepicker" placeholder="วัน/เดือน/ปี" value="<?=($row['created_at'] != null ? date("d/m/Y", strtotime($row['created_at'])) : date("d/m/Y"))?>">
                    </div>
                </div>
                <div class="row">
                    <div class="col-50">
                        <label class="label-titile">เวลาออก</label>
                        <select name="hourOut" class="form-control">
                            <option selected disabled>ชั่วโมง</option>
                            <?php for ($hh=7; $hh <= 22; $hh++) { ?>
                                <option value="<?php echo strlen($hh) < 2 ? '0'.$hh : $hh;?>" <?php echo date("h", strtotime($row['head_pr_timeOut'])) == $hh ? 'selected': ''?>><?php echo strlen($hh) < 2 ? '0'.$hh : $hh;?></option>
                            <?php } ?>
                        </select> : 
                        <select name="minuteOut" class="form-control">
                            <option selected disabled>นาที</option>
                            <?php for ($mm=00; $mm <= 60; $mm++) { ?>
                                <option value="<?php echo strlen($mm) < 2 ? '0'.$mm : $mm;?>" <?php echo date("i", strtotime($row['head_pr_timeOut'])) == $mm ? 'selected': ''?>><?php echo strlen($mm) < 2 ? '0'.$mm : $mm;?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="col-50">
                        <label class="label-titile">เวลาเข้า</label>
                        <select name="hourIn" class="form-control">
                            <option selected disabled>ชั่วโมง</option>
                            <?php for ($hh=7; $hh <= 22; $hh++) { ?>
                                <option value="<?php echo strlen($hh) < 2 ? '0'.$hh : $hh;?>" <?php echo date("h", strtotime($row['head_pr_timeIn'])) == $hh ? 'selected': ''?>><?php echo strlen($hh) < 2 ? '0'.$hh : $hh;?></option>
                            <?php } ?>
                        </select> : 
                        <select name="minuteIn" class="form-control">
                            <option selected disabled>นาที</option>
                            <?php for ($mm=00; $mm <= 60; $mm++) { ?>
                                <option value="<?php echo strlen($mm) < 2 ? '0'.$mm : $mm;?>" <?php echo date("i", strtotime($row['head_pr_timeIn'])) == $mm ? 'selected': ''?>><?php echo strlen($mm) < 2 ? '0'.$mm : $mm;?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
            </div>
            <table >
                <thead>
                    <tr>
                        <th>ลำดับ</th>
                        <th>รายการสินค้า</th>
                        <th>จำนวนที่ออก</th>
                        <th>จำนวนรับจริง</th>
                        <th>น้ำหนัก</th>
                        <th>ราคา/หน่วย</th>
                        <th>จำนวนเงิน</th>
                    </tr>
                </thead>
                <tbody id="edit_PO">
                    <?php 
                    foreach ($itemPO as $key => $rows) {
                    $size_r = multiexplode(array(",",".","|",":","("), $rows['po_itemOut_CySize']); ?>
                    <tr id="tdAppend_<?=$rows['po_itemOut_CyBrand']?>_<?=$rows['weight_NoID']?>">
                        <td>
                            <?=$key+1;?>
                            <input type="hidden" class="PRitemOut" name="pickitem[<?=$key?>]" id="<?=$rows['po_itemOut_CyBrand']?>_<?=$rows['weight_NoID']?>" 
                            data-info="<?=$rows['po_itemOut_CyBrand']?>_<?=$rows['weight_NoID']?>" value="<?=$rows['po_itemOut_CyBrand']?>/<?=$rows['po_itemOut_CySize']?>/<?=$rows['po_itemOut_CyAmount']?>/<?=$rows['po_itemOut_type']?>">
                        </td>
                        <td style="text-align: left;"><?=$rows['po_itemOut_CyBrand']?>/ ขนาด <?=$rows['po_itemOut_CySize']?> กก.</td>
                        <td><div id="qtyIn_<?=$rows['po_itemOut_CyBrand']?>_<?=$rows['weight_NoID']?>"><?=$rows['po_itemOut_CyAmount']?></div></td>
                        <td>
                            <?php
                            $fetchdataitem = new DB_con();
                            $itemEn = $fetchdataitem->fetchitemEntrance($row['head_pr_doc_ref'], $rows['po_itemOut_CyBrand'], $rows['po_itemOut_CySize'], $rows['po_itemOut_type']);
                            $itemEnReal = mysqli_fetch_array($itemEn); ?>
                            <div class="itemAmountrecent_<?=$rows['po_itemOut_CyBrand']?>_<?=$rows['weight_NoID']?>"><?=$rows['po_itemOut_CyAmount']?></div>
                            <input type="hidden" name="itemEntrance[]" id="itemAmountrecent_<?=$rows['po_itemOut_CyBrand']?>_<?=$rows['weight_NoID']?>" value="<?=$rows['po_itemOut_CyAmount']?>">
                        </td>
                        <td>
                            <span class="itemweight" id="result_weight<?=$rows['po_itemOut_CyBrand']?>_<?=$rows['weight_NoID']?>"><?=(float)$rows['wightSize'] * $rows['po_itemOut_CyAmount']?></span>
                        </td>
                        <td>
                            <input type="number" name="unitprice[]" class="form-control itemperprice" id="itemperprice_<?=$rows['po_itemOut_CyBrand']?>_<?=$rows['weight_NoID']?>" class="form-control" style="width: 80px;" value="" data-brand="<?=$rows['po_itemOut_CyBrand']?>" data-sizeid="<?=$rows['weight_NoID']?>" data-size="<?=$rows['wightSize']?>" step="any">
                        </td>
                        <td>
                            <input type="number" name="amtprice[]" id="resultPrice_<?=$rows['po_itemOut_CyBrand']?>_<?=$rows['weight_NoID']?>" class="form-control AmountPrice" data-brand="<?=$rows['po_itemOut_CyBrand']?>" data-size="<?=$rows['order_by_no']?>" style="width: 80px;" data-brand="<?=$rows['po_itemOut_CyBrand']?>" data-sizeid="<?=$rows['weight_NoID']?>" data-size="<?=$rows['wightSize']?>" step="any">
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="5" class="text-right" style="padding-right: 10px;" height="30">จำนวนทั้งหมด</td>
                        <td>
                            <span id="totalitem_PR">
                                <?php echo ($totalItem != null ? $totalItem[0] : '');?>
                            </span>
                            <input type="hidden" name="total" class="totalPR form-control" value="<?php echo ($totalItem != null ? $totalItem[0] : '');?>" style="width: 80px;">
                        </td>
                        <td class="text-left" style="padding-left: 10px;">ถัง</td>
                    </tr>
                    <tr>
                        <td colspan="5" class="text-right" style="padding-right: 10px;" height="30">น้ำหนักรับจริงทั้งหมด</td>
                        <td><div class="totalWeight"></div></td>
                        <td class="text-left" style="padding-left: 10px;">กิโลกรัม</td>
                    </tr>
                    <tr>
                        <td colspan="5" class="text-right" style="padding-right: 10px;" height="30">ราคาทั้งหมด</td>
                        <td><div id="TotalPrice"></div></td>
                        <td class="text-left" style="padding-left: 10px;">บาท</td>
                    </tr>
                </tfoot>
            </table>
            <div class="container">
                <button class="btn btn-primary" style="margin-top: 10px;" onClick="openModal()" type="button">เพิ่มถัง</button>
                <button class="btn btn-warning" style="margin-top: 10px;" onClick="openEditModal()" type="button">แก้ไขจำนวนรับจริง</button>
            </div>
            <div class="container">
                <h4>หมายเหตุ</h4>
                <textarea name="" id="" cols="30" rows="10" style="width: 100%;"><?=$row['head_po_comment']?></textarea>
                <div class="row" style="margin-top: 30px;">
                    <div class="col-12" style="text-align: right;">
                        <button type="submit" class="btn btn-success" onclick="">ยืนยัน</button>
                    </div>
                </div>
            </div>
            <div id="resultModal"></div>
            <div id="result_editinputItem"></div>

            <div id="add_data_Modal" class="modal" data-modal="add_data_modal">
                <div class="modal-inner-ml">
                    <div class="modal-content-ml">
                        <div class="modal-header">
                            <input type="hidden" name="" id="" class="modal_brand" value="">
                            <a class="modal-close-ml" data-modal-close="add_data_modal" data-id="" onclick="modalPR_close()" href="#">x</a>
                        </div>
                        <div class="modal-body">
                        <table>
                            <thead>
                                <tr>
                                    <th width="80" height="30" style="background-color: var(--blue); color: #fff"> ยี่ห้อ\ขนาด</th>
                                    <?php foreach ($dataSize as $key => $value) { ?>
                                    <th width="80" height="30" style="background-color: var(--blue); color: #fff"><?=$value['weightSize_id']?></th>
                                    <?php }// end for(1) ?>
                                </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($dataBrand as $index => $item) {?>
                            <tr>
                                <td width="80" height="50" >
                                    <div class="div-inside"><?=$item['ms_product_name']?></div>
                                </td>
                                <?php //for(1) 
                                foreach ($dataSize as $key => $value) { $stack = null; 
                                    foreach ($dataBS as $keyBS => $valueBS) {
                                        if ($value['weight_NoID'] == $valueBS['brandRelSize_weight_autoID'] && $item['ms_product_id'] == $valueBS['brandRelSize_ms_product_id']) { ?>
                                            <td width="150" height="50">
                                                <div class="row">
                                                    <div class="itemOut_<?=$item['ms_product_id']?>_<?=$value['weight_NoID']?>"></div>&nbsp;/&nbsp;
                                                    <div class="div-inside">
                                                        <select name="" class="pickitem_add_PO item_<?=$item['ms_product_id']?>" id="itemCy_<?=$item['ms_product_id']?>_<?=$value['weight_NoID']?>" data-brand="<?=$item['ms_product_id']?>" data-wightSize="<?=$value['wightSize']?>" data-sizeid="<?=$value['weight_NoID']?>" data-size="<?=$value['weightSize_id']?>">
                                                            <?php for ($n=0; $n <=20 ; $n++) { ?>
                                                                <option value="<?php echo $n; ?>" <?php echo $n == 0 ? 'selected':'' ?>><?php echo $n; ?></option>
                                                            <?php } ?>
                                                        </select>
                                                    </div>
                                                </div>
                                            </td>
                                    <?php $stack = true; } } if (!$stack) { ?>
                                        <td width="150" height="50"></td>
                                <?php } }// end for(1) ?>
                            </tr>
                            <?php }//end for(0) ?>
                            </tbody>
                        </table>
                        </div>
                    </div>
                </div>
            </div>

            <div id="EdititemEnModal" class="modal" data-modal="EdititemEnModal">
                <div class="modal-inner">
                    <div class="modal-content">
                        <div class="modal-header">
                            <input type="hidden" name="" id="" class="modal_brand" value="">
                            <a class="modal-close" data-modal-close="EdititemEnModal" data-id="" onclick="modalPR_close()" href="#">x</a>
                        </div>
                        <div class="modal-body">
                        <table>
                            <thead>
                                <tr>
                                    <th width="80" height="30" style="background-color: var(--yellow);"> ยี่ห้อ\ขนาด</th>
                                    <?php foreach ($dataSize as $key => $value) { ?>
                                    <th width="80" height="30" style="background-color: var(--yellow);"><?=$value['weightSize_id']?></th>
                                    <?php }// end for(1) ?>
                                </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($dataBrand as $index => $item) {?>
                            <tr>
                                <td width="80" height="50" >
                                    <div class="div-inside"><?=$item['ms_product_name']?></div>
                                </td>
                                <?php //for(1) 
                                foreach ($dataSize as $key => $value) { $stack = null; 
                                    foreach ($dataBS as $keyBS => $valueBS) {
                                        if ($value['weight_NoID'] == $valueBS['brandRelSize_weight_autoID'] && $item['ms_product_id'] == $valueBS['brandRelSize_ms_product_id']) { ?>
                                            <td width="80" height="50">
                                                <div class="div-inside">
                                                    <select name="" class="pickitem_edititemEn item_<?=$item['ms_product_id']?>" id="EdititemCy_<?=$item['ms_product_id']?>_<?=$value['weight_NoID']?>" data-brand="<?=$item['ms_product_id']?>" data-wightSize="<?=$value['wightSize']?>" data-sizeid="<?=$value['weight_NoID']?>" data-size="<?=$value['weightSize_id']?>">
                                                        <?php for ($n=0; $n <=20 ; $n++) { ?>
                                                            <option value="<?php echo $n; ?>" <?php echo $n == 0 ? 'selected':'' ?>><?php echo $n; ?></option>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                            </td>
                                    <?php $stack = true; } } if (!$stack) { ?>
                                        <td width="80" height="50"></td>
                                <?php } }// end for(1) ?>
                            </tr>
                            <?php }//end for(0) ?>
                            </tbody>
                        </table>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </section>
    <script src="js/jquery-3.6.0.min.js"></script>
    <script src="js/sweetalert2.all.min.js"></script>
    <script src="js/select2.min.js"></script>
    <script src="js/jquery-ui.min.js"></script>
    <script src="js/JQcustom.js"></script>
</body>

</html>