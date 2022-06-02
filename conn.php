<?php
    define('DB_HOST', 'localhost');
    define('DB_Username', 'root');
    define('DB_Password', '');
    define('DB_Name', 'GSM');

    class DB_con {
        function __construct() {
            $conn = mysqli_connect(DB_HOST, DB_Username, DB_Password, DB_Name);
            $this->dbcon = $conn;

            if (mysqli_connect_errno()) {
                echo "Fail to connect". mysqli_connect_error();
            }
        }

        public function insertPO($DocumentNo, $gas_filling, $comment)
        {
            $result = mysqli_query($this->dbcon, "INSERT INTO tb_head_preorder(head_po_docnumber, head_po_fillstation, head_po_status, head_po_comment, active_status) VALUES('$DocumentNo', '$gas_filling', 'Pending', '$comment', 'Y')");
            return $result;
        }

        public function fetchdataPO()
        {
            $result = mysqli_query($this->dbcon, "SELECT * FROM tb_head_preorder WHERE active_status='Y'");
            return $result;
        }

        public function RunningNo($prefix)
        {
            $result = mysqli_query($this->dbcon, "SELECT * FROM tb_prefix_header WHERE prefixH_name='$prefix'");
            $objResult = mysqli_fetch_array($result);
            $Seq = substr("0000".$objResult["prefixH_seq"],-5,5);
            $strSQL = mysqli_query($this->dbcon, "UPDATE tb_prefix_header SET prefixH_seq= prefixH_seq+1");
            return $prefix.$Seq;
        }

        public function deletePO($POID)
        {
            $result = mysqli_query($this->dbcon, "UPDATE tb_head_preorder SET active_status = 'N', deleted_at=CURRENT_TIMESTAMP WHERE head_po_id = '$POID'");
            return $result;
        }

        public function infoPO($POID)
        {
            $result = mysqli_query($this->dbcon, "SELECT * FROM tb_head_preorder WHERE head_po_docnumber = '$POID'");
            return $result;
        }

        public function CylinderPO($POID)
        {
            $result = mysqli_query($this->dbcon, "SELECT * FROM tb_po_itemout WHERE po_itemOut_docNo = '$POID'");
            return $result;
        }

        public function CylinderPOSum($POID)
        {
            $result = mysqli_query($this->dbcon, "SELECT sum(po_itemOut_CyAmount) FROM tb_po_itemout WHERE po_itemOut_docNo = '$POID'");
            return $result;
        }

        public function CylinderWeight($POID)
        {
            $result = mysqli_query($this->dbcon, "SELECT sum(po_itemOut_CySize) FROM tb_po_itemout WHERE po_itemOut_docNo = '$POID'");
            return $result;
        }

        public function insertItem($DocumentNo, $brand, $size, $amount, $cytype)
        {
            $result = mysqli_query($this->dbcon, "INSERT INTO tb_po_itemout(po_itemOut_docNo, po_itemOut_CyBrand, po_itemOut_CySize, po_itemOut_CyAmount, po_itemOut_type) VALUES('$DocumentNo', '$brand', '$size', '$amount', '$cytype')");
            return $result;
        }

        public function FunctionName($POID)
        {
            $result = mysqli_query($this->dbcon, "SELECT * FROM tb_po_itemout WHERE po_itemOut_docNo = '$POID'");
            return $result;
        }
    }
?>