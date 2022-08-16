<?php
// Require composer autoload
require_once __DIR__ . '../../vendor/autoload.php';
$defaultFontConfig = (new Mpdf\Config\FontVariables())->getDefaults();
$fontData = $defaultFontConfig['fontdata'];
$mpdf = new \Mpdf\Mpdf([
    'mode' => 'utf-8', 'format' => 'A4', 'tempDir' => __DIR__ . '/tmp',
    'default_font_size' => 16,
    'fontdata' => $fontData + [
        'sarabun' => [ //Lower Case
            'R' => 'THSarabunNew.ttf',
            'I' => 'THSarabunNewItalic.ttf',
            'B' =>  'THSarabunNewBold.ttf',
            'BI' => "THSarabunNewBoldItalic.ttf",
            'useOTL' => true,
        ]
    ],
]);
date_default_timezone_set("Asia/Bangkok");
ob_start(); // Start get HTML code
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GSM</title>
    <link rel="stylesheet" href="../css/report.css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Sarabun&display=swap" rel="stylesheet">
    <style>
        page[size="A4"] {
            background: white;
            width: 21cm;
            height: 29.7cm;
            display: block;
            margin: 0 auto;
            margin-bottom: 0.5cm;
            box-shadow: 0 0 0.5cm rgba(0,0,0,0.5);
        }
        @media print { 
            body, page[size="A4"] {
                margin: 0;
                box-shadow: 0;
            }
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
    <page class="A4">
        <div class="row col-100_nopad">
            <table class="docheader">
                <tr>
                    <td rowspan="3" width="150px" style="vertical-align: top;"><img src="../image/tglogo.webp" alt="tg_logo" height="50px"></td>
                    <td colspan="3" height="30px">
                        <h3>บริษัท ไทยแก๊ส คอร์ปอเรชั่น จำกัด สาขาที่ 1</h3>
                    </td>
                </tr>
                <tr height="40px">
                    <td colspan="2" style="text-align: left;"><strong>เติมแก๊ส ประจำวันที่</strong> <?= date_format($date, "d/m/Y")//strftime("%x"); ?></td>
                    <td style="text-align: right;"><strong>รอบที่</strong> 1</td>
                </tr>
                <tr height="40px">
                    <td style="text-align: left;"><strong>เวลาไป</strong> 12:00</td>
                    <td style="text-align: left;"><strong>เวลากลับ</strong> 14:00</td>
                    <td style="text-align: right;"><strong>โรงบรรจุ</strong> 
                    <?php if(!$fillingplant['FP_Name']){ echo "ไม่ระบุ"; } else { echo "$fillingplant[FP_Name]"; } ?></td>
                </tr>
            </table>
        </div>
        <div class="row col-100_nopad">
            <table class="report">
                <thead>
                    <tr style="border: 1px solid black;">
                        <th width="50px">ลำดับที่</th>
                        <th width="150px">ยี่ห้อ</th>
                        <th width="150px">ประเภทถัง</th>
                        <th width="80px">ขนาด (kg)</th>
                        <th width="70px">จำนวน</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($dataBrandSize as $key => $value) { ?>
                        <tr>
                            <td><?= $key + 1 ?></td>
                            <td><?= $value['ms_product_name'] ?></td> 
                            <td>
                                <?php 
                                if($value['po_itemOut_type'] == 'N') {
                                    echo "ถังหมุนเวียน";
                                } else if ($value['po_itemOut_type'] == 'Adv') {
                                    echo "ฝากเติม";
                                }
                                ?>
                            </td>
                            <td style="border-bottom: 0;"><?= $value['po_itemOut_CySize'] ?></td>
                            <td><?= $value['po_itemOut_CyAmount'] ?></td>
                        </tr>
                    <?php 
                        $weight += ($value['po_itemOut_CySize'] * $value['po_itemOut_CyAmount']);
                        $sizecount += $value['po_itemOut_CyAmount']; 
                    } ?>
                </tbody>
                <tfoot>
                    <tr style="border: 1px solid black;">
                        <th colspan="3" rowspan="2" style="text-align: left; vertical-align: top;">
                            &nbsp; หมายเหตุ : 
                        </th>
                        <th style="text-align: right; padding-right: 10px;">
                        รายการทั้งหมด (ถัง)</th>
                        <th><?= $sizecount ?></th>
                    </tr>
                    <tr style="border: 1px solid black;">
                        <th style="text-align: right; padding-right: 10px;">
                        น้ำหนักทั้งหมด (kg)</th>
                        <th><?= $weight ?></th>
                    </tr>
                </tfoot>
            </table>
        </div>
        <div class="row col-100_nopad nostyle" style="padding-top: 10px;"><strong>หมายเหตุ</strong></div>
        <div class="row col-100_nopad nostyle">
            <table class="moreinfo">
                <tr>
                    <td width="30%">ฝากเติม</td>
                    <td>..........................................................................................................................................</td>
                </tr>
                <tr>
                    <td>ถังส่งซ่อม (ขนาด/จำนวนถัง)</td>
                    <td>..........................................................................................................................................</td>
                </tr>
                <tr>
                    <td>รับถังซ่อมกลับ (ขนาด/จำนวนถัง)</td>
                    <td>..........................................................................................................................................</td>
                </tr>
                <tr>
                    <td>รับถังซ่อมกลับจากการซ่อมวันที่</td>
                    <td>..........................................................................................................................................</td>
                </tr>
            </table>
        </div>
        <div class="row col-100 nostyle">
            <table class="signature">
                <tr>
                    <th width="33.33%">ผู้ขับ</th>
                    <th width="33.33%">ผู้ตรวจนับถังไป</th>
                    <th width="33.33%">ผู้ตรวจนับรับถังกลับ</th>
                </tr>
                <tr style="height: 100px;">
                    <td><br>_________________________<br>(_________________________)</td>
                    <td><br>_________________________<br>(_________________________)</td>
                    <td><br>_________________________<br>(_________________________)</td>
                </tr>
            </table>
        </div>
        <!-- <div class="row col-100" style="text-align: right;"><strong>Update <?= strftime("%x %H:%M"); ?> ครั้งที่ 1/2022</strong></div> -->
    </page>
    
</body>

</html>

<?php
$html = ob_get_contents(); // ทำการเก็บค่า HTML จากคำสั่ง ob_start()
$mpdf->showWatermarkText = true; // ตั้งค่าให้แสดงผลลายน้ำ
$mpdf->SetWatermarkText("ห้ามดัดแปลงเอกสาร", 0.1); // ตั้งค่าลายน้ำ 0.1 คือค่าความเข้มของลายน้ำ
$mpdf->watermark_font = 'sarabun'; // เลือก Font สำหรับลายน้ำ
$time = strftime("%x %H:%M");
$mpdf->SetHeader('{PAGENO}');
$mpdf->defaultheaderfontsize = 16;
$mpdf->defaultheaderfontstyle = 'sarabun';
$mpdf->SetFooter("Update $time ครั้งที่ 1/2022");
$mpdf->defaultfooterfontsize = 16;
$mpdf->defaultfooterfontstyle = 'sarabun';
$mpdf->WriteHTML($html); // ทำการสร้าง PDF ไฟล์
$mpdf->Output("../PDF/REPORT.pdf", "F"); // ให้ทำการบันทึกโค้ด HTML เป็น PDF
header('location: ../PDF/REPORT.pdf'); // redirect ไปยังไฟล์ report
ob_end_flush();
// echo "<script>window.open('./PDF/REPORT.pdf', '', 'width=400'); window.location(history.back());</script>";
exit();
?>

<!-- ดาวน์โหลดรายงานในรูปแบบ PDF <a href="./PDF/REPORT.pdf">คลิกที่นี่</a> -->