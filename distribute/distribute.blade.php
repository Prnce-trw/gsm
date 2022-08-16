<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GSM</title>
    
    <link rel="stylesheet" href="../css/select2.min.css">
    <link rel="stylesheet" href="../css/jquery-ui.min.css">
    
    <script src="../js/jquery-3.6.0.min.js"></script>
    <script src="../js/sweetalert2.all.min.js"></script>
    <script src="../js/select2.min.js"></script>
    <script src="../js/jquery-ui.min.js"></script>
    <script src="../js/JQcustom.js"></script>
    <script src="js/distribute.js"></script>
    <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="bootstrap/js/bootstrap.min.js" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
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
    </style>
</head>
<body>
    <?php
        include_once('../conn.php');
        $fetchdata  = new DB_con();
        $dataItems  = $fetchdata->fetchdataItems();
        
    ?>
    <section>
        <form action="" method="POST">
            <div class="form-group row">
                <label class="col-sm-2 col-form-label">เลขที่เอกสาร</label>
                <div class="col-sm-4">
                    <div class="row">
                        <div class="col-8">
                            <input type="text" class="form-control" placeholder="เลขที่เอกสาร...">
                        </div>
                        <div class="col-4">
                            <button type="button" class="btn btn-primary btn-sm" title="ค้นหา">
                                <span class="material-symbols-outlined">search</span></button>
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
                <div class="col-sm-4 form-check form-check-inline">
                    <input class="form-check-input" type="checkbox" id="vat" value="vat" style="width: 25px; height: 25px;">
                    <input type="number" name="" id="input_vat" class="form-control" readonly>
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
                    <button class="btn btn-primary">ค้นหา</button>
                </div>
            </div>
            <table class="table table-hover">
                <thead>
                    <tr class="text-center">
                        <th scope="col">#</th>
                        <th scope="col">รหัสอุปกรณ์</th>
                        <th scope="col">ชื่ออุปกรณ์</th>
                        <th scope="col" width="150px">จำนวน</th>
                        <th scope="col">กระจาย</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($dataItems as $key => $value) { ?>
                        <tr>
                            <td class="text-center text-middle">
                                <input type="radio" name="selectItem" id="" style="width: 20px; height: 20px;">
                            </td>
                            <td class="text-middle"><?=$value['itemsCode']?></td>
                            <td class="text-middle"><?=$value['itemsName']?></td>
                            <td>
                                <input type="number" name="" id="" class="form-control text-center" min="0">
                            </td>
                            <td class="text-center">
                                <button type="button" class="btn btn-secondary btn-sm">
                                    <span class="material-symbols-outlined">arrow_forward</span>
                                </button>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </form>
    </section>
</body>
</html>