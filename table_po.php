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
                    <tr>
                        <td height="40">1</td>
                        <td>ใบส่งแก๊ส</td>
                        <td>1</td>
                        <td>กำลังจัดส่ง</td>
                        <td>05/05/2022</td>
                        <td>
                            <div class="row">
                                <div class="col-25">
                                    <button type="button">ดูข้อมูล</button>
                                </div>
                                <div class="col-25">
                                    <button type="button">แก้ไข</button>
                                </div>
                                <div class="col-25">
                                    <button type="button">พิมพ์</button>
                                </div>
                                <div class="col-25">
                                    <button type="button" onClick="btn_delete()">ลบ</button>
                                </div>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </section>

    <script src="js/jquery-3.6.0.min.js"></script>
    <script src="js/sweetalert2.all.min.js"></script>
    <script src="js/select2.min.js"></script>
    <script>
        function btn_delete () {
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
                }
            })
        }
    </script>
</body>
</html>