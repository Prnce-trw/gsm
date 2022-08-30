$(document).ready(function () {
    $('.GRitemIn').each(function() {
        var itemOut = $(this).data('info');
        var Dataitem = itemOut.split('_');
        var qty = $(this).val().split('/');
        var qtyIn = $('#info_'+Dataitem[1]+'_'+Dataitem[2]+'_'+Dataitem[3]).val();
        $('#qtyIn_'+Dataitem[1]+'_'+Dataitem[2]+'_'+Dataitem[3]).val(qty[2]).change();
    });

    $(".totalWeight").text(calWeight());

    var AmountPrice = 0;
    $('.itemperprice').each(function () {
        var value = $(this).val();
        var brand = $(this).data('brand');
        var sizeid = $(this).data('sizeid');
        var cytype = $(this).data('cytype');
        var curr_itemin = $('#itemIn_'+brand+'_'+sizeid+'_'+cytype).text();
        if (isNaN($(this).val()) || $(this).val() === "") {
            AmountPrice+=0;
        } else {
            AmountPrice+=parseFloat($(this).val());
        }
        console.log(AmountPrice);
        var sumary = parseFloat(curr_itemin).toFixed(2) * parseFloat(value).toFixed(2); 
        $('#resultTotal_'+brand+'_'+sizeid+'_'+cytype).val(sumary);
    });
    
    var amountItem = 0;
    $('.itemperprice').each(function () {
        if (isNaN($(this).val()) || $(this).val() === "") {
            AmountPrice+=0;
        } else {
            AmountPrice+=parseFloat($(this).val());
        }
        $('#TotalPrice').text(parseFloat(AmountPrice).toFixed(2));
    });
});

function numberWithCommas(number) {
    var parts = number.toString().split(".");
    parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    return parts.join(".");
}

$(document).on('input', '.adjustItems', function () {
    var qty = $(this).val();
    var brand = $(this).data('brand');
    var size = $(this).data('size');
    var weightid = $(this).data('weightid');
    var wightsize = $(this).data('wightsize');
    var cytype = $(this).data('cytype');
    var inputinfo = $('#info_'+brand+'_'+weightid+'_'+cytype).data('info');
    if (inputinfo == null && qty > 0) {
        var wordType = "";
        if (cytype == 'N') {
            wordType = 'น้ำแก๊ส';
        } else {
            wordType = 'ถังฝากเติม';
        }
        $('#itemRows').append('<tr id="trID_'+brand+'_'+weightid+'_'+cytype+'">'+
            '<td></td>'+
            '<td class="text-middle"> '+brand+'/ ขนาด '+size+' กก.'+
                '<input type="hidden" value="'+brand+'/'+size+'/'+qty+'/'+cytype+'" id="info_'+brand+'_'+weightid+'_'+cytype+'" data-info="info_'+brand+'_'+weightid+'_'+cytype+'"></td>'+
            '<td class="text-middle">'+wordType+'</td>'+
            '<td></td>'+
            '<td class="text-right text-middle"><span class="qtyItem" id="itemIn_'+brand+'_'+weightid+'_'+cytype+'">'+qty+'</span></td>'+
            '<td class="text-right text-middle"><span class="itemWeight" id="calItemWeight_'+brand+'_'+weightid+'_'+cytype+'">'+qty * wightsize+'</span></td>'+
            '<td><input type="number" class="form-control itemperprice" data-brand="'+brand+'" data-sizeid="'+weightid+'" data-cytype="'+cytype+'" style="width: 120px;" value=""></td>'+
            '<td><input type="number" class="form-control" id="resultTotal_'+brand+'_'+weightid+'_'+cytype+'" style="width: 120px;" value=""></td>'+
        '</tr>'
        );
        $(".totalWeight").text(calWeight());
        $(".totalitemIn").text(calQty());
    } else {
        if (qty != 0) {
            $('#info_'+brand+'_'+weightid+'_'+cytype).val(brand+'/'+size+'/'+qty+'/'+cytype);
            $('#itemIn_'+brand+'_'+weightid+'_'+cytype).text(qty);
            $('#calItemWeight_'+brand+'_'+weightid+'_'+cytype).text(qty * wightsize);
            $(".totalWeight").text(calWeight());
            $(".totalitemIn").text(calQty());
        } else {
            $('#trID_'+brand+'_'+weightid+'_'+cytype).remove();
            $(".totalWeight").text(calWeight());
            $(".totalitemIn").text(calQty());
        }
    }
});

function calWeight() {
    var sum = 0;
    $('.itemWeight').each(function() {
        if(isNaN($(this).text()) || $(this).text() === "") {
            sum+=0;
        } else {
            sum+=parseFloat($(this).text());
        }
    });
    return parseFloat(sum).toFixed(2);
}

function calQty() {
    var sum = 0;
    $('.qtyItem').each(function() {
        if(isNaN($(this).text()) || $(this).text() === "") {
            sum+=0;
        } else {
            sum+=parseFloat($(this).text());
        }
    });
    return parseFloat(sum);
}

$(document).on('input', '.maxlengthhour', function () {
    if(this.value.length==2){
        if (this.value > 22) {
            Swal.fire({
                icon: 'error',
                title: 'จำนวนชั่วโมงเกินกว่าที่กำหนดไว้',
            });
            $(this).val('0'+7);
        } else if (this.value <= 7) {
            Swal.fire({
                icon: 'error',
                title: 'จำนวนชั่วโมงต่ำกว่าที่กำหนดไว้',
            });
            $(this).val('0'+7);
        }
    };
});

$(document).on('input', '.maxlengthminute', function () {
    if(this.value.length==2){
        if (this.value > 60) {
            Swal.fire({
                icon: 'error',
                title: 'จำนวนนาทีเกินกว่าที่กำหนดไว้',
            });
            $(this).val(0);
        }
    };
});

$(document).on('input', '.itemperprice', SumTotal);
$(document).on('input', '.AmountPrice', SumTotal);

function SumTotal() {
    var value = $(this).val();
    var brand = $(this).data('brand');
    var sizeid = $(this).data('sizeid');
    var cytype = $(this).data('cytype');
    var curr_itemin = $('#itemIn_'+brand+'_'+sizeid+'_'+cytype).text(); 
    var sumary = parseFloat(curr_itemin).toFixed(2) * parseFloat(value).toFixed(2); 
    $('#resultTotal_'+brand+'_'+sizeid+'_'+cytype).val(sumary);
}