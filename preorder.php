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
    <section>
        <h4>จัดซื้อ</h4>
        <div class="row">
            <table>
                <thead>
                    <tr style="background-color: #e8e8e8;">
                        <th>ลำดับ</th>
                        <th>หัวข้อ</th>
                        <th>หมายเลขเอกสาร</th>
                        <th>วันที่ / เวลา</th>
                        <th>สถานะ</th>
                        <th>การจัดการ</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $sql = "SELECT * FROM tb_head_preorder WHERE head_pre_status NOT IN ('Deleted')";
                    $results = mysqli_query($conn, $sql);
                    foreach ($results as $key => $value) : { ?>
                    <tr id="<?php echo $value['head_pre_docnumber']; ?>">
                        <td style="height: 40px;"><?php echo $key+1; ?></td>
                        <td></td>
                        <td>
                            <a href="" title="พิมพ์เอกสาร"><?php echo $value['head_pre_docnumber']; ?></a>
                        </td>
                        <td><?php echo date("d-m-Y / H:i", strtotime($value['created_at'])); ?></td>
                        <td><?php echo $value['head_pre_status']; ?></td>
                        <td>
                            <a class="btn btn-warning" href="editpo.php?POid='<?php echo $value['head_pre_docnumber']; ?>'" title="แก้ไขเอกสาร">แก้ไข</a>
                            <button class="btn btn-danger" onclick="delPO('<?php echo $value['head_pre_docnumber']; ?>')" title="ลบเอกสาร">ยกเลิก</button>
                        </td>
                    </tr>
                    <?php } endforeach ?>
                </tbody>
            </table>
        </div>
    </section>
    <script src="js/jquery-3.6.0.min.js"></script>
    <script src="js/sweetalert2.all.min.js"></script>
    <script src="js/custom.js"></script>
    <script>
        function delPO(id) {
            Swal.fire({
                title: 'คุณแน่ใจหรือไม่?',
                text: "เอกสาร PO จะไม่สามารถกูข้อมูลกลับคืนมาได้!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'ยืนยัน',
                cancelButtonText: 'ยกเลิก'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        type: "POST",
                        url: "helper.php",
                        data: {id:id, parameter:"delPreOrder"},
                        dataType: "JSON",
                        success: function (response) {
                            if (response == 'success') {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'สำเร็จ',
                                    timer: 3000,
                                });
                                $('#'+id).hide('slow', function(){ $target.remove(); });
                            };
                        }
                    });
                }
            })
        }
    </script>
</body>
</html>