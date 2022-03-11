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
                    <tr>
                        <th>ลำดับ</th>
                        <th>หัวข้อ</th>
                        <th>หมายเลขเอกสาร</th>
                        <th>วันที่/เวลา</th>
                        <th>สถานะ</th>
                        <th>การจัดการ</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $sql = "SELECT * FROM tb_head_preorder";
                    $results = mysqli_query($conn, $sql);
                    foreach ($results as $key => $value) : { ?>
                    <tr>
                        <td><?php echo $key+1; ?></td>
                        <td></td>
                        <td><?php echo $value['head_pre_docnumber']; ?></td>
                        <td><?php echo date("d/m/Y", strtotime($value['created_at'])); ?></td>
                        <td><?php echo $value['head_pre_status']; ?></td>
                        <td></td>
                    </tr>
                    <?php } endforeach ?>
                </tbody>
            </table>
        </div>
    </section>
    <script src="js/custom.js"></script>
    <script src="js/jquery-3.6.0.min.js"></script>
</body>
</html>