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
        $('#itemCy_'+Dataitem[0]+'_'+Dataitem[1]+'_'+qty[3]).val(qty[2]).change();
    });

    $('.totalweight').each(function () {
        var sum = 0;
        $('.totalweight').each(function() {
            if(isNaN($(this).text()) || $(this).text() === "") {
                sum+=0;
            } else {
                sum+=parseFloat($(this).text());
            }
        });
        $(".resultWeight").text(parseFloat(sum).toFixed(2));
    });
});



$(document).on('input', '.adjustItemPO', function () {
    var qty         = $(this).val();
    var brand       = $(this).data('brand');
    var wightsize   = $(this).data('wightsize');
    var sizeid      = $(this).data('sizeid');
    var size        = $(this).data('size');
    var cytype      = $(this).data('cytype');
    var branch      = "BRC1-1";
    var tritem      = $('#'+brand+'_'+sizeid+'_'+cytype).data('info');
    console.log(brand, sizeid, cytype, tritem);
    $('#divedited').css("display", "block");
    $('#edited').val('edited');
    $('#btnsubmit').attr('disabled', true);
    $.ajax({
        type: "POST",
        url: "../controller/POController.php",
        data: {parameter: "aJaxCheckStock", weight: wightsize, brand: brand, amount: qty, branch: branch},
        dataType: "JSON",
        success: function (response) {
            if (parseFloat(qty) > parseFloat(response['qty_balance'])) {
                $('#itemCy_'+brand+'_'+sizeid+'_'+cytype).val(0);
                $('#trItem_'+brand+'_'+sizeid+'_'+cytype).remove();
                alert('ขออภัย! สินค้าภายในคลังคงเหลือ : '+response['qty_balance']+' ถัง');
            } else {
                // var currweight = 0;
                // $('.totalweight').each(function() {
                //     if(isNaN($(this).text()) || $(this).text() === "") {
                //         currweight+=0;
                //     } else {
                //         currweight+=parseFloat($(this).text());
                //     }
                // });
                // $(".resultWeight").text(parseFloat(currweight).toFixed(2));
                
                if (tritem == null && qty > 0) {
                    var wordType = "";
                    if (cytype == 'N') {
                        wordType = 'น้ำแก๊ส';
                    } else {
                        wordType = 'ถังฝากเติม';
                    }
                    $('#itemlist').after('<tr id="trItem_'+brand+'_'+sizeid+'_'+cytype+'">'+
                        '<td class="text-middle"><input type="text" name="Add_pickitem[]" data-info="'+brand+'_'+sizeid+'_'+cytype+'" id="'+brand+'_'+sizeid+'_'+cytype+'" value="'+brand+'/'+size+'/'+qty+'/'+cytype+'/'+sizeid+'"></td>'+
                        '<td class="text-left text-middle">'+brand+' / ขนาด '+size+' กก.</td>'+
                        '<td class="text-center text-middle">'+wordType+'</td>'+
                        '<td class="text-center text-middle"><span id="qtyIn_'+brand+'_'+sizeid+'_'+cytype+'">'+qty+'</span></td>'+
                        '<td class="text-center text-middle"><span class="totalweight" id="weightIn_'+brand+'_'+sizeid+'_'+cytype+'">'+parseFloat(qty) * parseFloat(wightsize).toFixed(2)+'</span></td>'+
                    '</tr>'
                    );
                } else {
                    if (qty != 0) {
                        $('#'+brand+'_'+sizeid+'_'+cytype).val(brand+'/'+size+'/'+qty+'/'+cytype+'/'+sizeid);
                        $('#qtyIn_'+brand+'_'+sizeid+'_'+cytype).text(qty);
                        $('#weightIn_'+brand+'_'+sizeid+'_'+cytype).text(parseFloat(qty) * parseFloat(wightsize).toFixed(2));
                    } else {
                        $('#trItem_'+brand+'_'+sizeid+'_'+cytype).remove();
                    }
                }

            }
        }
    });
    // console.log(qty, brand, wightsize, sizeid, size);
})

function btnCanclePO() {
    $('#POStatus').val('Cancel');
}

function btnDraftPO() {
    $('#POStatus').val('Draft');
}

function btnConfirmPO() {
    Swal.fire({
        title: 'คุณแน่ใจหรือไม่?',
        text: "เอกสารจะไม่แก้ไขได้ในภายหลัก กรุณาตรวจสอบก่อนยืนยัน!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'ยืนยัน',
        cancelButtonText: 'ยกเลิก'
      }).then((result) => {
        if (result.isConfirmed) {
            $('#POStatus').val('Confirm');
            $('#FormDraftPO').submit();
        }
      })
    
}
