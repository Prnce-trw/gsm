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
    <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="bootstrap/js/bootstrap.min.js" rel="stylesheet">
    <style>
        section {
            padding: 15px;
            height: 978px;
            background-color: #f8f9fe;
            width: 900px;
        }
    </style>
</head>
<body>
    <section>
        <form action="" method="POST">
            <div class="form-group row">
                <label class="col-sm-2 col-form-label">เลขที่เอกสาร</label>
                <div class="col-sm-4">
                    <input type="text" class="form-control" placeholder="เลขที่เอกสาร...">
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
                        <option selected disabled>เลือกรถขนส่ง...</option>
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
                <label class="col-sm-2 col-form-label">จำนวนทั้งหมด</label>
                <div class="col-sm-4">
                    <input type="number" name="" id="" class="form-control" placeholder="จำนวนทั้งหมด...">
                </div>
            </div>
            <hr>
        </form>
    </section>

</body>
</html>