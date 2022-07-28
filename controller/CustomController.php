<?php 
    include_once('../conn.php');
    include('../function.php');

    if ($_POST['parameter'] == 'aJaxModalCheckSize') {
        $size = $_POST['size'];
        $selectdata         = new DB_con();
        $resultSize = $selectdata->aJaxCheckWeight($size);
        $weightSize = mysqli_fetch_array($resultSize);
        $data = array(
            'resultSize' => $weightSize['weightSize_id'],
            'resultOrderBy' => $weightSize['order_by_no'],
        );
        echo json_encode($data);
    }