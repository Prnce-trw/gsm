<?php
include_once '../conn.php';
require '../vendor/autoload.php';
$conn = new DB_con();

use Mpdf\Tag\Q;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$params = $_GET['action'];

if(isset($params)) {
    if($params == 'search') {
        try {
            $dateST = DateTime::createFromFormat("d/m/Y H:i:s", $_GET['dateStart']." 00:00:00");
            $dateStart = date_format($dateST, "Y-m-d H:i:s");
            $dateN = DateTime::createFromFormat("d/m/Y H:i:s", $_GET['dateEnd']." 23:59:59");
            $dateEnd = date_format($dateN ,"Y-m-d H:i:s");
            
            $data = $conn->fetchdataBuyProd($_GET['branch'], $_GET['product'], $_GET['supplier'], $dateStart, $dateEnd);
            // $error = array("error" => $data);
            // print json_encode($error);
            // exit(0);
            if(!$data) {
                $error = array("error" => "Cannot Fetch Data");
                print json_encode($error);
                exit(0);
            }
            $data = $data->fetch_all(MYSQLI_ASSOC);
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();

            $sheet->setCellValue("A1", "รายงานรายการสินค้าเข้าตามช่วงเวลา");
            if($_GET['branch'] != "all") {
                $branch = $conn->fetchdataBranchName($_GET['branch'])->fetch_row();
                $sheet->setCellValue("A2", "บริษัท ไทยแก๊ส คอร์ปอเรชั่น จำกัด $branch[0]");
            } else {
                $sheet->setCellValue("A2", "บริษัท ไทยแก๊ส คอร์ปอเรชั่น จำกัด");
            }
            
            if($_GET['dateStart'] == $_GET['dateEnd']) {
                $sheet->setCellValue("A3", "ประจำวันที่ $_GET[dateEnd]");
            } else {
                $sheet->setCellValue("A3", "ประจำวันที่ $_GET[dateStart] - $_GET[dateEnd]");
            }        
            $sheet  ->setCellValue("A4", " ");
            foreach (range("1","4") as $row) {
                $sheet->mergeCells("A$row:J$row")->getStyle("A$row:J$row")->getAlignment()->setHorizontal("center");
                $sheet->getStyle("A$row:J$row")->getFont()->setSize(16);
                $sheet->getStyle("A$row:J$row")->getFont()->setBold(true);
            }

            $sheet  ->setCellValue("A5", "ลำดับที่")->setCellValue("B5", "วันที่")->setCellValue("C5", "เลขที่")
                    ->setCellValue("D5", "สาขา")->setCellValue("E5", "รหัสสินค้า")->setCellValue("F5", "น้ำหนัก (kg)")
                    ->setCellValue("G5", "ผู้จำหน่าย")->setCellValue("H5", "จำนวน (หน่วย)")->setCellValue("I5", "ราคา/หน่วย (บาท)")
                    ->setCellValue("J5", "ราคารวม (บาท)");
            $sheet  ->getStyle("A5:J5")->getAlignment()->setHorizontal("center");

            $totalCount = 0;
            $totalPrice = 0;
            foreach($data as $key => $value) {
                $row = $key+6;
                $timestamp = strtotime($value['created_at']);
                $date = date("d/m/Y", $timestamp);
                $branch = !$value['branch_name'] ? "ไม่ระบุ" : $value['branch_name'];
                // if (!$value['branch_name']) { $branch = "ไม่ระบุ"; } else { $branch = $value['branch_name']; }
                $sheet  ->setCellValue("A$row", $key+1)
                        ->setCellValue("B$row", "$date")
                        ->setCellValue("C$row", "$value[po_itemEnt_POID]")
                        ->setCellValue("D$row", "$branch")
                        ->setCellValue("E$row", "$value[itemsCode]")
                        ->setCellValue("F$row", "$value[po_itemEnt_CySize]")
                        ->setCellValue("G$row", "$value[supplier_name]")
                        ->setCellValue("H$row", "$value[po_itemEnt_CyAmount]")
                        ->setCellValue("I$row", "$value[po_itemEnt_unitPrice]")
                        ->setCellValue("J$row", "$value[po_itemEnt_AmtPrice]");
                $sheet  ->getStyle("F$row")->getAlignment()->setHorizontal("right");
                $sheet  ->getStyle("G$row")->getAlignment()->setHorizontal("left");
                $sheet  ->getStyle("H$row")->getAlignment()->setHorizontal("right");
                $styleI2J = $sheet->getStyle("I$row:J$row");
                $styleI2J->getAlignment()->setHorizontal("right");
                $styleI2J->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                
                $totalCount += $value['po_itemEnt_CyAmount'];
                $totalPrice += $value['po_itemEnt_AmtPrice'];
            }

            foreach (range("A", $sheet->getHighestColumn()) as $col) {
                $sheet->getColumnDimension($col)->setAutoSize(true);
            }

            $lastRow = $sheet->getHighestRow() + 1;
            $sheet  ->setCellValue("H$lastRow", "จำนวนรวมทั้งสิ้น")->setCellValue("H".($lastRow + 1), "ราคารวมทั้งสิ้น")
                    ->setCellValue("I$lastRow", $totalCount)->setCellValue("I".($lastRow + 1), $totalPrice)
                    ->setCellValue("J$lastRow", "หน่วย")->setCellValue("J".($lastRow + 1), "บาท");
            // $sheet  ->mergeCells("H$lastRow:I$lastRow")->getStyle("H$lastRow:I$lastRow")->getAlignment()->setHorizontal("right");
            // $sheet  ->mergeCells("H".($lastRow + 1).":I".($lastRow + 1))->getStyle("H".($lastRow + 1).":I".($lastRow + 1))->getAlignment()->setHorizontal("right");
            $sheet  ->getStyle("H$lastRow:J$lastRow")->getAlignment()->setHorizontal("right");
            $sheet  ->getStyle("H".($lastRow + 1).":J".($lastRow + 1))->getAlignment()->setHorizontal("right");
            // $sheet  ->getStyle("J$lastRow")->getAlignment()->setHorizontal("right");
            // $sheet  ->getStyle("J".($lastRow + 1))->getAlignment()->setHorizontal("right");
            $sheet  ->getStyle("I".($lastRow + 1))->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
            // $sheet  ->getStyle("K$lastRow")->getAlignment()->setHorizontal("right");
            // $sheet  ->getStyle("K".($lastRow + 1))->getAlignment()->setHorizontal("right");

            $now = DateTime::createFromFormat('U.u', microtime(true));
            $date = $now->setTimeZone(new DateTimeZone('Asia/Bangkok'))->format("d-m-Y_Hisu");
            $writer = new Xlsx($spreadsheet);
            $writer -> save("excel/report_$date.xlsx");
            $response = array("data" => $data, "filename" => "report_$date", "totalCount" => $totalCount, "totalPrice" => $totalPrice);
            print json_encode($response);
        } catch (\Throwable $th) {
			$error = array("error" => "$th");
			print json_encode($error);
        }
		exit(0);
    } else if($params == "categorySelect") {
		try {
			$data = $conn->fetchdataProductRep($_GET['category']);
			if(!$data) {
				$error = array("error" => "Cannot fetch data");
				print json_encode($error);
				exit(0);
			}
			$data = $data->fetch_all(MYSQLI_ASSOC);
			print json_encode($data);
		} catch (\Throwable $th) {
			$error = array("error" => "$th");
			print json_encode($error);
		}
		exit(0);
    }
} else {
    $error = array("error" => "Parameters '$_GET[action]' Not Found");
    print json_encode($error);
    exit(0);
}