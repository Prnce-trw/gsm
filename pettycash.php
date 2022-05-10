<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/select2.min.css">
    <link rel="stylesheet" href="css/custom.css">
    <title>GSM</title>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
</head>
<body>
    <section>
        <div class="container">
            <?php $branch = [1,2,3,4,5];?>
            <h4>เงินสดย่อย</h4>
            <div class="row">
                <input type="hidden" name="" id="branch" value="<?php echo count($branch);?>">
                <div class="col-40">
                    <div class="row">
                        <label for="don_pc_no" class="label-titile">เลขที่เอกสาร</label>
                        <h4 class="don_pc_no">PC00001</h4>
                    </div>
                </div>
                <div class="col-60">
                    <div class="row">
                        <label for="don_pc_no" class="label-titile">ประเภทวงเงิน</label>
                        <input type="radio" name="pc_type" id="normal" checked>
                            <label class="form-check-label" for="normal">วงเงินปกติ</label>
                        <input type="radio" name="pc_type" id="advance" > 
                            <label class="form-check-label" for="advance">วงเงินพิเศษ</label>
                    </div>
                </div>
                <div class="col-40">
                    <div class="row">
                        <label for="don_pc_no" class="label-titile">วันที่เริ่มใช้</label>
                        <input type="date" name="" class="form-control" style="margin-left: 8px;">
                    </div>
                </div>
                <div class="col-60">
                    <div class="row">
                        <label for="don_pc_no" class="label-titile">วันที่สิ้นสุด</label>
                        <input type="date" name="" class="form-control" style="margin-left: 8px;">
                    </div>
                </div>
                <div class="col-40">
                    <div class="row">
                        <label for="don_pc_no" class="label-titile">จำนวนวงเงิน</label>
                        <input type="number" name="" id="limit" class="form-control" style="margin-left: 8px;" placeholder="จำนวนวงเงิน...">
                    </div>
                </div>
                <div class="col-60">
                    <div class="row">
                        <label for="don_pc_no" class="label-titile">การเติมวงเงิน</label>
                        <input type="radio" name="pc_topup_type" class="pc_topup_type" id="auto" checked>
                            <label class="form-check-label" for="auto">อัตโนมัติ</label>
                        <input type="radio" name="pc_topup_type" class="pc_topup_type" id="manual" > 
                            <label class="form-check-label" for="manual">กำหนดเอง</label>
                    </div>
                </div>
                <div class="col-100" style="text-align: center;">
                    <button class="btn btn-warning" type="button" onclick="pc_calculate()">ปรับใช้</button>
                </div>
            </div>
            <hr>
            <br>
        </div>
        <table>
            <thead>
                <tr>
                    <th height="30">ลำดับ</th>
                    <th>ชื่อสาขา</th>
                    <th>วงเงินเต็มจำนวน</th>
                    <th>ยอดคงเหลือ</th>
                    <th>ยอดวงเงินทั้งหมด</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($branch as $key => $value) { ?>
                <tr>
                    <td height="30" ><?=$value?></td>
                    <td>สาขาที่ <?=$value?></td>
                    <td>10000</td>
                    <td>10000</td>
                    <td><input type="number" name="" class="form-control"></td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
    </section>
    <script src="js/jquery-3.6.0.min.js"></script>
    <script src="js/sweetalert2.all.min.js"></script>
    <script src="js/select2.min.js"></script>

    <script>
        function pc_calculate () {
            var branch = $('#branch').val();
            var pc_limit = $('#limit').val();
            if (pc_limit != '') {
                alert(jQuery.type(pc_limit));
            } else {
                Swal.fire({
                    icon: 'warning',
                    text: 'กรุณากรอกจำนวนวงเงิน',
                    showConfirmButton: false,
                    timer: 1500
                });
            }
        }
    </script>
</body>
</html>