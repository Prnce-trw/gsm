function numberWithCommas(number) {
    var parts = number.toString().split(".");
    parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    return parts.join(".");
}

$(document).ready(function() {
    $('.js-example-basic-single').select2({
        placeholder: "เลือกโรงบรรจุ...",
    });

    $(".datepicker").datepicker({dateFormat: 'dd/mm/yy'});

    $(function() {
        //----- OPEN
        $('[data-modal-open]').on('click', function(e)  {
            var modal_id = $(this).attr('data-modal');
            $.ajax({
                type: "GET",
                url: "modal/modal_po.php",
                data: {modal_id: modal_id},
                dataType: "HTML",
                success: function (response) {
                    let isExist = $('#resultModal').find('div#temp_'+modal_id).length;
                    if(isExist > 0) {
                        $('#temp_'+modal_id+" #add_data_Modal").css("display", "block");
                    } else {
                        $('#resultModal').append("<div id='temp_"+modal_id+"'>"+" </div>")
                        $('#temp_'+modal_id).html(response);
                        $('#temp_'+modal_id+" #add_data_Modal").css("display", "block");
                    }
                }
            });
        });
    });

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
                $input.val(amount_now);
            }
        } else {
            $input.val(0);
        }
    });

    $(document).on('click', '.changeAmount', function (e) {
        e.preventDefault();
        var brand = $(this).parent().data('brand');
        var size = $(this).parent().data('size');
        var cytype = $(this).parent().attr('data-Cytype');
        var size_r = size.toString().replace(/\./g, ""); // remove dot
        var $input = $(this).parent().find('input');
        var currentVal = parseInt($input.val());
        if (!isNaN(currentVal)) {
            $('#resultWeite_'+brand+'_'+size_r).text(currentVal*size);
            $('#'+brand+'_'+size_r+'_'+cytype).val(brand+'/'+size+'/'+currentVal+'/'+cytype);
        } else {
            $('#resultWeite_'+brand+'_'+size_r).text(0);
            $('#'+brand+brand+'_'+size_r+'_'+cytype).remove();
        }
        
    });

    var getWeight = 0;
    $('.itemweight').each(function() {
        if(isNaN($(this).text()) || $(this).text() === "") {
            getWeight+=0;
        } else {
            getWeight+=parseFloat($(this).text());
        }
    });
    $(".totalWeight").text(getWeight);
});

$(document).on('input', '.pickitem_advance', function () {
    var brand = $(this).data('id');
    var amount = $(this).val();
    var cylinder_size = $(this).attr('data-cylindersize');
    var cylinder_size_r = cylinder_size.replace(/\./g, ""); // remove dot
    var appendcylinder = $('#'+brand+'_'+cylinder_size_r).attr('data-appendcylinder');
    $("#total").text(getAllSum());
    if (appendcylinder == null && appendcylinder != 0) {
        $('#result_adv_'+brand).append('<input type="hidden" id="'+brand+'_'+cylinder_size_r+'" class="appendcylinder '+brand+'_'+cylinder_size_r+'" data-appendcylinder="'+brand+'_'+cylinder_size_r+'" value="'+amount+'">');
        $('#result_adv_'+brand).append('<span id="appendtext'+brand+'_'+cylinder_size_r+'">'+cylinder_size+' kg. [<span class="'+brand+'_'+cylinder_size_r+'">'+amount+'</span>] </br></span>');
    } else {
        $('.'+brand+'_'+cylinder_size_r).val(amount);
        if (amount != 0) {
            $('.'+brand+'_'+cylinder_size_r).text(amount);
        } else {
            $('#appendtext'+brand+'_'+cylinder_size_r).remove();
            $('.'+brand+'_'+cylinder_size_r).remove();
        }
    }
})

$(document).on('click','.modal-close', function() {
    let modal_id = $(this).data('id')
    $('#temp_'+modal_id+" #add_data_Modal").css("display", "none");
})

function modal_close(modal_id) {
    // console.log($(this).data('id'));
    $('#temp_'+modal_id+" #add_data_Modal").css("display", "none");
}

$(document).on('change', 'select', function(){
    var sum = 0;
    $('.pickitem').each(function() {
        sum += parseInt($(this).val());
    });
    
    $(".total").text(sum);
    $(".total").val(sum);
}); 

$(document).on('change', '.pickitem_add_PO', function () {
    var sum = 0;
    $('.pickitem_add_PO').each(function () {
        sum += parseInt($(this).val());
    });
    $("#totalitem_PR").text(sum);
    $(".totalPR").val(sum);
})

