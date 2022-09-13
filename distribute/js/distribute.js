$(document).ready(function () {
    $(document).on('input', '.DisItemQty', function () {
        var qty = $(this).val();
        var branchid = $(this).data('info');
        var branch_up = $('#brandchup_'+branchid).val();
        var result = qty * branch_up;
        $('#branchamount_'+branchid).val(result);

        var sum = 0;
        $('.DisItemQty').each(function() {
            if(isNaN($(this).val()) || $(this).val() === "") {
                sum+=0;
            } else {
                sum+=parseFloat($(this).val());
            }
        });
        $(".totalItem").text(sum);
        $(".totalItem").val(sum);
    });
});

$(document).on('click', '#vat', function (e) { 
    var price = $('#price').val();
    var vat_percentage = $('#vat_percentage').val();
    if (this.checked) {
        $('#input_vat').attr("readonly", false);
        $('#vat_percentage').attr("disabled", false);
        var calVat = price * vat_percentage / 100;
        var vatdec = parseFloat(calVat).toFixed(2);
        var result = parseFloat(price) + parseFloat(vatdec);
        $('#input_vat').val(vatdec);
        $('#totalPrice').val(parseFloat(result).toFixed(2));
    } else {
        $('#input_vat').attr("readonly", true);
        $('#vat_percentage').attr("disabled", true);
        $('#input_vat').val(0);
        $('#totalPrice').val(price);
    }
});

$(document).on('change', '#vat_percentage', function () {
    var vat_percentage = $(this).val();
    var price = $('#price').val();

    var calVat = price * vat_percentage / 100;
    var vatdec = parseFloat(calVat).toFixed(2);
    var result = parseFloat(price) + parseFloat(vatdec);
    $('#input_vat').val(vatdec);
    $('#totalPrice').val(parseFloat(result).toFixed(2));
})

var num = 0;
$(document).on('click', '.selectBranch', function () {
    var branchID = $(this).val();
    var BranchName = $(this).data('branchname');
    var unitprice = $('#resultitemup').val();
    if (this.checked) {
        $('#branchSelected').before('<tr id="trBranch'+branchID+'" class="trBranch">'+
            '<td class="text-middle">'+BranchName+'<input type="hidden" name="disitembranchid['+num+']" value="'+branchID+'"></td>'+
            '<td class="text-middle"><input type="number" name="disitemqty['+num+']" class="form-control text-center DisItemQty" data-info="'+branchID+'" id="branchqty_'+branchID+'" min="0"></td>'+
            '<td class="text-middle"><input type="number" name="disitemunitprice['+num+']" class="form-control text-center DisitemUP" data-info="'+branchID+'" id="brandchup_'+branchID+'" value="'+unitprice+'" min="0"></td>'+
            '<td class="text-middle"><input type="number" name="disitemamount['+num+']" class="form-control text-center" id="branchamount_'+branchID+'" min="0"></td>'+
            '</tr>'
        );
        num++;
    } else {
        $('#trBranch'+branchID).remove();
    }
});

$(document).on('click', '.radioItem', function () {
    var n_id = $(this).val();
    // var $radios = $(this.checked);
    if (this.checked == true) {
        $('#unitprice_'+n_id).attr("disabled", false);
        $('#qty_'+n_id).attr("disabled", false);
        $('#amount_'+n_id).attr("disabled", false);
        $('#btnDistribute_'+n_id).attr("disabled", false);
    } else {
        $('#unitprice_'+n_id).attr("disabled", true);
        $('#qty_'+n_id).attr("disabled", true);
        $('#amount_'+n_id).attr("disabled", true);
        $('#btnDistribute_'+n_id).attr("disabled", true);
    }
});

// $(document).on('input', '.ItemAmount', function () {
//     var currAmount = $(this).val();
//     var InvAmount = $('#amount').val();
//     if (parseInt(currAmount) > parseInt(InvAmount)) {
//         Swal.fire({
//             icon: 'warning',
//             title: 'จำนวนที่ต้องการกระจาย มีจำนวนมากกว่าจำนวนจริง!',
//         });
//         $(this).val(0);
//     } else if (InvAmount == 0 || InvAmount == null) {
//         Swal.fire({
//             icon: 'warning',
//             title: 'กรุณากรอกจำนวนทั้งหมดก่อน!',
//         });
//         $(this).val(0);
//     }
// });

