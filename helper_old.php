<?php
include('conn.php');

if ($_POST['parameter'] == "addPurchase") {
    $amount = $_POST['amount'];
    $brand_id = $_POST['brand_id'];
    $type = $_POST['type'];
    
    if ($type == "ถังหมุนเวียน") {
        $cylinder_type = "selected";
    } else {
        $cylinder_type = "";
    }
    if ($type == "ฝากเติม") {
        $cylinder_type = "selected";
    } else {
        $cylinder_type = "";
    }
    // echo $cylinder_type;
    // exit(0);
    $table = <<<HTML
    <table>
        <tr id="tr_preselect_{$brand_id}">
            <td style="width: 45.5px; height: 40.33px;"><img src="img/ptt-logo.png" alt="" width="25px"></td>
            <td style="width: 45.5px;">ปตท</td>
            <td style="width: 106.71px;">
                <select class="cylinder-type" name="pre_cylinder_type[]" id="" data-brand="">
                    <option value="ถังหมุนเวียน" {$cylinder_type}>ถังหมุนเวียน</option>
                    <option value="ฝากเติม" {$cylinder_type}>ฝากเติม</option>
                </select>
            </td>
            <td style="width: 73px;">
                <select class="cylinder-size" name="pre_cylinder_size[]" id="" data-brand="">
                    <option value="4">4</option>
                    <option value="8">8</option>
                    <option value="15">15</option>
                    <option value="48">48</option>
                </select>
            </td>
            <td style="width: 110px;">
                <div class="number">
                    <span class="minus" onclick="minus()">-</span>
                    <input name="pre_amount[]" class="input_amount input_append" type="text" value="{$amount}" id="input_preamount_{$brand_id}">
                    <span class="plus" onclick="plus(SumAmount(1))">+</span>
                </div>
            </td>
            <td>
                <span class="material-icons btn-danger add_cylinder" onclick="btn_del_preselect({$brand_id})">delete_forever</span>
            </td>
        </tr>
    </table>
    <input type="hidden" name="" id="preselect_id_{$brand_id}" value="{$brand_id}">
    HTML;
    header('Content-Type: text/html; charset=utf-8');
    echo json_encode($table);
} else if ($_POST['parameter'] == "addPreOrder") {
    $DocumentNo = GenerateNo("PO", $conn);
    $comment = $_POST['comment'];
    $gas_filling = $_POST['gas_filling'];
    $header_sql = "INSERT INTO tb_head_preorder (head_pre_docnumber, head_pre_fillstation, head_pre_comment, head_pre_status, created_at) VALUES ('PO$DocumentNo', '$gas_filling', '$comment', 'Pending', CURRENT_TIMESTAMP)";
    mysqli_query($conn, $header_sql);
    for ($i = 0; $i < count($_POST['pre_cylinder_type']); $i++) {
        $type = $_POST['pre_cylinder_type'][$i];
        $size = $_POST['pre_cylinder_size'][$i];
        $amount = $_POST['pre_amount'][$i];
        $sql = "INSERT INTO tb_preselect (preselect_cylinder_type, preselect_cylinder_size, preselect_amount, preselect_Hpreorder) VALUES ('$type', '$size', '$amount', 'PO$DocumentNo')";
        mysqli_query($conn, $sql);
    }
    header('location: preorder.php');
} else if ($_POST['parameter'] == "delPreOrder") {
    $docNo = $_POST['id'];
    $sql = "UPDATE tb_head_preorder SET head_pre_status='Deleted', updated_at=CURRENT_TIMESTAMP WHERE head_pre_docnumber = '$docNo'; ";
    mysqli_query($conn, $sql);
    $resultArray = array('success');
    echo json_encode($resultArray);
} else if ($_POST['parameter'] == "EditPO") {
    $docNo = $_POST['docNo'];
    $FillStation = $_POST['gas_filling'];
    $item_id = $_POST['item_id'];
    $doc_sql = "UPDATE tb_head_preorder SET head_pre_fillstation='$FillStation', updated_at=CURRENT_TIMESTAMP WHERE head_pre_docnumber = '$docNo'; ";
    mysqli_query($conn, $doc_sql);
    foreach ($item_id as $key => $value) {
        // echo $key ;
        $cylinder_type = $_POST['cylinder_type'][$key];
        $cylinder_size = $_POST['cylinder_size'][$key];
        $pre_amount = $_POST['pre_amount'][$key];
        $item_sql = "UPDATE tb_preselect SET preselect_cylinder_type='$cylinder_type', preselect_cylinder_size='$cylinder_size', preselect_amount='$pre_amount', updated_at=CURRENT_TIMESTAMP WHERE preselect_id = '$value'; ";
        mysqli_query($conn, $item_sql);
    } ;
    header('location: preorder.php');
}

function GenerateNo ($prefix, $conn) {
    $strSQL = "SELECT * FROM tb_prefix_header WHERE prefixH_name='$prefix'";
    $objQuery = mysqli_query($conn, $strSQL);
    $objResult = mysqli_fetch_array($objQuery); 
    $Seq = substr("0000".$objResult["prefixH_seq"],-5,5);
    $strSQL = "UPDATE tb_prefix_header SET prefixH_seq= prefixH_seq+1 ";
    $objQuery = mysqli_query($conn, $strSQL);
    return $Seq;
}
?>