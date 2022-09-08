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
    <link rel="stylesheet" href="../css/select2-bootstrap4.min.css">
    <link rel="stylesheet" href="//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
    <!-- DataTable css -->
    <link rel="stylesheet" href="../files/bower_components/datatables.net-bs4/css/dataTables.bootstrap4.min.css" />
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

        .table td,
        .table th {
            padding: .50rem;
        }

        /* .select2-selection__rendered {
            line-height: calc(2.25rem + 2px) !important;
            width: 100% !important;
        }

        .select2-selection {
            height: calc(2.25rem + 2px) !important;
            width: 100% !important;
        } */
    </style>
    <?php
    include_once '../conn.php';

    $conn = new DB_con();
    $branchData = $conn->fetchdataBranch();
    $categoryData = $conn->fetchdataCategory();
    $productData = $conn->fetchdataItems();
    ?>
</head>

<body>
    <section>
        <div class="form-group row">
            <div class="col-sm-3">
                <b>สาขา</b>
                <select name="branch" id="branch" class="form-control">
                    <option value="" disabled selected>เลือกสาขา</option>
                    <?php foreach ($branchData as $key => $value) { ?>
                        <option value="<?= $value['n_id'] ?>"><?= $value['branch_name'] ?></option>
                    <?php } ?>
                </select>
            </div>
            <div class="col-sm-3">
                <b>หมวดหมู่สินค้า</b>
                <select name="category" id="category" class="form-control">
                    <option value="" disabled selected>เลือกหมวดหมู่สินค้า</option>
                    <?php foreach ($categoryData as $key => $value) { ?>
                        <option value="<?= $value['items_category_textid'] ?>"><?= "$value[items_category_textid] $value[items_category_name]" ?></option>
                    <?php } ?>
                </select>
            </div>
            <div class="col-sm-3">
                <b>รหัสสินค้า</b>
                <select name="product" id="product" class="form-control">
                    <option value="" disabled selected>เลือกสินค้า</option>
                    <?php foreach ($productData as $key => $value) { ?>
                        <option value="<?= $value['n_id'] ?>"><?= "$value[itemsCode] $value[itemsName]" ?></option>
                    <?php } ?>
                </select>
            </div>
            <div class="col-sm-3 my-auto text-right">
                <button type="button" id="search" class="btn btn-primary"><i class="icofont icofont-search-alt-1"></i> ค้นหา</button>
                <button type="button" id="resetForm" class="btn btn-warning text-white"><i class="icofont icofont-ui-rotation"></i> รีเซ็ท</button>
            </div>
        </div>
        <div class="form-group row">
            <div class="col-sm-3">
                <b>วันที่เริ่ม</b>
                <input type="text" name="date_start" id="date_start" class="form-control datepicker" placeholder="วันที่เริ่มต้น...">
            </div>
            <div class="col-sm-3">
                <b>วันที่สิ้นสุด</b>
                <input type="text" name="date_end" id="date_end" class="form-control datepicker" placeholder="วันที่สิ้นสุด...">
            </div>
            <div class="col-sm-3"></div>
            <div class="col-sm-3 my-auto text-right">
                <button type="button" id="excel" disabled class="btn btn-success"><i class="icofont icofont-file-excel"></i> Excel</button>
            </div>
        </div>
        <table class="table table-hover table-bordered" id="dataTable">
            <thead>
                <tr class="text-center">
                    <th width="10%">วันที่</th>
                    <th width="5%">เลขที่</th>
                    <th width="10%">สาขา</th>
                    <th width="10%">รหัสสินค้า</th>
                    <th width="15%">ชื่อสินค้า</th>
                    <th width="10%">แบรนด์</th>
                    <th width="10%">น้ำหนัก</th>
                    <th width="15%">ผู้จำหน่าย</th>
                    <th width="10%">จำนวน</th>
                    <th width="10%">ราคา/หน่วย</th>
                    <th width="10%">ราคารวม</th>
                </tr>
            </thead>
            <tbody id="showData">

            </tbody>
        </table>
    </section>
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
    <!-- <script src="js/distribute.js"></script> -->
    <script src="../files/bower_components/datatables.net-bs4/js/dataTables.bootstrap4.min.js"></script>
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>
    <script src="js/report_buyproduct.js"></script>
</body>

</html>