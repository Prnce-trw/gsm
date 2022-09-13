$(document).ready(function () {
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

    $(document).on('input', '.pickitem', function(){
        var sum = 0;
        $('.pickitem').each(function() {
            if(isNaN($(this).val()) || $(this).val() === "") {
                sum+=0;
            } else {
                sum+=parseFloat($(this).val());
            }
        });
        $(".total").text(sum);
        $(".total").val(sum);
    });
});

$(document).on('click','.modal-close', function() {
    let modal_id = $(this).data('id')
    $('#temp_'+modal_id+" #add_data_Modal").css("display", "none");
});

function modal_close(modal_id) {
    $('#temp_'+modal_id+" #add_data_Modal").css("display", "none");
}

$(document).on('input', '.pickitem', function () {
    var brand = $(this).data('brand');
    var sizeID = $(this).attr('data-sizeid');
    var cytype = $(this).attr('data-Cytype');
    var weight = $(this).data('weight');
    var branch = "BRC01-2";
    var amount = $(this).val();
    var appendItem = $('#'+brand+'_'+sizeID+'_'+cytype).attr('data-info');
    console.log(brand, sizeID, cytype, weight, amount);
    $.ajax({
        type: "POST",
        url: "controller/POController.php",
        data: {parameter: "aJaxCheckStock", weight: weight, brand: brand, amount: amount, branch: branch, sizeID: sizeID},
        dataType: "JSON",
        success: function (response) {
            if (parseFloat(amount) > parseFloat(response['qty_balance'])) {
                $('#input_'+brand+'_'+sizeID+'_'+cytype).val(0).change();
                alert('ขออภัย! สินค้าภายในคลังคงเหลือ : '+response['qty_balance']+' ถัง');
                $('#'+brand+'_'+sizeID+'_'+cytype).remove();
                if (cytype == 'Adv') {
                    $('#appendtext'+brand+'_'+sizeID).remove();
                }
                $(".sumWeight_"+sizeID).text(sumTotalWeight(weight, sizeID));
            } else {
                $(".sumWeight_"+sizeID).text(sumTotalWeight(weight, sizeID));
                var getWeight = 0;
                $('.getSumWeight').each(function() {
                    if(isNaN($(this).text()) || $(this).text() === "") {
                        getWeight+=0;
                    } else {
                        getWeight+=parseFloat($(this).text());
                    }
                });
                $(".totalWeight").text(parseFloat(getWeight).toFixed(2));
                $(".totalWeight").val(parseFloat(getWeight).toFixed(2));
                if (appendItem == null && amount > 0) {
                    $('#result_inputItem').append('<input type="hidden" name="pickitem[]" id="'+brand+'_'+sizeID+'_'+cytype+'" data-info="'+brand+'_'+sizeID+'"  value="'+brand+'/'+weight+'/'+amount+'/'+cytype+'/'+sizeID+'">');
                    if (cytype == 'Adv') {
                        $('#result_adv_'+brand).append('<span id="appendtext'+brand+'_'+sizeID+'">'+weight+' Kg. [<span class="'+brand+'_'+sizeID+'">'+amount+'</span>] </br></span>');
                    }
                } else {
                    if (amount != 0) {
                        $('#'+brand+'_'+sizeID+'_'+cytype).val(brand+'/'+weight+'/'+amount+'/'+cytype+'/'+sizeID);
                        if (cytype == 'Adv') {
                            $('#appendtext'+brand+'_'+sizeID).html(weight + ' Kg. [<span class="'+brand+'_'+sizeID+'">'+amount+'</span>]');
                        }
                    } else {
                        $('#'+brand+'_'+sizeID+'_'+cytype).remove();
                        if (cytype == 'Adv') {
                            $('#appendtext'+brand+'_'+sizeID).remove();
                        }
                    }
                }
            }
        }
    });
});

function sumTotalWeight(weight, weight_id) {
    var result = 0;
    $('.weightSize_'+weight_id).each(function() {
        if(isNaN($(this).val()) || $(this).val() === "") {
            result+=0;
        } else {
            result+=parseFloat($(this).val());
        }
    });
    // console.log(weight);
    return parseFloat(result*weight).toFixed(2);
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
                    text: "กรุณาระบุข้อมูลถัง",
                    showConfirmButton: false,
                    timer: 1500
                })
            }
        }
    })
}