$(document).on('change', '.pickitem', function () {
    var brand = $(this).data('brand');
    var size = $(this).attr('data-size');
    var cytype = $(this).attr('data-Cytype');
    // var size_r = size.replace(/\./g, ""); // remove dot
    var amount = $(this).val();
    var appendItem = $('#'+brand+'_'+size+'_'+cytype).attr('data-info');
    $.ajax({
        type: "POST",
        url: "controller.php",
        data: {parameter: "aJaxCheckSize", size: size, brand: brand, amount: amount, cytype: cytype},
        dataType: "JSON",
        success: function (response) {
            var size = parseFloat(response['resultSize']).toFixed(1);
            $(".sumWeight_"+response['resultOrderBy']).text(sumTotalWeight(size, response['resultOrderBy']));
            var getWeight = 0;
            $('.getSumWeight').each(function() {
                if(isNaN($(this).text()) || $(this).text() === "") {
                    getWeight+=0;
                } else {
                    getWeight+=parseFloat($(this).text());
                }
            });
            $(".totalWeight").text(getWeight);
            $(".totalWeight").val(getWeight);
            if (appendItem == null) {
                $('#result_inputItem').append('<input type="hidden" name="pickitem[]" id="'+brand+'_'+response['resultOrderBy']+'_'+cytype+'" data-info="'+brand+'_'+response['resultOrderBy']+'"  value="'+brand+'/'+response['resultSize']+'/'+amount+'/'+cytype+'">');
                if (cytype == 'Adv') {
                    $('#result_adv_'+brand).append('<span id="appendtext'+brand+'_'+response['resultOrderBy']+'">'+response['resultSize']+' Kg. [<span class="'+brand+'_'+response['resultOrderBy']+'">'+amount+'</span>] </br></span>');
                }
            } else {
                if (amount != 0) {
                    $('#'+brand+'_'+response['resultOrderBy']+'_'+cytype).val(brand+'/'+response['resultSize']+'/'+amount+'/'+cytype);
                    if (cytype == 'Adv') {
                        $('#appendtext'+brand+'_'+response['resultOrderBy']).html(response['resultSize'] + ' Kg. [<span class="'+brand+'_'+response['resultOrderBy']+'">'+amount+'</span>]');
                    }
                } else {
                    $('#'+brand+'_'+response['resultOrderBy']+'_'+cytype).remove();
                    if (cytype == 'Adv') {
                        $('#appendtext'+brand+'_'+response['resultOrderBy']).remove();
                    }
                }
            }
        }
    });
});

$('select').change(function() {
    $("#total").text(getAllSum());
});

function sumTotalWeight(weight, weight_id) {
    var $input = $('.weightSize_'+weight_id).val();
    var curr_total = parseFloat($('.sumWeight_'+weight_id).text());
    var result = 0;
    var size = parseFloat(weight).toFixed(1);
    $('.weightSize_'+weight_id).each(function() {
        if(isNaN($(this).val()) || $(this).val() === "") {
            result+=0;
        } else {
            result+=parseInt($(this).val());
        }
    });
    return result*weight;
}

function getAllSum() {
    var result = 0;
    $('.pickitem :selected').each(function() {
        result += parseInt($(this).val());
    });

    $('.pickitem_pr :selected').each(function() {
        result += parseInt($(this).val());
    });
    $(".pickitem_advance").each(function() {
        if(isNaN($(this).val()) || $(this).val() === "") {
            result+=0;
        } else {
            result+=parseInt($(this).val());
        }
    });
    $(".itemAmountOut").each(function() {
        if(isNaN($(this).val()) || $(this).val() === "") {
            result+=0;
        } else {
            result+=parseInt($(this).val());
        }
    });
    return result;
}

$(document).on('change', '.itemgroup', function(){
    var size = $(this).data('sizenumber');
    var Cytype = $(this).attr('data-Cytype');
    $("#total_"+size+'_'+Cytype).text(getAllSumSize(size));
});

function getAllSumSize(size) {
    var result = 0;
    $('.itemSize_'+size+' :selected').each(function() {
        result += parseInt($(this).val());
    });
    return result
}

function cylinder_adcance(id) {
    if ($('#advance_'+id).is(":checked")) {
        $('.input_advance_'+id).show(300);
    } else {
        $('.input_advance_'+id).hide(300);
    }
}

function btn_submit_preselect () {
    Swal.fire({
        title: 'คุณแน่ใจหรือไม่?',
        text: "เอกสาร PO จะไม่สามารถแก้ไขได้! กรุณาตรวจสอบความถูกต้องก่อนยืนยัน",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'ยืนยัน',
        cancelButtonText: 'ยกเลิก'
    }).then((result) => {
        if (result.isConfirmed) {
            if ($('.total').text() != "0") {
                Swal.fire({
                    icon: 'success',
                    text: 'บันทึกข้อมูลสำเร็จ',
                    showConfirmButton: false,
                    timer: 1500
                });
                $('#POStatus').val('Confirm');
                $('#FormPreOrderCylinder').submit();
            } else {
                Swal.fire({
                    icon: 'warning',
                    text: "กรุณากรอกข้อมูลให้ครบ",
                    showConfirmButton: false,
                    timer: 1500
                })
            }
        }
    })
}

