<?php include('conn.php') ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/custom.css">
    <title>GSM</title>
</head>
<body>
    <section id="purchase">
        <h4>จัดซื้อ</h4>
        <form action="helper.php" method="POST" id="preselect">
            <input type="hidden" name="parameter" value="addPreOrder">
        <div class="row">
            <label for="gas_filling">โรงบรรจุ</label>
            <select name="gas_filling" id="gas_filling" style="width: 150px;">
                <?php for ($i=0; $i < 10; $i++) { 
                    echo "<option value=".$i.">".$i."</option>";
                } ?>
            </select>
        </div>
        <div class="row">
            <div class="col-50">
                <table>
                    <thead>
                        <tr>
                            <th colspan="2">แบรนด์</th>
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
                            <select name="cylinder_type" id="cylinder_type_<?php echo $i; ?>" data-brand="<?php echo $i; ?>">
                                <option value="ถังหมุนเวียน">ถังหมุนเวียน</option>
                                <option value="ฝากเติม">ฝากเติม</option>
                            </select>
                        </td>
                        <td>
                            <select name="cylinder_size" id="cylinder_size_<?php echo $i; ?>" data-brand="<?php echo $i; ?>">
                                <option value="4">4</option>
                                <option value="8">8</option>
                                <option value="15">15</option>
                                <option value="48">48</option>
                            </select>
                        </td>
                        <td>
                            <div class="number">
                                <span class="minus">-</span>
	                            <input class="input_amount" type="text" value="1" id="input_amount_<?php echo $i; ?>" data-brand="<?php echo $i; ?>">
                                <span class="plus">+</span>
                            </div>
                        </td>
                        <td>
                            <button type="button" class="add_cylinder" onclick="add_cylinder(<?php echo $i; ?>)">เพิ่ม</button>
                        </td>
                    </tr>
                    <?php } ?>
                </table>
            </div>
            <div class="col-50">
                <table>
                    <tr>
                        <th colspan="2">แบรนด์</th>
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
                        <td colspan="4" style="width: 180px;">จำนวนรายการ</td>
                        <td style="width: 110px;">
                            <input type="hidden" id="resultSum" value="0">
                            <span id="totalSum">0</span>
                        </td>
                        <td>ถัง</td>
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
                <button type="button" onclick="btn_submit_preselect()" style="padding: 4px; border-radius: 4px;">ยืนยัน</button>
            </div>
        </div>
        </form>
    </section>
    
    <script src="js/custom.js"></script>
    <script src="js/jquery-3.6.0.min.js"></script>
    <script src="js/sweetalert2.all.min.js"></script>
    <script>
        jQuery(document).ready(function(){
            // This button will increment the value
            $(document).on('click', '.plus', function (e) {
                // Stop acting like a button
                e.preventDefault();
                // Get the field name
                var $input = $(this).parent().find('input');
                // Get its current value
                var currentVal = parseInt($input.val());
                // If is not undefined
                if (!isNaN(currentVal)) {
                    // Increment
                    $input.val(currentVal + 1)
                } else {
                    // Otherwise put a 0 there
                    $input.val(1);
                }
            });
            // This button will decrement the value till 0
            $(document).on('click', '.minus', function (e) {
                // Stop acting like a button
                e.preventDefault();
                // Get the field name
                var $input = $(this).parent().find('input');
                // Get its current value
                var currentVal = parseInt($input.val());
                // If it isn't undefined or its greater than 0
                if (!isNaN(currentVal) && currentVal > 1) {
                    // Decrement one
                    $input.val(currentVal - 1)
                    var amount_now = currentVal -1;
                    if (amount_now != 0) {
                        SumAmount(-1);
                    }
                } else {
                    $input.val(1);
                }
            });
        });

        function add_cylinder (brand_id) {
            var amount = $('#input_amount_'+brand_id).val();
            var type = $('#cylinder_type_'+brand_id).val();
            var size = $('#cylinder_size_'+brand_id).val();
            var parameter = "addPurchase";
            // alert(amount +'\n' + type +'\n' + size + '\n' + jQuery.type(parseInt(amount)));
            SumAmount(amount);
            $.ajax({
                type: "POST",
                url: "helper.php",
                data: {parameter:parameter, amount:amount, brand_id:brand_id, type:type},
                dataType: "JSON",
                success: function (response) {
                    $('#resultTable').append(response);
                }
            });
        }

        function minus() {
            var $input = $(this).parent().find('input');
			var count = parseInt($input.val()) - 1;
			counts = count < 1 ? 1 : count;
			$input.val(counts);
			$input.change();
			return false;
        }

        function plus() {
            var $input = $(this).parent().find('input');
			$input.val(parseInt($input.val()) + 1);
			$input.change();
			return false;
        }

        function btn_del_preselect (id) {
            var amount = $('#input_preamount_'+id).val();
            $('#preselect_id_'+id).remove();
            $('#tr_preselect_'+id).remove();
            SumAmount(-amount);
        }

        function SumAmount (amount) {
            var total = $('#resultSum').val();
            var result = parseInt(total) + parseInt(amount);
            if (!result || result <= 0) {
                $('#resultSum').val(0);
                $('#totalSum').text(0);
            } else {
                $('#resultSum').val(result);
                $('#totalSum').text(result);
            }
        }

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
                if (result.isConfirmed) {
                    $('#preselect').submit();
                }
            })
        }
    </script>
</body>
</html>