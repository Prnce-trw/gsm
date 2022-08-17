$(document).ready(function () {
    $('.js-example-basic-single').select2({
        placeholder: "เลือกโรงบรรจุ...",
    });

    $('.PRitemOut').each(function() {
        var itemOut = $(this).data('info');
        var Dataitem = itemOut.split('_');
        var qty = $(this).val().split('/');
        var qtyIn = $('#qtyIn_'+Dataitem[0]+'_'+Dataitem[1]).val();
        $('.itemOut_'+Dataitem[0]+'_'+Dataitem[1]).text(qtyIn);
        $('#itemCy_'+Dataitem[0]+'_'+Dataitem[1]).val(qty[2]).change();
        // console.log(qty);
    });
});

$(document).on('input', '.adjustItemPO', function () {
    var qty         = $(this).val();
    var brand       = $(this).data('brand');
    var wightsize   = $(this).data('wightsize');
    var sizeid      = $(this).data('sizeid');
    var size        = $(this).data('size');
    var branch      = "BRC1-1";
    $.ajax({
        type: "POST",
        url: "../controller/POController.php",
        data: {parameter: "aJaxCheckStock", weight: wightsize, brand: brand, amount: qty, branch: branch},
        dataType: "JSON",
        success: function (response) {
            if (parseFloat(qty) > parseFloat(response['qty_balance'])) {
                console.log(response['qty_balance']);
                $(this).val(0);
                alert('ขออภัย! สินค้าภายในคลังคงเหลือ : '+response['qty_balance']+' ถัง');
            }
        }
    });
    // console.log(qty, brand, wightsize, sizeid, size);
})
