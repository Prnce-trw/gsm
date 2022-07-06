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
        <form action="controller.php" method="POST">
            <div class="container">
                <?php include_once('conn.php');
                $fetchdata = new DB_con();
                $sql = $fetchdata->infoPO($_GET['id']);
                $row = mysqli_fetch_array($sql);
                $itemPO = $fetchdata->CylinderPO($_GET['id']); ?>
                <input type="hidden" name="parameter" id="" value="PreOrderReCeipt">
                <input type="hidden" name="POID" id="" value="<?=$_GET['id']?>">
                <div class="row">
                    <div class="col-50">
                        <label class="label-titile">เอกสารอ้างอิง</label>
                        <input type="text" class="form-control" name="RefDO" placeholder="หมายเลขเอกสารอ้างอิง..." value="<?=$row['head_pr_doc_ref']?>">
                    </div>
                </div>
                <div class="row">
                    <div class="col-50">
                        <label for="filling" class="label-titile">โรงบรรจุ</label>
                        <select class="gas_filling js-example-basic-single" name="gas_filling" id="gas_filling" style="width: 150px;">
                            <option selected disabled>เลือกโรงบรรจุ...</option>
                            <?php for ($i=0; $i < 10; $i++) { ?>
                            <option value="<?php echo $i; ?>"
                                <?php echo $row['head_po_fillstation'] == $i ? 'selected':'' ?>><?php echo $i; ?>
                            </option>
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
                        <select name="" id="" class="js-example-basic-single" style="width: 150px;">
                            <option selected disabled>เลือกรอบขนส่ง...</option>
                            <?php for ($i=1; $i <= 5; $i++) { ?>
                            <option value="<?php echo $i; ?>" <?php echo $row['head_po_round'] == $i ? 'selected':'' ?>>
                                <?php echo $i; ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="col-50">
                        <label class="label-titile">วันที่</label>
                        <input type="text" class="form-control datepicker" placeholder="วัน/เดือน/ปี" value="<?=$row['created_at']?>">
                    </div>
                </div>
                <div class="row">
                    <div class="col-50">
                        <label class="label-titile">เวลาเข้า</label>
                        <input type="text" class="form-control" name="tineIn" placeholder="เวลาเข้า...">
                    </div>
                    <div class="col-50">
                        <label class="label-titile">เวลาออก</label>
                        <input type="text" class="form-control" name="timeOut" placeholder="เวลาออก...">
                    </div>
                </div>
            </div>
            <table border="1" cellspacing="1" cellpadding="1">
                <?php 
                    $brand = ['PTT','WP','Siam','Unit','PT','Other'];
                    $package = [4,7,8,11.5,13.5,15,48];
                    //for(0) ?>
                <tr>
                    <th width="80" height="30">ขนาด/ยี่ห้อ</th>
                    <?php //for(1)
                        for ($i=0; $i < count($brand); $i++) { ?>
                    <th width="80" height="30"><?=$brand[$i]?></th>
                    <?php }// end for(1) ?>
                    <th width="80" height="30">Total</th>
                    <th width="80" height="30">Unit</th>
                    <th width="80" height="30">Amount</th>
                </tr>
                <?php for ($x=0; $x < count($package); $x++) { ?>
                <tr>
                    <td width="80" height="30">
                        <div class="div-inside"><?=$package[$x]?></div>
                    </td>
                    <?php for ($n=0; $n < count($brand); $n++) { ?>
                    <td>
                        <?php $sqlPO = $fetchdata->CylinderPO($_GET['id']);
                            while ($rows = mysqli_fetch_array($sqlPO)) {
                                if ($package[$x] == $rows['po_itemOut_CySize'] && $brand[$n] == $rows['po_itemOut_CyBrand'] && $rows['po_itemOut_type'] == "N") {
                                    echo $rows['po_itemOut_CyAmount']." / ";
                                } } ?>
                        <select name="" class="pickitem itemgroup itemSize_<?=$x?>" id="" data-sizenumber="<?=$x?>" data-brand="<?=$brand[$n]?>" data-size="<?=$package[$x]?>" data-Cytype="N">
                            <?php for ($y=0; $y <=20 ; $y++) { ?>
                            <option value="<?php echo $y; ?>"><?php echo $y; ?></option>
                            <?php } ?>
                        </select>
                    </td>
                    <?php } ?>
                    <td>
                        <div id="total_<?=$x?>"></div>
                    </td>
                    <td>
                        <?php $sqlCountSize = $fetchdata->CylinderCountSize($_GET['id'], $package[$x], 'N'); 
                        $count = mysqli_fetch_array($sqlCountSize);
                        echo $count[0];?>
                    </td>
                    <td></td>
                </tr>
                <?php }
                $fetchdataPO = new DB_con();
                $sqlPO = $fetchdataPO->CylinderPOSum($_GET['id']);
                $sqlPOSize = $fetchdataPO->CylinderWeight($_GET['id']);
                $rowPO = mysqli_fetch_array($sqlPO);
                $rowPOSize = mysqli_fetch_array($sqlPOSize);
                //end for(0) ?>
                <tr>
                    <td colspan="<?=count($brand)+1?>" style="text-align: right; padding-right: 10px;" height="30">รายการทั้งหมด</td>
                    <td><div id="total">0</div></td>
                    <td colspan="2">รายการ</td>
                </tr>
            </table>
            <!-- Advance -->
            <?php   $brand = ['PTT','WP','Siam','Unit','PT','Other'];
                    $package = [4,7,8,11.5,13.5,15,48]; 
                    $findAdv = $fetchdataPO->findAdv($_GET['id']);
                    $itemAdv = mysqli_fetch_array($findAdv);
                    if ($itemAdv[0] != 0) { ?>
                        <table border="1" cellspacing="1" cellpadding="1">
                <thead>
                    <tr><th colspan="<?=count($brand)+4?>">ฝากเติม</th></tr>
                    <tr>
                        <th width="80" height="30">ขนาด/ยี่ห้อ</th>
                        <?php //for(1)
                            for ($i=0; $i < count($brand); $i++) { ?>
                            <th width="80" height="30"><?=$brand[$i]?></th>
                        <?php }// end for(1) ?>
                        <th width="80" height="30">Total</th>
                        <th width="80" height="30">Unit</th>
                        <th width="80" height="30">Amount</th>
                    </tr>
                </thead>
                <tbody>
                    <?php for ($x=0; $x < count($package); $x++) { ?>
                    <tr>
                        <td width="80" height="30"><div class="div-inside"><?=$package[$x]?></div></td>
                        <?php for ($n=0; $n < count($brand); $n++) { ?>
                        <td>
                            <?php $sqlPO = $fetchdata->CylinderPO($_GET['id']);
                                while ($rows = mysqli_fetch_array($sqlPO)) {
                                    if ($package[$x] == $rows['po_itemOut_CySize'] && $brand[$n] == $rows['po_itemOut_CyBrand'] && $rows['po_itemOut_type'] == "Adv") {
                                        echo $rows['po_itemOut_CyAmount']." / ";
                                    } } ?>
                            <select name="" class="pickitem" id="" data-brand="<?=$brand[$n]?>" data-size="<?=$package[$x]?>" data-Cytype="Adv">
                                <?php for ($y=0; $y <=20 ; $y++) { ?>
                                    <option value="<?php echo $y; ?>"><?php echo $y; ?></option>
                                <?php } ?>
                            </select>
                        </td>
                        <?php } ?>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <?php }
                    $fetchdataPO = new DB_con();
                    $sqlPO = $fetchdataPO->CylinderPOSum($_GET['id']);
                    $sqlPOSize = $fetchdataPO->CylinderWeight($_GET['id']);
                    $rowPO = mysqli_fetch_array($sqlPO);
                    $rowPOSize = mysqli_fetch_array($sqlPOSize); ?>
                    <tr>
                        <td colspan="<?=count($brand)-1?>" style="text-align: right; padding-right: 10px;" height="30">รายการทั้งหมด</td><td>
                            <div id="total">0</div>
                        </td>
                        <td>รายการ</td>
                    </tr>
                </tbody>
            </table>
            <?php } ?>
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
            <div id="result_inputItem"></div>
        </form>
    </section>
    <script src="js/jquery-3.6.0.min.js"></script>
    <script src="js/sweetalert2.all.min.js"></script>
    <script src="js/select2.min.js"></script>
    <script src="js/jquery-ui.min.js"></script>
    <script src="js/JQcustom.js"></script>
    <script>
        $(".datepicker").datepicker({dateFormat: 'dd/mm/yy'});
    </script>
</body>

</html>