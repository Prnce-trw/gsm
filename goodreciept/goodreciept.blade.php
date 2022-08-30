<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>GSM</title>
    
    <!-- Required Fremwork -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <!-- ico font -->
    <link rel="stylesheet" type="text/css" href="../files/assets/icon/icofont/css/icofont.css">
    <!-- Style.css -->
    <!-- <link rel="stylesheet" type="text/css" href="../files/assets/css/style.css"> -->
    <!-- Select 2 css -->
    <link rel="stylesheet" href="../files/bower_components/select2/css/select2.min.css" />
    <link rel="stylesheet" href="//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
    <style>
        section {
            padding: 15px;
            height: 1200px;
            background-color: #f8f9fe;
            width: 900px;
        }

        .text-middle {
            vertical-align: middle !important;
        }

        .material-symbols-outlined {
            font-variation-settings:
            'FILL' 0,
            'wght' 400,
            'GRAD' 0,
            'opsz' 48
        }

        .modal-dialog {
            max-width: 1000px !important;
        }
        .table td, .table th {
            padding: 0.25rem;
        }
    </style>
</head>
<body>
    <section>
        <?php
        include_once('../conn.php');
        include('../function.php');
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
        $dataCars   = $fetchdata->fetchdataCars();
        $dataEmp    = $fetchdata->fetchdataEmp(); 
        ?>
        <form action="../controller/PRController.php" method="post">
            <input type="hidden" name="parameter" id="" value="PreOrderReCeipt">
            <input type="hidden" name="POID" id="" value="<?=$_GET['id']?>">
            <div class="form-group row">
                <label class="col-sm-2 col-form-label">เอกสารใบกำกับ </label>
                <div class="col-sm-4">
                    <input type="text" class="form-control" name="RefDO" placeholder="หมายเลขเอกสารใบกำกับ..." value="<?=$row['head_pr_doc_ref']?>">
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-2 col-form-label">โรงบรรจุ </label>
                <div class="col-sm-4">
                    <select class="gas_filling js-example-basic-single form-control" name="gas_filling" id="gas_filling">
                        <option selected disabled>เลือกโรงบรรจุ...</option>
                        <?php foreach ($dataFP as $key => $value) { ?>
                            <option value="<?=$value['FP_ID']; ?>" <?php echo $row['head_po_fillstation'] == $value['FP_ID'] ? 'selected':'' ?>><?=$value['FP_Name']; ?></option>
                        <?php } ?>
                    </select>
                </div>
                <label class="col-sm-2 col-form-label">หมายเลขเอกสาร </label>
                <div class="col-sm-4">
                    <input type="text" value="<?=$_GET['id']?>" class="form-control" readonly>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-2 col-form-label">รอบการส่งที่ </label>
                <div class="col-sm-2">
                    <input type="text" value="<?=$row['head_po_round']?>" readonly class="form-control">
                </div>
                <label class="col-sm-2"></label>
                <label class="col-sm-2 col-form-label">วันที่ </label>
                <div class="col-sm-4">
                    <input type="text" class="form-control datepicker" placeholder="วัน/เดือน/ปี" value="<?=($row['head_po_docdate'] != null ? date("d/m/Y", strtotime($row['head_po_docdate'])) : date("d/m/Y"))?>">
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-2 col-form-label">เวลาออก</label>
                <div class="col-sm-4">
                    <div class="row">
                        <div class="col-6">
                            <input type="number" name="hourOut" class="form-control maxlengthhour" value="<?=date("H", strtotime($row['head_po_docdate']))?>" min="7" max="22" placeholder="ชั่วโมง" onKeyPress="if(this.value.length==2) return false;" readonly>
                        </div>
                        <div class="col-6">
                            <input type="number" name="minuteOut" class="form-control maxlengthminute" value="<?=date("i", strtotime($row['head_po_docdate']))?>" min="0" max="60" placeholder="นาที" onKeyPress="if(this.value.length==2) return false;" readonly>
                        </div>
                    </div>
                </div>
                <label class="col-sm-2 col-form-label">เวลาเข้า </label>
                <div class="col-sm-4">
                    <div class="row">
                        <div class="col-6">
                            <input type="number" name="hourIn" class="form-control maxlengthhour" max="7" min="22" placeholder="ชั่วโมง" onKeyPress="if(this.value.length==2) return false;">
                        </div>
                        <div class="col-6">
                            <input type="number" name="minuteIn" class="form-control maxlengthminute" max="0" min="60" placeholder="นาที" onKeyPress="if(this.value.length==2) return false;">
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-2 col-form-label">ทะเบียนรถขนส่ง</label>
                <div class="col-sm-4">
                    <select class="js-example-basic-single form-control" name="car" id="">
                        <option selected disabled>เลือกรถขนส่ง...</option>
                        <?php foreach ($dataCars as $key => $value) { ?>
                            <option value="<?=$value['car_Code']?>" <?=$row['head_po_carID'] == $value['car_Code'] ? 'selected':'' ?>><?=$value['car_license']?> (<?=$value['car_name']?>)</option>
                        <?php } ?>
                    </select>
                </div>
                <label class="col-sm-2 col-form-label">ผู้ขับ </label>
                <div class="col-sm-4">
                    <select class=" js-example-basic-single form-control" name="driver" id="">
                        <option selected disabled>เลือกผู้ขับ...</option>
                        <?php foreach ($dataEmp as $key => $value) { ?>
                            <option value="<?=$value['emp_id']?>" <?=$row['head_po_driverID'] == $value['emp_id'] ? 'selected':'' ?>><?=$value['emp_name']?> <?=$value['emp_lastname']?></option>
                        <?php } ?>
                    </select>
                </div>
            </div>
            <table class="table table-hover table-bordered">
                <thead>
                    <tr class="text-center">
                        <th>ลำดับ</th>
                        <th>รายการสินค้า</th>
                        <th>ประเภทถัง</th>
                        <th>จำนวนที่ออก</th>
                        <th>จำนวนรับจริง</th>
                        <th>น้ำหนัก</th>
                        <th>ราคา/หน่วย</th>
                        <th>จำนวนเงิน</th>
                    </tr>
                </thead>
                <tbody id="itemRows">
                    <?php foreach ($itemPO as $key => $value) { 
                        $brand = $value['po_itemOut_CyBrand'];
                        $weight = $value['po_itemOut_CySize'];
                        $weightID = $value['weight_NoID'];
                        $cytype = $value['po_itemOut_type'];
                        ?>
                        <tr id="trID_<?=$brand?>_<?=$weight?>_<?=$cytype?>">
                            <td class="text-center text-middle"><?=$key+1?></td>
                            <td class="text-middle"><?=$brand?>/ ขนาด <?=$weight?> กก.
                                <input type="hidden" class="GRitemIn" name="pickitem[<?=$key+1?>]" id="info_<?=$brand?>_<?=$weightID?>_<?=$cytype?>" class="" value="<?=$brand?>/<?=$weight?>/<?=$value['po_itemOut_CyAmount']?>/<?=$cytype?>"
                                data-info="info_<?=$brand?>_<?=$weightID?>_<?=$cytype?>">
                            </td>
                            <td class="text-middle"><?=CylinderType($cytype)?></td>
                            <td class="text-right text-middle">
                                <?=$value['po_itemOut_CyAmount']?>
                            </td>
                            <td class="text-right text-middle">
                                <span class="qtyItem" id="itemIn_<?=$brand?>_<?=$weightID?>_<?=$cytype?>"><?=$value['po_itemOut_CyAmount']?></span>
                            </td>
                            <td class="text-right text-middle">
                                <span class="itemWeight" id="calItemWeight_<?=$brand?>_<?=$weightID?>_<?=$cytype?>">
                                    <?=(float)$value['wightSize'] * $value['po_itemOut_CyAmount']?>
                                </span>
                            </td>
                            <td class="text-center text-middle">
                                <input type="number" name="unitprice[<?=$key+1?>]" min="0" style="width: 120px;"
                                class="form-control itemperprice" value="<?=UnitPrice($row['head_po_fillstation'], $weightID, 'BRC1-1', $value['po_itemOut_CyAmount'])?>" 
                                data-brand="<?=$brand?>" data-sizeid="<?=$weightID?>" data-cytype="<?=$cytype?>" step="any">
                            </td>
                            <td class="text-center text-middle">
                                <input type="number" name="amtprice[<?=$key+1?>]" min="0" value="" 
                                id="resultTotal_<?=$brand?>_<?=$weightID?>_<?=$cytype?>" style="width: 120px;" 
                                class="form-control AmountPrice" step="any">
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="6" class="text-right" height="30">จำนวนทั้งหมด</td>
                        <td class="text-right">
                            <span class="totalitemIn">
                                <?php echo ($totalItem != null ? $totalItem[0] : '');?>
                            </span>
                        </td>
                        <td class="text-left">ถัง</td>
                    </tr>
                    <tr>
                        <td colspan="6" class="text-right" height="30">น้ำหนักรับจริงทั้งหมด</td>
                        <td class="text-right"><div class="totalWeight"></div></td>
                        <td class="text-left">กิโลกรัม</td>
                    </tr>
                    <tr>
                        <td colspan="6" class="text-right" height="30">ราคาทั้งหมด</td>
                        <td class="text-right"><div id="TotalPrice"></div></td>
                        <td class="text-left">บาท</td>
                    </tr>
                </tfoot>
            </table>
            <div class="form-group row">
                <div class="col-sm-12">
                    <button class="btn btn-warning" type="button" data-toggle="modal" data-target="#EditGRCylinderN">แก้ไขจำนวนรับจริง</button>
                    <button class="btn btn-danger" type="button" data-toggle="modal" data-target="#EditGRCylinderAdv">แก้ไขจำนวนถังฝากเติม</button>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-2 col-form-label">หมายเหตุ </label>
                <div class="col-sm-12">
                    <textarea name="" id="" cols="30" rows="10" class="form-control"></textarea>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-sm-12">
                    <button class="btn btn-success" type="submit">ยืนยัน</button>
                </div>
            </div>

            <!-- modal แก้ไขจำนวนรับจริง -->
            <div class="modal fade" id="EditGRCylinderN" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="EditGRCylinderNLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header bg-warning">
                            <h5 class="modal-title" id="EditGRCylinderNLabel">จำนวนคงเหลือ</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body"> 
                            <table class="table table-hover table-bordered">
                                <thead>
                                    <tr>
                                        <th width="80" height="30"> ยี่ห้อ\ขนาด</th>
                                        <?php foreach ($dataSize as $key => $value) { ?>
                                        <th width="80" height="30" class="text-center"><?=$value['weightSize_id']?></th>
                                        <?php } ?>
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
                                                            <input type="number" name="" id="qtyIn_<?=$item['ms_product_id']?>_<?=$value['weight_NoID']?>_N" class="form-control text-center adjustItems" min="0" 
                                                            data-brand="<?=$item['ms_product_id']?>" data-size="<?=$value['weightSize_id']?>" data-weightid="<?=$value['weight_NoID']?>" data-wightsize="<?=$value['wightSize']?>" data-cytype="N">
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

            <!-- modal แก้ไขจำนวนถังฝากเติม -->
            <div class="modal fade" id="EditGRCylinderAdv" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="EditGRCylinderAdvLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header bg-danger">
                            <h5 class="modal-title" id="EditGRCylinderAdvLabel">จำนวนคงเหลือ</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body"> 
                            <table class="table table-hover table-bordered">
                                <thead>
                                    <tr>
                                        <th width="80" height="30"> ยี่ห้อ\ขนาด</th>
                                        <?php foreach ($dataSize as $key => $value) { ?>
                                        <th width="80" height="30" class="text-center"><?=$value['weightSize_id']?></th>
                                        <?php } ?>
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
                                                            <input type="number" name="" id="qtyIn_<?=$item['ms_product_id']?>_<?=$value['weight_NoID']?>_Adv" class="form-control text-center adjustItems" min="0" 
                                                            data-brand="<?=$item['ms_product_id']?>" data-size="<?=$value['wightSize']?>" data-weightid="<?=$value['weight_NoID']?>" data-wightsize="<?=$value['wightSize']?>" data-cytype="Adv">
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
    <script src="js/goodreciept.js"></script>
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>
    <script>
        $(document).ready(function() {
            $('.js-example-basic-single').select2();
        });

        $(".datepicker").datepicker({dateFormat: 'dd/mm/yy'});
    </script>
</body>
</html>