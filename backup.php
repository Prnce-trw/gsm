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
            </tr>
            <?php
                for ($x=0; $x < count($brand); $x++) {
            ?>
            <tr>
                <td width="80" height="80">
                    <div class="div-inside" style="border-bottom: 1px solid #000"><?=$brand[$x]?></div>
                    <div class="div-inside" style="background-color: #fff4e6;">
                        <input type="checkbox" name="" value="<?php echo $x;?>" id="advance_<?php echo $x;?>" onclick="cylinder_adcance(<?php echo $x;?>)">
                        <label for="advance_<?php echo $x;?>">ฝากเติม</label>
                    </div>
                </td>
                <?php //for(1) 
                    for ($i=0; $i < count($package); $i++) { ?>
                    <td width="80" height="80">
                        <div class="div-inside" style="border-bottom: 1px solid #000">
                            <select name="pickitem" id="">
                                <?php for ($n=0; $n <=20 ; $n++) { ?>
                                    <option value="<?php echo $n; ?>" <?php echo $n == 0 ? 'selected':'' ?>><?php echo $n; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="div-inside" style="background-color: #fff4e6;">
                            <input type="number" class="input_advance_<?php echo $x;?>" min="0" style="width: 50px; display: none;">
                        </div>
                    </td>
                <?php }// end for(1) ?>
            </tr>
                                
            <?php
            }//end for(0)
            ?>
            <tr>
                <td colspan="<?=count($package)-1?>" style="text-align: right; padding-right: 10px;" height="30">รายการทั้งหมด</td>
                <td><div id="total">0</div></td>
                <td>รายการ</td>
            </tr>
        </table>
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
            $('select :selected').each(function() {
                sum += Number($(this).val());
                console.log(sum);
            });
            $("#total").text(Number(sum));
        });

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
                
            })
        }
    </script>
</body>
</html>