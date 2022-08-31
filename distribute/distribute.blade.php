<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GSM</title>
    
    <!-- Required Fremwork -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <!-- ico font -->
    <link rel="stylesheet" type="text/css" href="../files/assets/icon/icofont/css/icofont.css">
    <!-- Style.css -->
    <!-- <link rel="stylesheet" type="text/css" href="../files/assets/css/style.css"> -->
    <!-- Select 2 css -->
    <link rel="stylesheet" href="../files/bower_components/select2/css/select2.min.css" />
    <link rel="stylesheet" href="//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
    <style>
        section {
            padding: 15px;
            height: 1200px;
            background-color: #f8f9fe;
            width: 900px;
        }

        .text-middle {
            vertical-align: middle !important;
        }

        .modal-dialog {
            max-width: 1000px !important;
        }

        .table td, .table th {
            padding: 0.50rem;
        }
    </style>
</head>
<body>
    <?php
        include_once('../conn.php');
        $fetchdata      = new DB_con();
        $dataItems      = $fetchdata->fetchdataItems();
        $dataBranch     = $fetchdata->fetchdataBranch();
        $dataOutstand   = $fetchdata->fetchdataOutstand();
    ?>
    <section>
        <form action="../controller/DistributeController.php" method="POST" id="Distribute">
            <input type="hidden" value="Distribute" name="parameter">
            <div class="form-group row">
                <label class="col-sm-2 col-form-label">เลขที่เอกสาร</label>
                <div class="col-sm-4">
                    <div class="row">
                        <div class="col-9">
                            <input type="text" id="docNo" class="form-control" placeholder="เลขที่เอกสาร..." readonly>
                        </div>
                        <div class="col-3 text-right">
                            <button type="button" class="btn btn-primary" title="ค้นหา" data-toggle="modal" data-target="#searchinvoice"><i class="icofont icofont-document-search"></i></button>
                        </div>
                    </div>
                </div>
                <label class="col-sm-2 col-form-label">วันที่</label>
                <div class="col-sm-4">
                    <input type="text" class="form-control" placeholder="วันที่..." value="<?=date("d/m/Y")?>" readonly>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-2 col-form-label">ผู้จำหน่าย</label>
                <div class="col-sm-4">
                    <select class="form-control js-example-basic-single" name="supplier" id="supplier">
                        <option selected disabled>เลือกผู้จำหน่าย...</option>
                        <?php for ($i=0; $i <= 10; $i++) { ?>
                            <option value="<?=$i?>"><?=$i?></option>
                        <?php } ?>
                    </select>
                </div>
                <label class="col-sm-2 col-form-label">วันที่รับสินค้า</label>
                <div class="col-sm-4">
                    <input type="text" class="form-control datepicker" name="date_received" id="date_received" placeholder="วันที่..." value="" autocomplete="off">
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-2 col-form-label">เอกสารอ้างอิง</label>
                <div class="col-sm-4">
                    <input type="text" name="refNo" id="refNo" class="form-control" placeholder="เอกสารอ้างอิง...">
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-2 col-form-label">จำนวนทั้งหมด</label>
                <div class="col-sm-4">
                    <input type="number" name="amount" id="amount" class="form-control" placeholder="จำนวนทั้งหมด..." min="0">
                </div>
                <label class="col-sm-2 col-form-label">จำนวนเงินรวม</label>
                <div class="col-sm-4">
                    <input type="number" name="price" id="price" class="form-control" placeholder="ราคา..." min="0">
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-2 col-form-label" for="vat">Vat 7%</label>
                <div class="col-sm-4 form-check form-check-inline row">
                    <div class="col-sm-2">
                        <input class="form-check-input" type="checkbox" name="vatSelect" id="vat" value="vatSelect" style="width: 25px; height: 25px;">
                    </div>
                    <div class="col-sm-10">
                        <input type="number" name="vat" id="input_vat" class="form-control" readonly>
                    </div>
                </div>
                <label class="col-sm-2 col-form-label">รวมทั้งสิ้น</label>
                <div class="col-sm-4">
                    <input type="number" name="totalPrice" id="totalPrice" class="form-control" placeholder="รวมทั้งสิ้น..." min="0">
                </div>
            </div>
            <hr>
            <div class="form-group row">
                <div class="col-sm-4 text-left">
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#selectasset">เพิ่มอุปกรณ์</button>
                </div>
                <div class="col-sm-8 text-right">
                    <button type="button" class="btn btn-warning" title="Reset" id="btnreload"><i class="icofont icofont-refresh"></i></button>
                </div>
            </div>
            <table class="table table-hover table-bordered">
                <thead>
                    <tr class="text-center">
                        <th scope="col">#</th>
                        <th scope="col">รหัสอุปกรณ์</th>
                        <th scope="col">ชื่ออุปกรณ์</th>
                        <th scope="col">จำนวน</th>
                        <th scope="col">ราคาต่อหน่วย</th>
                        <th scope="col">จำนวนเงิน</th>
                        <th scope="col">กระจาย</th>
                    </tr>
                </thead>
                <tbody id="assetsRow"></tbody>
                <tfoot>
                    <tr>
                        <th colspan="3" class="text-right">รวม</th>
                        <th class="text-right">
                            <div id="total_qty"></div>
                        </th>
                        <th class="text-right">
                            <div id="total_up"></div>
                        </th>
                        <th class="text-right">
                            <div id="total_amount"></div>
                        </th>
                    </tr>
                </tfoot>
            </table>
            <div class="form-group row">
                <div class="col-sm-12 text-right">
                    <button type="button" class="btn btn-success" form="Distribute" onclick="btnsubmitDis()">บันทึก</button>
                </div>
            </div>
        </form>
    </section>

    <!-- modal จำนวนคงเหลือ -->
    <div class="modal fade" id="searchinvoice" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="searchinvoiceLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="searchinvoiceLabel">จำนวนคงเหลือ</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body"> 
                    <table class="table table-hover table-bordered">
                        <thead>
                            <tr class="text-center">
                                <th scope="col">เลขที่เอกสาร</th>
                                <th scope="col">วันที่</th>
                                <th scope="col">รหัสอุปกรณ์</th>
                                <th scope="col">ชื่ออุปกรณ์</th>
                                <th scope="col">ราคาต่อหน่วย</th>
                                <th scope="col">จำนวนคงเหลือ</th>
                                <th scope="col">กระจาย</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($dataOutstand as $key => $value) { ?>
                            <tr>
                                <td class="text-center text-middle"><?=$value['dis_docNo']?> / <?=$value['dis_refNo']?></td>
                                <td class="text-middle"><?=date("d/m/Y", strtotime($value['dis_date_received']))?></td>
                                <td class="text-middle">
                                    <?=$value['itemsCode']?>
                                </td>
                                <td class="text-middle">
                                    <?=$value['itemsName']?>
                                </td>
                                <td class="text-center text-middle"><?=$value['disout_unitPrice']?></td>
                                <td class="text-center text-middle"><?=$value['disout_qty']?></td>
                                <td class="text-center text-middle">
                                    <button type="button" class="btn btn-primary btn-sm" onclick="selectHeadDis(<?=$value['dis_id']?>)" data-dismiss="modal" aria-label="Close">เลือก</button>
                                </td>
                            </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- modal กระจายอุปกรณ์ -->
    <div class="modal fade" id="distributeItem" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="distributeItemLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="distributeItemLabel">กระจายอุปกรณ์</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="disCloseModal()">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">รหัสอุปกรณ์</label>
                        <div class="col-sm-4">
                            <input type="text" class="form-control" id="resultitemname" placeholder="รหัสอุปกรณ์..." readonly>
                        </div>
                        <label class="col-sm-2 col-form-label">จำนวนทั้งหมด</label>
                        <div class="col-sm-2">
                            <input type="text" class="form-control" id="resultitemamount" placeholder="จำนวนทั้งหมด..." value="" readonly>
                        </div>
                        <div class="col-sm-2">
                            <input type="text" class="form-control" id="resultitemup" placeholder="Unit Price..." value="" readonly>
                        </div>
                    </div>
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#cylinderstore">เลือกสาขา</button>
                    <table class="table table-hover table-bordered mt-3">
                        <thead>
                            <tr>
                                <th class="text-center">ชื่อสาขา</th>
                                <th class="text-center" width="150px">จำนวน</th>
                                <th class="text-center" width="150px">ราคาต่อหน่วย</th>
                                <th class="text-center" width="150px">จำนวนเงิน</th>
                            </tr>
                        </thead>
                        <tbody id="branchSelected"></tbody>
                        <tfoot>
                            <tr>
                                <th class="text-right">จำนวนรวม</th>
                                <th class="text-right"><span class="totalItem"></span></th>
                                <th colspan="2"></th>
                            </tr>
                            <tr>
                                <th class="text-right">จำนวนเงินรวม</th>
                                <th class="text-right"></th>
                                <th colspan="2">บาท</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-success btnsubmit">บันทึก</button>
                </div>
            </div>
        </div>
    </div>

    <!-- modal เลือกสาขา -->
    <div class="modal fade" id="cylinderstore" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="cylinderstoreLabel" aria-hidden="true">
        <div class="modal-dialog modal-sm" style="max-width: 500px !important;">
            <div class="modal-content">
                <div class="modal-header bg-info">
                    <h5 class="modal-title" id="cylinderstoreLabel">เลือกสาขา</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <table class="table table-hover table-bordered">
                        <thead>
                            <tr>
                                <th class="text-center" width="50px">#</th>
                                <th class="text-center" width="100px">รหัสสาขา</th>
                                <th class="text-center">ชื่อสาขา</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($dataBranch as $key => $value) { ?>
                            <tr>
                                <td class="text-center text-middle">
                                    <input type="checkbox" name="" id="" class="selectBranch" style="width: 15px; height: 15px;" value="<?=$value['branch_id']?>" data-branchname="<?=$value['branch_name']?>">
                                </td>
                                <td class="text-center text-middle">
                                    <?=$value['branch_id']?>
                                </td>
                                <td>
                                    <?=$value['branch_name']?>
                                </td>
                            </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- modal เลือกอุปกรณ์ -->
     <div class="modal fade" id="selectasset" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="selectassetLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="selectassetLabel">เลือกอุปกรณ์</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group row">
                        <div class="col-sm-4"></div>
                        <div class="col-sm-4">
                            <input type="text" class="form-control" id="assetid" placeholder="ค้นหารหัสอุปกรณ์...">
                        </div>
                        <div class="col-sm-2">
                            <button type="button" class="btn btn-primary" id="btnassets">ค้นหา</button>
                        </div>
                    </div>
                    <table class="table table-hover table-bordered">
                        <thead>
                            <tr class="text-center">
                                <th scope="col">#</th>
                                <th scope="col">รหัสอุปกรณ์</th>
                                <th scope="col">ชื่ออุปกรณ์</th>
                            </tr>
                        </thead>
                        <tbody id="ItemListRow">
                            <?php foreach ($dataItems as $key => $value) { ?>
                                <tr>
                                    <td class="text-center text-middle">
                                        <input type="checkbox" name="selectItem" class="selectitemacc" 
                                        style="width: 20px; height: 20px;" value="<?=$value['n_id']?>" id="inputcheck_<?=$value['n_id']?>"
                                        data-itemname="<?=$value['itemsName']?>" data-itemcode="<?=$value['itemsCode']?>">
                                    </td>
                                    <td class="text-middle"><span id="itemcode_<?=$value['n_id']?>"><?=$value['itemsCode']?></span></td>
                                    <td class="text-middle"><?=$value['itemsName']?></td>
                                </tr>
                                <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div> 

    <!-- Required Jqurey -->
    <script src="../files/bower_components/jquery/js/jquery.min.js"></script>
    <script src="../files/bower_components/jquery-ui/js/jquery-ui.min.js"></script>
    <script src="../files/bower_components/popper.js/js/popper.min.js"></script>
    <!-- <script src="../files/bower_components/bootstrap/js/bootstrap.min.js"></script> -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
    <!-- sweet alert js -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <!-- Select 2 js -->
    <script src="../files/bower_components/select2/js/select2.full.min.js"></script>
    <script src="js/distribute.js"></script>
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>
    <script>
        $(document).ready(function() {
            $('.js-example-basic-single').select2();
        });

        $(".datepicker").datepicker({dateFormat: 'dd/mm/yy'});
    </script>
</body> 
</html>