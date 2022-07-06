<?php 
$brand = ['PTT','WP','Siam','Unit','PT','Other'];
$package = [4,7,8,11.5,13.5,15,48];
?>
<div id="add_data_Modal" class="modal" data-modal="add_data_modal">
    <div class="modal-inner">
        <div class="modal-content">
            <div class="modal-header">
                <input type="hidden" name="" id="" class="modal_brand" value="<?php echo $_GET['modal_id'];?>">
                <a class="modal-close" data-modal-close="add_data_modal" data-id="<?php echo $_GET['modal_id'];?>" onclick="modal_close()" href="#">x</a>
            </div>
            <div class="modal-body">
                <table>
                    <thead>
                        <tr>
                            <th width="80" height="30">
                                ยี่ห้อ\ขนาด
                            </th>
                            <?php
                                //for(1)
                                for ($i=0; $i < count($package); $i++) { 
                            ?>
                            <th width="80" height="30"><?=$package[$i]?></th>
                            <?php }// end for(1) ?>
                        </tr>
                    </thead>
                    <tbody>
                    <?php for ($x=0; $x < count($brand); $x++) { ?>
                        <tr>
                            <td width="80" height="50" >
                                <div class="div-inside"><?=$brand[$x]?></div>
                            </td>
                            <?php //for(1) 
                                for ($i=0; $i < count($package); $i++) { ?>
                                <td width="80" height="50">
                                    <div class="div-inside">
                                        <select name="" class="pickitem_edit_PO" id="" data-brand="<?=$brand[$x]?>" data-size="<?=$package[$i]?>" data-Cytype="N">
                                            <?php for ($n=0; $n <=20 ; $n++) { ?>
                                                <option value="<?php echo $n; ?>" <?php echo $n == 0 ? 'selected':'' ?>><?php echo $n; ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </td>
                            <?php }// end for(1) ?>
                        </tr>           
                        <?php }//end for(0) ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>