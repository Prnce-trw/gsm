<?php
    $pdo = include_once('conn.php');
    // include('function.php');
    if ($_POST['parameter'] == 'PreOrderCylinder') {
        try {
            $insertdata     = new DB_con();
            $DocumentNo     = $insertdata->RunningNo("PO");
            $filling        = $_POST['gas_filling'];
            $comment        = $_POST['comment'];
            $sql = $insertdata->insertPO($DocumentNo, $filling, $comment);
            
            foreach ($_POST['pickitem'] as $key => $value) {
                $item               = explode('/', $value);
                $itemType           = 'N';
                $sqlItem            = $insertdata->insertItem($DocumentNo, $item[0], $item[1], $item[2], $item[3]);
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
        // print_r($_POST);
        // exit(0);
        try {
            $insertdata     = new DB_con();
            $RefDO          = $_POST['RefDO'];
            $POID           = $_POST['POID'];
            $sql            = $insertdata->insertPOReceipt($POID);

            foreach ($_POST['pickitem'] as $key => $value) {
                $item               = explode('/', $value);
                $itemType           = 'N';
                $sqlItem            = $insertdata->insertItemEntrance($DocumentNo, $item[0], $item[1], $item[2], $item[3]);
            }
            
            echo "<script>alert('Success')</script>";
            echo "<script>window.location.href='table_po.php'</script>";
        } catch (\Exception $e) {
            echo "<script>alert('Failed: "+$e->getMessage()+"')</script>";
            echo "<script>window.location.href='table_po.php'</script>";
        }
        exit(0);
    } else { 
        echo "<script>alert('ไม่พบหน้าที่ต้องการ')</script>";
        echo "<script>window.location.href='table_po.php'</script>";
    }
?>