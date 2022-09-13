$(document).ready(function () {
    $("#category").on("select2:select", (event) => {
        $("#product").trigger({
            type: 'select2:unselect',
        });
        $("#product").html("");
        $.ajax({
            type: "GET",
            dataType: "JSON",
            url: "report_buyproduct_controller.php",
            data: { 
                action: "categorySelect", 
                category: $("#category").val(), },
            success: (data) => {
                // console.log(data);
                if ("error" in data) {
                    Swal.fire({
                        icon: "error",
                        title: "เกิดข้อผิดพลาด",
                        text: "ไม่สามารถค้นหาข้อมูลสินค้าได้",
                    });
                    return false;
                }
                if (Array.isArray(data)) {
                    $("#product").append(new Option("สินค้าทั้งหมด", "all"));
                    data.forEach((value, key) => {
                        // console.log(value);
                        $("#product").append(new Option("[" + value.itemsCode + "] " + value.itemsName, value.n_id));
                    });
                }
            },
            error: (data) => {
                // console.log($("#category").val());
                // console.log(data);
                Swal.fire({
                    icon: "error",
                    title: "เกิดข้อผิดพลาด",
                    text: "ไม่สามารถค้นหาข้อมูลสินค้าได้",
                });
            },
        });
        $("#product").prop("disabled", false);
    });

    $("#category").on("select2:unselect", (event) => {
        // console.log("category!!!!");
        // $("#product").prop("selectedIndex", 0);
        $("#product").trigger({
            type: 'select2:unselect',
        });
        $("#product").html("");
        $("#product").prop("disabled", true);
    });

    $("#search").click((event) => {
        var branch = $("#branch").val();
        var product = $("#product").val();
        var supplier = $("#supplier").val();
        var dateStart = $("#date_start").val();
        var dateEnd = $("#date_end").val();
        $("#showData").html("");
        if (!branch || !product || !supplier || !dateStart || !dateEnd) {
            if (!branch) {
                Swal.fire({
                    icon: "error",
                    title: "กรุณาเลือกข้อมูลให้ครบ",
                    text: "ยังไม่ได้เลือกสาขา",
                });
            } else if (!product) {
                Swal.fire({
                    icon: "error",
                    title: "กรุณาเลือกข้อมูลให้ครบ",
                    text: "ยังไม่ได้เลือกสินค้า",
                });
            } else if (!supplier) {
                Swal.fire({
                    icon: "error",
                    title: "กรุณาเลือกข้อมูลให้ครบ",
                    text: "ยังไม่ได้เลือกผู้จำหน่าย",
                });
            }
            return false;
        }
        // console.log(branch, product, supplier, dateStart, dateEnd);
        $.ajax({
            type: "GET",
            dataType: "JSON",
            url: "report_buyproduct_controller.php",
            data: { action: "search", branch: branch, product: product, supplier: supplier, dateStart: dateStart, dateEnd: dateEnd },
            success: (data) => {
                console.log(data);
                if ("error" in data) {
                    Swal.fire({
                        icon: "error",
                        title: "เกิดข้อผิดพลาด",
                        text: "ไม่สามารถค้นหาข้อมูลในระบบได้ หรือไม่พบข้อมูลในระบบ",
                    });
                    $("#excel").prop("disabled", true);
                    return false;
                }
                $("#excel").prop("disabled", false);
                if (Array.isArray(data.data)) {
                    console.log(data.data);
                    data.data.forEach((value, key) => {
                        var fullDate = new Date(value.created_at);
                        var formatted = `${fullDate.getDate()}/${(fullDate.getMonth() + 1)}/${fullDate.getFullYear()}`;
                        // console.log(value);
                        // console.log(value.po_itemEnt_POID);
                        var row = "<tr><td>" + formatted + "</td>" // วันที่ลง
                            + "<td>" + value.po_itemEnt_POID + "</td>" // หมายเลข PO
                            + "<td class='text-left'>" + value.branch_name + "</td>" // สาขา
                            + "<td>" + value.itemsCode + "</td>"
                            // + "<td class='text-center'>" + value.ms_product_name + "</td>"
                            + "<td class='text-right'>" + value.po_itemEnt_CySize + "</td>"
                            + "<td class='text-left'>" + value.supplier_name + "</td>" // ผู้จำหน่าย
                            + "<td class='text-right'>" + value.po_itemEnt_CyAmount + "</td>"
                            + "<td class='text-right'>" + parseFloat(value.po_itemEnt_unitPrice).toFixed(2) + "</td>"
                            + "<td class='text-right'>" + parseFloat(value.po_itemEnt_AmtPrice).toFixed(2) + "</td></tr>";
                        $("#showData").append(row);
                    });
                    $("#totalCount").html(data.totalCount);
                    $("#totalPrice").html(parseFloat(data.totalPrice).toFixed(2));
                    $("#excel").prop("disabled", false);
                    $("#excel").attr("onclick", "window.open('excel/" + data.filename + ".xlsx', '_parent', 'download')");
                }
            },
            error: (data, status, error) => {
                console.log("error =>", data);
                Swal.fire({
                    icon: "error",
                    title: "เกิดข้อผิดพลาด",
                    text: "ไม่สามารถค้นหาข้อมูลในระบบได้",
                });
            }
        });
    });

    $("#resetForm").click((event) => {
        location.reload();
        // $("#branch").trigger({
        //     type: "select2:unselect",
        // });
        // $("#product").trigger({
        //     type: "select2:unselect",
        // })
        // $("#branch").prop("selectedIndex", 0);
        // $("#product").prop("selectedIndex", 0);
        // $("#product").prop("disabled", true);
        // $(".datepicker").datepicker("setDate", "today");
        // $("#excel").prop("disabled", true);
        // $("#excel").attr("onclick", "");
        // $("#showData").html("");
    });
});

$(".datepicker").datepicker({
    dateFormat: "dd/mm/yy",
});
$(".datepicker").datepicker("setDate", "today");

$("#branch").select2({
    placeholder: "เลือกสาขา",
    allowClear: true,
    theme: "bootstrap4",
});

$("#category").select2({
    placeholder: "เลือกหมวดหมู่",
    allowClear: true,
    theme: "bootstrap4",
});

$("#product").select2({
    placeholder: "เลือกสินค้า",
    allowClear: true,
    theme: "bootstrap4",
    pagination: {
        "more": true
    },
});

$("#supplier").select2({
    placeholder: "เลือกผู้จำหน่าย",
    allowClear: true,
    theme: "bootstrap4",
});