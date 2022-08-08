<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@40,300,0,0" />
<style>
.material-symbols-outlined {
  font-variation-settings:
  'FILL' 0,
  'wght' 400,
  'GRAD' 0,
  'opsz' 48
}
</style>
<div id="htmlDisplay">
    <?php  
    include_once('../conn.php');
    $FPID = $_GET['FillingPlant'];
    $fetchdata = new DB_con();
    $dataItem = $fetchdata->fetchdataItem($FPID); ?> 
    <form action="../controller/PriceBoardController.php" method="get">
        <input type="hidden" name="parameter" value="InsertPriceBoard">
        <table border="1" cellspacing="1" cellpadding="1">
            <thead>
                <tr>
                    <th width="80" height="30">ลำดับ</th>
                    <th>รายการสินค้า</th>
                    <th width="150" height="30">ราคา</th>
                    <th width="80" height="30">หน่วย</th>
                    <th width="150" height="30">วันที่เริ่ม</th>
                    <th width="80" height="30">ประวัติ</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($dataItem as $key => $value) { ?>
                <tr>
                    <td><?=$key+1?></td>
                    <td class="text-left">ถังแก๊สขนาด <?=$value['weightSize_id']?> กก.</td>
                    <td>
                        <input type="number" name="pricerate[]" id="" class="form-control" value="<?=$value['PB_itemPrice']?>">
                    </td>
                    <td>บาท/กก.</td>
                    <td>
                        <input type="text" name="eff_date[]" class="form-control datepicker" placeholder="วันที่เริ่มใช้...">
                    </td>
                    <td>
                        <button type="button" class="btn priceHistory" title="ประวัติ" data-branch="BRC1-1" data-sizeID="<?=$value['weight_NoID']?>">
                            <span class="material-symbols-outlined"> history </span></button>
                    </td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
        <div class="container">
            <div class="row" style="margin-top: 30px;">
                <button type="button" class="btn btn-success">บันทึก</button>
            </div>
        </div>
    </form>
</div>

<script>
    $(".datepicker").datepicker({dateFormat: 'dd/mm/yy'});
</script>