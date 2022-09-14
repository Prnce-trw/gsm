<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GSM | Purchase Order : <?= $_GET['PO_ID'] ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Sarabun&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link href="../css/report.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Sarabun', sans-serif !important;
            height: 100%;
            margin: 0px;
        }
    </style>
</head>

<body>
    <?php
    include_once("../conn.php");
    $fetchdata = new DB_con();
    // echo "$_GET[PO_ID]";
    $dataBrandSize = $fetchdata->fetchdataReport($_GET['PO_ID']);
    $fillingplant = $fetchdata->fetchdataReportHeader($_GET['PO_ID'])->fetch_assoc();
    $date = date_create($fillingplant['head_po_docdate']);
    $sizecount = 0;
    $weight = 0;
    ?>
    <div class="a4">
        <div class="row col-100_nopad">
            <table class="docheader">
                <tr>
                    <td rowspan="3" width="150px" style="vertical-align: center;"><img src="../image/tglogo.webp" alt="tg_logo" height="50px"></td>
                    <td colspan="3" height="25px">
                        <h3>บริษัท ไทยแก๊ส คอร์ปอเรชั่น จำกัด สาขาที่ 1</h3>
                    </td>
                </tr>
                <tr height="40px">
                    <td colspan="2" style="text-align: left;"><strong>เติมแก๊ส ประจำวันที่</strong> <?= date_format($date, "d/m/Y") ?></td>
                    <td style="text-align: right;"><strong>รอบที่</strong> 1</td>
                </tr>
                <tr height="40px">
                    <td style="text-align: left;"><strong>เวลาไป</strong> 12:00</td>
                    <td style="text-align: left;"><strong>เวลากลับ</strong> 14:00</td>
                    <td style="text-align: right;"><strong>โรงบรรจุ</strong>
                        <?php if (!$fillingplant['FP_Name']) {
                            echo "ไม่ระบุ";
                        } else {
                            echo "$fillingplant[FP_Name]";
                        } ?></td>
                </tr>
            </table>
        </div>
        <div class="row col-100">
            <table class="report">
                <thead>
                    <tr style="border: 1px solid black;">
                        <th width="8%">ลำดับที่</th>
                        <th width="20%">ยี่ห้อ</th>
                        <th width="15%">ประเภทถัง</th>
                        <th width="10%">ขนาด</th>
                        <th width="18%">จำนวน</th>
                        <th width="15%">น้ำหนักทั้งหมด</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($dataBrandSize as $key => $value) { ?>
                        <tr style="border-bottom: 0;">
                            <td><?= $key + 1 ?></td>
                            <td><?= $value['ms_product_name'] ?></td>
                            <td>
                                <?php
                                if ($value['po_itemOut_type'] == 'N') {
                                    echo "ถังหมุนเวียน";
                                } else if ($value['po_itemOut_type'] == 'Adv') {
                                    echo "ฝากเติม";
                                }
                                ?>
                            </td>
                            <td style="text-align: right; padding-right: 10px;"><?= $value['po_itemOut_CySize'] ?> กก.</td>
                            <td style="text-align: right; padding-right: 10px;"><?= $value['po_itemOut_CyAmount'] ?> ถัง</td>
                            <td style="text-align: right; padding-right: 10px;"><?= $value['po_itemOut_CySize'] * $value['po_itemOut_CyAmount'] ?> กก.</td>
                        </tr>
                    <?php
                        $weight += ($value['po_itemOut_CySize'] * $value['po_itemOut_CyAmount']);
                        $sizecount += $value['po_itemOut_CyAmount'];
                    } ?>
                </tbody>
                <tfoot>
                    <tr style="border-top: 1px solid black; border-right: 1px solid black;">
                        <th colspan="4" rowspan="2" style="text-align: left; vertical-align: top; border-left: 1px solid black; 
                        border-bottom: 1px solid black; padding-left: 5px;">
                            หมายเหตุ :
                        </th>
                        <th style="text-align: right; padding-right: 10px; border: 1px solid black; border-bottom: none; text-align: right;">
                            รายการทั้งหมด
                        </th>
                        <th style="text-align: right; padding-right: 10px;"><?= $sizecount ?> ถัง</th>
                    </tr>
                    <tr style="border-top: none; border-right: 1px solid black; text-align: right;">
                        <th style="padding-right: 10px; border-left: 1px solid black; border-right: 1px solid black; border-bottom: 1px solid black;">
                            น้ำหนักทั้งหมด (kg)
                        </th>
                        <th style="padding-right: 10px; border-bottom: 1px solid black;"><?= $weight ?> กก</th>
                    </tr>
                </tfoot>
            </table>
        </div>
        <!-- footer -->
        <div class="row col-100" style="display: block; padding-top: 10px; bottom: 0; z-index: 9999;">
            <table class="moreinfo" style="text-align: left; padding-bottom: 10px;">
                <tr>
                    <td colspan="2"><strong>หมายเหตุ</strong></td>
                </tr>
                <tr>
                    <td width="150px">ฝากเติม</td>
                    <td>..........................................................................................................................................</td>
                </tr>
                <tr>
                    <td width="80%">ถังส่งซ่อม (ขนาด/จำนวนถัง)</td>
                    <td>..........................................................................................................................................</td>
                </tr>
                <tr>
                    <td width="80%">รับถังซ่อมกลับ (ขนาด/จำนวนถัง)</td>
                    <td>..........................................................................................................................................</td>
                </tr>
                <tr>
                    <td width="80%">รับถังซ่อมกลับจากการซ่อมวันที่</td>
                    <td>..........................................................................................................................................</td>
                </tr>
            </table>
            <table class="signature">
                <tr style="border: 1px solid black;">
                    <th width="33.33%" style="border-right: 1px solid black;">ผู้ขับ</th>
                    <th width="33.33%" style="border-right: 1px solid black;">ผู้ตรวจนับถังไป</th>
                    <th width="33.33%">ผู้ตรวจนับรับถังกลับ</th>
                </tr>
                <tr style="border: 1px solid black; height: 150px; vertical-align: bottom;">
                    <td style="border-right: 1px solid black; padding-bottom: 10px;">
                        .................................................
                        <br><br>
                        (.................................................)
                    </td>
                    <td style="border-right: 1px solid black; padding-bottom: 10px;">
                        .................................................
                        <br><br>
                        (.................................................)
                    </td>
                    <td style="padding-bottom: 10px;">
                        .................................................
                        <br><br>
                        (.................................................)
                    </td>
                </tr>
                <tr>
                    <td colspan="3" style="text-align: right;"><strong>Update <?= strftime("%x %H:%M"); ?> ครั้งที่ 1/2022</strong></td>
                </tr>
            </table>
        </div>
    </div>
</body>
<script>
    document.addEventListener('DOMContentLoaded', (event) => {
        setTimeout(function() {
            window.focus();
            window.print();
            window.onafterprint = window.close();
        }, 1);
    })
</script>
</html>