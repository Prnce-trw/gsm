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
                url: "../modal/modal_po.php",
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
        $('#result_adv_'+brand).append('<span id="appendtext'+brand+'_'+cylinder_size_r+'">'+cylinder_size+' kg. [<span class="'+brand+'_'+cylinder_size_r+'">'+amount+'</span>] <br></span>');
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

$('select').change(function(){
    var sum = 0;
    $('.pickitem').each(function() {
        sum += parseInt($(this).val());
    });
    $("#total").text(sum);
});

$('.pickitem').change(function () {
    var brand = $(this).data('brand');
    var size = $(this).data('size');
    var amount = $(this).val();
    var appendItem = $('#'+brand+'_'+size).attr('data-info');
    if (appendItem == null) {
        $('#result_inputItem').append('<input type="hidden" name="pickitem[]" id="'+brand+'_'+size+'" data-info="'+brand+'_'+size+'"  value="'+brand+'/'+size+'/'+amount+'">');
    } else {
        if (amount != 0) {
            $('#'+brand+'_'+size).val(amount);
        } else {
            $('#'+brand+'_'+size).remove();
        }
    }
})

$('select').change(function(){
    $("#total").text(getAllSum());
});

function getAllSum() {
    var result = 0;
    $('.pickitem :selected').each(function() {
        result += parseInt($(this).val());
    });
    $(".pickitem_advance").each(function() {
        if(isNaN($(this).val()) || $(this).val() === "") {
            result+=0;
        } else {
            result+=parseInt($(this).val());
        }
    })
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
            if ($('#total').text() != "0") {
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
                    text: 'กรูณากรอกตรวจสอบข้อมูลก่อนยืนยัน',
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