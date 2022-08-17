<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/select2.min.css">
    <link rel="stylesheet" href="css/custom.css">
    <title>GSM</title>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
</head>
<body>
    <section id="purchase">
        <div class="container">
            <h4>จัดซื้อ</h4>
            <!-- <div class="function_bar">555</div> -->
            <table border="1" cellspacing="1" cellpadding="1">
                <thead>
                    <tr>
                        <th width="50" height="40" rowspan="2">ลำดับ</th>
                        <th colspan="2">การจัดการ</th>
                        <th rowspan="2">หัวข้อ</th>
                        <th rowspan="2">รอบ</th>
                        <th rowspan="2">สถานะ</th>
                        <th rowspan="2" width="80">เวลาการจัดส่ง</th>
                        <th rowspan="2" width="350">การจัดการ</th>
                    </tr>
                    <tr>
                        <th>ลงรับ</th>
                        <th>แก้ไข</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    include_once('conn.php');
                    include('function.php');
                    $fetchdata = new DB_con();
                    $sql = $fetchdata->fetchdataPO();
                    $index = 1;
                    while ($row = mysqli_fetch_array($sql)) { ?>
                    <tr id="POID_<?=$row['head_po_id']?>">
                        <td height="40"><?php echo $index?></td>
                        <td>
                            <?php if ($row['head_pr_docnumber'] == null && $row['head_po_stock_status'] == 'Confirm') { ?>
                                <a href="preorderreceipt.php?id=<?=$row['head_po_docnumber']?>">รับถังเข้า</a>
                            <?php } ?> 
                        </td>
                        <td>
                            <?php if ($row['head_pr_docnumber'] != null) { ?>
                                <a href="edit/editPO.php?id=<?=$row['head_po_docnumber']?>">แก้ไขเอกสาร</a>
                            <?php } ?>
                        </td>
                        <td><?=$row['head_po_docnumber']?></td>
                        <td><?=$row['head_po_round']?></td>
                        <td>
                            <?php if ($row['head_po_stock_status'] == 'Draft') { ?>
                                <a href="draft/draftPO.php?id=<?=$row['head_po_docnumber']?>"><?=POStatus($row['head_po_stock_status'])?></a>
                            <?php } else { ?>
                                <?=POStatus($row['head_po_stock_status'])?>
                            <?php } ?>
                        </td>
                        <td>05/05/2022</td>
                        <td>
                            <div class="row">
                                <!-- <div class="col-25">
                                    <a href="info/info_po.php?id=<?=$row['head_po_docnumber']?>">ดูข้อมูล</a>
                                </div> -->
                                <div class="col-25">
                                    <a href="info/po_pdf.php" target="popup" class="btn btn-primary" onclick="window.open('info/po_pdf.php?PO_ID=<?=$row['head_po_docnumber']?>','popup','width=850,height=800,scrollbars=no,resizable=no'); return false;">พิมพ์</a>
                                </div>
                                <div class="col-25">
                                    <button type="button" class="btn btn-danger" style="color: var(--white);" onClick="btn_delete(<?=$row['head_po_id']?>)">ยกเลิก</button>
                                </div> 
                            </div>
                        </td>
                    </tr>
                    <?php $index++; } ?>
                </tbody>
            </table>
        </div>
    </section>

    <script src="js/jquery-3.6.0.min.js"></script>
    <script src="js/sweetalert2.all.min.js"></script>
    <script src="js/select2.min.js"></script>
    <script src="js/JQcustom.js"></script>
</body>
</html>