function btn_submit_draft_preselect() {
    Swal.fire({
        title: 'คุณแน่ใจหรือไม่?',
        text: "บันทึกเอกสารแบบฉบับร่าง",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'ยืนยัน',
        cancelButtonText: 'ยกเลิก'
    }).then((result) => {
        if (result.isConfirmed) {
            if ($('.total').text() != "0") {
                Swal.fire({
                    icon: 'success',
                    text: 'บันทึกข้อมูลสำเร็จ',
                    showConfirmButton: false,
                    timer: 1500
                });
                $('#POStatus').val('Draft');
                $('#FormPreOrderCylinder').submit();
            } else {
                Swal.fire({
                    icon: 'warning',
                    text: "กรุณากรอกข้อมูลให้ครบ",
                    showConfirmButton: false,
                    timer: 1500
                })
            }
        }
    })
}

function btn_delete (id) {
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
            $.ajax({
                type: "POST",
                url: "controller.php",
                data: {POID: id, parameter: "deletePO"},
                dataType: "JSON",
                success: function (response) {
                    if (response == 'Y') {
                        $('#POID_'+id).remove();
                        Swal.fire({
                            icon: 'success',
                            text: 'บันทึกข้อมูลสำเร็จ',
                            showConfirmButton: false,
                            timer: 1500
                        });
                    }
                }
            });
        }
    })
}

function addAdvance(modal_id) {
    $.ajax({
        type: "GET",
        url: "modal/modal_edit.php",
        data: {modal_id: modal_id},
        dataType: "HTML",
        success: function (response) {
            let isExist = $('#resultModal').find('div#temp_'+modal_id).length;
            if(isExist > 0) {
                $('#temp_'+modal_id+" #add_data_Modal").css("display", "block");
            } else {
                $('#resultModal').append("<div id='temp_"+modal_id+"'>"+" </div>")
                $('#temp_'+modal_id).html(response);
                $('#temp_'+modal_id+" #add_data_Modal").css("display", "block");
            }
        }
    });
}

function openModal() {
    $('#add_data_Modal').css("display", "block");
    
    $('.PRitemOut').each(function() {
        var itemOut = $(this).data('info');
        var Dataitem = itemOut.split('_');
        var qty = $(this).val().split('/');
        var qtyIn = $('#qtyIn_'+Dataitem[0]+'_'+Dataitem[1]).text();
        $('.itemOut_'+Dataitem[0]+'_'+Dataitem[1]).text(qtyIn);
        $('#itemCy_'+Dataitem[0]+'_'+Dataitem[1]).val(qty[2]).change();
        // $('#itemCy_'+Dataitem[0]+'_'+Dataitem[1]).prop('disabled', true);
    });
}

function modalPR_close() {
    $('#add_data_Modal').css("display", "none");
    $('#EdititemEnModal').css("display", "none");
}

$(document).on('change', '.pickitem_add_PO', function () {
    var brand = $(this).data('brand');
    var sizeID = $(this).attr('data-sizeid');
    var size = $(this).attr('data-size');
    var wightSize = $(this).attr('data-wightSize');
    var amount = $(this).val();
    var appendItem = $('#'+brand+'_'+sizeID).attr('data-info');
    var cytype = $(this).attr('data-Cytype');
    // console.log(size);
    if (appendItem == null) {
        $('#result_editinputItem').append('<input type="hidden" name="pickitem[]" id="'+brand+'_'+sizeID+'" data-info="'+brand+'_'+sizeID+'"  value="'+brand+'/'+size+'/'+amount+'">');
        $('#edit_PO tr:last').after('<tr id="tdAppend_'+brand+'_'+sizeID+'">'+
        '<td></td>'+
        '<td style="text-align: left;">'+brand+'/ ขนาด '+size+' กก.</td>'+
        '<td></td>'+
        '<td>'+
            '<div class="itemAmountrecent_'+brand+'_'+sizeID+'">'+amount+'</div>'+
            '<input type="hidden" name="itemEntrance[]" id="itemAmountrecent_'+brand+'_'+sizeID+'" value="'+amount+'">'+
        '</td>'+
        '<td><div class="itemweight" id="result_weight'+brand+'_'+sizeID+'">'+wightSize * amount+'</td>'+
        '<td><input type="number" name="" id="itemperprice_'+brand+'_'+sizeID+'" class="form-control itemperprice" style="width: 80px;" data-brand="'+brand+'" data-sizeid="'+sizeID+'" step="any"></td>'+
        '<td><input type="number" name="" id="resultPrice_'+brand+'_'+sizeID+'" class="form-control AmountPrice" style="width: 80px;" step="any"></td>'+
        '</tr>');
    } else {
        if (amount != 0) {
            $('#'+brand+'_'+sizeID).val(brand+'/'+size+'/'+amount);
            $('.itemAmountrecent_'+brand+'_'+sizeID).text(amount);
            $('#itemAmountrecent_'+brand+'_'+sizeID).val(amount);
            $('#result_weight'+brand+'_'+sizeID).text(wightSize * amount);
        } else {
            $('#'+brand+'_'+sizeID).remove();
            $('#tdAppend_'+brand+'_'+sizeID).remove();
        }
    }

    var getWeight = 0;
    $('.itemweight').each(function() {
        if(isNaN($(this).text()) || $(this).text() === "") {
            getWeight+=0;
        } else {
            getWeight+=parseFloat($(this).text());
        }
    });
    $(".totalWeight").text(getWeight);
});

