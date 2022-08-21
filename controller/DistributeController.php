<?php 
    include_once('../conn.php');
    include('../function.php');
    if ($_POST['parameter'] == 'SearchAssets') {
        $assetID                = $_POST['assetID'];
        $fetchdata              = new DB_con();
        $dataAssets             = $fetchdata->searchAssets($assetID);
        $AssetsCode             = mysqli_fetch_array($dataAssets);
        echo json_encode($AssetsCode);
    }