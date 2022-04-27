jQuery(document).ready(function(){
    $(document).on('click', '.plus', function (e) {
        e.preventDefault();
        var $input = $(this).parent().find('input');
        var currentVal = parseInt($input.val());
        if (!isNaN(currentVal)) {
            $input.val(currentVal + 1)
        } else {
            $input.val(1);
        }
    });
    $(document).on('click', '.minus', function (e) {
        e.preventDefault();
        var $input = $(this).parent().find('input');
        var currentVal = parseInt($input.val());
        if (!isNaN(currentVal) && currentVal > 1) {
            $input.val(currentVal - 1)
            var amount_now = currentVal -1;
            if (amount_now != 0) {
                SumAmount(-1);
            }
        } else {
            $input.val(1);
        }
    });
});

function add_cylinder (brand_id) {
    var amount = $('#input_amount_'+brand_id).val();
    var type = $('#cylinder_type_'+brand_id).val();
    var size = $('#cylinder_size_'+brand_id).val();
    var parameter = "addPurchase";
    // alert(amount +'\n' + type +'\n' + size + '\n' + jQuery.type(parseInt(amount)));
    SumAmount(amount);
    $.ajax({
        type: "POST",
        url: "helper.php",
        data: {parameter:parameter, amount:amount, brand_id:brand_id, type:type},
        dataType: "JSON",
        success: function (response) {
            $('#resultTable').append(response);
        }
    });
}

function minus() {
    var $input = $(this).parent().find('input');
    var count = parseInt($input.val()) - 1;
    counts = count < 1 ? 1 : count;
    $input.val(counts);
    $input.change();
    return false;
}

function plus() {
    var $input = $(this).parent().find('input');
    $input.val(parseInt($input.val()) + 1);
    $input.change();
    return false;
}

function btn_del_preselect (id) {
    var amount = $('#input_preamount_'+id).val();
    $('#preselect_id_'+id).remove();
    $('#tr_preselect_'+id).remove();
    SumAmount(-amount);
}

function SumAmount (amount) {
    var total = $('#resultSum').val();
    var result = parseInt(total) + parseInt(amount);
    if (!result || result <= 0) {
        $('#resultSum').val(0);
        $('#totalSum').text(0);
    } else {
        $('#resultSum').val(result);
        $('#totalSum').text(result);
    }
}