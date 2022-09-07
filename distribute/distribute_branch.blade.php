<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>GSM</title>
    
    <!-- Required Fremwork -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <!-- ico font -->
    <link rel="stylesheet" type="text/css" href="../files/assets/icon/icofont/css/icofont.css">
    <!-- Style.css -->
    <!-- <link rel="stylesheet" type="text/css" href="../files/assets/css/style.css"> -->
    <!-- Select 2 css -->
    <link rel="stylesheet" href="../files/bower_components/select2/css/select2.min.css" />
    <link rel="stylesheet" href="//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
    <style>
        section {
            padding: 15px;
            height: 1200px;
            background-color: #f8f9fe;
            width: 900px;
        }

        .text-middle {
            vertical-align: middle !important;
        }

        .modal-dialog {
            max-width: 1000px !important;
        }

        .table td, .table th {
            padding: 0.25rem;
        }
    </style>
</head>
<body>
    <?php
        include_once('../conn.php');
        include('../function.php');
        $fetchdata      = new DB_con();
        $branchid       = 'BRC01-2';
        $dataItems      = $fetchdata->fetchdataitemBranchPending($branchid);
        // $count          = $fetchdata->CountitemBranchPending($branchid);
    ?>
    <section>
        <div class="form-group row">
            <div class="col-sm-4 text-left">
                <h3>สาขา 2</h3>
            </div>
            <div class="col-sm-8 text-right">
                <button type="button" class="btn btn-warning" title="Reset" id="btnreload">รีเซ็ท <i class="icofont icofont-refresh"></i></button>
            </div>
        </div>
        <form action="../controller/DistributeController.php" method="post" id="AcceptAccToBranch">
            <input type="hidden" value="AcceptAccToBranch" name="parameter">
            <input type="hidden" value="<?=$branchid?>" name="branchid">
            <table class="table table-hover table-bordered">
                <thead>
                    <tr class="text-center">
                        <th scope="col"><input type="checkbox" name="" id="" style="width: 15px; height: 15px;"></th>
                        <th scope="col">หมายเลขเอกสาร</th>
                        <th scope="col" width="100px">วันที่</th>
                        <th scope="col" width="80px">สถานะ</th>
                        <th scope="col">รหัสอุปกรณ์</th>
                        <th scope="col">ชื่ออุปกรณ์</th>
                        <th scope="col" width="70px">จำนวน</th>
                    </tr>
                </thead>
                <tbody id="accRow">
                    <?php foreach ($dataItems as $key => $value) { ?>
                        <tr>
                            <td class="text-middle text-middle">
                                <input type="checkbox" name="itemid[<?=$key?>]" class="itemselected" style="width: 15px; height: 15px;" value="<?=$value['itemsCode']?>" data-itemid="<?=$value['n_id']?>">
                            </td>
                            <td class="text-middle">
                                <b><?=$value['accbranch_HdisID']?></b> <br> <span class="text-secondary"><?=$value['dis_refNo']?></span>
                            </td>
                            <td class="text-center text-middle">
                                <?=date("d/m/Y H:i", strtotime($value['created_at']))?>
                            </td>
                            <td class="text-center text-middle">
                                <?=TransitStatus($value['accbranch_status'])?>
                            </td>
                            <td class="text-center text-middle">
                                <?=$value['itemsCode']?>
                            </td>
                            <td class="text-middle">
                                <?=$value['itemsName']?>
                            </td>
                            <td class="text-center text-middle">
                                <?=$value['accbranch_qty']?>
                                <input type="hidden" name="iteminfo[<?=$key?>]" value="<?=$value['accbranch_qty']?>/<?=$value['dis_refNo']?>/<?=$value['accbranch_amount']?>/<?=$value['accbranch_id']?>" id="inputitemid_<?=$value['n_id']?>" disabled>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
            <div class="form-group row">
                <div class="col-sm-12 text-right">
                    <button type="button" class="btn btn-success" id="btn_submitdistributebranch" form="AcceptAccToBranch">บันทึก</button>
                </div>
            </div>
            <div id="result"></div>
        </form>
    </section>

    <!-- Required Jqurey -->
    <script src="../files/bower_components/jquery/js/jquery.min.js"></script>
    <script src="../files/bower_components/jquery-ui/js/jquery-ui.min.js"></script>
    <script src="../files/bower_components/popper.js/js/popper.min.js"></script>
    <!-- <script src="../files/bower_components/bootstrap/js/bootstrap.min.js"></script> -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
    <!-- sweet alert js -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <!-- Select 2 js -->
    <script src="../files/bower_components/select2/js/select2.full.min.js"></script>
    <script src="js/distribute_branch.js"></script>
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>
    <script>
        $(document).ready(function() {
            $('.js-example-basic-single').select2();
        });

        $(".datepicker").datepicker({dateFormat: 'dd/mm/yy'});
    </script>
</body>
</html>