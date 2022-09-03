$(document).ready(function() {
    $('.js-example-basic-single').select2();
    // $('#dataTable').DataTable();

    $('#search').click(() => {
        var branch = $('#branch').val();
        var product = $('#product').val();
        var dateStart = $('#date_start').val();
        var dateEnd = $('#date_end').val();
        $('#showData').html('');
        console.log(branch, product, dateStart, dateEnd);
        $.ajax({
            type: "GET",
            dataType: "JSON",
            url: "report_buyproduct_controller.php",
            data: {action: 'search', branch: branch, product: product, dateStart: dateStart, dateEnd: dateEnd},
            success: (data) => {
                console.log("success", data);
                $('#excel').prop('disabled', false);
                if(Array.isArray(data)) {
                    data.forEach((value, key) => {
                        var fullDate = new Date(value.created_at);
                        var formatted = `${fullDate.getDate()}/${fullDate.getMonth()}/${fullDate.getFullYear()}`;

                        console.log(value);
                        console.log(value.po_itemEnt_POID);
                        var row = "<tr><td>"+formatted+"</td>" 
                        + "<td>"+value.po_itemEnt_POID+"</td>" 
                        + "<td>"+"</td>" 
                        + "<td>"+value.po_itemEnt_CyBrand+"</td>" 
                        + "<td>"+"</td>" 
                        + "<td class='text-center'>"+value.ms_product_name+"</td>" 
                        + "<td class='text-right'>"+value.po_itemEnt_CySize+"</td>"
                        + "<td>"+"</td>" + "<td class='text-right'>"+value.po_itemEnt_CyAmount+"</td>" 
                        + "<td class='text-right'>"+value.po_itemEnt_unitPrice+"</td>" 
                        + "<td class='text-right'>"+value.po_itemEnt_AmtPrice+"</td></tr>";
                        $('#showData').append(row);
                    });
                }
                var date = new Date();
                var formatted = `${("0"+date.getDate()).slice(-2)}-${("0"+(date.getMonth() + 1)).slice(-2)}-${date.getFullYear()}`;
                $('#excel').prop('disabled', false);
                $('#excel').attr("onclick", "window.open('excel/report_"+formatted+".xlsx','_blank')");
            },
            error: (data) => {
                console.log(data);
                alert("error", data);
            }
        });
    });

    $('#resetForm').click(() => {
        $('#branch').prop('selectedIndex', 0);
        $('#product').prop('selectedIndex', 0);
        $(".datepicker").datepicker('setDate', 'today');
        $('#excel').prop('disabled', true);
        $('#excel').attr('onclick', '');
        $('#showData').html('');
    });

    // $('#excel').click(() => {

    // });
});

$(".datepicker").datepicker({
    dateFormat: 'dd/mm/yy'
});
$(".datepicker").datepicker('setDate', 'today');

$("#branch").select2({
    placeholder: "เลือกสาขา",
    allowClear: true,
    theme: 'bootstrap4',
});

$("#category").select2({
    placeholder: "เลือกหมวดหมู่",
    allowClear: true,
    theme: 'bootstrap4',
});

$("#product").select2({
    placeholder: "เลือกสินค้า",
    allowClear: true,
    theme: 'bootstrap4',
});