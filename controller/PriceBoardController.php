<?php
    include_once('../conn.php');
    include('../function.php');

    if ($_POST['parameter'] == 'InsertPriceBoard') {
        $insertdata         = new DB_con();
        $FPID               = $_POST['fillingplant'];
        $branchID           = $_POST['branchID'];
        foreach ($_POST['unitcheck'] as $key => $value) {
            $unitPrice          = $_POST['unitprice'][$key];
            $sql                = $insertdata->insertPriceBoard($branchID, $FPID, $value, $unitPrice);
        }
        echo "<script>alert('Success')</script>";
        echo "<script>window.location.href='../table_po.php'</script>";
        
    } elseif ($_POST['parameter'] == 'PriceHistory') {
        $branchID           = $_POST['branchID'];
        $sizeID             = $_POST['sizeID'];
        $FPID               = $_POST['fpID'];
        $insertdata         = new DB_con();
        $history            = $insertdata->CheckPriceHistory($branchID, $sizeID, $FPID);
        exit(0);
    }