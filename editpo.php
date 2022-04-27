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
    <?php
        $POID = $_GET['POid'];
        $sql_fillstation = "SELECT * FROM tb_head_preorder WHERE head_pre_docnumber=$POID";
        $query = mysqli_query($conn, $sql_fillstation);
        $PODoc = mysqli_fetch_array($query);
    ?>
    <section>
        <form action="helper.php" method="POST" id="EditFormPO">
            <input type="hidden" name="parameter" value="EditPO">
            <input type="hidden" name="docNo" value="<?php echo $PODoc['head_pre_docnumber'];?>">
        <h4>แก้ไขเอกสารจัดซื้อ <?php echo $PODoc['head_pre_docnumber'];?>
        <div class="row">
            <label for="gas_filling">โรงบรรจุ</label>
            <select class="gas_filling" name="gas_filling" id="gas_filling" style="width: 150px;">
                <?php for ($i=0; $i < 10; $i++) { ?>
                    <option value="<?php echo $i; ?>" <?php echo $PODoc['head_pre_fillstation'] == $i ? 'selected' : '' ?>><?php echo $i; ?></option>
                <?php } ?>
            </select>
        </div>
        <div class="row">
        <table>
            <thead>
                <tr>
                    <th colspan="2">แบรนด์</th>
                    <th>ประเภทถัง</th>
                    <th>ขนาดถัง</th>
                    <th>จำนวน</th>
                </tr>
            </thead>
                <?php 
                    $sql = "SELECT * FROM tb_preselect WHERE preselect_Hpreorder=$POID";
                    $SUMsql = "SELECT SUM(preselect_amount) FROM tb_preselect WHERE preselect_Hpreorder=$POID";
                    $Arraysum = mysqli_query($conn, $SUMsql); 
                    $sum = mysqli_fetch_array($Arraysum);
                    $results = mysqli_query($conn, $sql);
                    foreach ($results as $key => $value) : { ?>
                <tr>
                    <td><img src="img/ptt-logo.png" alt="" width="25px"></td>
                    <td>
                        ปตท
                        <input type="hidden" name="item_id[]" value="<?php echo $value['preselect_id']; ?>">
                    </td>
                    <td>
                        <select name="cylinder_type[]" id="cylinder_type_" data-brand="">
                            <option value="ถังหมุนเวียน"<?php echo $value['preselect_cylinder_type'] == 'ถังหมุนเวียน' ? 'selected' : ''; ?>>ถังหมุนเวียน</option>
                            <option value="ฝากเติม" <?php echo $value['preselect_cylinder_type'] == 'ฝากเติม' ? 'selected' : ''; ?>>ฝากเติม</option>
                        </select>
                    </td>
                    <td>
                        <select name="cylinder_size[]" id="cylinder_size_" data-brand="">
                            <option value="4" <?php echo $value['preselect_cylinder_size'] == 4 ? 'selected' : ''; ?>>4</option>
                            <option value="8" <?php echo $value['preselect_cylinder_size'] == 8 ? 'selected' : ''; ?>>8</option>
                            <option value="15" <?php echo $value['preselect_cylinder_size'] == 15 ? 'selected' : ''; ?>>15</option>
                            <option value="48" <?php echo $value['preselect_cylinder_size'] == 48 ? 'selected' : ''; ?>>48</option>
                        </select>
                    </td>
                    <td>
                        <div class="number">
                            <span class="minus">-</span>
	                        <input class="input_amount" name="pre_amount[]" type="number" value="<?php echo $value['preselect_amount']; ?>" id="input_amount_" data-brand="">
                            <span class="plus">+</span>
                        </div>
                    </td>
                </tr>
                <?php } endforeach ?>
                <tr>
                    <td colspan="4">
                        <p>รวมทั้งหมด</p>
                    </td>
                    <td>
                        <input type="hidden" id="resultSum" value="<?php echo $sum[0]; ?>">
                        <span id="totalSum"><?php echo $sum[0]; ?></span>
                    </td>
                </tr>
            </table>
        </div>
        <div class="row" style="padding-top: 12px;">
            <div class="col-12">
                <h4>หมายเหตุ</h4>
                <textarea style="width: 100%; height: 150px;" name="comment"></textarea>
            </div>
        </div>

        <div class="row">
            <div class="col-12" style="text-align: right;">
                <button type="button" class="btn btn-success" onclick="btn_edit_preselect()">บันทึก</button>
            </div>
        </div>
        </form>
    </section>
    <script src="js/jquery-3.6.0.min.js"></script>
    <script src="js/sweetalert2.all.min.js"></script>
    <script src="js/custom.js"></script>
    <script>
        function btn_edit_preselect () {
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
                    $('#EditFormPO').submit();
                }
            })
        }
    </script>
</body>
</html>