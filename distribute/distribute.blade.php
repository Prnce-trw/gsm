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
    
    <style>
        section {
            padding: 15px;
            height: 978px;
            background-color: #f8f9fe;
            width: 900px;
        }

        .text-middle {
            vertical-align: middle !important;
        }

        .material-symbols-outlined {
            font-variation-settings:
            'FILL' 0,
            'wght' 400,
            'GRAD' 0,
            'opsz' 48
        }

        .modal-dialog {
            max-width: 1000px !important;
        }
    </style>
</head>
<body>
    <?php
        include_once('../conn.php');
        $fetchdata      = new DB_con();
        $dataItems      = $fetchdata->fetchdataItems();
        $dataBranch     = $fetchdata->fetchdataBranch();
    ?>
    <section>
        <form action="" method="POST">
            <div class="form-group row">
                <label class="col-sm-2 col-form-label">เลขที่เอกสาร</label>
                <div class="col-sm-4">
                    <div class="row">
                        <div class="col-8">
                            <input type="text" class="form-control" placeholder="เลขที่เอกสาร..." readonly>
                        </div>
                        <div class="col-4">
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
                    <select class="form-control js-example-basic-single" name="" id="">
                        <option selected disabled>เลือกผู้จำหน่าย...</option>
                        <?php for ($i=0; $i <= 10; $i++) { ?>
                            <option value="<?=$i?>"><?=$i?></option>
                        <?php } ?>
                    </select>
                </div>
                <label class="col-sm-2 col-form-label">วันที่รับสินค้า</label>
                <div class="col-sm-4">
                    <input type="text" class="form-control datepicker" placeholder="วันที่..." value="">
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-2 col-form-label">เอกสารอ้างอิง</label>
                <div class="col-sm-4">
                    <input type="text" name="" id="" class="form-control" placeholder="เอกสารอ้างอิง...">
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-2 col-form-label">จำนวนทั้งหมด</label>
                <div class="col-sm-4">
                    <input type="number" name="" id="" class="form-control" placeholder="จำนวนทั้งหมด..." min="0">
                </div>
                <label class="col-sm-2 col-form-label">ราคา</label>
                <div class="col-sm-4">
                    <input type="number" name="" id="" class="form-control" placeholder="ราคา..." min="0">
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-2 col-form-label" for="vat">Vat 7%</label>
                <div class="col-sm-4 form-check form-check-inline row">
                    <div class="col-sm-2">
                        <input class="form-check-input" type="checkbox" id="vat" value="vat" style="width: 25px; height: 25px;">
                    </div>
                    <div class="col-sm-10">
                        <input type="number" name="" id="input_vat" class="form-control" readonly>
                    </div>
                </div>
                <label class="col-sm-2 col-form-label">รวมทั้งสิ้น</label>
                <div class="col-sm-4">
                    <input type="number" name="" id="" class="form-control" placeholder="รวมทั้งสิ้น..." min="0">
                </div>
            </div>
            <hr>
            <div class="form-group row">
                <div class="col-sm-5 text-center">
                    <input type="text" name="" id="" class="form-control" placeholder="ค้นหารหัสอุปกรณ์..." min="0">
                </div>
                <div class="col-sm-5 text-center">
                    <input type="text" name="" id="" class="form-control" placeholder="ค้นหาชื่ออุปกรณ์..." min="0">
                </div>
                <div class="col-sm-2">
                    <button class="btn btn-primary">ค้นหาอุปกรณ์</button>
                </div>
            </div>
            <table class="table table-hover table-bordered">
                <thead>
                    <tr class="text-center">
                        <th scope="col">#</th>
                        <th scope="col">รหัสอุปกรณ์</th>
                        <th scope="col">ชื่ออุปกรณ์</th>
                        <th scope="col">Unit Price</th>
                        <th scope="col">จำนวน</th>
                        <th scope="col">Amount</th>
                        <th scope="col">กระจาย</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($dataItems as $key => $value) { ?>
                        <tr>
                            <td class="text-center text-middle">
                                <input type="radio" name="selectItem" id="" class="radioItem" style="width: 20px; height: 20px;" value="<?=$value['n_id']?>">
                            </td>
                            <td class="text-middle"><?=$value['itemsCode']?></td>
                            <td class="text-middle"><?=$value['itemsName']?></td>
                            <td>
                                <input type="number" name="unitprice" id="" class="form-control text-center" style="width: 80px;" min="0">
                            </td>
                            <td>
                                <input type="number" name="qty" id="" class="form-control text-center" style="width: 80px;" min="0">
                            </td>
                            <td>
                                <input type="number" name="amount" id="amount_<?=$value['n_id']?>" class="form-control text-center" style="width: 80px;" min="0">
                            </td>
                            <td class="text-center">
                                <button type="button" class="btn btn-secondary btn-sm" data-toggle="modal" data-target="#distributeItem" id="btnDistribute_<?=$value['n_id']?>" 
                                    onclick="distributeItem(<?=$value['n_id']?>)" data-itemcode="<?=$value['itemsCode']?>" disabled>
                                    <i class="icofont icofont-rounded-double-right"></i>
                                </button>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </form>
    </section>

    {{-- modal แก้ไขถังหมุนเวียน --}}
    <div class="modal fade" id="searchinvoice" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="searchinvoiceLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="searchinvoiceLabel">แก้ไขถังหมุนเวียน</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body"> 
                    <table class="table table-hover table-bordered">
                        <thead>
                            <tr class="text-center">
                                <th scope="col">เลขที่เอกสาร</th>
                                <th scope="col">รหัสอุปกรณ์</th>
                                <th scope="col">ชื่ออุปกรณ์</th>
                                <th scope="col">Unit Price</th>
                                <th scope="col">จำนวนคงเหลือ</th>
                                <th scope="col">กระจาย</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($dataItems as $key => $value) { ?>
                            <tr>
                                <td>Inv<?=$key+1?></td>
                                <td>
                                    <?=$value['itemsCode']?>
                                </td>
                                <td>
                                    <?=$value['itemsName']?>
                                </td>
                                <td></td>
                                <td></td>
                                <td>
                                    <button type="button" class="btn btn-primary">เลือก</button>
                                </td>
                            </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- modal กระจายอุปกรณ์ --}}
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
                            <input type="text" class="form-control" placeholder="รหัสอุปกรณ์..." readonly>
                        </div>
                        <label class="col-sm-2 col-form-label">จำนวนทั้งหมด</label>
                        <div class="col-sm-4">
                            <input type="text" class="form-control" id="resultitemamount" placeholder="จำนวนทั้งหมด..." value="" readonly>
                        </div>
                    </div>
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#cylinderstore">เลือกสาขา</button>
                    <table class="table table-hover table-bordered mt-3">
                        <thead>
                            <tr>
                                <th class="text-center">ชื่อสาขา</th>
                                <th class="text-center" width="150px">จำนวน</th>
                            </tr>
                        </thead>
                        <tbody id="branchSelected"></tbody>
                        <tfoot>
                            <tr>
                                <th class="text-right">รวม</th>
                                <th class="text-right"><span class="totalItem"></span></th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-success">บันทึก</button>
                </div>
            </div>
        </div>
    </div>

    {{-- modal เลือกสาขา --}}
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
</body> 
</html>