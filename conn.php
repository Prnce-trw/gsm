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

        public function fetchdataBS()
        {
            $result = mysqli_query($this->dbcon, "SELECT * FROM tb_brandrelsize");
            return $result;
        }

        public function fetchdataSizeRelate($brand)
        {
            $result = mysqli_query($this->dbcon, "SELECT * FROM tb_brandrelsize LEFT JOIN items_gas_weightsize ON tb_brandrelsize.brandRelSize_weight_autoID = items_gas_weightsize.weight_NoID WHERE tb_brandrelsize.brandRelSize_ms_product_id = '$brand' ORDER BY items_gas_weightsize.order_by_no ASC");
            return $result;
        }

        public function Curmovement($cerrent_year, $brand, $size, $qty)
        {
            $itemCode = 'I00-02C-'.$brand.$size;

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
            // echo $itemCode;
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
                $result = mysqli_query($this->dbcon, "INSERT INTO tb_head_po_receipt(head_pr_docnumber, head_pr_docnumber_po, head_pr_doc_ref, head_pr_fillstation, head_pr_timeIn, head_pr_timeOut)
                 VALUES('$DocumentNo', '$POID', '$RefDO', '$Fillstation', '$timeIn', '$timeOut')");
            } else {
                $result = mysqli_query($this->dbcon, "UPDATE tb_head_po_receipt SET head_pr_doc_ref = '$RefDO', head_pr_fillstation = '$Fillstation', head_pr_timeIn = '$timeIn', head_pr_timeOut = '$timeOut' WHERE head_pr_docnumber_po = '$POID'");
            }
            return $result;
        }

        public function CurmovementIn($cerrent_year, $RefDO, $Total, $brand, $size, $qty, $price)
        {
            $itemCode = 'I00-01G-'.$brand.$size;
            
            // หา n_id
            $sqlN_id            = mysqli_query($this->dbcon, "SELECT MAX(n_id) AS n_id FROM items_inventory_movement WHERE YEAR(transaction_date) = '$cerrent_year'");
            $fetchDataN_id      = mysqli_fetch_array($sqlN_id);

            // หา Balance ปัจจุบัน
            $sqlSum             = mysqli_query($this->dbcon, "SELECT *, SUM(qty_balance) AS Curr_QTY_Balance FROM items_inventory_branch WHERE itemsCode = '$itemCode' AND branchID = 'BRC1-1' AND store_area = '00'");
            $fetchQTY           = mysqli_fetch_array($sqlSum);

            // หาจำนวนสินค้าทั้งหมดในสาขา
            $sqlSumItem         = mysqli_query($this->dbcon, "SELECT *, SUM(qty_balance) AS ResultItem FROM items_inventory_branch WHERE itemsCode = '$itemCode' AND branchID = 'BRC1-1' GROUP BY itemsCode");
            $ItemInven          = mysqli_fetch_array($sqlSumItem);

            $Curr_QTY_Balance   = $fetchQTY['Curr_QTY_Balance']; // Balance ปัจจุบัน
            $n_id               = $fetchDataN_id['n_id']+1; // n_id+1
            $curr_year          = date("y");
            $tranID             = $curr_year.''.$n_id; // transaction(เอาเฉพาะปี) + n_id

            $Curr_item_bal      = $Curr_QTY_Balance + $qty;
            $movAvgCost         = $fetchQTY['movAvgCost'] + $price;

            $resultItemCurr     = $ItemInven['ResultItem']; // จำนวนในคลังทั้งหมด
            $ResultmovAvgCostCur    = $ItemInven['movAvgCost'] * $resultItemCurr; // มูลค่าในคลังทั้งหมด

            $newItemCurr        = $resultItemCurr + $qty; // จำนวนในคลังทั้งหมด + จำนวนที่เข้ามาใหม่

            // (มูลค่าในคลังทั้งหมด + มูลค่าใหม่ที่รับมา) / จำนวนสิ้นค้าทั้งหมด
            $ResultAvgCost      = ($ResultmovAvgCostCur + $price) / $newItemCurr;
            $NewAvgCost         = number_format($ResultAvgCost, 2, '.', '');

            // var_dump($ItemInven['movAvgCost']);
            // echo $NewAvgCost.
            // '<br>'.$ItemInven['movAvgCost'].' * '.$resultItemCurr.' = '.$ResultmovAvgCostCur.
            // '<br>'.$price.' + '.$ResultmovAvgCostCur.' = '.floatval($price + $ResultmovAvgCostCur).
            // '<br>'.$newItemCurr
            // ;
            // exit(0);

            // บันทึก inventory movement
            $resultInventMoving = mysqli_query($this->dbcon, "INSERT INTO items_inventory_movement(n_id, transaction_id, itemsCode, branchID, transaction_date, transaction_desc, transaction_docRef, transaction_type, transaction_qty, store_area_in, transaction_last_balance, transaction_new_balance, movAvgCost, InvoiceCost) 
            VALUES ('$n_id', '$tranID', '$itemCode', 'BRC1-1', CURRENT_TIMESTAMP, 'From-Refill', '$RefDO', 'IN', '$qty', '00', '$Curr_QTY_Balance', '$Curr_item_bal', '$NewAvgCost', '$price');");
            
            $resultUpdateStockBranchBS = mysqli_query($this->dbcon, "UPDATE items_inventory_branch SET qty_balance = '$Curr_item_bal', movAvgCost = '$NewAvgCost' WHERE itemsCode = '$itemCode' AND branchID = 'BRC1-1' AND store_area = '00';");
            $resultUpdateStockBranchFS = mysqli_query($this->dbcon, "UPDATE items_inventory_branch SET movAvgCost = '$NewAvgCost' WHERE itemsCode = '$itemCode' AND branchID = 'BRC1-1' AND store_area = '01';");
            $data = array(
                'resultInventMoving' => $resultInventMoving, 
                'resultUpdateStockBranchBS' => $resultUpdateStockBranchBS, 
                'resultUpdateStockBranchFS' => $resultUpdateStockBranchFS, 
            );
            return $data;
        }

        public function insertItemEntrance($POID, $RefDO, $brand, $size, $amount, $unitprice, $amtprice)
        {
            $result = mysqli_query($this->dbcon, "INSERT INTO tb_po_itementrance(po_itemEnt_RefDO, po_itemEnt_POID, po_itemEnt_CyBrand, po_itemEnt_CySize, po_itemEnt_CyAmount, po_itemEnt_unitPrice, po_itemEnt_AmtPrice) VALUES('$RefDO', '$POID', '$brand', '$size', '$amount', '$unitprice', '$amtprice')");
            return $result;
        }

        public function UpdateStatusDraftPO($POID, $FPID, $POStatus, $car, $driver)
        {
            $currdate = date('Y-m-d');
            $countPO = mysqli_query($this->dbcon, "SELECT COUNT(head_po_stock_status) AS POround FROM tb_head_preorder WHERE head_po_docdate = '$currdate' AND head_po_stock_status = 'Confirm'");
            $resultPOround = mysqli_fetch_array($countPO);
            $PORound = $resultPOround['POround']+1;

            $result = mysqli_query($this->dbcon, "UPDATE tb_head_preorder SET head_po_stock_status = '$POStatus', head_po_fillstation = '$FPID', head_po_round = '$PORound', head_po_carID = '$car', head_po_driverID = '$driver' WHERE head_po_docnumber = '$POID'");
            return $result;
        }

        public function insertPO($DocumentNo, $gas_filling, $comment, $POStatus, $CarID, $DriverID)
        {
            $result = mysqli_query($this->dbcon, "INSERT INTO tb_head_preorder(head_po_docnumber, head_po_fillstation, head_po_status, head_po_comment, head_po_stock_status, head_po_docdate, head_po_carID, head_po_driverID, active_status) 
                                                    VALUES('$DocumentNo', '$gas_filling', 'Pending', '$comment', '$POStatus', CURRENT_TIMESTAMP, '$CarID', '$DriverID', 'Y')");
            return $result;
        }

        public function fetchdataFP()
        {
            $result = mysqli_query($this->dbcon, "SELECT * FROM tb_fillingplant WHERE FP_StatusActive = 'Y'");
            return $result;
        }

        public function fetchdataItem($FPID)
        {
            $result = mysqli_query($this->dbcon, "SELECT * FROM items_gas_weightsize LEFT JOIN tb_curr_priceboard ON items_gas_weightsize.weight_NoID = tb_curr_priceboard.currPB_itemCode WHERE items_gas_weightsize.active_status = 'Y' ORDER BY items_gas_weightsize.order_by_no ASC;");
            return $result;
        }

        public function editCylinderPO($POID)
        {
            $result = mysqli_query($this->dbcon, "SELECT * FROM tb_po_itemout 
                                                    LEFT JOIN items_gas_weightsize ON tb_po_itemout.po_itemOut_CySizeWeightID = items_gas_weightsize.weight_NoID 
                                                    WHERE tb_po_itemout.po_itemOut_docNo = '$POID' 
                                                    ORDER BY po_itemOut_CyBrand ASC");
            return $result;
        }

        public function fetchPriceItem()
        {
            $result = mysqli_query($this->dbcon, "SELECT * FROM items_gas_weightsize WHERE active_status = 'Y' ORDER BY order_by_no ASC");
            return $result;
        }

        public function CheckPriceHistory($branchID, $sizeID, $FPID)
        {
            $result = mysqli_query($this->dbcon, "SELECT * FROM tb_priceboard LEFT JOIN items_gas_weightsize ON tb_priceboard.PB_itemCode = items_gas_weightsize.weight_NoID WHERE tb_priceboard.PB_FPID = '$FPID' AND tb_priceboard.PB_itemCode = '$sizeID' AND tb_priceboard.PB_BranchID = '$branchID' ORDER BY PB_ID DESC");
            return $result;
        }

        public function fetchPriceUnit($sizeID, $branchID, $FPID)
        {
            $result = mysqli_query($this->dbcon, "SELECT currPB_itemPrice AS currPB_itemPrice FROM tb_curr_priceboard WHERE currPB_FPID = '$FPID' AND currPB_BranchID = '$branchID' AND currPB_itemCode = '$sizeID'");
            return $result;
        }

        public function insertPriceBoard($branchID, $FPID, $sizeID, $unitPrice)
        {
            $resultWeight = mysqli_query($this->dbcon, "SELECT * FROM tb_curr_priceboard WHERE currPB_FPID = '$FPID' AND currPB_BranchID = '$branchID' AND currPB_itemCode = '$sizeID'");
            $weightRow = mysqli_fetch_array($resultWeight);
            // var_dump($branchID, $FPID, $sizeID, $unitPrice);
            if ($weightRow) {
                // echo 1;
                // exit();
                $result = mysqli_query($this->dbcon, "UPDATE tb_curr_priceboard SET currPB_itemPrice = '$unitPrice', updated_at = CURRENT_TIMESTAMP WHERE currPB_FPID = '$FPID' AND currPB_BranchID = '$branchID' AND currPB_itemCode = '$sizeID'");
                $resultmaxPBId = mysqli_query($this->dbcon, "SELECT PB_ID FROM tb_priceboard");
                $MaxPBId = mysqli_num_rows($resultmaxPBId);
                $PBPBId = $MaxPBId+1;
                $sql = mysqli_query($this->dbcon, "INSERT INTO tb_priceboard (PB_ID, PB_FPID, PB_itemCode, PB_BranchID, PB_itemPrice, created_at) VALUES ('PB$PBPBId', '$FPID', '$sizeID', '$branchID', '$unitPrice', CURRENT_TIMESTAMP)");
            } else {
                // echo 2;
                // exit();
                $resultmaxID = mysqli_query($this->dbcon, "SELECT currPB_ID FROM tb_curr_priceboard");
                $MaxID = mysqli_num_rows($resultmaxID);
                $currPBId = $MaxID+1;
                $result = mysqli_query($this->dbcon, "INSERT INTO tb_curr_priceboard (currPB_ID, currPB_FPID, currPB_itemCode, currPB_BranchID, currPB_itemPrice, created_at) VALUES ('CPB$currPBId', '$FPID', '$sizeID', '$branchID', '$unitPrice', CURRENT_TIMESTAMP)");
                
                $resultmaxPBId = mysqli_query($this->dbcon, "SELECT PB_ID FROM tb_priceboard");
                $MaxPBId = mysqli_num_rows($resultmaxID);
                $PBPBId = $MaxPBId+1;
                $sql = mysqli_query($this->dbcon, "INSERT INTO tb_priceboard (PB_ID, PB_FPID, PB_itemCode, PB_BranchID, PB_itemPrice, created_at) VALUES ('PB$PBPBId', '$FPID', '$sizeID', '$branchID', '$unitPrice', CURRENT_TIMESTAMP)");
            }
            return $result;
        }

        public function aJaxCheckStock($brand, $weight, $amount, $branch)
        {
            $itemsCode = "I00-02C-".$brand.$weight;
            $result = mysqli_query($this->dbcon, "SELECT qty_balance FROM items_inventory_branch WHERE itemsCode = '$itemsCode' AND branchID = '$branch' AND store_area = '00'");
            return $result;
        }

        public function itemUnitPrice($FPID, $sizeID, $branchID)
        {
            $result = mysqli_query($this->dbcon, "SELECT currPB_itemPrice FROM tb_curr_priceboard 
                                                    WHERE currPB_FPID = '$FPID' 
                                                    AND currPB_itemCode = '$sizeID' 
                                                    AND currPB_BranchID = '$branchID'");
            return $result;
        }
        
        public function fetchdataReport($POID)
        {
            $result = mysqli_query($this->dbcon, "SELECT itemout.po_itemOut_CySize, itemout.po_itemOut_CyAmount, itemout.po_itemOut_type, product.ms_product_name, product.ms_product_id, size.wightSize
                                                    FROM tb_head_preorder as po
                                                    LEFT JOIN tb_po_itemout as itemout
                                                    ON po.head_po_docnumber = itemout.po_itemOut_docNo
                                                    LEFT JOIN ms_product as product
                                                    ON itemout.po_itemOut_CyBrand = product.ms_product_id
                                                    LEFT JOIN tb_fillingplant as fp
                                                    ON po.head_po_fillstation = fp.FP_ID
                                                    LEFT JOIN items_gas_weightsize as size
                                                    ON po_itemOut_CySizeWeightID = size.weight_NoID
                                                    WHERE po.head_po_docnumber = '$POID' AND po.active_status = 'Y'
                                                    ORDER BY FIELD(itemout.po_itemOut_Type, 'N', 'Adv'), size.order_by_no ASC, product.ms_product_orderby_no ASC");
            return $result;

        }

        public function fetchdataReportHeader($POID)
        {
            $result = mysqli_query($this->dbcon, "SELECT po.head_po_docdate, po.head_po_round, pr.head_pr_timeIn, pr.head_pr_timeOut, fp.FP_Name, emp.emp_name, emp.emp_lastname
                                                    FROM tb_head_preorder as po
                                                    LEFT JOIN tb_head_po_receipt as pr
                                                    ON po.head_po_docnumber = pr.head_pr_docnumber_po
                                                    LEFT JOIN tb_fillingplant as fp
                                                    ON po.head_po_fillstation = fp.FP_ID
                                                    LEFT JOIN emp
                                                    ON po.head_po_driverID = emp.emp_id
                                                    WHERE po.head_po_docnumber = '$POID'
                                                    LIMIT 1");
            return $result;
        }

        public function fetchdataCars()
        {
            $result = mysqli_query($this->dbcon, "SELECT * FROM tb_cars");
            return $result;
        }

        public function fetchdataEmp()
        {
            $result = mysqli_query($this->dbcon, "SELECT * FROM emp WHERE emp_position_id = 'PST01-4'");
            return $result;
        }

        public function fetchdataItems()
        {
            $result = mysqli_query($this->dbcon, "SELECT * FROM items WHERE itemsType = '03A' AND itemsActive = 'Y' LIMIT 10");
            return $result;
        }

        public function fetchdataBSDraft($POID, $brand, $size)
        {
            $result = mysqli_query($this->dbcon, "SELECT * FROM tb_brandrelsize");
            return $result;
        }

        public function fetchdataBranch()
        {
            $result = mysqli_query($this->dbcon, "SELECT * FROM owner_branch WHERE branch_active = 'Y'");
            return $result;
        }

        public function UpdateItemOut($POID, $brand, $size, $amount, $cylindertype)
        {
            $result = mysqli_query($this->dbcon, "UPDATE tb_po_itemout SET po_itemOut_CyAmount = '$amount', updated_at=CURRENT_TIMESTAMP 
                                                    WHERE po_itemOut_docNo = '$POID' 
                                                    AND po_itemOut_CyBrand = '$brand'
                                                    AND po_itemOut_CySize = '$size'
                                                    AND po_itemOut_type = '$cylindertype'");
            return $result;
        }

        public function searchAssets($assetID)
        {
            $result = mysqli_query($this->dbcon, "SELECT n_id, itemsCode, itemsName FROM items WHERE itemsCode LIKE '%$assetID%' AND itemsType = '03A'");
            return $result;
        }

        public function RunningDisID()
        {
            $sqlN_id            = mysqli_query($this->dbcon, "SELECT MAX(dis_id) AS dis_id FROM tb_head_distribute");
            $RunningID          = mysqli_fetch_array($sqlN_id);
            $currRunningID      = $RunningID['dis_id']+1;
            return $currRunningID;
        }

        public function insertHeadDis($DisID, $supplier, $date_received, $refNo, $amount, $price, $vatSelect, $vat, $totalPrice)
        {
            $result = mysqli_query($this->dbcon, "INSERT INTO tb_head_distribute (dis_docNo, dis_supplierID, dis_date_received, dis_refNo, dis_amount, dis_price, dis_totalPrice, dis_vat) 
                                                    VALUES ('HD$DisID', '$supplier', '$date_received', '$refNo', '$amount', '$price', '$totalPrice', '$vat')");
            return $result;
        }

        public function insertDisOut($DisID, $itemID, $unitprice, $totalitemprice, $qty)
        {
            $result = mysqli_query($this->dbcon, "INSERT INTO tb_distribute_outstanding (disout_HdisID, disout_itemID, disout_unitPrice, disout_amount, disout_qty) 
                                                    VALUES ('HD$DisID', '$itemID', '$unitprice', '$totalitemprice', '$qty')");
            return $result;
        }

        public function fetchdataOutstand()
        {
            $result = mysqli_query($this->dbcon, "SELECT * FROM tb_distribute_outstanding 
                                                    LEFT JOIN tb_head_distribute ON tb_distribute_outstanding.disout_HdisID = tb_head_distribute.dis_docNo
                                                    LEFT JOIN items ON tb_distribute_outstanding.disout_itemID = items.n_id");
            return $result;
        }

        public function selectHeadDis($dis_id)
        {
            $result = mysqli_query($this->dbcon, "SELECT * FROM tb_head_distribute
                                                    LEFT JOIN tb_distribute_outstanding ON tb_head_distribute.dis_docNo = tb_distribute_outstanding.disout_HdisID
                                                    LEFT JOIN items ON tb_distribute_outstanding.disout_itemID = items.n_id
                                                    WHERE tb_head_distribute.dis_id = '$dis_id'");
            $rawdata = mysqli_fetch_array($result);
            return $rawdata;
        }

        public function fetchdataInventoryOut()
        {
            $result = mysqli_query($this->dbcon, "SELECT * FROM items_inventory_branch WHERE itemsCode LIKE 'I00-02C-%' AND branchID = 'BRC1-1' ORDER BY qty_balance ASC");
            return $result;
        }

        public function fetchdataInventoryIn()
        {
            $result = mysqli_query($this->dbcon, "SELECT * FROM items_inventory_branch WHERE itemsCode LIKE 'I00-01G-%' AND branchID = 'BRC1-1' ORDER BY qty_balance DESC");
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

        public function insertItem($DocumentNo, $brand, $size, $amount, $cytype, $sizeID)
        {
            $result = mysqli_query($this->dbcon, "INSERT INTO tb_po_itemout(po_itemOut_docNo, po_itemOut_CyBrand, po_itemOut_CySize, po_itemOut_CySizeWeightID, po_itemOut_CyAmount, po_itemOut_type) 
                                                    VALUES('$DocumentNo', '$brand', '$size', '$sizeID', '$amount', '$cytype')");
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