<?php
include_once '../conn.php';
require '../vendor/autoload.php';
$conn = new DB_con();

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

if(isset($_GET['action'])) {
    if($_GET['action'] == 'search') {
        try {
            $data = $conn->fetchdataBuyProd($_GET['branch'], $_GET['product'], $_GET['dateStart'], $_GET['dateEnd'])->fetch_all(MYSQLI_ASSOC);
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();

            $sheet->setCellValue("A1", "รายงานรายการสินค้าเข้าตามช่วงเวลา")->setCellValue("A2", "บริษัท ไทยแก๊ส คอร์ปอเรชั่น จำกัด สาขาที่ $_GET[branch]");
            if($_GET['dateStart'] == $_GET['dateEnd']) {
                $sheet->setCellValue("A3", "ประจำวันที่ $_GET[dateEnd]");
            } else {
                $sheet->setCellValue("A3", "ประจำวันที่ $_GET[dateStart] - $_GET[dateEnd]");
            }        
            $sheet  ->setCellValue("A4", " ");
            foreach (range("1","4") as $row) {
                $sheet->mergeCells("A$row:L$row")->getStyle("A$row:L$row")->getAlignment()->setHorizontal("center");
                $sheet->getStyle("A$row:L$row")->getFont()->setSize(16);
                $sheet->getStyle("A$row:L$row")->getFont()->setBold(true);
            }

            $sheet  ->setCellValue("A5", "ลำดับที่")->setCellValue("B5", "วันที่")->setCellValue("C5", "เลขที่")->setCellValue("D5", "สาขา")
                    ->setCellValue("E5", "รหัสสินค้า")->setCellValue("F5", "ชื่อสินค้า")->setCellValue("G5", "แบรนด์")->setCellValue("H5", "น้ำหนัก")
                    ->setCellValue("I5", "ผู้จำหน่าย")->setCellValue("J5", "จำนวน")->setCellValue("K5", "ราคา/หน่วย")->setCellValue("L5", "ราคารวม");
            foreach (range("A5", "L5") as $col) {
                $sheet->getStyle($col)->getAlignment()->setHorizontal("center");
                // $sheet->getStyle($col)->getFont()->setBold(true);
            }
            
            foreach($data as $key => $value) {
                $row = $key+6;
                $timestamp = strtotime($value['created_at']);
                $date = date("d/m/Y", $timestamp);
                $sheet  ->setCellValue("A$row", $key+1)->setCellValue("B$row", "$date")
                        ->setCellValue("C$row", "$value[po_itemEnt_POID]")->setCellValue("D$row", "")
                        ->setCellValue("E$row", "$value[po_itemEnt_CyBrand]")->setCellValue("F$row", "")
                        ->setCellValue("G$row", "$value[ms_product_name]")->setCellValue("H$row", "$value[po_itemEnt_CySize]")
                        ->setCellValue("I$row", "")->setCellValue("J$row", "$value[po_itemEnt_CyAmount]")
                        ->setCellValue("K$row", "$value[po_itemEnt_unitPrice]")->setCellValue("L$row", "$value[po_itemEnt_AmtPrice]");
            }

            // $sheet->fromArray($data, NULL, 'A6');
            foreach (range("A", $sheet->getHighestColumn()) as $col) {
                $sheet->getColumnDimension($col)->setAutoSize(true);
            }
            $date = date("d-m-Y");
            $writer = new Xlsx($spreadsheet);
            $writer->save("excel/report_$date.xlsx");

            header("Content-Type: application/json");
            echo json_encode($data);
        } catch (Throwable $th) {
            echo $th;
        }
    }
} else {
    echo "error";
}