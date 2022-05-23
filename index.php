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
        <form action="function.php" method="POST" id="FormPreOrderCylinder">
            <input type="hidden" name="parameter" value="PreOrderCylinder">
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
                <th width="150" height="30">ฝากเติม</th>
            </tr>
            <?php for ($x=0; $x < count($brand); $x++) { ?>
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
                    <div class="row">
                        <div class="col-50" style="border-right: 1px solid #e8e8e8;">
                            <button type="button" name="add" id="add" data-toggle="modal" data-modal="<?=$brand[$x]?>" data-modal-open="add_data_modal" class="btn btn-warning open_modal">Add</button>
                        </div>
                        <div class="col-50">
                            <div id="result_adv_<?=$brand[$x]?>"></div>
                        </div>
                    </div>
                </td>
            </tr>
                                
            <?php }//end for(0) ?>
            <tr>
                <td colspan="<?=count($package)?>" style="text-align: right; padding-right: 10px;" height="30">รายการทั้งหมด</td>
                <td><div id="total">0</div></td>
                <td>รายการ</td>
            </tr>
        </table>
        <div class="container">
            <h4>หมายเหตุ</h4>
            <textarea name="" id="" cols="30" rows="10" style="width: 100%;"></textarea>
            <div class="row" style="margin-top: 30px;">
                <div class="col-12" style="text-align: right;">
                    <button type="button" class="btn btn-success" onclick="btn_submit_preselect()">ยืนยัน</button>
                </div>
            </div>
        </div>

        <div id="resultModal"></div>
        </form>
    </section>
    <script src="js/jquery-3.6.0.min.js"></script>
    <script src="js/sweetalert2.all.min.js"></script>
    <script src="js/select2.min.js"></script>
    <script src="js/JQcustom.js"></script>
</body>
</html>