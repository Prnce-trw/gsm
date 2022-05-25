<?php
    include_once
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