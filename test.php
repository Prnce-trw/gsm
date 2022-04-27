<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <table border="1" cellspacing="1" cellpadding="1">
        <?php 
            $brand = ['PTT','WP','Siam','Unit','PT','Other'];
            $package = [4,7,8,11.5,13.5,15,48];
            //for(0)
        ?>
        <tr>
            <td width="80" height="30"></td>
            <?php
                //for(1)
                for ($i=0; $i < count($package); $i++) { 
            ?>
            <td width="80" height="30"><?=$package[$i]?></td>
            <?php
                }// end for(1)
            ?>
        </tr>
        <?php
            for ($x=0; $x < count($brand); $x++) {  
        ?>
        <tr>
            <td><?=$brand[$x]?></td>
            <?php //for(1) 
            for ($i=0; $i < count($package); $i++) { ?>
            <td width="80" height="30"><select></select></td>
            <?php }// end for(1) ?>
        </tr>

        <?php
        }//end for(0)
    ?>
    </table>
</body>

</html>