function distributeItem (n_id) {
    var amount = $('#qty_'+n_id).val();
    var itemcode = $('#itemcode_'+n_id).text();
    var unitprice = $('#unitprice_'+n_id).val();
    $('#itemid').val(n_id);
    $('#resultitemamount').val(amount);
    $('#resultitemname').val(itemcode);
    $('#resultitemup').val(unitprice);
}

function disCloseModal() {
    $('.trBranch').each(function() {
        $(this).remove();
    });
    $('.selectBranch').each(function () {
        this.checked = false;
    });
    $('.totalItem').text('');
}

$(document).on('click', '.btnsubmit', function () {
    var totalItem = $('.totalItem').val();
    var itemAmount = $('#resultitemamount').val();
    var countinput = 0;
    $('.DisItemQty').each(function () {
        if ($(this).val() == '' || $(this).val() == 0) {
            countinput++;
        }
    });
    if (countinput == 0) {
        if (parseFloat(totalItem) > parseFloat(itemAmount)) {
            Swal.fire({
                icon: 'error',
                title: 'ไม่สามารถบันทึกได้!',
                html: "จำนวนทั้งหมด <b class='text-danger'>น้อยกว่า</b> จำนวนรวม", 
            });
        } else if (itemAmount == null || totalItem == 0 || totalItem == null) {
            Swal.fire({
                icon: 'warning',
                title: 'กรอกจำนวนที่ต้องการกระจาย!',
            });
        } else if (parseFloat(totalItem) < parseFloat(itemAmount)) {
            Swal.fire({
                title: 'คุณแน่ใจหรือไม่?',
                html: "จำนวนรวม <b class='text-danger'>น้อยกว่า</b> จำนวนทั้งหมด อุปกรณ์ที่เหลือจะถูกบันทึกเป็น OutStanding", 
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'ยืนยัน',
                cancelButtonText: 'ยกเลิก',
            }).then((result) => {
                if (result.isConfirmed) {
                    $('#FormAccDistribute').submit();
                }
            });
        } else if (parseFloat(totalItem) == parseFloat(itemAmount)) {
            Swal.fire({
                title: 'คุณแน่ใจหรือไม่?',
                title: 'ตรวจสอบความถูกต้องก่อนยืนยัน!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'ยืนยัน',
                cancelButtonText: 'ยกเลิก',
            }).then((result) => {
                if (result.isConfirmed) {
                    $('#FormAccDistribute').submit();
                }
            });
        }
    } else {
        Swal.fire({
            icon: 'warning',
            title: 'กรอกจำนวนที่ต้องการกระจาย!',
        });
    }
});

$('#btnassets').click(function () {
    var assetID = $('#assetid').val();
    if (assetID) {
        $.ajax({
            type: "POST",
            url: "../controller/DistributeController.php",
            data: {parameter: "SearchAssets", assetID: assetID},
            dataType: "JSON",
            success: function (response) {
                $('#ItemListRow').empty();
                // console.log(response);
                if (response.length > 0) {
                    for (let index = 0; index < response.length; index++) {
                        $('#ItemListRow').append('<tr>'+
                            '<td class="text-center text-middle"><input type="checkbox" name="selectitem" class="selectitemacc"'+
                            'style="width: 20px; height: 20px;" value="'+response[index]['n_id']+'" id="inputcheck_'+response[index]['n_id']+'" data-itemname="'+response[index]['itemsName']+'" data-itemcode="'+response[index]['itemsCode']+'"></td>'+
                            '<td class="text-middle"><span id="itemcode_'+response[index]['n_id']+'">'+response[index]['itemsCode']+'</span></td>'+
                            '<td class="text-middle">'+response[index]['itemsName']+'</td>'+
                        '</tr>'
                        );
                    }
                }
            }
        });
    } else {
        Swal.fire({
            icon: 'warning',
            title: 'กรุณากรอกรหัสอุปกรณ์!',
        });
    }
});