$(document).on('change', '.pickitem_edititemEn', function () {
    alert()
});

function openEditModal() {
    $('#EdititemEnModal').css("display", "block");
}

$(document).on('change', '.pickitem_pr', function () {
    var brand = $(this).data('brand');
    var size = $(this).attr('data-size');
    var size_r = size.replace(/\./g, ""); // remove dot
    var amount = $(this).val();
    var appendItem = $('#'+brand+'_'+size).attr('data-info');
    var cytype = $(this).attr('data-Cytype');
    $("#total_"+cytype).text(getAllSum());
    $('.total').val(getAllSum());
    if (appendItem == null) {
        $('#result_inputItemPR').append('<input type="text" name="pickitem[]" id="'+brand+'_'+size_r+'" data-info="'+brand+'_'+size_r+'"  value="'+brand+'/'+size+'/'+amount+'/'+cytype+'">');
        if (cytype == 'Adv') {
            $('#result_adv_'+brand).append('<span id="appendtext'+brand+'_'+size_r+'">'+size+' Kg. [<span class="'+brand+'_'+size_r+'">'+amount+'</span>] </br></span>');
        }
    } else {
        if (amount != 0) {
            $('#'+brand+'_'+size_r).val(brand+'/'+size+'/'+amount+'/'+cytype);
            if (cytype == 'Adv') {
                $('#appendtext'+brand+'_'+size_r).html(size + ' Kg. [<span class="'+brand+'_'+size_r+'">'+amount+'</span>]');
            }
        } else {
            $('#'+brand+'_'+size_r).remove();
            if (cytype == 'Adv') {
                $('#appendtext'+brand+'_'+size_r).remove();
            }
        }
    }
});

$(document).on('input', '.itemAmountOut', function () {
    var brand = $(this).parent().data('brand');
    var size = $(this).parent().data('size');
    var $input = $(this).parent().find('input');
    var cytype = $(this).parent().attr('data-Cytype');
    var size_r = size.toString().replace(/\./g, ""); // remove dot
    var currentVal = parseInt($input.val());
    var cur_total = $('#totalitem_PR').text();
    // alert(cur_total + '\n' + currentVal);
    if (!isNaN(currentVal)) {
        $('#resultWeite_'+brand+'_'+size_r).text(currentVal*size);
        $('#totalitem_PR').text(parseInt(currentVal)+parseInt(cur_total));
        $('#'+brand+'_'+size_r+'_'+cytype).val(brand+'/'+size+'/'+currentVal+'/'+cytype);
    } else {
        $('#resultWeite_'+brand+'_'+size_r).text(0);
        $('#totalitem_PR').text(getAllSum());
    }
});

function btnCanclePO() {
    $('#POStatus').val('Cancel');
}

function btnConfirmPO() {
    $('#POStatus').val('Confirm');
}

$(document).on('click', '.priceHistory', function () {
    console.log($(this).data('branch'), $(this).data('sizeID'));
});

$(document).on('input', '.itemperprice', SumTotalPrice);
$(document).on('input', '.AmountPrice', SumTotalPrice);

function SumTotalPrice() {
    var brand = $(this).data('brand');
    var sizeID = $(this).data('sizeid');
    var price = $(this).val();
    
    var curritemEn = $('#itemAmountrecent_'+brand+'_'+sizeID).val();
    if (!isNaN(curritemEn)) {
        $('#resultPrice_'+brand+'_'+sizeID).val(parseFloat(price * curritemEn).toFixed(2));
    }

    var AmountPrice = 0;
    $('.AmountPrice').each(function () {
        if (isNaN($(this).val()) || $(this).val() === "") {
            AmountPrice+=0;
        } else {
            AmountPrice+=parseFloat($(this).val());
        }
    });
    $('#TotalPrice').text(numberWithCommas(parseFloat(AmountPrice).toFixed(2)));
}