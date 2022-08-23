<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
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
    $fetchdata              = new DB_con();
    $dataInventoryOut       = $fetchdata->fetchdataInventoryOut();
    $dataInventoryIn        = $fetchdata->fetchdataInventoryIn();
    ?>
    <div class="row">
        <div class="col-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="text-center">Stock ขาออก</h3><hr>
                    <h5>02C = ถังเปล่า</h5>
                    <h5>00 = Stock หลังร้าน</h5>
                    <br>
                </div>
                <div class="card-body" style="background-color: rgb(149, 160, 255);">
                    <table class="table table-hover table-bordered">
                        <thead>
                            <tr class="text-center">
                                <th scope="col">ลำดับ</th>
                                <th scope="col">รหัสสินค้า</th>
                                <th scope="col">Store Area</th>
                                <th scope="col">จำนวนคงคลัง</th>
                                <th scope="col">MovAvgCost</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($dataInventoryOut as $key => $value) { ?>
                                <tr>
                                    <td class="text-center"><?=$key+1?></td>
                                    <td><?=$value['itemsCode']?></td>
                                    <td class="text-center"><?=$value['store_area']?></td>
                                    <td class="text-right"><?=$value['qty_balance']?></td>
                                    <td class="text-right"><?=$value['movAvgCost']?></td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="text-center">Stock ขาเข้า</h3><hr>
                    <h5>01G = น้ำแก๊ส</h5>
                    <h5>00 = Stock หลังร้าน</h5>
                    <h5>01 = Stock หน้าร้าน</h5>
                </div>
                <div class="card-body" style="background-color: rgb(255, 149, 149);">
                    <table class="table table-hover table-bordered">
                        <thead>
                            <tr class="text-center">
                                <th scope="col">ลำดับ</th>
                                <th scope="col">รหัสสินค้า</th>
                                <th scope="col">Store Area</th>
                                <th scope="col">จำนวนคงคลัง</th>
                                <th scope="col">MovAvgCost</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($dataInventoryIn as $key => $value) { ?>
                                <tr>
                                    <td class="text-center"><?=$key+1?></td>
                                    <td><?=$value['itemsCode']?></td>
                                    <td class="text-center"><?=$value['store_area']?></td>
                                    <td class="text-right"><?=$value['qty_balance']?></td>
                                    <td class="text-right"><?=$value['movAvgCost']?></td>
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