function btnsubmitDis() {
    // Swal.fire({
    //     icon: 'warning',
    //     title: 'คุณต้องการกระจายอุปกรณ์ด้วยหรือไม่?',
    //     showDenyButton: true,
    //     showCancelButton: true,
    //     confirmButtonText: 'บันทึกและกระจาย',
    //     denyButtonText: `บันทึก`,
    //     cancelButtonText: `ยกเลิก`,
    //     confirmButtonColor: '#28a745',
    //     denyButtonColor: '#007bff',
    //     cancelButtonColor: '#d33',
    // }).then((result) => {
    //     if (result.isConfirmed) {
    //         $('#Distribute').submit();
    //         var date_received = $('#date_received').val();
    //         var refNo = $('#refNo').val();
    //         if (!date_received) {
    //             $('#date_received').addClass(' bg-warning');
    //         } 
    //         if (!refNo) {
    //             $('#refNo').addClass(' bg-warning');
                
    //         }
    //     } else if (result.isDenied) {
    //         $('#Distribute').submit();
    //     } else if (result.isCancel) {
    //       $('#Distribute').submit();
    //     }
        $('#Distribute').submit();
    // });
}

$(document).on('input', '#price', function () {
    var price = $(this).val();
    $('#totalPrice').val(price);
});

function selectHeadDis(dis_id) {
    $.ajax({
        type: "POST",
        url: "../controller/DistributeController.php",
        data: {parameter: "selectHeadDis", dis_id: dis_id},
        dataType: "JSON",
        success: function (response) {
            var rawdate = response['datahead']['dis_date_received'].split("-");
            var date_received = rawdate[2]+'/'+rawdate[1]+'/'+rawdate[0];
            $('#date_received').val(date_received);
            $('#refNo').val(response['datahead']['dis_refNo']);
            $('#docNo').val(response['datahead']['dis_docNo']);
            // $('#amount').val(response['dis_amount']);
            $('#price').val(response['datahead']['dis_price']);
            $('#input_vat').val(response['datahead']['dis_vat']);
            $('#totalPrice').val(response['datahead']['dis_totalPrice']);
            $('#assetsRow').empty();
            $('#assetID').val(response['datahead']['itemsCode']);
            $('#headdocid').val(response['datahead']['dis_docNo']);
            if (response['datahead']['dis_vat'] == 'Y') {
                $('#vat').prop('checked', true);
                $('#vat_percentage').val(7).change();
            } else {
                $('#vat').prop('checked', false);
                $('#vat_percentage').val(0).change();
            }
            $('#btnselectacc').attr("disabled", true);
            $('#btn_distributeheadanddetail').attr("disabled", true);
            if (response['dataacc'].length > 0) {
                for (let index = 0; index < response['dataacc'].length; index++) {
                    $('#assetsRow').append('<tr>'+
                        '<td class="text-center text-middle"></td>'+
                        '<td class="text-middle"><span id="itemcode_'+response['dataacc'][index]['disout_itemID']+'">'+response['dataacc'][index]['itemsCode']+'</span></td>'+
                        '<td class="text-middle">'+response['dataacc'][index]['itemsName']+'</td>'+
                        '<td class="text-center text-middle"><input type="number" name="qty" id="qty_'+response['dataacc'][index]['disout_itemID']+'" value="'+response['dataacc'][index]['disout_bal']+'" class="form-control text-center ItemAmount" style="width: 80px;" min="0" ></td>'+
                        '<td class="text-center text-middle"><input type="number" style="width: 110px;" name="unitprice" id="unitprice_'+response['dataacc'][index]['disout_itemID']+'" value="'+parseFloat(response['dataacc'][index]['disout_unitPrice']).toFixed(2)+'" class="form-control text-center" style="width: 80px;" min="0" ></td>'+
                        '<td class="text-center text-middle"><input type="number" style="width: 110px;" name="amountitem" id="amount_'+response['dataacc'][index]['disout_itemID']+'" value="'+parseFloat(response['dataacc'][index]['disout_amount']).toFixed(2)+'" class="form-control text-center" style="width: 80px;" min="0" ></td>'+
                        '<td class="text-center text-middle"><button type="button" class="btn btn-secondary btn-sm" data-toggle="modal" data-target="#distributeItem" id="btnDistribute_'+response['dataacc'][index]['disout_itemID']+'"'+
                        'onclick="distributeItem('+response['dataacc'][index]['disout_itemID']+')" data-itemcode="'+response['dataacc'][index]['itemsCode']+'" ><i class="icofont icofont-rounded-double-right"></i></button>'+
                        '</tr>'
                    );
                }
            }
        }
    });
}

