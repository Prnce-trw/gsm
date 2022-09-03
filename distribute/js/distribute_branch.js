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
          
        }
    })
})