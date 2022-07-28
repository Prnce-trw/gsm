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
        <form action="../controller/POController.php" method="POST">
            <input type="hidden" name="parameter" id="DraftPO" value="DraftPO">
            <input type="hidden" name="POStatus" id="POStatus" value="">
            <input type="hidden" name="POID" id="" value="<?=$_GET['id']?>">
            <div class="container">
                <?php
                include_once('../conn.php');
                $fetchdata = new DB_con();
                $sql = $fetchdata->editPO($_GET['id']);
                $row = mysqli_fetch_array($sql);
                $itemPO = $fetchdata->editCylinderPO($_GET['id']); 
                $totalSum = $fetchdata->SumAmountItem($_GET['id']);
                $totalItem = mysqli_fetch_array($totalSum); ?>
                <div class="row">
                    <div class="col-50">
                        <label for="filling" class="label-titile">โรงบรรจุ</label>
                        <select class="gas_filling js-example-basic-single" name="gas_filling" id="gas_filling" style="width: 150px;" disabled>
                            <option selected disabled>เลือกโรงบรรจุ...</option>
                            <?php for ($i=0; $i < 10; $i++) { ?>
                                <option value="<?php echo $i; ?>" <?php echo $row['head_po_fillstation'] == $i ? 'selected':'' ?>><?php echo $i; ?>
                            </option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="col-50">
                        <label class="label-titile">หมายเลขเอกสาร</label> <?=$_GET['id']?>
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
                    </tr>
                </thead>
                <tbody id="edit_PO">
                    <?php 
                    foreach ($itemPO as $key => $rows) {
                    $size_r = explode(".",$rows['po_itemOut_CySize']);
                    ?>
                    <tr id="tdAppend_<?=$rows['po_itemOut_CyBrand']?>_<?=$size_r[0].(isset($size_r[1]) ? $size_r[1] : '')?>">
                        <td>
                            <?=$key+1;?>
                            <input type="hidden" name="pickitem[<?=$key;?>]" id="<?=$rows['po_itemOut_CyBrand']?>_<?=$size_r[0].(isset($size_r[1]) ? $size_r[1] : '')?>_<?=$rows['po_itemOut_type']?>" 
                            data-info="<?=$rows['po_itemOut_CyBrand']?>_<?=$size_r[0].(isset($size_r[1]) ? $size_r[1] : '')?>" value="<?=$rows['po_itemOut_CyBrand']?>/<?=$rows['po_itemOut_CySize']?>/<?=$rows['po_itemOut_CyAmount']?>/<?=$rows['po_itemOut_type']?>">
                        </td>
                        <td style="text-align: left;"><?=$rows['po_itemOut_CyBrand']?>/ ขนาด <?=$rows['po_itemOut_CySize']?> กก.</td>
                        <td><?=$rows['po_itemOut_CyAmount']?></td>
                        <td>
                            <span id="resultWeite_<?=$rows['po_itemOut_CyBrand']?>_<?=$size_r[0].(isset($size_r[1]) ? $size_r[1] : '')?>"><?=$rows['po_itemOut_CySize'] * $rows['po_itemOut_CyAmount']?></span>
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="2" class="text-right" style="padding-right: 10px;" height="30">จำนวนทั้งหมด</td>
                        <td>
                            <span id="totalitem_PR">
                                <?php echo ($totalItem != null ? $totalItem[0] : '');?>
                            </span>
                            <input type="hidden" name="total" class="total" value="<?php echo ($totalItem != null ? $totalItem[0] : '');?>">
                        </td>
                        <td class="text-left" style="padding-left: 10px;">ถัง</td>
                    </tr>
                    <tr>
                        <td colspan="2" class="text-right" style="padding-right: 10px;" height="30">ราคาทั้งหมด</td>
                        <td></td>
                        <td class="text-left" style="padding-left: 10px;">บาท</td>
                    </tr>
                </tfoot>
            </table>
            <div class="container">
                <h4>หมายเหตุ</h4>
                <textarea name="" id="" cols="30" rows="10" style="width: 100%;"><?=$row['head_po_comment']?></textarea>
                <div class="row" style="margin-top: 30px;">
                    <div class="col-12" style="text-align: right;">
                        <button type="submit" class="btn btn-danger" onclick="btnCanclePO()">ยกเลิก</button>
                        <button type="submit" class="btn btn-success" onclick="btnConfirmPO()">ยืนยัน</button>
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
</body>

</html>