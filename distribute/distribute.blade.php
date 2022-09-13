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
            padding: 0.10rem;
        }
        .nav-tabs .nav-item.show .nav-link, .nav-tabs .nav-link.active {
            color: #fff;
            background-color: #007bff;
        }
    </style>
</head>
<body>
    <?php
        include_once('../conn.php');
        include('../function.php');
        $fetchdata      = new DB_con();
        $dataItems      = $fetchdata->fetchdataItems();
        $dataBranch     = $fetchdata->fetchdataBranch();
        $dataOutstand   = $fetchdata->fetchdataOutstand();
        $itemtoBranch   = $fetchdata->fetchdataitemtoBranch();
        
    ?>
    <section>
        <ul class="nav nav-tabs md-tabs" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" data-toggle="tab" href="#maindistribute" role="tab">กระจายอุปกรณ์</a>
                <div class="slide"></div>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#checkdistribute" role="tab">ตรวจสอบอุปกรณ์</a>
                <div class="slide"></div>
            </li>
        </ul>
        <div class="tab-content card-block">
            <div class="tab-pane active" id="maindistribute" role="tabpanel">
                <form action="../controller/DistributeController.php" method="POST" id="distributeheadanddetail">
                    <input type="hidden" value="Distribute" name="parameter">
                    <div class="form-group row" style="padding-top: 25px;">
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
                        <label class="col-sm-2 col-form-label">เอกสารอ้างอิง</label>
                        <div class="col-sm-4">
                            <input type="text" name="refNo" id="refNo" class="form-control" placeholder="เอกสารอ้างอิง...">
                        </div>
                        <label class="col-sm-2 col-form-label">วันที่รับสินค้า</label>
                        <div class="col-sm-4">
                            <input type="text" class="form-control datepicker" name="date_received" id="date_received" placeholder="วันที่..." value="" autocomplete="off">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">ผู้จำหน่าย</label>
                        <div class="col-sm-4">
                            <select class="form-control js-example-basic-single" name="supplier" id="supplier" style="width: 100%;">
                                <option selected disabled>เลือกผู้จำหน่าย...</option>
                                <?php for ($i=0; $i <= 10; $i++) { ?>
                                    <option value="<?=$i?>"><?=$i?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">จำนวนเงินรวม</label>
                        <div class="col-sm-4">
                            <input type="number" name="price" id="price" class="form-control" placeholder="ราคา..." min="0" autocomplete="off">
                        </div>
                        <label class="col-sm-2 col-form-label">VAT
                            <input type="checkbox" name="vatSelect" id="vat" value="Y" style="width: 20px; height: 20px;">
                        </label>
                        <div class="col-sm-2">
                            <select name="vat_percentage" id="vat_percentage" class="form-control" style="width: auto;" disabled>
                                <option value="0">0 %</option>
                                <option value="7" selected>7 %</option>
                            </select>
                        </div>
                        <div class="col-sm-2">
                            <input type="number" name="vat" id="input_vat" class="form-control" readonly autocomplete="off">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-6"></label>
                        <label class="col-sm-2 col-form-label">รวมทั้งสิ้น</label>
                        <div class="col-sm-4">
                            <input type="number" name="totalPrice" id="totalPrice" class="form-control" placeholder="รวมทั้งสิ้น..." min="0" autocomplete="off">
                        </div>
                    </div>
                    <hr>
                    <div class="form-group row">
                        <div class="col-sm-4 text-left">
                            <button type="button" class="btn btn-primary" id="btnselectacc" data-toggle="modal" data-target="#selectasset">เพิ่มอุปกรณ์</button>
                        </div>
                        <div class="col-sm-8 text-right">
                            <button type="button" class="btn btn-warning" title="Reset" id="btnreload">รีเซ็ท <i class="icofont icofont-refresh"></i></button>
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
                            <button type="submit" class="btn btn-success" form="distributeheadanddetail" id="btn_distributeheadanddetail" onclick="btnsubmitDis()">บันทึก</button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="tab-pane" id="checkdistribute" role="tabpanel">
                <div class="card-block accordion-block" style="padding-top: 25px;">
                    <div class="row">
                        <div class="col-12">
                            <button type="button" class="btn btn-warning btn-sm">ปรับปรุงคลัง</button>
                            <a class="accordion-msg btn btn-outline-info btn-sm" data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                คัดกรอง <i class="icofont icofont-filter"></i>
                            </a>
                        </div>
                    </div>
                    <div id="accordion" role="tablist" aria-multiselectable="true">
                        <div class="accordion-panel">
                            <div class="accordion-heading" role="tab" id="headingOne">
                                <p class="card-title accordion-title" style="padding-top: 15px;"> </p>
                            </div>
                            <div id="collapseOne" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOne">
                                <div class="accordion-content accordion-desc">
                                    <div class="form-group row">
                                        <div class="col-sm-4">
                                            <label class="col-form-label ">สถานะ</label>
                                            <select name="" id="" class="form-control">
                                                <option value="ALL">ALL</option>
                                                <option value="TF-OUT">TF-OUT</option>
                                                <option value="TF-IN">TF-IN</option>
                                            </select>
                                        </div>
                                        <div class="col-sm-4">
                                            <label class="col-form-label">สาขา</label>
                                            <select name="" id="" class="form-control">
                                                <option value="ALL">ALL</option>
                                                <?php foreach ($dataBranch as $key => $value) { ?>
                                                    <option value="<?=$value['branch_id']?>"><?=$value['branch_name']?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                        <div class="col-sm-4">
                                            <label class="col-form-label">ค้นหารหัสอุปกรณ์</label>
                                            <input type="text" name="" id="" class="form-control" placeholder="ค้นหารหัสอุปกรณ์...">
                                        </div>
                                    </div>
                                    <div class="form-group row text-center">
                                        <div class="col-sm-12">
                                            <button type="button" class="btn btn-primary">ค้นหา</button>
                                            <button type="button" class="btn btn-warning" title="Reset">รีเซ็ท <i class="icofont icofont-refresh"></i></button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <table class="table table-hover">
                    <thead class="text-center">
                        <tr>
                            <th rowspan="2" class="text-middle" style="width: 40px;">#</th>
                            <th colspan="2">สถานะ</th>
                            <th colspan="2">ข้อมูลอุปกรณ์</th>
                            <th rowspan="2" class="text-middle" style="width: 80px;">วัน / เวลา</th>
                            <th rowspan="2" class="text-middle" style="width: 180px;">การจัดการ</th>
                        </tr>
                        <tr>
                            <th class="text-middle" style="width: 70px;">การยืนยัน</th>
                            <th class="text-middle" style="width: 90px;">สาขา</th>
                            <th class="text-middle">ชื่อ / รหัส</th>
                            <th class="text-middle" style="width: 60px;">จำนวน</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($itemtoBranch as $key => $value) { ?>
                            <tr>
                                <td class="text-middle text-center">
                                    <input type="checkbox" name="" id="" style="width: 15px; height: 15px;">
                                </td>
                                <td class="text-middle text-center">
                                    <span id="badge_status_<?=$value['accbranch_id']?>"><?=TransitStatus($value['accbranch_status'])?></span>
                                </td>
                                <td class="text-middle"><?=$value['accbranch_branchID']?></td>
                                <td class="text-middle" style="width: 200px;">
                                    <?=$value['itemsName']?><br>
                                    <span style="color: gray;"><?=$value['itemsCode']?></span>
                                    
                                </td>
                                <td class="text-middle text-center"><?=$value['accbranch_qty']?></td>
                                <td class="text-middle text-right"><?=date("d/m/Y H:i:m", strtotime($value['created_at']));?></td>
                                <td class="text-middle text-center">
                                    <?php if ($value['accbranch_status'] == 'Pending') { ?>
                                        <button type="button" class="btn btn-danger btn-sm" onclick="rejectitem(<?=$value['accbranch_id']?>)">Reject</button>
                                    <?php } ?>
                                    <button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#accbranchinfo" onclick="accbranchinfo(<?=$value['accbranch_id']?>)">ตรวจสอบ</button>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
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
                                <th scope="col">กระจาย</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- <?php var_dump($dataOutstand);?> -->
                            <?php foreach ($dataOutstand as $key => $value) { ?>
                                <tr>
                                    <td><?=$value['dis_docNo']?> / <?=$value['dis_refNo']?></td>
                                    <td><?=$value['dis_date_received']?></td>
                                    <td class="text-center text-middle">
                                        <button type="button" class="btn btn-primary btn-sm" onclick="selectHeadDis(<?=$value['dis_id']?>)" data-dismiss="modal" aria-label="Close">เลือก</button>
                                    </td>
                                </tr>
                            <?php } ?>
                            
                            <!-- <?php foreach ($dataOutstand as $key => $value) { ?>
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
                                <td class="text-center text-middle"><?=$value['disout_bal']?></td>
                                <td class="text-center text-middle">
                                    <button type="button" class="btn btn-primary btn-sm" onclick="selectHeadDis(<?=$value['disout_id']?>)" data-dismiss="modal" aria-label="Close">เลือก</button>
                                </td>
                            </tr>
                            <?php } ?> -->
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
                    <form action="../controller/DistributeController.php" method="post" id="FormAccDistribute">
                        <input type="hidden" name="parameter" value="AccDistribute">
                        <input type="hidden" name="itemid" id="itemid">
                        <input type="hidden" name="headdocid" id="headdocid">
                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label">รหัสอุปกรณ์</label>
                            <div class="col-sm-4">
                                <input type="text" class="form-control" id="resultitemname" name="itemcode" placeholder="รหัสอุปกรณ์..." readonly>
                            </div>
                            <label class="col-sm-2 col-form-label">จำนวนทั้งหมด</label>
                            <div class="col-sm-2">
                                <input type="text" class="form-control" id="resultitemamount" placeholder="จำนวนทั้งหมด..." value="" readonly>
                            </div>
                            <div class="col-sm-2">
                                <input type="hidden" class="form-control" id="resultitemup" placeholder="Unit Price..." value="" readonly>
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
                                    <th class="text-right"><span class="totalItem"></span><input type="hidden" name="totalItem" class="totalItem"></th>
                                    <th colspan="2"></th>
                                </tr>
                                <tr>
                                    <th class="text-right">จำนวนเงินรวม</th>
                                    <th class="text-right"></th>
                                    <th colspan="2">บาท</th>
                                </tr>
                            </tfoot>
                        </table>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-success btnsubmit" form="FormAccDistribute">บันทึก</button>
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
                                        <input type="checkbox" class="selectitemacc" 
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
                                <input type="text" class="form-control" id="branch_name" aria-describedby="branch_name" placeholder="ชื่อสาขา..." value="" readonly>
                            </div>
                            <div class="col-4">
                                <label for="branch_id">รหัสสาขา</label>
                                <input type="text" class="form-control" id="branch_id" aria-describedby="branch_id" placeholder="รหัสสาขา..." value="" readonly>
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
                                <input type="text" class="form-control" id="dis_docNo" aria-describedby="dis_docNo" placeholder="เลขที่เอกสาร..." value="" readonly>
                            </div>
                            <div class="col-4">
                                <label for="dis_refNo">เอกสารอ้างอิง</label>
                                <input type="text" class="form-control" id="dis_refNo" aria-describedby="dis_refNo" placeholder="เอกสารอ้างอิง..." value="" readonly>
                            </div>
                            <div class="col-4">
                                <label for="dis_refNo">เอกสารอ้างอิง</label>
                                <input type="text" class="form-control" id="dis_refNo" aria-describedby="dis_refNo" placeholder="เอกสารอ้างอิง..." value="" readonly>
                            </div>
                        </div>
                    </div>
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