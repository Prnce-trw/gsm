<?php include('conn.php') ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/custom.css">
    <title>GSM</title>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
</head>
<body>
    <section id="purchase">
        <h4>จัดซื้อ</h4>
        <form action="helper.php" method="POST" id="preselect">
            <input type="hidden" name="parameter" value="addPreOrder">
        <div class="row">
            <label for="gas_filling">โรงบรรจุ</label>
            <select class="gas_filling" name="gas_filling" id="gas_filling" style="width: 150px;">
                <?php for ($i=0; $i < 10; $i++) { 
                    echo "<option value=".$i.">".$i."</option>";
                } ?>
            </select>
        </div>
        <div class="row">
            <div class="col-50">
                <table>
                    <thead style="background-color: #e8e8e8; height: 30px;">
                        <tr>
                            <th colspan="2">ยี่ห้อแก๊ส</th>
                            <th>ประเภทถัง</th>
                            <th>ขนาดถัง</th>
                            <th>จำนวน</th>
                            <th>เพิ่ม</th>
                        </tr>
                    </thead>
                    <?php for ($i=0; $i < 5; $i++) {  ?>
                    <tr>
                        <td><img src="img/ptt-logo.png" alt="" width="25px"></td>
                        <td>ปตท</td>
                        <td>
                            <select class="cylinder-type" name="cylinder_type" id="cylinder_type_<?php echo $i; ?>" data-brand="<?php echo $i; ?>">
                                <option value="ถังหมุนเวียน">ถังหมุนเวียน</option>
                                <option value="ฝากเติม">ฝากเติม</option>
                            </select>
                        </td>
                        <td>
                            <select class="cylinder-size" name="cylinder_size" id="cylinder_size_<?php echo $i; ?>" data-brand="<?php echo $i; ?>">
                                <option value="4">4</option>
                                <option value="8">8</option>
                                <option value="15">15</option>
                                <option value="48">48</option>
                            </select>
                        </td>
                        <td>
                            <div class="number">
                                <span class="minus">-</span>
	                            <input class="input_amount" type="number" value="1" id="input_amount_<?php echo $i; ?>" data-brand="<?php echo $i; ?>">
                                <span class="plus">+</span>
                            </div>
                        </td>
                        <td>
                            <button type="button" class="add_cylinder" onclick="add_cylinder(<?php echo $i; ?>)">>></button>
                        </td>
                    </tr>
                    <?php } ?>
                </table>
            </div>
            <div class="col-50">
                <table>
                    <tr class="tb-blue">
                        <th colspan="2">ยี่ห้อแก๊ส</th>
                        <th>ประเภทถัง</th>
                        <th style="width: 73px;">ขนาดถัง</th>
                        <th style="width: 110px;">จำนวน</th>
                        <th>ลบ</th>
                    </tr>
                </table>
                <!-- <iframe src="test.html" frameborder="1" width="100%" height="350px" style="background-color:#fff;"> -->
                    <div id="resultTable"></div>
                <!-- </iframe> -->
                <table>
                    <tr>
                        <td colspan="4" style="width: 165px; background-color: #e8e8e8;">จำนวนรายการ</td>
                        <td style="width: 110px;">
                            <input type="hidden" id="resultSum" value="0">
                            <span id="totalSum">0</span>
                        </td>
                        <td style="background-color: #e8e8e8;">ถัง</td>
                    </tr>
                </table>
            </div>
        </div>
        <div class="row" style="padding-top: 12px;">
            <div class="col-12">
                <h4>หมายเหตุ</h4>
                <textarea style="width: 100%; height: 150px;" name="comment"></textarea>
            </div>
        </div>

        <div class="row">
            <div class="col-12" style="text-align: right;">
                <button type="button" class="btn btn-success" onclick="btn_submit_preselect()">ยืนยัน</button>
            </div>
        </div>
        </form>
    </section>
    <script src="js/jquery-3.6.0.min.js"></script>
    <script src="js/sweetalert2.all.min.js"></script>
    <script src="js/custom.js"></script>
    
    <script type="text/javascript">
        function btn_submit_preselect () {
            Swal.fire({
                title: 'คุณแน่ใจหรือไม่?',
                text: "เอกสาร PO จะไม่สามารถแก้ไขได้! กรุณาตรวจสอบความถูกต้องก่อนยืนยัน",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'ยืนยัน',
                cancelButtonText: 'ยกเลิก'
            }).then((result) => {
                var item_amount = $('#totalSum').text();
                if (result.isConfirmed && item_amount != '0') {
                    $('#preselect').submit();
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'กรุณาเลือกสินค้าให้ครบ',
                        timer: 3000,
                    });
                }
            })
        }
    </script>
</body>
</html>