$(document).ready(function() {
    $('.js-example-basic-single').select2({
        placeholder: "เลือกโรงบรรจุ...",
    });

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
    console.log($(this).data('id'));
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

$(document).on('change', '.pickitem', function () {
    console.log('123');
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
                    getWeight+=parseInt($(this).text());
                }
            });
            $(".totalWeight").text(getWeight);
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

$(document).on('change', '.pickitem_edit_PO', function () {
    var brand = $(this).data('brand');
    var size = $(this).attr('data-size');
    var amount = $(this).val();
    var appendItem = $('#'+brand+'_'+size).attr('data-info');
    var cytype = $(this).attr('data-Cytype');

    $.ajax({
        type: "POST",
        url: "controller/CustomController.php",
        data: {parameter: 'aJaxModalCheckSize', size: size},
        dataType: "json",
        success: function (response) {
            if (appendItem == null) {
                $('#result_editinputItem').append('<input type="text" name="pickitem[]" id="'+brand+'_'+response['resultOrderBy']+'" data-info="'+brand+'_'+response['resultOrderBy']+'"  value="'+brand+'/'+response['resultSize']+'/'+amount+'/'+cytype+'">');
                $('#edit_PO tr:last').after('<tr id="tdAppend_'+brand+'_'+response['resultOrderBy']+'">'+
                '<td></td>'+
                '<td style="text-align: left;">'+brand+'/ ขนาด '+response['resultSize']+' กก.</td>'+
                '<td><div id="edit_amount'+brand+'_'+response['resultOrderBy']+'">'+amount+'</div></td>'+
                '<td><div id="edit_weight'+brand+'_'+response['resultOrderBy']+'">'+response['resultSize'] * amount+'</td>'+
                '<td><input type="number" name="" id="" class="form-control" style="width: 80px;"></td>'+
                '<td>'+
                    '<div class="number">'+
                        '<span class="minus">-</span>'+
                        '<input class="input_amount" type="number" value="0" id="input_amount_" data-brand="">'+
                        '<span class="plus">+</span>'+
                    '</div>'+
                '</td>'+
                '<td><input type="number" name="" id="" class="form-control" style="width: 80px;"></td>'+
                '</tr>');
            } else {
                if (amount != 0) {
                    $('#'+brand+'_'+response['resultOrderBy']).val(brand+'/'+response['resultSize']+'/'+amount+'/'+cytype);
                    $('#edit_amount'+brand+'_'+response['resultOrderBy']).text(amount);
                    $('#edit_weight'+brand+'_'+response['resultOrderBy']).text(response['resultSize'] * amount);
                } else {
                    $('#'+brand+'_'+response['resultOrderBy']).remove();
                    $('#tdAppend_'+brand+'_'+response['resultOrderBy']).remove();
                }
            }
        }
    });
});

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

$(document).on('input', '.AmountPrice', function () {
    var amountprince = $(this).val();
    var brand = $(this).data('brand');
    var size = $(this).data('size');
    console.log(brand, size);
});