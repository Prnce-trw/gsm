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
            padding: .50rem;
        }
    </style>
</head>
<body>
    <section>
        <div class="form-group row">
            <div class="col-sm-3">
                <b>สาขา</b>
                <select name="branch" class="form-control"></select>
            </div>
            <div class="col-sm-3">
                <b>รหัสสินค้า</b>
                <select name="branch" class="form-control"></select>
            </div>
            <div class="col-sm-3">
                <b>วันที่เริ่ม</b>
                <input type="text" name="date_start" class="form-control datepicker" placeholder="วันที่เริ่มต้น...">
            </div>
            <div class="col-sm-3">
                <b>วันที่สิ้นสุด</b>
                <input type="text" name="date_end" class="form-control datepicker" placeholder="วันที่สิ้นสุด...">
            </div>
        </div>
        <div class="form-group row">
            <div class="col-sm-12 text-center">
                <button type="button" class="btn btn-primary btn-sm"><i class="icofont icofont-search-alt-1"></i> ค้นหา</button>
                <button type="button" class="btn btn-danger btn-sm"><i class="icofont icofont-ui-rotation"></i> รีเซ็ท</button>
            </div>
        </div>
        <table class="table table-hover table-bordered">
            <thead>
                <tr class="text-center">
                    <th>วันที่</th>
                    <th>เลขที่</th>
                    <th>สาขา</th>
                    <th>รหัสสินค้า</th>
                    <th>ชื่อสินค้า</th>
                    <th>แบรนด์</th>
                    <th>น้ำหนัก</th>
                    <th>ผู้จำหน่าย</th>
                    <th>จำนวน</th>
                    <th>ราคา/หน่วย</th>
                    <th>ราคารวม</th>
                </tr>
            </thead>
        </table>
        <div class="form-group row">
            <div class="col-sm-12 text-right">
                <button type="button" class="btn btn-success btn-sm"><i class="icofont icofont-file-excel"></i> Excel</button>
            </div>
        </div>
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
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>
    <script>
        $(document).ready(function() {
            $('.js-example-basic-single').select2();
        });

        $(".datepicker").datepicker({dateFormat: 'dd/mm/yy'});
    </script>
</body>
</html>