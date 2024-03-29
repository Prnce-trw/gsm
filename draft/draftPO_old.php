<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GSM</title>
    
    <script src="js/jquery.slim.min.js"></script>
    <script src="js/bootstrap.bundle.min.js"></script>
    <script src="js/draft.js"></script>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/select2.min.css">
    <script src="../js/sweetalert2.all.min.js"></script>
    <script src="../js/select2.min.js"></script>
    <script src="../js/jquery-3.6.0.min.js"></script>
    <style>
        section {
            padding: 15px;
            height: 978px;
            background-color: #f8f9fe;
            width: 900px;
        }

        .text-middle {
            vertical-align: middle !important;
        }
    </style>
</head>
<body>
    <?php
    include_once('../conn.php');
    include('../function.php');
    $fetchdata          = new DB_con();
    $dataFP             = $fetchdata->fetchdataFP();
    $dataHeadDoc        = $fetchdata->editPO($_GET['id']);
    $row                = mysqli_fetch_array($dataHeadDoc);
    $dataCars           = $fetchdata->fetchdataCars();
    $dataEmp            = $fetchdata->fetchdataEmp(); 
    $dataBrand          = $fetchdata->fetchdataBrand();
    $dataSize           = $fetchdata->fetchdataSize(); 
    $itemPO             = $fetchdata->editCylinderPO($_GET['id']);
    $totalSum           = $fetchdata->SumAmountItem($_GET['id']); 
    $totalItem          = mysqli_fetch_array($totalSum);
    $dataBS     = $fetchdata->fetchdataBS();
    ?>
    <section>
        <form action="" method="post">
            <div class="form-group row">
                <label class="col-sm-2 col-form-label">โรงบรรจุ</label>
                <div class="col-sm-4">
                    <select class="gas_filling js-example-basic-single" name="gas_filling" id="gas_filling" style="width: 170px;">
                        <option selected disabled>เลือกโรงบรรจุ...</option>
                        <?php foreach ($dataFP as $key => $value) { ?>
                            <option value="<?=$value['FP_ID']; ?>" <?=$row['head_po_fillstation'] == $value['FP_ID'] ? 'selected':'' ?>><?=$value['FP_Name']; ?></option>
                        <?php } ?>
                    </select>
                </div>
                <label class="col-sm-4 col-form-label">หมายเลขเอกสาร <span class="badge badge-primary badge-xl"><?=$_GET['id']?></span></label>
            </div>
            <div class="form-group row">
                <label class="col-sm-2 col-form-label">ทะเบียนรถขนส่ง</label>
                <div class="col-sm-4">
                    <select class="js-example-basic-single" name="car" id="" style="width: 170px;">
                        <option selected disabled>เลือกรถขนส่ง...</option>
                        <?php foreach ($dataCars as $key => $value) { ?>
                            <option value="<?=$value['car_Code']?>"><?=$value['car_license']?> (<?=$value['car_name']?>)</option>
                        <?php } ?>
                    </select>
                </div>
                <label class="col-sm-2 col-form-label">ผู้ขับ</label>
                <div class="col-sm-4">
                    <select class=" js-example-basic-single" name="driver" id="" style="width: 250px;">
                        <option selected disabled>เลือกผู้ขับ...</option>
                        <?php foreach ($dataEmp as $key => $value) { ?>
                            <option value="<?=$value['emp_name']?>"><?=$value['emp_name']?> <?=$value['emp_lastname']?></option>
                        <?php } ?>
                    </select>
                </div>
            </div>
            <hr>
            <table class="table table-bordered table-hover" style="width: 100%;">
                <thead>
                    <tr>
                        <th class="text-center" style="background-color: #e8e8e8;">ลำดับ</th>
                        <th class="text-center" style="background-color: #e8e8e8;">รายการสินค้า</th>
                        <th class="text-center" style="background-color: #e8e8e8;">ประเภทถัง</th>
                        <th class="text-center" style="background-color: #e8e8e8;">จำนวนที่ออก</th>
                        <th class="text-center" style="background-color: #e8e8e8;">น้ำหนัก</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($itemPO as $key => $value) { ?>
                        <tr>
                            <td class="text-center text-middle" style="width: 50px;">
                                <?=$key+1?>
                                <input type="hidden" class="PRitemOut" name="pickitem[<?=$key?>]" id="<?=$value['po_itemOut_CyBrand']?>_<?=$value['weight_NoID']?>" 
                            data-info="<?=$value['po_itemOut_CyBrand']?>_<?=$value['weight_NoID']?>" value="<?=$value['po_itemOut_CyBrand']?>/<?=$value['po_itemOut_CySize']?>/<?=$value['po_itemOut_CyAmount']?>/<?=$value['po_itemOut_type']?>">
                            </td>
                            <td class="text-left text-middle"><?=$value['po_itemOut_CyBrand']?> / ขนาด <?=$value['po_itemOut_CySize']?> กก. </td>
                            <td class="text-center text-middle" style="width: 120px;">
                                <?=CylinderType($value['po_itemOut_type'])?>
                            </td>
                            <td class="text-center" style="width: 120px;">
                                <span id="qtyIn_<?=$value['po_itemOut_CyBrand']?>_<?=$value['weight_NoID']?>"><?=$value['po_itemOut_CyAmount']?></span>
                            </td>
                            <td class="text-center" style="width: 120px;">
                                <span>
                                    <?=number_format((float)$value['wightSize'] * $value['po_itemOut_CyAmount'], 2, '.', '')?>
                                </span>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="3" class="text-right" style="padding-right: 10px;" height="30">จำนวนทั้งหมด</td>
                        <td class="text-right" style="padding-right: 10px;">
                            <span id="totalitem_PR">
                                <?php echo ($totalItem != null ? $totalItem[0] : '');?>
                            </span>
                            <input type="hidden" name="total" class="totalPR form-control" value="<?php echo ($totalItem != null ? $totalItem[0] : '');?>">
                        </td>
                        <td class="text-left" style="padding-left: 10px;">ถัง</td>
                    </tr>
                    <tr>
                        <td colspan="3" class="text-right" style="padding-right: 10px;" height="30">น้ำหนักทั้งหมด</td>
                        <td><div class="totalWeight"></div></td>
                        <td class="text-left" style="padding-left: 10px;">กิโลกรัม</td>
                    </tr>
                </tfoot>
            </table>
            <button type="button" class="btn btn-warning" data-toggle="modal" data-target="#staticBackdrop">แก้ไขถังหมุนเวียน</button>
            <button type="button" class="btn btn-danger" data-toggle="modal" data-target="">แก้ไขถังฝากเติม</button>
            <div class="form-group row mt-3">
                <label class="col-sm-2 col-form-label ">หมายเหตุ</label>
            </div>
            <textarea name="" id="" cols="30" value="5" class="form-control"></textarea>
            <div class="form-group mt-3">
                <button class="btn btn-success btn-sm" type="button">บันทึกฉบับร่าง</button>
                <button class="btn btn-success btn-sm" type="button">บันทึก</button>
            </div>

            <div class="modal fade" id="staticBackdrop" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                <div class="modal-dialog modal-xl">
                    <div class="modal-content">
                        <div class="modal-header bg-warning">
                            <h5 class="modal-title" id="staticBackdropLabel">แก้ไขถังหมุนเวียน</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body"> 
                            <table class="table table-bordered table-hover" style="width: 100%;">
                                <thead>
                                    <tr class="text-center">
                                        <th width="50" height="30"> ยี่ห้อ\ขนาด</th>
                                        <?php foreach ($dataSize as $key => $value) { ?>
                                        <th width="80" height="30"><?=$value['weightSize_id']?></th>
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
                                                    <input type="number" name="" id="itemCy_<?=$item['ms_product_id']?>_<?=$value['weight_NoID']?>" 
                                                    class="form-control text-center adjustItemPO" style="width: 80px;" min="0"
                                                    data-brand="<?=$item['ms_product_id']?>" data-wightsize="<?=$value['wightSize']?>" data-sizeid="<?=$value['weight_NoID']?>" data-size="<?=$value['weightSize_id']?>">
                                                    <!-- <div class="div-inside">
                                                        <select name="" class="pickitem_add_PO item_<?=$item['ms_product_id']?>" 
                                                        id="itemCy_<?=$item['ms_product_id']?>_<?=$value['weight_NoID']?>" data-brand="<?=$item['ms_product_id']?>" data-wightSize="<?=$value['wightSize']?>" data-sizeid="<?=$value['weight_NoID']?>" data-size="<?=$value['weightSize_id']?>">
                                                            <?php for ($n=0; $n <=20 ; $n++) { ?>
                                                                <option value="<?php echo $n; ?>" <?php echo $n == 0 ? 'selected':'' ?>><?php echo $n; ?></option>
                                                            <?php } ?>
                                                        </select>
                                                    </div> -->
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
        </form>
    </section>
</body>
</html>