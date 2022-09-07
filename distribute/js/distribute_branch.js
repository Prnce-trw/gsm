$('#btnreload').click(function () {
    location.reload();
});

$('#btn_submitdistributebranch').click(function () {
    Swal.fire({
        title: 'คุณแน่ใจหรือไม่?',
        text: "คุณจะไม่สามารถแก้ไขจำนวนอุปกรณ์ได้อีก กรูณาตรวจสอบความถูกต้องก่อนยืนยัน!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'ยืนยัน',
        cancelButtonText: 'ยกเลิก',
    }).then((result) => {
        if (result.isConfirmed) {
            $('#AcceptAccToBranch').submit();
        }
    })
});

$(document).on('click', '.itemselected', function () {
    var itemid = $(this).data('itemid');
    if (this.checked) {
        $('#inputitemid_'+itemid).prop("disabled", false);
    } else {
        $('#inputitemid_'+itemid).prop('disabled', true);
    }
});