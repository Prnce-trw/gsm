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
    <link href="../css/_reportdiv.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Sarabun', sans-serif !important;
            height: 100%;
            margin: 0px;
        }
    </style>
    <!-- <script type="text/javascript">
        function Process() {
            var rows = document.getElementsByClassName("data");
            var len = rows.length;
            for (var x = 0; x < len; x++) {
                var ctr = x + 1;
                if (ctr % 22 == 0) {
                    createPageBreak(x, rows[x]);
                    console.log("Break =>", rows[x])
                }
            }

            function createPageBreak(x, row) {
                // row.setAttribute("STYLE", "page-break-after:always;");
                row.css({
                    "page-break-after": "always",
                    "-webkit-column-break-after": "always",
                    "-webkit-region-break-after": "always",
                });
            }
        }
    </script> -->
</head>
<?php
include_once("../conn.php");
$fetchdata = new DB_con();
date_default_timezone_set("Asia/Bangkok");
$dataBrandSize = $fetchdata->fetchdataReport($_GET['PO_ID']);
$docHeader = $fetchdata->fetchdataReportHeader($_GET['PO_ID'])->fetch_assoc();
$date = date_create($docHeader['head_po_docdate']);
// $count = 0;
$sizecount = 0;
$weight = 0;
?>

<body>
    <div class="gridPage">
        <div class="docHeader">
            <div class="docHeaderCell" id="tgLogo">
                <img src="../image/tglogo.webp" alt="tg_logo" height="50px">
            </div>
            <div class="docHeaderCell" id="tgName">
                <h3>บริษัท ไทยแก๊ส คอร์ปอเรชั่น จำกัด สาขาที่ 1</h3>
            </div>
            <div class="docHeaderCell" id="headerDate">
                <strong>เติมแก๊ส ประจำวันที่</strong> <?= date_format($date, "d/m/Y") ?>
            </div>
            <div class="docHeaderCell" id="headerRound">
                <strong>รอบที่</strong> 1
            </div>
            <div class="docHeaderCell" id="outTime">
                <strong>เวลาไป</strong> 12:00
            </div>
            <div class="docHeaderCell" id="arrivedTime">
                <strong>เวลากลับ</strong> 14:00
            </div>
            <div class="docHeaderCell" id="fillingPlant">
                <strong>โรงบรรจุ</strong>
                <?php if (!$docHeader['FP_Name']) {
                    echo "ไม่ระบุ";
                } else {
                    echo "$docHeader[FP_Name]";
                } ?></td>
            </div>
        </div>
        <div class="docContent">
            <!-- <div class="docContentCell" id="num">ลำดับที่</div>
            <div class="docContentCell" id="brand">ยี่ห้อ</div>
            <div class="docContentCell" id="cyType">ประเภทถัง</div>
            <div class="docContentCell" id="cySize">ขนาด</div>
            <div class="docContentCell" id="cyCount">จำนวน</div>
            <div class="docContentCell" id="cyWeight">น้ำหนักทั้งหมด</div>
            <div class="docContentCell" id="data"> -->
            <table class="report" width="100%">
                <thead>
                    <tr style="border-top: 1px solid black;">
                        <th width="8%" style="border-left: 1px solid black">ลำดับที่</th>
                        <th width="29%">ยี่ห้อ</th>
                        <th width="10%">ประเภทถัง</th>
                        <th width="10%">ขนาด</th>
                        <th width="8%">จำนวน</th>
                        <th width="10%" colspan="2">น้ำหนักทั้งหมด</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($dataBrandSize as $key => $value) { ?>
                        <tr class="data" style="border-bottom: 0;" id="row_<?= $key + 1 ?>">
                            <td><?= $key + 1 ?></td>
                            <td style="text-align: left; padding-left: 10px;"><?= $value['ms_product_name'] ?></td>
                            <td>
                                <?php
                                if ($value['po_itemOut_type'] == 'N') {
                                    echo "น้ำแก๊ส";
                                } else if ($value['po_itemOut_type'] == 'Adv') {
                                    echo "ฝากเติม";
                                }
                                ?>
                            </td>
                            <td style="text-align: right; padding-right: 10px;"><?= $value['po_itemOut_CySize'] ?> กก.</td>
                            <td style="text-align: right; padding-right: 10px;"><?= $value['po_itemOut_CyAmount'] ?> ถัง</td>
                            <td colspan="2" style="text-align: right; padding-right: 10px;"><?= $value['wightSize'] * $value['po_itemOut_CyAmount'] ?> กก.</td>
                        </tr>
                    <?php
                        // if ((($key + 1) % 22) == 0) {
                        //     break;
                        // }
                        $weight += ($value['wightSize'] * $value['po_itemOut_CyAmount']);
                        $sizecount += $value['po_itemOut_CyAmount'];
                        // $count++;
                    } ?>
                    <tr style="border-top: 1px solid black; border-right: 1px solid black;">
                        <th colspan="3" rowspan="2" style="text-align: left; vertical-align: top; border-left: 1px solid black; 
                                border-bottom: 1px solid black; padding-left: 5px;">
                            หมายเหตุ :
                        </th>
                        <th colspan="2" style="text-align: right; padding-right: 10px; border: 1px solid black; border-bottom: none; text-align: right;">
                            รายการทั้งหมด
                        </th>
                        <th style="text-align: right;"><?= $sizecount ?></th>
                        <th style="text-align: left; padding-left: 5px;" width="1%">ถัง</th>
                    </tr>
                    <tr style="border-top: none; border-right: 1px solid black; text-align: right; padding-top: 10px">
                        <th colspan="2" style="padding-right: 10px; border-left: 1px solid black; border-right: 1px solid black; border-bottom: 1px solid black;">
                            น้ำหนักทั้งหมด
                        </th>
                        <th style="text-align: right; border-bottom: 1px solid black;"><?= $weight ?></th>
                        <th style="text-align: left; padding-left: 5px; border-bottom: 1px solid black;" width="1%">กก.</th>
                    </tr>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="7" style="border-top: 1px"></td>
                    </tr>
                </tfoot>
            </table>
        </div>
        <div class="docFooter">
            <div class="moreInfoHeader">หมายเหตุ</div>
            <div class="toFilling">ฝากเติม</div>
            <div class="toFixing">ถังส่งซ่อม (ขนาด/จำนวนถัง)</div>
            <div class="fixedReturn">รับถังซ่อมกลับ (ขนาด/จำนวนถัง)</div>
            <div class="fixedDate">รับถังซ่อมกลับจากการซ่อมวันที่</div>
            <div class="toFillingWrite">
                .........................................................................................................................................</div>
            <div class="toFixingWrite">
                .........................................................................................................................................</div>
            <div class="fixedReturnWrite">
                .........................................................................................................................................</div>
            <div class="fixedDateWrite">
                .........................................................................................................................................</div>

            <div class="signature">
                <table class="signature">
                    <tr style="border: 1px solid black;">
                        <th width="33.33%" style="border-right: 1px solid black;">ผู้ขับ</th>
                        <th width="33.33%" style="border-right: 1px solid black;">ผู้ตรวจนับถังออก</th>
                        <th width="33.33%">ผู้ตรวจนับถังเข้า
                    <tr style="border: 1px solid black; height: 150px; vertical-align: bottom;">
                        <td style="border-right: 1px solid black; padding-bottom: 10px;">
                            .................................................<br><br>
                            <?php
                            if (!$docHeader['emp_name'] && !$docHeader['emp_lastname']) {
                                echo "(.................................................)";
                            } else {
                                echo "($docHeader[emp_name] $docHeader[emp_lastname])";
                            }
                            ?>
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
                    <!-- <tr>
                    <td colspan="3" style="text-align: right;"><strong>Update <?= strftime("%e/%m/%Y %H:%M"); ?> ครั้งที่ 1/2022</strong></td>
                </tr> -->
                </table>
            </div>

            <div class="updatedAt"><strong>Update <?= strftime("%x %H:%M"); ?> ครั้งที่ 1/2022</strong></div>
        </div>
    </div>

    <script src="../js/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            // setTimeout(function() {
            //     window.focus();
            //     window.print();
            //     window.onafterprint = window.close();
            // }, 200);

            // var row = 0, max = 22, 
            // pageBreak = '<tr class="page-break"><td style="border-top: 1px solid black; height: 1px;" colspan="7">&nbsp;</td></tr>',
            // pb = '<tr><td style="border: none; height: 1px;" colspan="7">&nbsp;</td></tr>';
            // $('.data').filter(':visible').each(function() {
            //     row++;
            //     console.log(row);

            //     if (row >= max) {
            //         for(var i = 0; i < 17; i++) {
            //             $(this).after(pb);
            //         }
            //         $(this).after(pageBreak);
            //         // $(this).css({"page-break-after": "always",});
            //         row = 0;
            //     }
            // });
        });
    </script>
</body>

</html>