<?php
    include_once('conn.php');
    // include('function.php');
    if ($_POST['parameter'] == 'PreOrderCylinder') {
        foreach ($_POST['pickitem'] as $key => $value) {
            $item = explode('/', $value);
            print_r($item[0]) ;
        }
        try {
            $insertdata = new DB_con();
            $DocumentNo     = $insertdata->RunningNo("PO", );
            $filling        = $_POST['gas_filling'];
            $comment        = $_POST['comment'];
            $sql = $insertdata->insertPO($DocumentNo, $filling, $comment);
    
            echo "<script>alert('Success')</script>";
            echo "<script>window.location.href='table_po.php'</script>";
        } catch (\Exception $e) {
            echo "<script>alert('Failed: "+$e->getMessage()+"')</script>";
            echo "<script>window.location.href='table_po.php'</script>";
        }
    } else if($_POST['parameter'] == 'deletePO') {
        try {
            $deletePO = new DB_con();
            $POID       = $_POST['POID'];
            $sql = $deletePO->deletePO($POID);

            header('Content-Type: application/json');
            echo json_encode('Y');
            exit;
        } catch (\Exception $e) {
            echo "<script>alert('Failed: "+$e->getMessage()+"')</script>";
            echo "<script>window.location.href='table_po.php'</script>";
            exit;
        }
    } else { 
        echo "<script>alert('ไม่พบหน้าที่ต้องการ')</script>";
        echo "<script>window.location.href='table_po.php'</script>";
    }
?>