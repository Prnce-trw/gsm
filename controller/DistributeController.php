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

            $Exdate                 = explode('/', $rawdate);
            $date_received          = $Exdate[2].'-'.$Exdate[1].'-'.$Exdate[0];
            $fetchdata              = new DB_con();

            $DisID                  = $fetchdata->RunningDisID();
            $HeadDis                = $fetchdata->insertHeadDis($DisID, $supplier, $date_received, $refNo, $amount, $price, $vatSelect, $vat, $totalPrice);

            foreach ($_POST['selectItem'] as $key => $value) {
                $itemID                 = $value;
                $qty                    = $_POST['qty'][$key];
                $unitprice              = $_POST['unitprice'][$key];
                $totalitemprice         = $_POST['totalitemprice'][$key];
                $DisOutstand            = $fetchdata->insertDisOut($DisID, $itemID, $unitprice, $totalitemprice, $qty);
            }
            
            // exit(0);
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
    } else if ($_POST['parameter'] == 'AccDistribute') {
        $fetchdata              = new DB_con();
        try {
            $itemid                     = $_POST['itemid'];
            $headdocid                  = $_POST['headdocid'];
            foreach ($_POST['disitemqty'] as $key => $value) {
                $unitprice                  = $_POST['disitemunitprice'][$key];
                $amount                     = $_POST['disitemamount'][$key];
                $DistributetoBranch         = $fetchdata->DistributetoBranch($headdocid, $itemid, $value, $unitprice, $amount);
            }

            echo "<script>alert('Success')</script>";
            echo "<script>window.location.href='../distribute/distribute.blade.php'</script>";
        } catch (\Throwable $th) {
            echo "<script>alert('Failed: "+$th->getMessage()+"')</script>";
            echo "<script>window.location.href='../distribute/distribute.blade.php'</script>";
        }
    }