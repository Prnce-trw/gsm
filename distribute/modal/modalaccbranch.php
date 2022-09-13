<?php
    include_once('../../conn.php');
    include('../../function.php');
    $fetchdata      = new DB_con();
    $RowAccBranch   = $fetchdata->AccBranchInfo($_GET['itemid']);
    $row            = mysqli_fetch_array($RowAccBranch);
?>
<div class="modal fade" id="accbranchinfo" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="accbranchinfoLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="accbranchinfoLabel">ข้อมูลการกระจายอุปกรณ์</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body"> 
                <h5>ข้อมูลสาขา</h5>
                <br>
                <div class="form-group">
                    <div class="row">
                        <div class="col-4">
                            <label for="branch_name">ชื่อสาขา</label>
                            <input type="text" class="form-control" id="branch_name" aria-describedby="branch_name" placeholder="ชื่อสาขา..." value="<?=$row['branch_name']?>" readonly>
                        </div>
                        <div class="col-4">
                            <label for="branch_id">รหัสสาขา</label>
                            <input type="text" class="form-control" id="branch_id" aria-describedby="branch_id" placeholder="รหัสสาขา..." value="<?=$row['branch_id']?>" readonly>
                        </div>
                    </div>
                </div>
                <br>
                <h5>ข้อมูลเอกสาร</h5>
                <hr>
                <div class="form-group">
                    <div class="row">
                        <div class="col-4">
                            <label for="dis_docNo">เลขที่เอกสาร</label>
                            <input type="text" class="form-control" id="dis_docNo" aria-describedby="dis_docNo" placeholder="เลขที่เอกสาร..." value="<?=$row['dis_docNo']?>" readonly>
                        </div>
                        <div class="col-4">
                            <label for="dis_refNo">เอกสารอ้างอิง</label>
                            <input type="text" class="form-control" id="dis_refNo" aria-describedby="dis_refNo" placeholder="เอกสารอ้างอิง..." value="<?=$row['dis_refNo']?>" readonly>
                        </div>
                        <div class="col-4">
                            <label for="dis_refNo">เอกสารอ้างอิง</label>
                            <input type="text" class="form-control" id="dis_refNo" aria-describedby="dis_refNo" placeholder="เอกสารอ้างอิง..." value="<?=$row['dis_refNo']?>" readonly>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>