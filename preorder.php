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
                    $row = array();
                    while ($row = mysqli_fetch_row($results)) { 
                        $rows[] = $row;
                    }
                    foreach ($rows as $key => $item)
                    {
                    ?>
                    <tr>
                        <td><?php echo $key+1; ?></td>
                        <td><?php echo $item[$key+2]; ?></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </section>
    <script src="js/custom.js"></script>
    <script src="js/jquery-3.6.0.min.js"></script>
</body>
</html>