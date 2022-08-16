$(document).ready(function () {
    
});

$(document).on('click', '#vat', function (e) { 
    if (this.checked) {
        $('#input_vat').attr("readonly", false); ;
    } else {
        $('#input_vat').attr("readonly", true); ;
    }
});