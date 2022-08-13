<?php
// Require composer autoload
    require_once __DIR__ . '/../vendor/autoload.php';
    $defaultFontConfig = (new Mpdf\Config\FontVariables())->getDefaults();
    $fontData = $defaultFontConfig['fontdata'];
    $mpdf = new \Mpdf\Mpdf(['tempDir' => __DIR__ . '/tmp',
    'fontdata' => $fontData + [
            'sarabun' => [ //Lower Case
                'R' => 'THSarabunNew.ttf',
                'I' => 'THSarabunNewItalic.ttf',
                'B' =>  'THSarabunNewBold.ttf',
                'BI' => "THSarabunNewBoldItalic.ttf",
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
    <link rel="stylesheet" href="css/select2.min.css">
    <link rel="stylesheet" href="css/custom.css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Sarabun&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: sarabun;
        }
        .a4 {
            width: 21cm;
            height: 29.7cm;
            padding: 10mm 10mm 10mm 10mm;
            
        }
        
        .nostyle table, .nostyle td, .nostyle tr, .nostyle th {
            border: none;
            background-color: #fff;
            height: 40px;
        }

        .report table, .report td, .report td, .report th {
            border: 1 px solid black;
            background-color: #fff;
            height: 40px;
        }
        .col-90 {
            float: left;
            width: 90%;
            padding: 10px;
        }

        .col-10 {
            float: left;
            width: 10%;
            padding: 10px;
        }
    </style>
</head>
<body>
    <div class="a4">
        <div class="row">
            <table class="nostyle">
                <tr>
                    <td rowspan="3" width="150px"><img src="./image/tglogo.webp" alt="tg_logo"></td>
                    <td colspan="3" class="nostyle"><h3>บริษัท ไทยแก๊ส คอร์ปอเรชั่น จำกัด สาขา 1</h3></td>
                </tr>
                <tr height="40px">
                    <td colspan="2" style="text-align: left;">เติมแก๊ส ประจำวันที่ <?= strftime("%x");?></td>
                    <td style="text-align: right;">รอบที่ 1</td>
                </tr>
                <tr height="40px">
                    <td style="text-align: left;">เวลาไป 12:00</td>
                    <td style="text-align: left;">เวลากลับ 14:00</td>
                    <td style="text-align: right;">โรงบรรจุ บางนา</td>
                </tr>
            </table>
        </div>
        <div class="row">
            <table class="report">
                <thead>
                    <tr>
                        <th width="15%">ขนาด (kg)</th>
                        <th width="40%">ยี่ห้อ</th>
                        <th width="15%">ถังหมุนเวียน</th>
                        <th width="15%">ฝากเติม</th>
                        <th width="15%">รวมทั้งสิ้น</th>
                    </tr>
                </thead>
                <tbody>
                    <?php // while($data = $dataBrandSize->fetch_array()) { ?>
                    <tr>
                        <td>5</td>
                        <td>test</td>
                        <td>2</td>
                        <td>2</td>
                        <td>4</td>
                    </tr>
                    <?php // } ?>
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="2">รวมทั้งหมด</th>
                        <th>2</th>
                        <th>2</th>
                        <th>4</th>
                    </tr>
                </tfoot>
            </table>
        </div>
        <div class="row col-100"><strong>หมายเหตุ</strong></div>
        <div class="row col-100">ฝากเติม ..............................................</div>
        <div class="row col-100">หมายเหตุ</div>
        <div class="row col-100"><strong>Update <?= strftime("%x %H:%M"); ?> ครั้งที่ 1/2022</strong></div>
    </div>
</body>
</html>

<?php
    $html = ob_get_contents(); // ทำการเก็บค่า HTML จากคำสั่ง ob_start()
    $mpdf   -> WriteHTML($html); // ทำการสร้าง PDF ไฟล์
    $mpdf   -> Output("./PDF/REPORT.pdf"); // ให้ทำการบันทึกโค้ด HTML เป็น PDF โดยบันทึกเป็นไฟล์ชื่อ MyPDF.pdf
    ob_end_flush()
?>

ดาวโหลดรายงานในรูปแบบ PDF <a href="./PDF/REPORT.pdf">คลิกที่นี้</a>