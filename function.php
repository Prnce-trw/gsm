<?php


function POStatus($param)
{
    if ($param == 'Draft') {
        return 'ฉบับร่าง';
    } else if ($param == 'Confirm') {
        return 'ตัวจริง';
    } elseif ($param == 'Cancel') {
        return 'ยกเลิก';
    } else {
        return 'ไม่พบสถานะ';
    }
}

function multiexplode ($delimiters,$string) {
    $ready = str_replace($delimiters, $delimiters[0], $string);
    $launch = explode($delimiters[0], $ready);
    return  $launch;
}

function StatustoText($param)
{
    if ($param == 'Y') {
        return '<span class="text-success">ใช้งาน</span>';
    } elseif ($param == 'N') {
        return '<span class="text-danger">ยกเลิก</span>';
    } else {
        return 'ไม่พบข้อมูล';
    }
}

function CylinderType($param)
{
    if ($param == 'N') {
        return 'น้ำแก๊สหมุนเวียน';
    } elseif ($param == 'Adv') {
        return 'ถังฝากเติม';
    } else {
        return 'ไม่พบข้อมูล';
    }
}

function getUnitPrice($sizeID, $branchID, $FPID)
{
    $fetchdata      = new DB_con();
    $dataItem       = $fetchdata->fetchPriceUnit($sizeID, $branchID, $FPID);
    $row            = mysqli_fetch_array($dataItem);
    if (isset($row)) {
        return $row['currPB_itemPrice'];
    } else {
        return '';
    }
}

function UnitAmount($FPID, $sizeID, $branchID, $qty)
{
    $fetchdata      = new DB_con();
    $dataItem       = $fetchdata->itemUnitPrice($FPID, $sizeID, $branchID);
    $row            = mysqli_fetch_array($dataItem);
    if (isset($row)) {
        return number_format($row['currPB_itemPrice'] * $qty,2);
    } else {
        return '';
    }
}

function UnitPrice($FPID, $sizeID, $branchID, $qty)
{
    $fetchdata      = new DB_con();
    $dataItem       = $fetchdata->itemUnitPrice($FPID, $sizeID, $branchID);
    $row            = mysqli_fetch_array($dataItem);
    if (isset($row)) {
        return number_format($row['currPB_itemPrice'], 2);
    } else {
        return '';
    }
}