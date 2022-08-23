$(document).ready(function () {
    $(document).on('input', '.DisItemAmount', function () {
        var resultitemamount = $('#resultitemamount').val();
        var currtotal = $('.totalItem').text();
        // console.log(parseFloat(currtotal) > resultitemamount);
        var sum = 0;
        $('.DisItemAmount').each(function() {
            if(isNaN($(this).val()) || $(this).val() === "") {
                sum+=0;
            } else {
                sum+=parseFloat($(this).val());
            }
        });
        $(".totalItem").text(sum);
    });
});

$(document).on('click', '#vat', function (e) { 
    var price = $('#price').val();
    if (this.checked) {
        $('#input_vat').attr("readonly", false);
        var calVat = price * 7 / 100;
        var vatdec = parseFloat(calVat).toFixed(2);
        var result = parseFloat(price) + parseFloat(vatdec);
        $('#input_vat').val(vatdec);
        $('#totalPrice').val(parseFloat(result).toFixed(2));
    } else {
        $('#input_vat').attr("readonly", true);
        $('#input_vat').val(0);
        $('#totalPrice').val(price);
    }
});

$(document).on('click', '.selectBranch', function () {
    var branchID = $(this).val();
    var BranchName = $(this).data('branchname');
    if (this.checked) {
        $('#branchSelected').before('<tr id="trBranch'+branchID+'" class="trBranch">'+
            '<td class="text-middle">'+BranchName+'</td>'+
            '<td class="text-middle"><input type="number" class="form-control text-center DisItemAmount" min="0"></td>'+
            '<td class="text-middle"><input type="number" class="form-control text-center" min="0"></td>'+
            '<td class="text-middle"><input type="number" class="form-control text-center" min="0"></td>'+
            '</tr>'
        );
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

$(document).on('input', '.ItemAmount', function () {
    var currAmount = $(this).val();
    var InvAmount = $('#amount').val();
    if (parseInt(currAmount) > parseInt(InvAmount)) {
        Swal.fire({
            icon: 'warning',
            title: 'จำนวนที่ต้องการกระจาย มีจำนวนมากกว่าจำนวนจริง!',
        });
        $(this).val(0);
    } else if (InvAmount == 0 || InvAmount == null) {
        Swal.fire({
            icon: 'warning',
            title: 'กรุณากรอกจำนวนทั้งหมดก่อน!',
        });
        $(this).val(0);
    }
});

function distributeItem (n_id) {
    var amount = $('#qty_'+n_id).val();
    var itemcode = $('#itemcode_'+n_id).text();
    $('#resultitemamount').val(amount);
    $('#resultitemname').val(itemcode);
    
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
    var totalItem = $('.totalItem').text();
    var itemAmount =$('#resultitemamount').val();
    if (parseFloat(totalItem) > parseFloat(itemAmount)) {
        Swal.fire({
            icon: 'error',
            title: 'ไม่สามารถบันทึกได้!',
            html: "จำนวนทั้งหมด <b class='text-danger'>น้อยกว่า</b> จำนวนรวม", 
        })
    } else if (itemAmount == "") {
        Swal.fire({
            icon: 'warning',
            title: 'กรอกจำนวนที่ต้องการกระจาย!',
        });
    }
});

$('#btnassets').click(function () {
    var assetID = $('#assetID').val();
    if (assetID) {
        $.ajax({
            type: "POST",
            url: "../controller/DistributeController.php",
            data: {parameter: "SearchAssets", assetID: assetID},
            dataType: "JSON",
            success: function (response) {
                $('#assetsRow').empty();
                $('#assetsRow').append('<tr>'+
                    '<td class="text-center text-middle"><input type="checkbox" name="selectItem" id="'+response['n_id']+'" class="radioItem" style="width: 20px; height: 20px;" value="'+response['n_id']+'"></td>'+
                    '<td class="text-middle"><span id="itemcode_'+response['n_id']+'">'+response['itemsCode']+'</span></td>'+
                    '<td class="text-middle">'+response['itemsName']+'</td>'+
                    '<td class="text-center text-middle"><input type="number" name="qty" id="qty_'+response['n_id']+'" class="form-control text-center ItemAmount" style="width: 80px;" min="0" disabled></td>'+
                    '<td class="text-center text-middle"><input type="number" name="unitprice" id="unitprice_'+response['n_id']+'" class="form-control text-center" style="width: 80px;" min="0" disabled></td>'+
                    '<td class="text-center text-middle"><input type="number" name="amountitem" id="amount_'+response['n_id']+'" class="form-control text-center" style="width: 80px;" min="0" disabled></td>'+
                    '<td class="text-center text-middle"><button type="button" class="btn btn-secondary btn-sm" data-toggle="modal" data-target="#distributeItem" id="btnDistribute_'+response['n_id']+'"'+
                    'onclick="distributeItem('+response['n_id']+')" data-itemcode="'+response['itemsCode']+'" disabled><i class="icofont icofont-rounded-double-right"></i></button>'+
                '</tr>'
            );
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
    $('#Distribute').submit();
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
            var rawdate = response['dis_date_received'].split("-");
            var date_received = rawdate[2]+'/'+rawdate[1]+'/'+rawdate[0];
            console.log(response);
            $('#date_received').val(date_received);
            $('#refNo').val(response['dis_refNo']);
            $('#docNo').val(response['dis_docNo']);
            $('#amount').val(response['dis_amount']);
            $('#price').val(response['dis_price']);
            $('#input_vat').val(response['dis_vat']);
            $('#totalPrice').val(response['dis_totalPrice']);
            $('#assetsRow').empty();
            $('#assetID').val(response['itemsCode']);
            $('#assetsRow').append('<tr>'+
                    '<td class="text-center text-middle"><input type="checkbox" name="selectItem" id="'+response['n_id']+'" class="radioItem" style="width: 20px; height: 20px;" value="'+response['n_id']+'"></td>'+
                    '<td class="text-middle"><span id="itemcode_'+response['n_id']+'">'+response['itemsCode']+'</span></td>'+
                    '<td class="text-middle">'+response['itemsName']+'</td>'+
                    '<td class="text-center text-middle"><input type="number" name="qty" id="qty_'+response['n_id']+'" value="'+response['disout_qty']+'" class="form-control text-center ItemAmount" style="width: 80px;" min="0" disabled></td>'+
                    '<td class="text-center text-middle"><input type="number" style="width: 110px;" name="unitprice" id="unitprice_'+response['n_id']+'" value="'+parseFloat(response['disout_unitPrice']).toFixed(2)+'" class="form-control text-center" style="width: 80px;" min="0" disabled></td>'+
                    '<td class="text-center text-middle"><input type="number" style="width: 110px;" name="amountitem" id="amount_'+response['n_id']+'" value="'+parseFloat(response['disout_amount']).toFixed(2)+'" class="form-control text-center" style="width: 80px;" min="0" disabled></td>'+
                    '<td class="text-center text-middle"><button type="button" class="btn btn-secondary btn-sm" data-toggle="modal" data-target="#distributeItem" id="btnDistribute_'+response['n_id']+'"'+
                    'onclick="distributeItem('+response['n_id']+')" data-itemcode="'+response['itemsCode']+'" disabled><i class="icofont icofont-rounded-double-right"></i></button>'+
                '</tr>'
            );
        }
    });
}

$('#btnreload').click(function () {
    location.reload();
})