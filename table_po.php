<!DOCTYPE html>
<html lang="en">
<head>
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
            <div class="function_bar">555</div>
            <table border="1" cellspacing="1" cellpadding="1">
                <thead>
                    <tr>
                        <th width="50" height="40">ลำดับ</th>
                        <th>หัวข้อ</th>
                        <th>รอบ</th>
                        <th>สถานะ</th>
                        <th width="80">เวลาการจัดส่ง</th>
                        <th width="350">การจัดการ</th>
                    </tr>
                </thead>
                <tbody>

                    <?php 
                    include_once('conn.php');
                    $fetchdata = new DB_con();
                    $sql = $fetchdata->fetchdata();
                    $index = 1;
                    while ($row = mysqli_fetch_array($sql)) { ?>
                    <tr id="POID_<?=$row['head_po_id']?>">
                        <td height="40"><?php echo $index?></td>
                        <td><?=$row['head_po_docnumber']?></td>
                        <td>1</td>
                        <td>กำลังจัดส่ง</td>
                        <td>05/05/2022</td>
                        <td>
                            <div class="row">
                                <div class="col-25">
                                    <a href="info/info_po.php?id=<?=$row['head_po_id']?>">ดูข้อมูล</a>
                                </div>
                                <div class="col-25">
                                    <a href="edit/edit_po.php?id=<?=$row['head_po_id']?>">แก้ไข</a>
                                </div>
                                <div class="col-25">
                                    <button type="button">พิมพ์</button>
                                </div>
                                <div class="col-25">
                                    <button type="button" onClick="btn_delete(<?=$row['head_po_id']?>)">ลบ</button>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <?php $index++; } ?>
                </tbody>
            </table>
        </div>
    </section>

    <script src="js/jquery-3.6.0.min.js"></script>
    <script src="js/sweetalert2.all.min.js"></script>
    <script src="js/select2.min.js"></script>
    <script>
        function btn_delete (id) {
            Swal.fire({
                title: 'คุณแน่ใจหรือไม่?',
                text: "เอกสาร PO จะไม่สามารถกู้คืนได้!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'ยืนยัน',
                cancelButtonText: 'ยกเลิก'
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        icon: 'success',
                        text: 'บันทึกข้อมูลสำเร็จ',
                        showConfirmButton: false,
                        timer: 1500
                    })
                    $('#POID_'+id).remove();
                }
            })
        }
    </script>
</body>
</html>