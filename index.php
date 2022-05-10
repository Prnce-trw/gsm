<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/select2.min.css">
    <link rel="stylesheet" href="css/custom.css">
    <title>GSM</title>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
</head>
<body>
    <section id="purchase">
        <div class="container">
            <h4>จัดซื้อ</h4>
            <div class="row">
                <div class="col-100">
                    <label for="filling" class="label-titile">โรงบรรจุ</label>
                    <select class="gas_filling js-example-basic-single" name="gas_filling" id="gas_filling" style="width: 150px;">
                        <option selected disabled>เลือกโรงบรรจุ...</option>
                        <?php for ($i=0; $i < 10; $i++) { ?>
                            <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                        <?php } ?>
                    </select>
                </div>
            </div>
        </div>
        <div class="table-cover"></div>
            <table border="1" cellspacing="1" cellpadding="1">
            <?php 
                $brand = ['PTT','WP','Siam','Unit','PT','Other'];
                $package = [4,7,8,11.5,13.5,15,48];
                //for(0)
            ?>
            <tr>
                <th width="80" height="30">
                    ยี่ห้อ\ขนาด
                </th>
                <?php
                    //for(1)
                    for ($i=0; $i < count($package); $i++) { 
                ?>
                <th width="80" height="30"><?=$package[$i]?></th>
                <?php
                    }// end for(1)
                ?>
                <th width="80" height="30">ฝากเติม</th>
            </tr>
            <?php
                for ($x=0; $x < count($brand); $x++) {
            ?>
            <tr>
                <td width="80" height="50" >
                    <div class="div-inside"><?=$brand[$x]?></div>
                    
                </td>
                <?php //for(1) 
                    for ($i=0; $i < count($package); $i++) { ?>
                    <td width="80" height="50">
                        <div class="div-inside">
                            <select name="pickitem" class="pickitem" id="">
                                <?php for ($n=0; $n <=20 ; $n++) { ?>
                                    <option value="<?php echo $n; ?>" <?php echo $n == 0 ? 'selected':'' ?>><?php echo $n; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </td>
                <?php }// end for(1) ?>
                <td>
                    <button type="button" class="open_modal" data-modal="<?=$i?>">เพิ่ม</button>
                </td>
            </tr>
                                
            <?php
            }//end for(0)
            ?>
            <tr>
                <td colspan="<?=count($package)?>" style="text-align: right; padding-right: 10px;" height="30">รายการทั้งหมด</td>
                <td><div id="total">0</div></td>
                <td>รายการ</td>
            </tr>
        </table>
        <div class="row">
            <h4>หมายเหตุ</h4>
            <textarea name="" id="" cols="30" rows="10" style="width: 100%;"></textarea>
        </div>
        <div class="row" style="margin-top: 30px;">
            <div class="col-12" style="text-align: right;">
                <button type="button" class="btn btn-success" onclick="btn_submit_preselect()">ยืนยัน</button>
            </div>
        </div>
    </section>
    <script src="js/jquery-3.6.0.min.js"></script>
    <script src="js/sweetalert2.all.min.js"></script>
    <script src="js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.js-example-basic-single').select2({
                placeholder: "เลือกโรงบรรจุ...",
            });
        });

        $('select').change(function(){
            var sum = 0;
            $('.pickitem').each(function() {
                sum += parseInt($(this).val());
            });
            $("#total").text(sum);
        });

        $("input[class^='input_advance_']").on('input',function() {
            $("#total").text(getAllSum());
        });
        $('select').change(function(){
            $("#total").text(getAllSum());
        });

        function getAllSum() {
            var result = 0;
            $('.pickitem :selected').each(function() {
                result += parseInt($(this).val());
            });
            $("input[class^='input_advance_']").each(function() {
                if(isNaN($(this).val()) || $(this).val() === "") {
                    result+=0;
                } else {
                    result+=parseInt($(this).val());
                }
            })
            return result
        }

        function cylinder_adcance(id) {
            if ($('#advance_'+id).is(":checked")) {
                $('.input_advance_'+id).show(300);
            } else {
                $('.input_advance_'+id).hide(300);
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
                    if ($('#total').text() != "0") {
                        Swal.fire({
                            icon: 'success',
                            text: 'บันทึกข้อมูลสำเร็จ',
                            showConfirmButton: false,
                            timer: 1500
                        })
                    } else {
                        Swal.fire({
                            icon: 'warning',
                            text: 'กรูณากรอกตรวจสอบข้อมูลก่อนยืนยัน',
                            showConfirmButton: false,
                            timer: 1500
                        })
                    }
                }
            })
        }

        function open_modal (id) {
            $("#modal").show();
        }
    </script>
</body>
</html>