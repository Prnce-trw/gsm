<?php 
    if(!$_GET['PO_ID']) {
        header('location: ../table_po.php');
        // echo '<script>history.back();</script>';
        exit();
    }
?>
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
    <link href="../css/_report.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Sarabun', sans-serif !important;
            height: 100%;
            margin: 0px;
        }
    </style>
    <script type="text/javascript">
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
    </script>
</head>
<?php
include_once("../conn.php");
$fetchdata = new DB_con();
date_default_timezone_set("Asia/Bangkok");
$dataBrandSize = $fetchdata->fetchdataReport($_GET['PO_ID']);
$fillingplant = $fetchdata->fetchdataReportHeader($_GET['PO_ID'])->fetch_assoc();
$date = date_create($fillingplant['head_po_docdate']);
// $count = 0;
$sizecount = 0;
$weight = 0;
?>

<body onload="Process();">
    <table class="docpage">
        <!-- header -->
        <thead>
            <tr>
                <td>
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
                </td>
            </tr>
        </thead>
        <!-- header -->
        <tbody>
            <tr>
                <td>
                    <!-- body -->
                    <table class="report" style="width: 100%; page-break-inside: auto;">
                        <thead>
                            <tr style="border: 1px solid black;">
                                <th width="8%">ลำดับที่</th>
                                <th width="39%">ยี่ห้อ</th>
                                <th width="10%">ประเภทถัง</th>
                                <th width="8.5%">ขนาด</th>
                                <th width="6.5%">จำนวน</th>
                                <th colspan="2" width="10%">น้ำหนักทั้งหมด</th>
                            </tr>
                        </thead>
                        <tbody style="border-bottom: 1px solid black;">
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
                                    <td colspan="2" style="text-align: right; padding-right: 10px;"><?= $value['po_itemOut_CySize'] * $value['po_itemOut_CyAmount'] ?> กก.</td>
                                </tr>
                            <?php
                                // if ((($key + 1) % 22) == 0) {
                                //     break;
                                // }
                                $weight += ($value['po_itemOut_CySize'] * $value['po_itemOut_CyAmount']);
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
                            <tr style="line-height: 1px;">
                                <td colspan="7" style="border-top: 1px;">&nbsp;</td>
                            </tr>
                        </tfoot>
                    </table>
                    <!-- body -->
                </td>
            </tr>
        </tbody>
        <tfoot>
            <tr>
                <td>
                    <!-- footer -->
                    <table>
                        <tr>
                            <td>
                                <table class="moreinfo" style="text-align: left; padding-bottom: 10px;">
                                    <tr>
                                        <td colspan="2"><strong>หมายเหตุ</strong></td>
                                    </tr>
                                    <tr>
                                        <td width="250px">ฝากเติม</td>
                                        <td>.........................................................................................................................................</td>
                                    </tr>
                                    <tr>
                                        <td width="">ถังส่งซ่อม (ขนาด/จำนวนถัง)</td>
                                        <td>.........................................................................................................................................</td>
                                    </tr>
                                    <tr>
                                        <td width="">รับถังซ่อมกลับ (ขนาด/จำนวนถัง)</td>
                                        <td>.........................................................................................................................................</td>
                                    </tr>
                                    <tr>
                                        <td width="">รับถังซ่อมกลับจากการซ่อมวันที่</td>
                                        <td>.........................................................................................................................................</td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                        <tr>
                            <td>
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
                            </td>
                        </tr>
                    </table>
                    <!-- footer -->
                </td>
            </tr>
        </tfoot>
    </table>

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
            //         for(var i = 0; i < 50; i++) {
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