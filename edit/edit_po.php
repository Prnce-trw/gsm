<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/select2.min.css">
    <link rel="stylesheet" href="../css/custom.css">
    <link rel="stylesheet" href="../css/jquery-ui.min.css">
    <title>GSM</title>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
</head>

<body>
    <section id="purchase">
        <form action="../controller.php" method="POST">
            <div class="container">
                <?php
                include_once('../conn.php');
                $fetchdata = new DB_con();
                $sql = $fetchdata->editPO($_GET['id']);
                $row = mysqli_fetch_array($sql);
                $itemPO = $fetchdata->editCylinderPO($_GET['id']); 
                ?>
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
            <table >
                <thead>
                    <tr>
                        <th>ลำดับ</th>
                        <th>รายการสินค้า</th>
                        <th>จำนวน</th>
                        <th>น้ำหนัก</th>
                        <th>ราคา/หน่วย</th>
                        <th>รับจริง</th>
                        <th>จำนวนเงิน</th>
                    </tr>
                </thead>
                <tbody id="edit_PO">
                    <?php 
                    $index = 1;
                    while ($rows = mysqli_fetch_array($itemPO)) { 
                    $size_r = explode(".",$rows['po_itemOut_CySize']);
                    ?>
                    <tr id="tdAppend_<?=$rows['po_itemOut_CyBrand']?>_<?=$size_r[0].(isset($size_r[1]) ? $size_r[1] : '')?>">
                        <td>
                            <?=$index;?>
                            <input type="hidden" name="pickitem[]" id="<?=$rows['po_itemOut_CyBrand']?>_<?=$size_r[0].(isset($size_r[1]) ? $size_r[1] : '')?>" 
                            data-info="<?=$rows['po_itemOut_CyBrand']?>_<?=$size_r[0].(isset($size_r[1]) ? $size_r[1] : '')?>" value="<?=$rows['po_itemOut_CyBrand']?>/<?=$rows['po_itemOut_CySize']?>/<?=$rows['po_itemOut_CyAmount']?>/<?=$rows['po_itemOut_type']?>">
                        </td>
                        <td style="text-align: left;"><?=$rows['po_itemOut_CyBrand']?>/ ขนาด <?=$rows['po_itemOut_CySize']?> กก.</td>
                        <td>
                            <div class="number" data-brand="<?=$rows['po_itemOut_CyBrand']?>" data-size="<?=$rows['po_itemOut_CySize']?>">
                                <span class="minus changeAmount">-</span>
	                            <input class="input_amount" type="number" value="<?=$rows['po_itemOut_CyAmount']?>" id="input_amount_" data-brand="">
                                <span class="plus changeAmount">+</span>
                            </div>
                        </td>
                        <td>
                            <span id="resultWeite_<?=$rows['po_itemOut_CyBrand']?>_<?=$size_r[0].(isset($size_r[1]) ? $size_r[1] : '')?>"><?=$rows['po_itemOut_CySize'] * $rows['po_itemOut_CyAmount']?></span>
                        </td>
                        <td>1</td>
                        <td>
                            <?php
                            $fetchdataitem = new DB_con();
                            $itemEn = $fetchdataitem->fetchitemEntrance($row['head_pr_doc_ref'], $rows['po_itemOut_CyBrand'], $rows['po_itemOut_CySize'], $rows['po_itemOut_type']);
                            $test = mysqli_fetch_array($itemEn); ?>
                            <div class="number">
                                <span class="minus">-</span>
	                            <input class="input_amount" type="number" value="<?php echo $test == null ? 0 : $test['po_itemEnt_CySize']; ?>" id="input_amount_" data-brand="">
                                <span class="plus">+</span>
                            </div>
                        </td>
                        <td>1</td>
                    </tr>
                    <?php $index++;} ?>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="5" class="text-right" style="padding-right: 10px;" height="30">จำนวนทั้งหมด</td>
                        <td></td>
                        <td class="text-left" style="padding-left: 10px;">ถัง</td>
                    </tr>
                    <tr>
                        <td colspan="5" class="text-right" style="padding-right: 10px;" height="30">ราคาทั้งหมด</td>
                        <td></td>
                        <td class="text-left" style="padding-left: 10px;">บาท</td>
                    </tr>
                </tfoot>
            </table>
            <div class="container">
                <button class="btn btn-primary" style="margin-top: 10px;" onClick="addAdvance('<?=$_GET['id']?>')" type="button">เพิ่มถัง</button>
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
        </form>
    </section>
    <script src="../js/jquery-3.6.0.min.js"></script>
    <script src="../js/sweetalert2.all.min.js"></script>
    <script src="../js/select2.min.js"></script>
    <script src="../js/jquery-ui.min.js"></script>
    <script src="../js/JQcustom.js"></script>
    <script>
        $(".datepicker").datepicker({dateFormat: 'dd/mm/yy'});
    </script>
</body>

</html>