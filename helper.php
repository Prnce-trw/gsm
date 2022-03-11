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
            <td style="width: 36px; height: 48.33px;"><img src="img/ptt-logo.png" alt="" width="25px"></td>
            <td style="width: 44px;">ปตท</td>
            <td style="width: 100px;">
                <select name="pre_cylinder_type[]" id="" data-brand="">
                    <option value="ถังหมุนเวียน" {$cylinder_type}>ถังหมุนเวียน</option>
                    <option value="ฝากเติม" {$cylinder_type}>ฝากเติม</option>
                </select>
            </td>
            <td style="width: 73px;">
                <select name="pre_cylinder_size[]" id="" data-brand="">
                    <option value="4">4</option>
                    <option value="8">8</option>
                    <option value="15">15</option>
                    <option value="48">48</option>
                </select>
            </td>
            <td style="width: 109px;">
                <div class="number">
                    <span class="minus" onclick="minus()">-</span>
                    <input name="pre_amount[]" class="input_amount input_append" type="text" value="{$amount}" id="input_preamount_{$brand_id}">
                    <span class="plus" onclick="plus(SumAmount(1))">+</span>
                </div>
            </td>
            <td>
                <button type="button" onclick="btn_del_preselect({$brand_id})">ลบ</button>
            </td>
        </tr>
    </table>
    <input type="hidden" name="" id="preselect_id_{$brand_id}" value="{$brand_id}">
    HTML;
    header('Content-Type: text/html; charset=utf-8');
    echo json_encode($table);
} else if ($_POST['parameter'] == "addPreOrder") {
    $DocumentNo = GenerateNo("PO", $conn);
    $header_sql = "INSERT INTO tb_head_preorder (head_pre_docnumber) VALUES ('PO$DocumentNo')";
    mysqli_query($conn, $header_sql);
    for ($i = 0; $i < count($_POST['pre_cylinder_type']); $i++) {
        $type = $_POST['pre_cylinder_type'][$i];
        $size = $_POST['pre_cylinder_size'][$i];
        $amount = $_POST['pre_amount'][$i];
        $sql = "INSERT INTO tb_preselect (preselect_cylinder_type, preselect_cylinder_size, preselect_amount, preselect_Hpreorder) VALUES ('$type', '$size', '$amount', 'PO$DocumentNo')";
        mysqli_query($conn, $sql);
    }
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