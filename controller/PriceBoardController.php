<?php
    include_once('../conn.php');
    include('../function.php');

    if ($_POST['parameter'] == 'InsertPriceBoard') {
        var_dump($_POST);
        exit(0);
    } elseif ($_POST['parameter'] == 'PriceHistory') {
        $branchID           = $_POST['branchID'];
        $sizeID             = $_POST['sizeID'];
        $FPID               = $_POST['fpID'];
        $insertdata         = new DB_con();
        $history            = $insertdata->CheckPriceHistory($branchID, $sizeID, $FPID);
        exit(0);
    }