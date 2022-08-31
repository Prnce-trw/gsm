<?php 
    include_once('../conn.php');
    include('../function.php');
    if ($_POST['parameter'] == 'SearchAssets') {
        $assetID                = $_POST['assetID'];
        $fetchdata              = new DB_con();
        $dataAssets             = $fetchdata->searchAssets($assetID);
        // $AssetsCode             = mysqli_fetch_array($dataAssets);
        echo json_encode($dataAssets);
    } else if ($_POST['parameter'] == 'Distribute') {
        try {
            $supplier               = $_POST['supplier'];
            $rawdate                = $_POST['date_received'];
            $refNo                  = $_POST['refNo'];
            $amount                 = $_POST['amountitem'];
            $price                  = $_POST['price'];
            $vatSelect              = $_POST['vatSelect'];
            $vat                    = $_POST['vat'];
            $totalPrice             = $_POST['totalPrice'];

            $itemID                 = $_POST['selectItem'];
            $unitprice              = $_POST['unitprice'];
            $totalitemprice         = $_POST['totalitemprice'];
            $qty                    = $_POST['qty'];

            $Exdate                 = explode('/', $rawdate);
            $date_received          = $Exdate[2].'-'.$Exdate[1].'-'.$Exdate[0];
            $fetchdata              = new DB_con();
            $DisID                  = $fetchdata->RunningDisID();
            // var_dump($_POST);
            // exit();
            $HeadDis                = $fetchdata->insertHeadDis($DisID, $supplier, $date_received, $refNo, $amount, $price, $vatSelect, $vat, $totalPrice);
            $DisOutstand            = $fetchdata->insertDisOut($DisID, $itemID, $unitprice, $totalitemprice, $qty);

            echo "<script>alert('Success')</script>";
            echo "<script>window.location.href='../distribute/distribute.blade.php'</script>";
        } catch (\Throwable $th) {
            echo "<script>alert('Failed: "+$th->getMessage()+"')</script>";
            echo "<script>window.location.href='../distribute/distribute.blade.php'</script>";
        }
    } else if ($_POST['parameter'] == 'selectHeadDis') {
        $dis_id                 = $_POST['dis_id'];
        $fetchdata              = new DB_con();
        $HeadDis                = $fetchdata->selectHeadDis($dis_id);
        echo json_encode($HeadDis);
    }