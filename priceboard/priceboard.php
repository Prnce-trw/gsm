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
        <?php 
            include_once('../conn.php');
            $fetchdata = new DB_con();
            $dataFP = $fetchdata->fetchdataFP();?>
        <form action="../controller/PriceBoardController.php" method="POST">
            <input type="hidden" name="parameter" value="InsertPriceBoard">
            <div class="container">
                <h4>กระดาษราคา</h4>
            </div>
            <div class="row">
                <div class="col-40">
                    <label for="filling" class="label-titile">โรงบรรจุ</label>
                    <select class="js-example-basic-single selectFP" name="fillingplant" id="fillingplant" style="width: 150px;">
                        <option selected disabled>เลือกโรงบรรจุ...</option>
                        <?php foreach ($dataFP as $key => $value) { ?>
                            <option value="<?php echo $value['FP_ID']; ?>"><?php echo $value['FP_Name']; ?></option>
                        <?php } ?>
                    </select>
                </div>
                <div class="col-30">
                    <label for="filling" class="label-titile">สาขา</label>
                    <select class="js-example-basic-single" name="branchID" id="branchID" style="width: 150px;">
                        <option selected disabled>เลือกโรงบรรจุ...</option>
                        <option value="BRC1-1">BRC1-1</option>
                        <!-- <?php foreach ($dataFP as $key => $value) { ?>
                            <option value="<?php echo $value['FP_ID']; ?>"><?php echo $value['FP_Name']; ?></option>
                        <?php } ?> -->
                    </select>
                </div>
                <div class="col-30">
                    <button type="button" id="btnSearch" class="btn btn-primary">ค้นหา</button>
                </div>
            </div>
            <div id="resultHTML"></div>
        </form>
    </section>
    <script src="../js/jquery-3.6.0.min.js"></script>
    <script src="../js/sweetalert2.all.min.js"></script>
    <script src="../js/select2.min.js"></script>
    <script src="../js/jquery-ui.min.js"></script>
    <script src="../js/JQcustom.js"></script>
    <script>
        $(document).on('click', '#btnSearch', function () {
            var FillingPlant = $('#fillingplant').val(); 
            var branchID = $('#branchID').val(); 
            if (FillingPlant == null) {
                alert('กรุณาเลือกโรงบรรจุ');
            } else if (branchID == null) {
                alert('กรุณาเลือกสาขา');
            } else if (FillingPlant != null && branchID != null) {
                $.ajax({
                    type: "GET",
                    url: "display.php",
                    data: {FillingPlant: FillingPlant, branchID: branchID},
                    contentType: "application/json; charset=utf-8",
                    dataType: "HTML",
                    success: function (response) {
                        $("#resultHTML").html(response);
                    }
                });
            }
        });
    </script>
</body>
</html>