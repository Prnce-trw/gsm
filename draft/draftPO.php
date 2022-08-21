<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GSM</title>
    <!-- Required Fremwork -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <!-- Style.css -->
    <!-- <link rel="stylesheet" type="text/css" href="../files/assets/css/style.css"> -->
    <!-- Select 2 css -->
    <link rel="stylesheet" href="../files/bower_components/select2/css/select2.min.css" />
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
        
        .modal-dialog {
            max-width: 1000px !important;
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
    $dataBS             = $fetchdata->fetchdataBS();
    ?>
    <section>
        <form action="../controller/POController.php" method="post" id="FormDraftPO">
            <input type="hidden" name="parameter" value="DraftPO">
            <input type="hidden" name="POStatus" id="POStatus" value="">
            <div class="form-group row" style="display: none" id="divedited">
                <div class="col-sm-12">
                    <span class="badge badge-danger">มีการแก้ไข</span>
                    <input type="hidden" value="" id="edited" name="edited">
                </div>
            </div>
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
                <label class="col-sm-2 col-form-label">หมายเลขเอกสาร</label>
                <div class="col-sm-4">
                    <input type="text" class="form-control" name="POID" value="<?=$_GET['id']?>" readonly>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-2 col-form-label">ทะเบียนรถขนส่ง</label>
                <div class="col-sm-4">
                    <select class="js-example-basic-single" name="car" id="" style="width: 170px;">
                        <option selected disabled>เลือกรถขนส่ง...</option>
                        <?php foreach ($dataCars as $key => $value) { ?>
                            <option value="<?=$value['car_Code']?>" <?=$row['head_po_carID'] == $value['car_Code'] ? 'selected':'' ?>><?=$value['car_license']?> (<?=$value['car_name']?>)</option>
                        <?php } ?>
                    </select>
                </div>
                <label class="col-sm-2 col-form-label">ผู้ขับ</label>
                <div class="col-sm-4">
                    <select class=" js-example-basic-single" name="driver" id="" style="width: 250px;">
                        <option selected disabled>เลือกผู้ขับ...</option>
                        <?php foreach ($dataEmp as $key => $value) { ?>
                            <option value="<?=$value['emp_code']?>" <?=$row['head_po_driverID'] == $value['emp_code'] ? 'selected':'' ?>><?=$value['emp_name']?> <?=$value['emp_lastname']?></option>
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
                <tbody id="itemlist">
                    <?php foreach ($itemPO as $key => $value) { ?>
                        <tr id="trItem_<?=$value['po_itemOut_CyBrand']?>_<?=$value['weight_NoID']?>_<?=$value['po_itemOut_type']?>">
                            <td class="text-center text-middle" style="width: 50px;">
                                <?=$key+1?>
                                <input type="hidden" class="PRitemOut" name="pickitem[<?=$value['po_itemOut_id']?>]" id="<?=$value['po_itemOut_CyBrand']?>_<?=$value['weight_NoID']?>_<?=$value['po_itemOut_type']?>" 
                            data-info="<?=$value['po_itemOut_CyBrand']?>_<?=$value['weight_NoID']?>_<?=$value['po_itemOut_type']?>" value="<?=$value['po_itemOut_CyBrand']?>/<?=$value['po_itemOut_CySize']?>/<?=$value['po_itemOut_CyAmount']?>/<?=$value['po_itemOut_type']?>">
                            </td>
                            <td class="text-left text-middle"><?=$value['po_itemOut_CyBrand']?> / ขนาด <?=$value['po_itemOut_CySize']?> กก. </td>
                            <td class="text-center text-middle" style="width: 150px;">
                                <?=CylinderType($value['po_itemOut_type'])?>
                            </td>
                            <td class="text-center" style="width: 120px;">
                                <span id="qtyIn_<?=$value['po_itemOut_CyBrand']?>_<?=$value['weight_NoID']?>_<?=$value['po_itemOut_type']?>"><?=$value['po_itemOut_CyAmount']?></span>
                            </td>
                            <td class="text-center" style="width: 120px;">
                                <span class="totalweight" id="weightIn_<?=$value['po_itemOut_CyBrand']?>_<?=$value['weight_NoID']?>_<?=$value['po_itemOut_type']?>">
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
                        <td class="text-right"><div class="resultWeight"></div></td>
                        <td class="text-left" style="padding-left: 10px;">กิโลกรัม</td>
                    </tr>
                </tfoot>
            </table>
            <button type="button" class="btn btn-warning" data-toggle="modal" data-target="#NormalCylinder">แก้ไขถังหมุนเวียน</button>
            <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#AdvanceCylinder">แก้ไขถังฝากเติม</button>
            <div class="form-group row mt-3">
                <label class="col-sm-2 col-form-label ">หมายเหตุ</label>
            </div>
            <textarea name="" id="" cols="30" value="5" class="form-control"></textarea>
            <div class="form-group mt-3 row">
                <div class="col-6">
                    <button type="submit" class="btn btn-danger btn-sm" onclick="btnCanclePO()">ยกเลิก</button>
                </div>
                <div class="col-6 text-right">
                    <button class="btn btn-success btn-sm" type="submit" onclick="btnDraftPO()">บันทึกฉบับร่าง</button>
                    <button class="btn btn-success btn-sm" type="button" id="btnsubmit" onclick="btnConfirmPO()">บันทึก</button>
                </div>
            </div>

            <!-- น้ำแก๊สหมุนเวียน -->
            <div class="modal fade" id="NormalCylinder" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="NormalCylinderLabel" aria-hidden="true">
                <div class="modal-dialog modal-xl">
                    <div class="modal-content">
                        <div class="modal-header bg-warning">
                            <h5 class="modal-title" id="NormalCylinderLabel">แก้ไขน้ำแก๊สหมุนเวียน</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body"> 
                            <table class="table-bordered nowrap" style="width: 100%;">
                                <thead>
                                    <tr>
                                        <th class="text-center" width="50" height="30"> ยี่ห้อ\ขนาด</th>
                                        <?php foreach ($dataSize as $key => $value) { ?>
                                        <th class="text-center" width="90" height="30"><?=$value['weightSize_id']?></th>
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
                                                <td class="text-center">
                                                        <input type="number" name="" id="itemCy_<?=$item['ms_product_id']?>_<?=$value['weight_NoID']?>_N" 
                                                        class="form-control text-center adjustItemPO" style="width: 80px;" min="0"
                                                        data-brand="<?=$item['ms_product_id']?>" data-wightsize="<?=$value['wightSize']?>" data-sizeid="<?=$value['weight_NoID']?>" data-size="<?=$value['weightSize_id']?>"
                                                        data-cytype="N">
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
                                            <td></td>
                                    <?php } }// end for(1) ?>
                                </tr>
                                <?php }//end for(0) ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ถังฝากเติม -->
            <div class="modal fade" id="AdvanceCylinder" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="AdvanceCylinderLabel" aria-hidden="true">
                <div class="modal-dialog modal-xl">
                    <div class="modal-content">
                        <div class="modal-header bg-danger">
                            <h5 class="modal-title" id="AdvanceCylinderLabel">แก้ไขถังฝากเติม</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body"> 
                            <table class="table-bordered nowrap" style="width: 100%;">
                                <thead>
                                    <tr>
                                        <th class="text-center" width="50" height="30"> ยี่ห้อ\ขนาด</th>
                                        <?php foreach ($dataSize as $key => $value) { ?>
                                        <th class="text-center" width="90" height="30"><?=$value['weightSize_id']?></th>
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
                                                <td class="text-center">
                                                    <input type="number" name="" id="itemCy_<?=$item['ms_product_id']?>_<?=$value['weight_NoID']?>_Adv" 
                                                    class="form-control text-center adjustItemPO" style="width: 80px;" min="0"
                                                    data-brand="<?=$item['ms_product_id']?>" data-wightsize="<?=$value['wightSize']?>" data-sizeid="<?=$value['weight_NoID']?>" data-size="<?=$value['weightSize_id']?>"
                                                    data-cytype="Adv">
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
                                            <td></td>
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

    <!-- Required Jqurey -->
    <script src="../files/bower_components/jquery/js/jquery.min.js"></script>
    <script src="../files/bower_components/jquery-ui/js/jquery-ui.min.js"></script>
    <script src="../files/bower_components/popper.js/js/popper.min.js"></script>
    <!-- <script src="../files/bower_components/bootstrap/js/bootstrap.min.js"></script> -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
    <!-- sweet alert js -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <!-- Select 2 js -->
    <script src="../files/bower_components/select2/js/select2.full.min.js"></script>
    <!-- custom js -->
    <script src="js/draft.js"></script>
</body>
</html>