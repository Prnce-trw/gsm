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
    alert($(this).val());
})