$('#btnreload').click(function () {
    location.reload();
});

var index = 0;
$(document).on('click', '.selectitemacc', function () {
    var itemID = $(this).val();
    var itemname = $(this).data('itemname');
    var itemcode = $(this).data('itemcode');
    var curritem = $('#selectitemAcc_'+itemID).val();
    if (curritem == null) {
        $('#assetsRow').append('<tr id="itemAccRows_'+itemID+'">'+
        '<td class="text-center text-middle"><button type="button" onclick="delitemAcc('+itemID+')" class="btn btn-danger btn-sm"><i class="icofont icofont-trash"></i></button></td>'+
        '<td class="text-middle"><span id="itemcode_'+itemID+'">'+itemcode+'</span><input type="hidden" name="selectItem['+index+']" id="selectitemAcc_'+itemID+'" value="'+itemID+'"></td>'+
        '<td class="text-middle">'+itemname+'</td>'+
        '<td class="text-center text-middle"><input type="number" name="qty['+index+']" id="qty_'+itemID+'" value="" data-info="'+itemID+'" class="form-control text-center itemqty" style="width: 80px;" min="0"></td>'+
        '<td class="text-center text-middle"><input type="number" style="width: 110px;" name="unitprice['+index+']" id="unitprice_'+itemID+'" data-info="'+itemID+'" class="form-control text-center itemup" style="width: 80px;" min="0" step="any"></td>'+
        '<td class="text-center text-middle"><input type="number" style="width: 110px;" name="totalitemprice['+index+']" id="amount_'+itemID+'" class="form-control text-center itemamount" style="width: 80px;" min="0" step="any"></td>'+
        '<td class="text-center text-middle"></td>'+
        '</tr>'
        );
        index++;
    } else {
        $('#itemAccRows_'+itemID).remove();
    }
});

function delitemAcc(id) {
    $('#itemAccRows_'+id).remove();
    $('#inputcheck_'+id).prop('checked', false);
}

$(document).on('input', '.itemqty', function () {
    var item_info = $(this).data('info');
    var itemqty = $('#unitprice_'+item_info).val();
    var currqty = $(this).val();
    var sum = 0;
    $('.itemqty').each(function() {
        if(isNaN($(this).val()) || $(this).val() === "") {
            sum+=0;
        } else {
            sum+=parseFloat($(this).val());
        }
    });
    $("#total_qty").text(sum);
    $("#amount_"+item_info).val(parseFloat(currqty) * parseFloat(itemqty));
});

$(document).on('input', '.itemup', function () {
    var item_info = $(this).data('info');
    var itemqty = $('#qty_'+item_info).val();
    var currup = $(this).val();
    var sum = 0;
    $('.itemup').each(function() {
        if(isNaN($(this).val()) || $(this).val() === "") {
            sum+=0;
        } else {
            sum+=parseFloat($(this).val());
        }
    });
    // console.log(parseFloat(itemqty) * parseFloat(sum));
    $("#total_up").text(sum);
    $("#amount_"+item_info).val(parseFloat(itemqty) * parseFloat(currup));
});

$(document).on('input', '.DisitemUP', function () {
    var currup = $(this).val();
    var branchid = $(this).data('info');
    var currqty = $('#branchqty_'+branchid).val();
    var sum = parseFloat(currup) * parseFloat(currqty);
    $('#branchamount_'+branchid).val(parseFloat(sum).toFixed(2));
});

function rejectitem(itemid) {
    $.ajax({
        type: "POST",
        url: "../controller/DistributeController.php",
        data: {parameter: "RejectItem", itemid: itemid},
        dataType: "JSON",
        success: function (response) {
            console.log(response);
            if (response == 'Success') {
                $('#badge_status_'+itemid).html('<span class="badge badge-danger badge-sm">Reject</span>');
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'ขออภัยเกิดข้อผิดพลาด กรุณาตรวจสอบใหม่อีกครั้ง',
                });
            }
        }
    });
}

function accbranchinfo(itemid) {
    $.ajax({
        type: "GET",
        url: "../controller/DistributeController.php",
        data: {parameter: "AccBranchInfo", itemid: itemid},
        dataType: "HTML",
        success: function (response) {
            console.log(response);
        }
    });
}