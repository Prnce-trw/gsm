<?php 
    include_once('../conn.php');
    include('../function.php');
    if ($_POST['parameter'] == 'PreOrderCylinder') {
        try {
            $insertdata     = new DB_con();
            $DocumentNo     = $insertdata->RunningNo("PO");
            $filling        = $_POST['gas_filling'];
            $comment        = $_POST['comment'];
            $POStatus       = $_POST['POStatus'];
            $CarID          = $_POST['car'];
            $DriverID       = $_POST['driver'];
            $sql            = $insertdata->insertPO($DocumentNo, $filling, $comment, $POStatus, $CarID, $DriverID);

            foreach ($_POST['pickitem'] as $key => $value) {
                $item               = explode('/', $value);
                $itemType           = 'N';
                $sqlItem            = $insertdata->insertItem($DocumentNo, $item[0], $item[1], $item[2], $item[3], $item[4]);

                if ($POStatus == 'Confirm') {
                    $cerrent_year   = date("Y");
                    $Total          = $_POST['total'];
                    $datan_id       = $insertdata->Curmovement($cerrent_year, $item[0], $item[1], $item[2]);
                }
            }
            // exit(0);
            echo "<script>alert('Success')</script>";
            echo "<script>window.location.href='../table_po.php'</script>";
        } catch (\Throwable $th) {
            echo "<script>alert('Failed: "+$th->getMessage()+"')</script>";
            echo "<script>window.location.href='../table_po.php'</script>";
        }
    } elseif ($_POST['parameter'] == 'DraftPO') {
        try {
            $insertdata     = new DB_con();
            $POID           = $_POST['POID'];
            $POStatus       = $_POST['POStatus'];
            $car            = $_POST['car'];
            $FPID           = $_POST['gas_filling'];
            $driver         = $_POST['driver'];
            $sql            = $insertdata->UpdateStatusDraftPO($POID, $FPID, $POStatus, $car, $driver);
            
            if ($_POST['POStatus'] == 'Confirm') {
                foreach ($_POST['pickitem'] as $key => $value) {
                    $item           = explode('/', $value);
                    $cerrent_year   = date("Y");
                    $Total          = $_POST['total'];
                    $updateItemOut  = $insertdata->UpdateItemOut($POID, $item[0], $item[1], $item[2], $item[3]);
                    $datan_id       = $insertdata->Curmovement($cerrent_year, $item[0], $item[1], $item[2]);
                }
            } elseif ($_POST['POStatus'] == 'Draft') {
                foreach ($_POST['pickitem'] as $key => $value) {
                    $item           = explode('/', $value);
                    $updateItemOut  = $insertdata->UpdateItemOut($POID, $item[0], $item[1], $item[2], $item[3]);
                }

                foreach ($_POST['Add_pickitem'] as $key => $valueItem) {
                    $newitem           = explode('/', $valueItem);
                    $insertNewItemOut  = $insertdata->insertItem($POID, $newitem[0], $newitem[1], $newitem[2], $newitem[3], $newitem[4]);
                }
            }

            if ($_POST['POStatus'] == 'Draft') {
                echo "<script>alert('Success')</script>";
                echo "<script>window.location.href='../draft/draftPO.php?id=".$POID."'</script>";
            } else {
                echo "<script>alert('Success')</script>";
                echo "<script>window.location.href='../table_po.php'</script>";
            }
        } catch (\Throwable $th) {
            echo "<script>alert('Failed: "+$th->getMessage()+"')</script>";
            echo "<script>window.location.href='../table_po.php'</script>";
        }
    } else if ($_POST['parameter'] == 'EditPO') {
        try {
            $insertdata     = new DB_con();
            $RefDO          = $_POST['RefDO'];
            $POID           = $_POST['POID'];
            $Fillstation    = $_POST['gas_filling'];
            $Round          = $_POST['round'];
            $timeIn         = $_POST['hourIn'].":".$_POST['minuteIn'].":00";
            $timeOut        = $_POST['hourOut'].":".$_POST['minuteOut'].":00";
            $sql            = $insertdata->insertPOReceipt($POID, $RefDO, $timeIn, $timeOut, $Fillstation);
            $sqlPO          = $insertdata->updateHeadPO($POID, $Fillstation, $Round);

            foreach ($_POST['pickitem'] as $key => $value) {
                $item               = explode('/', $value);
                $itemType           = 'N';
                // $sqlItem            = $insertdata->insertItemEntrance($POID, $RefDO, $item[0], $item[1], $item[2], $item[3], $unitprice, $amtprice);

                if ($_POST['sub_parameter'] == 'PreOrder') {
                    $cerrent_year   = date("Y");
                    $Total          = $_POST['total'];
                    $price          = $_POST['priceUnit'][$key];
                    $datan_id       = $insertdata->CurmovementIn($cerrent_year, $RefDO, $Total, $item[0], $item[1], $item[2], $price);
                }
            }
            
            echo "<script>alert('Success')</script>";
            echo "<script>window.location.href='table_po.php'</script>";
        } catch (\Exception $e) {
            echo "<script>alert('Failed: "+$e->getMessage()+"')</script>";
            echo "<script>window.location.href='table_po.php'</script>";
        }
        exit(0);
    } elseif ($_POST['parameter'] == 'aJaxCheckSize') {
        $size = $_POST['size'];
        $selectdata         = new DB_con();
        $resultSize = $selectdata->aJaxCheckWeight($size);
        $weightSize = mysqli_fetch_array($resultSize);
        $data = array(
            'resultSize' => $weightSize['weightSize_id'],
            'resultOrderBy' => $weightSize['order_by_no'],
        );
        echo json_encode($data);
    } elseif ($_POST['parameter'] == "aJaxCheckStock") {
        $brand                  = $_POST['brand'];
        $weight                 = $_POST['weight'];
        $amount                 = $_POST['amount'];
        $branch                 = $_POST['branch'];
        $fetchdata              = new DB_con();
        $resultSize             = $fetchdata->aJaxCheckStock($brand, $weight, $amount, $branch);
        $fetchqty               = mysqli_fetch_array($resultSize);
        echo json_encode($fetchqty);
    }