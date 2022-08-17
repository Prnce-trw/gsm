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
    })
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
        $('#btnDistribute_'+n_id).attr("disabled", false);
    } else {
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