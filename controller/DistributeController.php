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
            // $amount                 = $_POST['amountitem'];
            $price                  = $_POST['price'];
            if ($_POST['vatSelect'] == null) {
                $vatSelect          = 'N';
            } else {
                $vatSelect              = $_POST['vatSelect'];
            }
            $vat_percentage         = $_POST['vat_percentage'];
            $totalPrice             = $_POST['totalPrice'];

            $Exdate                 = explode('/', $rawdate);
            $date_received          = $Exdate[2].'-'.$Exdate[1].'-'.$Exdate[0];
            $fetchdata              = new DB_con();

            $DisID                  = $fetchdata->RunningDisID();
            $HeadDis                = $fetchdata->insertHeadDis($DisID, $supplier, $date_received, $refNo, $price, $vatSelect, $vat_percentage, $totalPrice);

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
            $itemcode                   = $_POST['itemcode'];
            $headdocid                  = $_POST['headdocid'];
            $totalItem                  = $_POST['totalItem'];

            $updatebal                  = $fetchdata->DistributeUpdateOST($headdocid, $itemid, $totalItem);
            if ($updatebal['status'] == true) {
                foreach ($_POST['disitemqty'] as $key => $value) {
                    $unitprice                  = $_POST['disitemunitprice'][$key];
                    $amount                     = $_POST['disitemamount'][$key];
                    $branchID                   = $_POST['disitembranchid'][$key];
                    $DistributetoBranch         = $fetchdata->DistributetoBranch($headdocid, $itemid, $value, $unitprice, $amount, $branchID);
                    $DistributeMovement         = $fetchdata->DistributeMovement($headdocid, $itemid, $value, $unitprice, $amount, $branchID);
                }

                echo "<script>alert('Success')</script>";
                echo "<script>window.location.href='../distribute/distribute.blade.php'</script>";
            } else if ($updatebal['status'] == false) {
                echo "<script>alert('Failed: จำนวนคงเหลือไม่เพียงพอต่อการกระจายอุปกรณ์)</script>";
                echo "<script>window.location.href='../distribute/distribute.blade.php'</script>";
            }
        } catch (\Throwable $th) {
            echo "<script>alert('Failed: "+$th->getMessage()+"')</script>";
            echo "<script>window.location.href='../distribute/distribute.blade.php'</script>";
        }
    } else if ($_POST['parameter'] == 'AcceptAccToBranch') {
        try {
            $fetchdata              = new DB_con();
            $branchID               = $_POST['branchid'];
            $cerrent_year           = date("Y");
            if ($_POST['itemid'] != null) {
                foreach ($_POST['itemid'] as $key => $value) {
                    $iteminfo               = explode('/', $_POST['iteminfo'][$key]);
                    $qty                    = $iteminfo[0];
                    $docRef                 = $iteminfo[1];
                    $price                  = $iteminfo[2];
                    $AccBranchID            = $iteminfo[3];
                    $datamovement           = $fetchdata->insertInventMovment($cerrent_year, $value, $branchID, $qty, $docRef, $price, $AccBranchID);
                }
            }

            echo "<script>alert('Success')</script>";
            echo "<script>window.location.href='../distribute/distribute_branch.blade.php'</script>";
        } catch (\Throwable $th) {
            echo "<script>alert('Failed: "+$th->getMessage()+"')</script>";
            echo "<script>window.location.href='../distribute/distribute_branch.blade.php'</script>";
        }
    } else if ($_POST['parameter'] == 'RejectItem') {
        try {
            $fetchdata              = new DB_con();
            $itemid                 = $_POST['itemid'];
            $updatestatusitem       = $fetchdata->UpdateStatusItem($itemid);
            $status       = 'Success';
            echo json_encode($status);
        } catch (\Throwable $th) {
            $status       = 'False';
            echo json_encode($status);
        }
    } else if ($_POST['parameter'] == 'AccBranchInfo') {
        $fetchdata              = new DB_con();
        $itemid                 = $_POST['itemid'];
        $Accitem                = $fetchdata->AccBranchInfo($itemid);
        $data                   = mysqli_fetch_array($Accitem);
        echo json_encode($data);
    }