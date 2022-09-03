<?php
    include_once('../conn.php');
    
    header('Content-Type: text/event-stream');
    header('Cache-Control: no-cache');

    $fetchdata      = new DB_con();
    $branchid       = 'BRC01-2';
    $dataItems      = $fetchdata->CountitemBranchPending($branchid);
    // $html = 'test';
    // foreach ($dataItems as $key => $value) {
    //     $html .= "<tr><td>"+$value['accbranch_id']+"</td></tr>";
    // }
    // echo "<tr><td></td></tr>";
    echo "data:{$dataItems}\n\n";
    flush();
?>