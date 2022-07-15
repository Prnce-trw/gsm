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

        public function fetchdataBrand()
        {
            $result = mysqli_query($this->dbcon, "SELECT * FROM ms_product WHERE ms_product_active = 'Y' ORDER BY ms_product_orderby_no ASC");
            return $result;
        }

        public function fetchdataSize()
        {
            $result = mysqli_query($this->dbcon, "SELECT * FROM items_gas_weightsize WHERE active_status = 'Y' ORDER BY order_by_no ASC");
            return $result;
        }

        public function Curmovement($cerrent_year, $DocumentNo, $Total, $brand, $size, $qty)
        {
            $itemCode = 'I00-01G-'.$brand.$size;

            // หา n_id
            $sqlN_id            = mysqli_query($this->dbcon, "SELECT MAX(n_id) AS n_id FROM items_inventory_movement WHERE YEAR(transaction_date) = '$cerrent_year'");
            $fetchDataN_id      = mysqli_fetch_array($sqlN_id);

            // หา Balance ปัจจุบัน
            $sqlSum             = mysqli_query($this->dbcon, "SELECT *, SUM(qty_balance) AS Curr_QTY_Balance FROM items_inventory_branch WHERE itemsCode = '$itemCode' AND branchID = 'BRC1-1' AND store_area = '00'");
            $fetchQTY           = mysqli_fetch_array($sqlSum);

            $Curr_QTY_Balance   = $fetchQTY['Curr_QTY_Balance']; // Balance ปัจจุบัน
            $n_id               = $fetchDataN_id['n_id']+1; // n_id+1
            $curr_year          = date("y");
            $tranID             = $curr_year.''.$n_id; // transaction(เอาเฉพาะปี) + n_id
            // $cur_bal            = $Curr_QTY_Balance - $Total; // Balance ปัจจุบัน

            $Curr_item_bal      = $Curr_QTY_Balance - $qty;
            // echo 'I00-01G-'.$brand.$size;
            // var_dump($n_id);
            // exit(0);
            
            // บันทึก inventory movement
            $resultInventMoving = mysqli_query($this->dbcon, "INSERT INTO items_inventory_movement(n_id, transaction_id, itemsCode, branchID, transaction_date, transaction_desc, transaction_type, transaction_qty, store_area_out, transaction_last_balance, transaction_new_balance) VALUES ('$n_id', '$tranID', '$itemCode', 'BRC1-1', CURRENT_TIMESTAMP, 'To-Refill', 'OUT', $qty, '00', '$Curr_QTY_Balance', $Curr_item_bal);");

            $resultUpdateStockBranch = mysqli_query($this->dbcon, "UPDATE items_inventory_branch SET qty_balance = '$Curr_item_bal' WHERE itemsCode = '$itemCode' AND branchID = 'BRC1-1' AND store_area = '00';");

            $data = array(
                'resultInventMoving' => $resultInventMoving, 
                'resultUpdateStockBranch' => $resultUpdateStockBranch, 
            );
            return $data;
        }

        public function RunningNo($prefix)
        {
            $result = mysqli_query($this->dbcon, "SELECT * FROM tb_prefix_header WHERE prefixH_name='$prefix'");
            $objResult = mysqli_fetch_array($result);
            $Seq = substr("0000".$objResult["prefixH_seq"],-5,5);
            $strSQL = mysqli_query($this->dbcon, "UPDATE tb_prefix_header SET prefixH_seq= prefixH_seq+1 WHERE prefixH_name='$prefix'");
            return $prefix.$Seq;
        }

        public function aJaxCheckWeight($size)
        {
            $result = mysqli_query($this->dbcon, "SELECT * FROM items_gas_weightsize WHERE order_by_no='$size'");
            return $result;
        }

        public function infoPO($POID)
        {
            $result = mysqli_query($this->dbcon, "SELECT * FROM tb_head_preorder LEFT JOIN tb_head_po_receipt ON tb_head_preorder.head_po_docnumber = tb_head_po_receipt.head_pr_docnumber_po WHERE head_po_docnumber = '$POID'");
            return $result;
        }

        public function updateHeadPO($POID, $Fillstation, $Round)
        {
            $result = mysqli_query($this->dbcon, "UPDATE tb_head_preorder SET head_po_fillstation = '$Fillstation', head_po_round = '$Round' WHERE head_po_docnumber = '$POID'");
            return $result;
        }

        public function insertPOReceipt($POID, $RefDO, $timeIn, $timeOut, $Fillstation)
        {
            $sql = mysqli_query($this->dbcon, "SELECT * FROM tb_head_po_receipt WHERE head_pr_docnumber_po = '$POID'");
            $valid = mysqli_fetch_array($sql);
            if (!isset($valid['head_pr_docnumber_po'])) {
                $insertdata     = new DB_con();
                $DocumentNo     = $insertdata->RunningNo("PR");
                $result = mysqli_query($this->dbcon, "INSERT INTO tb_head_po_receipt(head_pr_docnumber, head_pr_docnumber_po, head_pr_doc_ref,head_pr_fillstation, head_pr_timeIn, head_pr_timeOut) VALUES('$DocumentNo', '$POID', '$RefDO', '$Fillstation', '$timeIn', '$timeOut')");
            } else {
                $result = mysqli_query($this->dbcon, "UPDATE tb_head_po_receipt SET WHERE head_pr_docnumber_po = '$POID'");
            }
            return $result;
        }

        public function CurmovementIn($cerrent_year, $RefDO, $Total, $brand, $size, $qty)
        {
            
            $itemCode = 'I00-01G-'.$brand.$size;
            
            // หา n_id
            $sqlN_id            = mysqli_query($this->dbcon, "SELECT MAX(n_id) AS n_id FROM items_inventory_movement WHERE YEAR(transaction_date) = '$cerrent_year'");
            $fetchDataN_id      = mysqli_fetch_array($sqlN_id);

            // หา Balance ปัจจุบัน
            $sqlSum             = mysqli_query($this->dbcon, "SELECT *, SUM(qty_balance) AS Curr_QTY_Balance FROM items_inventory_branch WHERE itemsCode = '$itemCode' AND branchID = 'BRC1-1' AND store_area = '00'");
            $fetchQTY           = mysqli_fetch_array($sqlSum);

            $Curr_QTY_Balance   = $fetchQTY['Curr_QTY_Balance']; // Balance ปัจจุบัน
            $n_id               = $fetchDataN_id['n_id']+1; // n_id+1
            $curr_year          = date("y");
            $tranID             = $curr_year.''.$n_id; // transaction(เอาเฉพาะปี) + n_id

            $Curr_item_bal      = $Curr_QTY_Balance + $qty;
            
            

            // บันทึก inventory movement
            $resultInventMoving = mysqli_query($this->dbcon, "INSERT INTO items_inventory_movement(n_id, transaction_id, itemsCode, branchID, transaction_date, transaction_desc, transaction_docRef, transaction_type, transaction_qty, store_area_in) 
            VALUES ('$n_id', '$tranID', '$itemCode', 'BRC1-1', CURRENT_TIMESTAMP, 'From-Refill', $RefDO, 'IN', '$qty', '00');");
            var_dump($resultInventMoving);
            print_r($resultInventMoving);
            exit(0);
            $resultUpdateStockBranch = mysqli_query($this->dbcon, "UPDATE items_inventory_branch SET qty_balance = '$Curr_item_bal' WHERE itemsCode = '$itemCode' AND branchID = 'BRC1-1' AND store_area = '00';");
            $data = array(
                'resultInventMoving' => $resultInventMoving, 
                'resultUpdateStockBranch' => $resultUpdateStockBranch, 
            );
            return $data;
        }

        public function insertItemEntrance($POID, $RefDO, $brand, $size, $amount, $cytype)
        {
            $result = mysqli_query($this->dbcon, "INSERT INTO tb_po_itementrance(po_itemEnt_RefDO, po_itemEnt_POID, po_itemEnt_CyBrand, po_itemEnt_CySize, po_itemEnt_CyAmount, po_itemEnt_type) VALUES('$RefDO', '$POID', '$brand', '$size', '$amount', '$cytype')");
            return $result;
        }



















        public function insertPO($DocumentNo, $gas_filling, $comment)
        {
            $result = mysqli_query($this->dbcon, "INSERT INTO tb_head_preorder(head_po_docnumber, head_po_fillstation, head_po_status, head_po_comment, active_status) VALUES('$DocumentNo', '$gas_filling', 'Pending', '$comment', 'Y')");
            return $result;
        }

        public function fetchdataPO()
        {
            $result = mysqli_query($this->dbcon, "SELECT * FROM tb_head_preorder LEFT JOIN tb_head_po_receipt ON tb_head_preorder.head_po_docnumber = tb_head_po_receipt.head_pr_docnumber_po WHERE active_status='Y'");
            return $result;
        }

        

        public function deletePO($POID)
        {
            $result = mysqli_query($this->dbcon, "UPDATE tb_head_preorder SET active_status = 'N', deleted_at=CURRENT_TIMESTAMP WHERE head_po_id = '$POID'");
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

        

        

        public function CylinderCountSize($POID, $size, $type)
        {
            $result = mysqli_query($this->dbcon, "SELECT SUM(po_itemOut_CyAmount) FROM tb_po_itemout WHERE po_itemOut_docNo = '$POID' AND po_itemOut_CySize = '$size' AND po_itemOut_type = '$type'");
            return $result;
        }

        public function findAdv($POID)
        {
            $result = mysqli_query($this->dbcon, "SELECT COUNT(po_itemOut_CyAmount) FROM tb_po_itemout WHERE po_itemOut_docNo = '$POID' AND po_itemOut_type = 'Adv'");
            return $result;
        }

        public function editPO($POID)
        {
            $result = mysqli_query($this->dbcon, "SELECT * FROM tb_head_preorder LEFT JOIN tb_head_po_receipt ON tb_head_preorder.head_po_docnumber = tb_head_po_receipt.head_pr_docnumber_po WHERE head_po_docnumber = '$POID'");
            return $result;
        }

        public function editCylinderPO($POID)
        {
            $result = mysqli_query($this->dbcon, "SELECT * FROM tb_po_itemout WHERE po_itemOut_docNo = '$POID' ORDER BY po_itemOut_CyBrand");
            return $result;
        }

        public function fetchitemEntrance($refPO, $brand, $size, $type)
        {
            $result = mysqli_query($this->dbcon, "SELECT po_itemEnt_CyAmount FROM tb_po_itementrance WHERE po_itemEnt_RefDO = '$refPO' AND po_itemEnt_CyBrand = '$brand' AND po_itemEnt_CySize = '$size' AND po_itemEnt_type = '$type'");
            return $result;
        }

        

        public function SumAmountItem($POID)
        {
            $result = mysqli_query($this->dbcon, "SELECT sum(po_itemOut_CyAmount) FROM tb_po_itemout WHERE po_itemOut_docNo = '$POID'");
            return $result;
        }
    }
?>