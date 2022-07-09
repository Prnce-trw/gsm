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
        var size_r = size.toString().replace(/\./g, ""); // remove dot
        var $input = $(this).parent().find('input');
        var currentVal = parseInt($input.val());
        if (!isNaN(currentVal)) {
            $('#resultWeite_'+brand+'_'+size_r).text(currentVal*size);
        } else {
            $('#resultWeite_'+brand+'_'+size_r).text(0);
        }
        
    });
});

function calTotal() {
    alert()
}

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
    $("#total").text(sum);
}); 

$(document).on('change', '.pickitem', function () {
    var brand = $(this).data('brand');
    var size = $(this).attr('data-size');
    var size_r = size.replace(/\./g, ""); // remove dot
    var amount = $(this).val();
    var appendItem = $('#'+brand+'_'+size).attr('data-info');
    var cytype = $(this).attr('data-Cytype');
    // alert(size_r +'\n'+ jQuery.type(parseInt(size_r)));
    if (appendItem == null) {
        $('#result_inputItem').append('<input type="text" name="pickitem[]" id="'+brand+'_'+size_r+'" data-info="'+brand+'_'+size_r+'"  value="'+brand+'/'+size+'/'+amount+'/'+cytype+'">');
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

$('select').change(function() {
    $("#total").text(getAllSum());
});

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
    })
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
            if ($('#total').text() != "0" && $('#gas_filling').val() != null) {
                Swal.fire({
                    icon: 'success',
                    text: 'บันทึกข้อมูลสำเร็จ',
                    showConfirmButton: false,
                    timer: 1500
                });
                $('#FormPreOrderCylinder').submit();
            } else {
                Swal.fire({
                    icon: 'warning',
                    text: AlertText(1),
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

function AlertText(param) {
    return 'test';
}

function addAdvance(modal_id) {
    $.ajax({
        type: "GET",
        url: "../modal/mocal_edit.php",
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
    var size_r = size.replace(/\./g, ""); // remove dot
    var amount = $(this).val();
    var appendItem = $('#'+brand+'_'+size_r).attr('data-info');
    var cytype = $(this).attr('data-Cytype');
    if (appendItem == null) {
        $('#result_editinputItem').append('<input type="text" name="pickitem[]" id="'+brand+'_'+size_r+'" data-info="'+brand+'_'+size_r+'"  value="'+brand+'/'+size+'/'+amount+'/'+cytype+'">');
        $('#edit_PO tr:last').after('<tr id="tdAppend_'+brand+'_'+size_r+'">'+
        '<td></td>'+
        '<td style="text-align: left;">'+brand+'/ ขนาด '+size+' กก.</td>'+
        '<td><div id="edit_amount'+brand+'_'+size_r+'">'+amount+'</div></td>'+
        '<td><div id="edit_weight'+brand+'_'+size_r+'">'+size * amount+'</td>'+
        '<td></td>'+
        '<td>'+
            '<div class="number">'+
                '<span class="minus">-</span>'+
                '<input class="input_amount" type="number" value="0" id="input_amount_" data-brand="">'+
                '<span class="plus">+</span>'+
            '</div>'+
        '</td>'+
        '<td></td>'+
        '</tr>');
    } else {
        if (amount != 0) {
            $('#'+brand+'_'+size_r).val(brand+'/'+size+'/'+amount+'/'+cytype);
            $('#edit_amount'+brand+'_'+size_r).text(amount);
            $('#edit_weight'+brand+'_'+size_r).text(size * amount);
        } else {
            $('#'+brand+'_'+size_r).remove();
            $('#tdAppend_'+brand+'_'+size_r).remove();
        }
    }
});

$(document).on('change', '.pickitem_pr', function () {
    var brand = $(this).data('brand');
    var size = $(this).attr('data-size');
    var size_r = size.replace(/\./g, ""); // remove dot
    var amount = $(this).val();
    var appendItem = $('#'+brand+'_'+size).attr('data-info');
    var cytype = $(this).attr('data-Cytype');
    $("#total_"+cytype).text(getAllSum());
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
    var size_r = size.toString().replace(/\./g, ""); // remove dot
    var currentVal = parseInt($input.val());
    var cur_total = $('#totalitem_PR').text();
    // alert(cur_total + '\n' + currentVal);
    if (!isNaN(currentVal)) {
        $('#resultWeite_'+brand+'_'+size_r).text(currentVal*size);
        $('#totalitem_PR').text(parseInt(currentVal)+parseInt(cur_total));
    } else {
        $('#resultWeite_'+brand+'_'+size_r).text(0);
        $('#totalitem_PR').text(getAllSum());
    }
});