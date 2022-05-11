<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/select2.min.css">
    <link rel="stylesheet" href="../css/custom.css">
    <link rel="stylesheet" href="../css/jquery-ui.min.css">
    <title>GSM</title>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
</head>
<body>
    <section id="purchase">
        <div class="container">
            <h4>จัดซื้อ</h4>
            <div class="row">
                <div class="col-50">
                    <label for="filling" class="label-titile">โรงบรรจุ</label>
                    บางนา
                </div>
                <div class="col-50">
                    <label class="label-titile">หมายเลขเอกสาร</label> <?=$_GET['id']?>
                </div>
            </div>
            <div class="row">
                <div class="col-50">
                    <label class="label-titile">รอบ</label> 
                    2
                </div>
                <div class="col-50">
                    <label class="label-titile">วันที่</label> 
                    15/01/2022
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
                            
                        </div>
                    </td>
                <?php }// end for(1) ?>
                <td>
                    4 กก: 2 <br>
                    8 กก: 1
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
        <div class="container">
            <h4>หมายเหตุ</h4>
            Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.
            <div class="row" style="margin-top: 30px;">
                <div class="col-12" style="text-align: right;">
                    <a href="../table_po.php" class="btn btn-danger">ย้อนกลับ</a>
                    <button type="button" class="btn btn-primary">พิมพ์เอกสสาร</button>
                </div>
            </div>
        </div>
    </section>
    <script src="../js/jquery-3.6.0.min.js"></script>
    <script src="../js/sweetalert2.all.min.js"></script>
    <script src="../js/select2.min.js"></script>
    <script src="../js/jquery-ui.min.js"></script>
    <script>
    </script>
</body>
</html>