<?php $package = [4,7,8,11.5,13.5,15,48];?>
<div id="add_data_Modal" class="modal" data-modal="add_data_modal">
    <div class="modal-inner">
        <div class="modal-content">
            <div class="modal-header">
                <input type="hidden" name="" id="temp_" class="modal_brand" value="<?php echo $_GET['modal_id'];?>">
                <a class="modal-close" data-modal-close="add_data_modal" data-id="<?php echo $_GET['modal_id'];?>" onclick="modal_close()" href="#">x</a>
            </div>
            <div class="modal-body">
                <table>
                    <tbody>
                        <tr>
                            <td rowspan="2">ขนาด</td>
                            <?php for ($i=0; $i < count($package); $i++) { ?>
                                <td><?=$package[$i]?></td>
                            <?php } ?>
                        </tr>
                        <tr>
                            <?php for ($i=0; $i < count($package); $i++) { ?>
                            <td>
                                <select name="" class="pickitem" id="" data-Cytype="Adv" data-brand="<?=$_GET['modal_id'];?>" data-size="<?=$package[$i]?>">
                                    <?php for ($n=0; $n <=20 ; $n++) { ?>
                                        <option value="<?php echo $n; ?>" <?php echo $n == 0 ? 'selected':'' ?>><?php echo $n; ?></option>
                                    <?php } ?>
                                </select>
                            </td>
                            <?php } ?>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>