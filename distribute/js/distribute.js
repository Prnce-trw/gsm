$(document).ready(function () {
    $(document).on('input', '.DisItemAmount', function () {
        var resultitemamount = $('#resultitemamount').val();
        var currtotal = $('.totalItem').text();
        console.log(parseFloat(currtotal) > resultitemamount);
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
    if (this.checked) {
        $('#input_vat').attr("readonly", false); ;
    } else {
        $('#input_vat').attr("readonly", true); ;
    }
});

$(document).on('click', '.selectBranch', function () {
    var branchID = $(this).val();
    var BranchName = $(this).data('branchname');
    if (this.checked) {
        $('#branchSelected').before('<tr id="trBranch'+branchID+'" class="trBranch">'+
            '<td class="text-middle">'+BranchName+'</td>'+
            '<td><input type="number" class="form-control text-center DisItemAmount" min="0"></td>'+
            '</tr>'
        );
    } else {
        $('#trBranch'+branchID).remove();
    }
});

$(document).on('click', '.radioItem', function () {
    var n_id = $(this).val();
    var $radios = $(this.checked);
    if ($radios.is(':checked') == false) {
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
})

function distributeItem (n_id) {
    var amount = $('#amount_'+n_id).val();
    $('#resultitemamount').val(amount);
}

function disCloseModal() {
    $('.trBranch').each(function() {
        $(this).remove();
    });
    $('.selectBranch').each(function () {
        this.checked = false;
    })
}

$(document).on('click', '.btnsubmit', function () {
    var totalItem = $('.totalItem').text();
    var itemAmount =$('#resultitemamount').val();
    if (totalItem > itemAmount) {
        Swal.fire({
            icon: 'error',
            title: 'ไม่สามารถบันทึกได้!',
            html: "จำนวนทั้งหมด <b class='text-danger'>น้อยกว่า</b> จำนวนรวม", 
        })
    }
});

$('#btnassets').click(function () {
    var assetID = $('#assetID').val();
    if (assetID) {
        $.ajax({
            type: "POST",
            url: "../controller/DistributeController.php",
            data: {parameter: "SearchAssets", assetID: assetID, assetName: assetName},
            dataType: "JSON",
            success: function (response) {
                $('#assetsRow').empty();
                $('#assetsRow').append('<tr>'+
                    '<td class="text-center text-middle"><input type="radio" name="selectItem" id="'+response['n_id']+'" class="radioItem" style="width: 20px; height: 20px;" value="'+response['n_id']+'"></td>'+
                    '<td class="text-middle">'+response['itemsCode']+'</td>'+
                    '<td class="text-middle">'+response['itemsName']+'</td>'+
                    '<td class="text-center text-middle"><input type="number" name="unitprice" id="unitprice_'+response['n_id']+'" class="form-control text-center" style="width: 80px;" min="0" disabled></td>'+
                    '<td class="text-center text-middle"><input type="number" name="qty" id="qty_'+response['n_id']+'" class="form-control text-center" style="width: 80px;" min="0" disabled></td>'+
                    '<td class="text-center text-middle"><input type="number" name="amount" id="amount_'+response['n_id']+'" class="form-control text-center ItemAmount" style="width: 80px;" min="0" disabled></td>'+
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
})