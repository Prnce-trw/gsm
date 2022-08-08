<?php 
    include_once('../conn.php');
    include('../function.php');

    if ($_POST['parameter'] == 'PreOrderReCeipt') {
        try {
            $insertdata     = new DB_con();
            $RefDO          = $_POST['RefDO'];
            $POID           = $_POST['POID'];
            $Fillstation    = $_POST['gas_filling'];
            $Round          = $_POST['round'];
            $timeIn         = $_POST['hourIn'].":".$_POST['minuteIn'].":00";
            $timeOut        = $_POST['hourOut'].":".$_POST['minuteOut'].":00";
            $sql            = $insertdata->insertPOReceipt($POID, $RefDO, $timeIn, $timeOut, $Fillstation);
            $sqlPO          = $insertdata->updateHeadPO($POID, $Fillstation, $Round);

            foreach ($_POST['pickitem'] as $key => $value) {
                $item               = explode('/', $value);
                // var_dump($_POST['priceUnit'][1], $_POST['itemPerPrice'][1], $item[3]);
                // exit(0);
                $itemType           = 'N';
                $unitprice          = $_POST['unitprice'][$key];
                $amtprice           = $_POST['amtprice'][$key];
                $sqlItem            = $insertdata->insertItemEntrance($POID, $RefDO, $item[0], $item[1], $item[2], $unitprice, $amtprice);

                $cerrent_year   = date("Y");
                $Total          = $_POST['total'];
                $datan_id       = $insertdata->CurmovementIn($cerrent_year, $RefDO, $Total, $item[0], $item[1], $item[2], $amtprice);
            }

            echo "<script>alert('Success')</script>";
            echo "<script>window.location.href='../table_po.php'</script>";
        } catch (\Throwable $th) {
            echo "<script>alert('Failed: "+$th->getMessage()+"')</script>";
            echo "<script>window.location.href='../table_po.php'</script>";
        }
    }