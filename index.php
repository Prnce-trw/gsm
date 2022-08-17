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
    <?php
    include_once('conn.php');
    $fetchdata  = new DB_con();
    $dataBrand  = $fetchdata->fetchdataBrand();
    $dataSize   = $fetchdata->fetchdataSize(); 
    $dataFP     = $fetchdata->fetchdataFP();
    $dataBS     = $fetchdata->fetchdataBS();
    $dataCars   = $fetchdata->fetchdataCars();
    $dataEmp   = $fetchdata->fetchdataEmp(); ?>
    <section id="purchase">
        <form action="controller/POController.php" method="POST" id="FormPreOrderCylinder">
            <input type="hidden" name="parameter" value="PreOrderCylinder" id="PreOrderCylinder">
            <input type="hidden" name="POStatus" value="Confirm" id="POStatus">
        <div class="container">
            <h4>รายการนำถังไปบรรจุ</h4>
            <div class="row">
                <div class="col-50">
                    <label for="filling" class="label-titile">โรงบรรจุ</label>
                    <select class="gas_filling js-example-basic-single" name="gas_filling" id="gas_filling" style="width: 170px;">
                        <option selected disabled>เลือกโรงบรรจุ...</option>
                        <?php foreach ($dataFP as $key => $value) { ?>
                            <option value="<?php echo $value['FP_ID']; ?>"><?php echo $value['FP_Name']; ?></option>
                        <?php } ?>
                    </select>
                </div>
                <div class="col-50">
                    <label class="label-titile">วันที่</label>
                    <input type="text" name="date" class="form-control" placeholder="วัน/เดือน/ปี" value="<?=date("d/m/Y")?>" readonly>
                </div>
            </div>
            <div class="row">
                <div class="col-50">
                    <label for="filling" class="label-titile">ทะเบียนรถขนส่ง</label>
                    <select class="js-example-basic-single" name="car" id="" style="width: 170px;">
                        <option selected disabled>เลือกรถขนส่ง...</option>
                        <?php foreach ($dataCars as $key => $value) { ?>
                            <option value="<?=$value['car_Code']?>"><?=$value['car_license']?> (<?=$value['car_name']?>)</option>
                        <?php } ?>
                    </select>
                    </div>
                <div class="col-50">
                    <label for="filling" class="label-titile">ผู้ขับ</label>
                    <select class=" js-example-basic-single" name="driver" id="" style="width: 250px;">
                        <option selected disabled>เลือกผู้ขับ...</option>
                        <?php foreach ($dataEmp as $key => $value) { ?>
                            <option value="<?=$value['emp_name']?>"><?=$value['emp_name']?> <?=$value['emp_lastname']?></option>
                        <?php } ?>
                    </select>
                    </div>
                </div>
            </div>
        </div>
        <table border="1" cellspacing="1" cellpadding="1">
            <tr>
                <th width="80" height="30"> ยี่ห้อ\ขนาด </th>
                <?php foreach ($dataSize as $key => $value) { ?>
                <th width="80" height="30"><?=$value['weightSize_id']?></th>
                <?php }// end for(1) ?>
                <th width="300" height="30">ฝากเติม</th>
            </tr>
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
                            <input type="number" class="form-control pickitem weightSize_<?=$value['weight_NoID']?>" style="width: 50px;" id="input_<?=$item['ms_product_id']?>_<?=$value['weight_NoID']?>_N" data-brand="<?=$item['ms_product_id']?>" data-sizeid="<?=$value['weight_NoID']?>" data-weight="<?=$value['wightSize']?>" data-Cytype="N" min="0">
                                <div class="div-inside">
                                    
                                    <!-- <select name="" class="pickitem weightSize_<?=$value['weight_NoID']?>" id="input_<?=$item['ms_product_id']?>_<?=$value['weight_NoID']?>_N" data-brand="<?=$item['ms_product_id']?>" data-sizeid="<?=$value['weight_NoID']?>" data-weight="<?=$value['wightSize']?>" data-Cytype="N">
                                        <?php for ($n=0; $n <=20 ; $n++) { ?>
                                            <option value="<?php echo $n; ?>" <?php echo $n == 0 ? 'selected':'' ?>><?php echo $n; ?></option>
                                        <?php } ?>
                                    </select> -->
                                </div>
                            </td>
                        <?php $stack = true;}
                    } if (!$stack) { ?>
                        <td width="80" height="50"></td>
                    <?php } ?>
                <?php }// end for(1) ?>
                <td>
                    <div class="row">
                        <div class="col-50" style="border-right: 1px solid #e8e8e8;">
                            <button type="button" name="add" id="add" data-toggle="modal" data-modal="<?=$item['ms_product_id']?>" data-modal-open="add_data_modal" class="btn btn-warning open_modal">Add</button>
                        </div>
                        <div class="col-50">
                            <div id="result_adv_<?=$item['ms_product_id']?>"></div>
                        </div>
                    </div>
                </td>
            </tr>
            <?php }//end for(0) ?>
            <!-- <tr>
                <td><b>รวม</b></td>
                <?php foreach ($dataSize as $key => $value) { ?>
                    <td><span class="sumcurrWeight_<?=$value['weight_NoID']?> getcurrWeight"></span></td>
                <?php } ?>
                <td colspan="2"></td>
            </tr> -->
            <tr>
                <td height="50"><b>น้ำหนักทั้งหมด</b></td>
                <?php foreach ($dataSize as $key => $value) { ?>
                    <td><span class="sumWeight_<?=$value['weight_NoID']?> getSumWeight"></span></td>
                <?php } ?>
                <td colspan="2"><span class="sumWeightAdv"></span></td>
            </tr>
            <tr>
                <td colspan="<?=$dataSize->num_rows?>" style="text-align: right; padding-right: 10px;" height="30">รายการทั้งหมด</td>
                <td>
                    <div class="total">0</div>
                    <input type="hidden" name="total" class="total" value="0">
                </td>
                <td>รายการ</td>
            </tr>
            <tr>
                <td colspan="<?=$dataSize->num_rows?>" style="text-align: right; padding-right: 10px;" height="30">น้ำหนักทั้งหมด</td>
                <td>
                    <div class="totalWeight">0</div>
                    <input type="hidden" name="totalWeight" class="totalWeight">
                </td>
                <td>กิโลกรัม</td>
            </tr>
        </table>
        <div class="container">
            <h4>หมายเหตุ</h4>
            <textarea name="comment" id="" cols="30" rows="10" style="width: 100%;"></textarea>
            <div class="row" style="margin-top: 30px;">
                <div class="col-12" style="text-align: right;">
                    <button type="button" class="btn btn-success" onclick="btn_submit_draft_preselect()">บันทึกฉบับร่าง</button>
                    <!-- <button type="button" class="btn btn-success" onclick="btn_submit_preselect()" disabled>ยืนยัน</button> -->
                </div>
            </div>
        </div>

        <div id="resultModal"></div>
        <div id="result_inputItem"></div>
        </form>
    </section>
    <script src="js/jquery-3.6.0.min.js"></script>
    <script src="js/sweetalert2.all.min.js"></script>
    <script src="js/select2.min.js"></script>
    <script src="js/jquery-ui.min.js"></script>
    <!-- <script src="js/JQcustom.js"></script> -->
    <script src="js/index.js"></script>
</body>
</html>