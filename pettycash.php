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
            <h4>เงินสดย่อย</h4>
            <div class="row">
                <div class="col-30">
                    <div class="row">
                        <label for="don_pc_no" class="label-titile">เลขที่เอกสาร</label>
                        <h4 class="don_pc_no">PC00001</h4>
                    </div>
                </div>
                <div class="col-70">
                    <div class="row">
                        <label for="don_pc_no" class="label-titile">ประเภทวงเงิน</label>
                        <input type="radio" name="pc_type" id="normal" checked>
                            <label class="form-check-label" for="normal">วงเงินปกติ</label>
                        <input type="radio" name="pc_type" id="advance" > 
                            <label class="form-check-label" for="advance">วงเงินพิเศษ</label>
                    </div>
                </div>
                <div class="col-30">
                    <div class="row">
                        <label for="don_pc_no" class="label-titile">วันที่เริ่มใช้</label>
                        <input type="date" name="" id="">
                    </div>
                </div>
                <div class="col-70">
                    <div class="row">
                        <label for="don_pc_no" class="label-titile">วันที่สิ้นสุด</label>
                        <input type="date" name="" id="">
                    </div>
                </div>
                <div class="col-100">
                    <div class="row">
                        <label for="don_pc_no" class="label-titile">การเติมวงเงิน</label>
                        <input type="radio" name="pc_topup_type" id="auto" checked>
                            <label class="form-check-label" for="auto">อัตโนมัติ</label>
                        <input type="radio" name="pc_topup_type" id="manual" > 
                            <label class="form-check-label" for="manual">กำหนดเอง</label>
                    </div>
                </div>
            </div>
        </div>
        <table>
            <thead>
                <tr>
                    <th>ลำดับ</th>
                    <th>ชื่อสาขา</th>
                    <th>วงเงินเต็มจำนวน</th>
                    <th>ยอดคงเหลือ</th>
                    <th>ยอดวงเงินทั้งหมด</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>1</td>
                    <td>สาขาที่ 1</td>
                    <td>10000</td>
                    <td>10000</td>
                    <td>10000</td>
                </tr>
            </tbody>
        </table>
    </section>
    <script src="js/jquery-3.6.0.min.js"></script>
    <script src="js/sweetalert2.all.min.js"></script>
    <script src="js/select2.min.js"></script>
</body>
</html>