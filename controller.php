<?php
    include_once('conn.php');
    // include('function.php');
    if ($_POST['parameter'] == 'PreOrderCylinder') {
        try {
            $insertdata     = new DB_con();
            $DocumentNo     = $insertdata->RunningNo("PO");
            $filling        = $_POST['gas_filling'];
            $comment        = $_POST['comment'];
            $sql            = $insertdata->insertPO($DocumentNo, $filling, $comment);

            // var_dump($fetchData['n_id']+1);
            // print_r($_POST['pickitem']);
            // echo $_POST;
            
            // exit(0);
            foreach ($_POST['pickitem'] as $key => $value) {
                $item               = explode('/', $value);
                $itemType           = 'N';
                $sqlItem            = $insertdata->insertItem($DocumentNo, $item[0], $item[1], $item[2], $item[3]);

                // print_r($key);
                // echo $key;
            
                // exit(0);
                $cerrent_year   = date("Y");
                $Total          = $_POST['total'];
                $datan_id       = $insertdata->Curmovement($cerrent_year, $DocumentNo, $Total, $item[0], $item[1], $item[2]);
            }
            
            if ($sql && $sqlItem) {
                echo "<script>alert('Success')</script>";
                echo "<script>window.location.href='table_po.php'</script>";
            } else {
                echo "<script>alert('False')</script>";
                echo "<script>window.location.href='table_po.php'</script>";
            }
        } catch (\Exception $e) {
            echo "<script>alert('Failed: "+$e->getMessage()+"')</script>";
            echo "<script>window.location.href='table_po.php'</script>";
        }
    } else if ($_POST['parameter'] == 'deletePO') {
        try {
            $deletePO       = new DB_con();
            $POID           = $_POST['POID'];
            $sql            = $deletePO->deletePO($POID);

            header('Content-Type: application/json');
            echo json_encode('Y');
            exit;
        } catch (\Exception $e) {
            echo "<script>alert('Failed: "+$e->getMessage()+"')</script>";
            echo "<script>window.location.href='table_po.php'</script>";
            exit(0);
        }
    } elseif ($_POST['parameter'] == 'PreOrderReCeipt') {
        print_r($_POST);
        // exit(0);
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
                $sqlItem            = $insertdata->insertItemEntrance($POID, $RefDO, $item[0], $item[1], $item[2], $item[3]);
            }
            
            exit(0);
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
        // var_dump($weightSize["weightSize_id"]);
        // print_r($weightSize["weightSize_id"]);
        // echo $weightSize["weightSize_id"];
        // exit(0);
        $data = array(
            'resultSize' => $weightSize['weightSize_id'],
            'resultOrderBy' => $weightSize['order_by_no'],
        );
        echo json_encode($data);
    } else { 
        echo "<script>alert('ไม่พบหน้าที่ต้องการ')</script>";
        echo "<script>window.location.href='table_po.php'</script>";
    }
?>