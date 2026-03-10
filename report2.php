<?php
$select_job = select_job_project();



// echo "<pre>";
// print_r($select_job);
// echo "</pre>";
// exit;
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php $datatable = true; ?>
    <?php //include_once 'config/base.php'; 
    ?>
    <?php include_once 'layout/meta.php' ?>
    <?php include_once 'layout/css.php' ?>
    <title>หน้าแรก</title>
    <style>
        /* ป้องกันการตัดคำในคอลัมน์ที่ 9 */
    </style>
</head>

<style>
    table>thead>tr>th:nth-child(1) {
        width: 100px;
        text-wrap: nowrap;
        /* กำหนดความกว้างของ th ตัวที่ 1 */
    }

    table>thead>tr>th:nth-child(2) {
        width: 95px;
        text-wrap: nowrap;
        /* กำหนดความกว้างของ th ตัวที่ 1 */
    }

    table>thead>tr>th:nth-child(3) {
        width: 100px;
        text-wrap: nowrap;
        /* กำหนดความกว้างของ th ตัวที่ 1 */
    }


    table>thead>tr>th:nth-child(6) {
        width: 170px;

        /* กำหนดความกว้างของ th ตัวที่ 1 */
    }

    table>thead>tr>th:nth-child(7) {
        width: 170px;

        /* กำหนดความกว้างของ th ตัวที่ 1 */
    }

    table>thead>tr>th:nth-child(8) {
        width: 120px;
        /* กำหนดความกว้างของ th ตัวที่ 1 */
    }

    table>thead>tr>th:nth-child(10) {
        width: 140px;
        text-wrap: nowrap;
        /* กำหนดความกว้างของ th ตัวที่ 1 */
    }

    table>thead>tr>th:nth-child(11) {
        width: 140px;
        /* กำหนดความกว้างของ th ตัวที่ 1 */
    }

    td {
        vertical-align: top;
        text-align: start;


    }

    .row .card {
        transition: transform 0.3s ease-out, box-shadow 0.3s ease-out;
    }

    .row .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.12);
    }

    #loadingOverlay {
        display: none;
        /* เริ่มต้นซ่อนไว้ */
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.6);
        /* สีพื้นหลังเทาโปร่งแสง */
        z-index: 1050;
        /* ให้ Overlay อยู่บนสุด */
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .loading-content {
        text-align: center;
    }
</style>

<body id="kt_body" data-kt-app-header-stacked="true" data-kt-app-header-primary-enabled="true" data-kt-app-header-secondary-enabled="false" data-kt-app-toolbar-enabled="true" class="app-default">
    <?php include_once 'layout/modechange.php'; ?>
    <!--begin::App-->
    <div class="d-flex flex-column flex-root app-root" id="kt_app_root">
        <!--begin::Page-->
        <div class="app-page  flex-column flex-column-fluid " id="kt_app_page">
            <?php include_once 'layout/navbar.php'; ?>
            <!--begin::Wrapper-->
            <div class="app-wrapper  flex-column flex-row-fluid " id="kt_app_wrapper">
                <!--begin::Wrapper container-->
                <div class="app-container bg-light container-sm d-flex flex-row flex-column-fluid ">
                    <!--begin::Main-->
                    <div class="app-main flex-column flex-row-fluid" id="kt_app_main">
                        <!--begin::Content wrapper-->
                        <div class="d-flex flex-column flex-column-fluid">
                            <!--begin::Toolbar-->
                            <div id="kt_app_toolbar" class="app-toolbar  d-flex flex-stack py-4 py-lg-8 ">
                                <!--begin::Toolbar wrapper-->
                                <div class="d-flex flex-grow-1 flex-stack flex-wrap gap-2 mb-n10" id="kt_toolbar">
                                    <!--begin::Page title-->
                                    <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3 ">
                                        <!--begin::Title-->
                                        <h1 class="page-heading d-flex text-dark fw-bold fs-3 flex-column justify-content-center my-0">
                                            หน้าแรก
                                        </h1>
                                        <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                                            <li class="breadcrumb-item text-muted">
                                                <a href="index.php?token=<?php echo $_SESSION['token'] ?>" class="text-muted text-hover-primary">
                                                    หน้าแรก
                                                </a>
                                            </li>
                                            <li class="breadcrumb-item">
                                                -
                                            </li>
                                            <li class="breadcrumb-item text-muted">
                                                <span>รายงาน</span>
                                            </li>
                                        </ul>
                                    </div>

                                    <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3 d-none">
                                        <a type="button" class="btn btn-primary" href="https://innovation.asefa.co.th/ChangeRequestForm/InsertForm?token=<?php echo $_SESSION['token'] ?>">
                                            <i class="bi bi-clipboard-plus-fill fs-4"></i> เพิ่มใบขอเปลี่ยนแปลง
                                        </a>
                                    </div>

                                </div>
                            </div>
                            <div id="kt_app_content" class="app-content  flex-column-fluid ">

                                <div class="card mb-8">
                                    <!--begin::Body-->

                                    <!--begin::About-->
                                    <div class="row flex-column flex-md-row" style="padding: 15px;">
                                        <!--begin::Wrapper-->
                                        <div class="mt-4 col-12 col-md-6 fs-5 fw-semibold text-gray-600">
                                            <label for="jon_no_select" class="form-label"><i class="fas fa-file-alt fs-4"></i> Job No.</label>
                                            <select class="form-select" id="jon_no_select" name="jon_no_select[]" multiple>

                                            </select>
                                        </div>

                                        <div class="col-12 col-md-4 fs-5 fw-semibold text-gray-600 row">
                                            <div class="mt-4 col-6 fs-5 fw-semibold text-gray-600">
                                                <label for="jon_no_select" class="form-label"><i class="fa-solid fa-clock fs-4"></i> Date Start</label>
                                                <input type="date" name="date_start" id="date_start" class="form-control">
                                            </div>

                                            <div class="mt-4 col-6 fs-5 fw-semibold text-gray-600">
                                                <label for="jon_no_select" class="form-label"><i class="fa-solid fa-clock-rotate-left fs-4"></i> Date End</label>
                                                <input type="date" name="date_end" id="date_end" class="form-control">
                                            </div>
                                        </div>

                                        <div class="mt-4 col-12 col-md-2 d-flex align-items-end justify-content-center">
                                            <button type="button" id="search-job" class="btn btn-primary"><i class="fas fa-search fs-4 me-2"></i> Search</button>
                                        </div>
                                    </div>
                                </div>

                                <div class="table-responsive w-100">

                                    <div class="card p-1">
                                        <div class="">
                                            <button id="export-excel" class="btn btn-sm btn-success">Excel <i class="fa-solid fa-file-excel"></i></button>
                                        </div>

                                        <table id="example" class="table mt-2 w-full text-sm text-left 
                                        rtl:text-right text-gray-500 dark:text-gray-400">
                                            <thead class="text-xs table-light">

                                            </thead>

                                        </table>
                                    </div>
                                </div>

                            </div>
                        </div>

                        <div id="loadingOverlay">
                            <div class="loading-content">
                                <div class="d-flex justify-content-center">
                                    <div class="spinner-grow text-dark" style="width: 3rem; height: 3rem;"></div>
                                    <div class="spinner-grow text-dark" style="width: 3rem; height: 3rem; animation-delay: 0.2s;"></div>
                                    <div class="spinner-grow text-dark" style="width: 3rem; height: 3rem; animation-delay: 0.4s;"></div>
                                    <div class="spinner-grow text-dark" style="width: 3rem; height: 3rem; animation-delay: 0.6s;"></div>
                                </div>
                                <p class="mt-3 text-white">กำลังดาวน์โหลด...</p>
                            </div>
                        </div>

                        <?php include_once 'layout/scoreup.php'; ?>
                        <?php include_once 'layout/footer.php'; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include_once 'layout/js.php' ?>
    <script src="https://cdn.jsdelivr.net/npm/exceljs@4.2.1/dist/exceljs.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $('#jon_no_select').select2({
            placeholder: "Select Job No.",
            allowClear: true
        });


        let table = $('#example').DataTable({
            // responsive: true,
            "ordering": false,
            scrollX: true,
            stateSave: true,
            ajax: {
                url: './Report_Api2_Edit',
                type: 'POST',
                beforeSend: function() {
                    $("#loadingOverlay").show();
                },
                complete: function() {
                    $("#loadingOverlay").hide();
                },
                data: function(d) {
                    d.startDate = $('#date_start').val(); // Get start date from input
                    d.endDate = $('#date_end').val(); // Get end date from input
                    d.jobNo = $('#jon_no_select').val(); // Get selected job numbers
                },
                dataSrc: function(json) {
                    console.log(json);
                    if (!json || !json.data || !Array.isArray(json.data)) {
                        console.error("Invalid JSON format:", json);
                        return [];
                    }
                    return json.data;
                },
                error: function(xhr, error, thrown) {
                    console.error("AJAX Error:", error, thrown, xhr.responseText);
                },
            },
            columns: [{
                    data: 'Status_Date',
                    title: 'วันที่'
                },
                {
                    data: 'doc_no',
                    title: 'เลขที่'
                },
                {
                    data: 'jobno',
                    title: 'ใบเปิดงาน'
                },
                {
                    data: 'wa_no',
                    title: 'W/A',
                    render: function(data, type, row) {
                        if (data) {
                            return data.replace(/,/g, '<br>'); // แทน , ด้วย <br> เพื่อขึ้นบรรทัดใหม่
                        }
                        return data; // กรณีไม่มีข้อมูล ให้แสดง -
                    }
                },
                {
                    data: 'doc_status',
                    title: 'สถานะ',
                    render: function(data, type, row) {
                        let statusN;
                        if (row.doc_status == "Close") {
                            statusN = `<span class="badge rounded-pill text-bg-secondary">ปิดงาน</span>`;
                        } else if (row.doc_status == "Recheck") {
                            statusN = `<span class="badge rounded-pill text-bg-warning">กำลังตรวจสอบ</span>`;
                        } else if (row.doc_status == "Approve") {
                            statusN = `<span class="badge rounded-pill text-bg-success">อนุมัติ</span>`;
                        } else {
                            statusN = `<span class="badge rounded-pill text-bg-danger">ไม่อนุมัติ</span>`;
                        }
                        return statusN;
                    }
                },
                {
                    data: 'project_name',
                    title: 'โครงการ'
                },
                {
                    data: 'details',
                    title: 'รายละเอียด'
                },
                {
                    data: 'job_remark',
                    title: 'สาเหตุ'
                },
                {
                    data: 'expenses',
                    title: 'ค่าใช้จ่าย'
                },
                {
                    data: 'expensesummary',
                    title: 'คิดเงินลูกค้า',
                    render: function(data, type, row) {
                        if (!data || data.trim() === "") {
                            return ''; // กรณีเป็นค่าว่าง
                        }
                        var parsedData = JSON.parse(data);
                        let customtotal = '';

                        var docCharge = parsedData.doc_charge;

                        // ตรวจสอบ docCharge
                        if (docCharge != null && docCharge.trim() !== '') {
                            customtotal += `<span class="">${docCharge}</span></br>`;
                            customtotal += `<span class="">${parsedData.doc_newwork}</span>`;
                        } else if (!data || data.trim() === "") {
                            customtotal = '';
                        }
                        // กรณีทั้งหมดเป็นค่าว่างหรือ null



                        // แสดงผลในรูปแบบที่ต้องการ
                        return customtotal;

                    }
                },
                {
                    data: 'expensesummary',
                    title: 'ไม่คิดเงินลูกค้า',
                    render: function(data, type, row) {

                        // ตรวจสอบว่า 'expensesummary' เป็นค่าว่างหรือไม่
                        if (!data || data.trim() === "") {
                            return ''; // กรณีเป็นค่าว่าง
                        }


                        // แปลงจาก JSON string ไปเป็น Object
                        var parsedData = JSON.parse(data);
                        let customtotal = '';

                        var inasmuch = parsedData.inasmuch;

                        // ตรวจสอบ inasmuch
                        if (inasmuch != null && inasmuch.trim() !== '') {
                            customtotal = `<span class="">${inasmuch}</span>`;

                        } else if (!data || data.trim() === "") {
                            customtotal = '';
                        }
                        // กรณีทั้งหมดเป็นค่าว่างหรือ null



                        // แสดงผลในรูปแบบที่ต้องการ
                        return customtotal;

                    }
                }
            ]
        });

        $(document).ready(function() {
            $("#loadingOverlay").hide();

            $.ajax({
                url: "./Report203",
                type: 'GET',
                success: function(response) {
                    // แปลง response ที่ได้รับเป็น JSON
                    let item = response;

                    // ตรวจสอบว่า item มีข้อมูลหรือไม่
                    if (item && Array.isArray(item.data)) {
                        let options = '';

                        // วนลูป item เพื่อนำค่ามาสร้าง <option>
                        item.data.forEach(function(element) {
                            options += `<option value="${element.jobno}">${element.jobno}</option>`;
                        });

                        // เติม options ลงใน select2
                        $('#jon_no_select').html(options);

                        // รีเฟรช select2
                        $('#jon_no_select').select2();
                    }
                }
            });


        });



        $('#search-job').on('click', function() {
            let selectedJobs = $('#jon_no_select').val(); // Get selected job numbers
            let startDate = $('#date_start').val(); // Get start date
            let endDate = $('#date_end').val(); // Get end date

            console.log(startDate);
            console.log(endDate);
            if (!startDate) {
                Swal.fire({
                    icon: 'warning',
                    text: 'กรุณาระบุ StartDate',
                    confirmButtonText: 'ตกลง'
                });
                return; // หยุดการทำงานต่อ
            }
            if (!endDate) {
                Swal.fire({
                    icon: 'warning',
                    text: 'กรุณาระบุ endDate',
                    confirmButtonText: 'ตกลง'
                });
                return; // หยุดการทำงานต่อ
            }

            // ถ้าผ่านเงื่อนไข ส่งค่าต่อไป
            table.ajax.reload(); // Reload the table with new data
        });



        $('#export-excel').on('click', function() {
            let startDate = $('#date_start').val(); // Get start date
            let endDate = $('#date_end').val();

            var table = $('#example').DataTable();
            var workbook = new ExcelJS.Workbook();
            var worksheet = workbook.addWorksheet('Sheet1');
            worksheet.getColumn(1).width = 6; // ลำดับ
            worksheet.getColumn(2).width = 16; // 163px
            worksheet.getColumn(3).width = 14; // 97px
            worksheet.getColumn(4).width = 18; // 126px
            worksheet.getColumn(5).width = 12; // 200px
            worksheet.getColumn(6).width = 8; // 652px
            worksheet.getColumn(7).width = 25; // 173px
            worksheet.getColumn(8).width = 59; // 108px
            worksheet.getColumn(9).width = 22; // 154px
            worksheet.getColumn(10).width = 10; // 137px
            worksheet.getColumn(11).width = 15; // 137px
            worksheet.getColumn(12).width = 15; // 253px

            // เพิ่มแถวก่อนหัวตาราง (แถวที่ 1-4)
            var row1 = worksheet.addRow(['บริษัท อาซีฟา จำกัด (มหาชน)']);
            worksheet.mergeCells('A1:L1'); // Merge คอลัมน์ A ถึง M

            var mergedCell = worksheet.getCell('A1'); // ใช้ getCell('A1') เพื่อกำหนดคุณสมบัติของเซลล์ที่ถูก Merge
            mergedCell.alignment = {
                horizontal: 'center', // จัดให้อยู่กึ่งกลางแนวนอน
                vertical: 'middle' // จัดให้อยู่กึ่งกลางแนวตั้ง
            };
            mergedCell.font = {
                name: 'Cordia New',
                size: 12,
                // bold: true // ทำให้ตัวอักษรหนา
            };
            let startNew = startDate == null || startDate.trim() == '' ? "วันที่ : -" : `วันที่ : ${startDate} - ${endDate}`
            let headcol = startNew;

            var row2 = worksheet.addRow(['EN0203-รายงานติดตามใบเปลี่ยนแปลงนอก Scope']);
            row2.getCell(1).alignment = {
                horizontal: 'left',
                vertical: 'middle'
            };
            worksheet.mergeCells('A2:B2');
            row2.font = {
                name: 'Cordia New',
                size: 12
            };

            var row3 = worksheet.addRow([headcol]);
            row3.getCell(1).alignment = {
                horizontal: 'left',
                vertical: 'middle'
            };
            row3.font = {
                name: 'Cordia New',
                size: 12
            };
            worksheet.mergeCells('A3:B3');

            var row4 = worksheet.addRow();
            row4.getCell(1).alignment = {
                horizontal: 'left',
                vertical: 'middle'
            };
            row4.font = {
                name: 'Cordia New',
                size: 12
            };
            worksheet.mergeCells('A4:B4');

            // ตอนนี้แถว 1 ถึง 4 เป็นข้อมูลพิเศษของคุณแล้ว 
            // หัวตาราง (ชื่อคอลัมน์) จะเริ่มต้นที่แถวที่ 5
            var headerRow = worksheet.addRow([
                'ลำดับ', 'วันที่', 'เลขที่', 'ใบเปิดงาน', 'W/A', 'สถานะ', 'โครงการ',
                'รายละเอียด', 'สาเหตุ', 'ค่าใช้จ่าย', 'คิดเงินลูกค้า', 'ไม่คิดเงินลูกค้า'
            ]);

            headerRow.font = {
                name: 'Cordia New',
                size: 12,
                bold: true // ทำให้ฟอนต์หัวตารางเป็นตัวหนา
            };

            // กำหนดความสูงของแถวหัวตาราง
            worksheet.getRow(5).height = 40;

            let rowIndex = 1; // กำหนดให้แถวแรกมีลำดับเป็น 1
            // ดึงข้อมูลจาก DataTable และเพิ่มไปยังแถวใน Excel
            table.rows({
                search: 'applied'
            }).every(function() {
                var row = this.data();

                // แปลงข้อมูลตาม render ใน DataTable
                var status = '';
                if (row.doc_status === "Close") {
                    status = 'ปิดงาน';
                } else if (row.doc_status === "Recheck") {
                    status = 'กำลังตรวจสอบ';
                } else if (row.doc_status === "Approve") {
                    status = 'อนุมัติ';
                } else {
                    status = 'ไม่อนุมัติ';
                }

                // สำหรับ expensesummary แปลงจาก JSON string ไปเป็น Object
                var expenseSummaryClient = '';
                var expenseSummaryNonClient = '';
                if (row.expensesummary && row.expensesummary.trim() !== "") {
                    var parsedData = JSON.parse(row.expensesummary);
                    if (parsedData.doc_charge) {
                        expenseSummaryClient = parsedData.doc_charge + '\n' + parsedData.doc_newwork;
                    }
                    if (parsedData.inasmuch) {
                        expenseSummaryNonClient = parsedData.inasmuch;
                    }
                }

                // สร้างแถวใหม่โดยรวมข้อมูลทั้งหมด
                var rowValues = [
                    rowIndex++,
                    row.Status_Date,
                    row.doc_no,
                    row.jobno,
                    row.wa_no,
                    status,
                    row.project_name,
                    row.details,
                    row.job_remark,
                    row.expenses,
                    expenseSummaryClient,
                    expenseSummaryNonClient
                ];

                worksheet.addRow(rowValues);
                rowIndex++; // เพิ่มค่า index ทีละ 1

            });

            // ตั้งค่าการห่อข้อความในทุกเซลล์ และกำหนดขอบ
            worksheet.eachRow(function(row, rowNumber) {
                row.eachCell(function(cell, colNumber) {
                    if (rowNumber === 1) {
                        cell.alignment = {
                            horizontal: 'center', // กึ่งกลางแนวนอน
                            vertical: 'bottom' // อยู่ด้านล่างของเซลล์
                        };
                        return; // ไม่ต้องกำหนดค่าอื่นๆ ในแถวนี้
                    }

                    cell.alignment = {
                        wrapText: true, // เปิดการห่อข้อความ
                        horizontal: 'left', // จัดข้อความให้อยู่ทางซ้าย
                        vertical: 'top' // ตั้งค่าให้ข้อความอยู่ด้านบนสุด
                    };

                    if (rowNumber < 5) {
                        return; // ข้ามการตั้งค่าขอบในแถว 1-4
                    }

                    cell.border = {
                        top: {
                            style: 'thin',
                            color: {
                                argb: 'FF000000'
                            }
                        }, // ขอบบน
                        left: {
                            style: 'thin',
                            color: {
                                argb: 'FF000000'
                            }
                        }, // ขอบซ้าย
                        bottom: {
                            style: 'thin',
                            color: {
                                argb: 'FF000000'
                            }
                        }, // ขอบล่าง
                        right: {
                            style: 'thin',
                            color: {
                                argb: 'FF000000'
                            }
                        } // ขอบขวา
                    };

                    // กำหนดสีพื้นหลังสำหรับหัวตาราง (แถวที่ 5)
                    if (rowNumber == 5) {

                        cell.font = {
                            name: 'Cordia New', // ฟอนต์
                            size: 12, // ขนาดฟอนต์
                            bold: true // ทำให้ฟอนต์เป็นตัวหนา
                        };
                        cell.alignment = {
                            horizontal: 'center', // จัดข้อความให้อยู่กลางแนวนอน
                            vertical: 'middle' // จัดข้อความให้อยู่กลางแนวตั้ง
                        };
                    }

                    cell.font = {
                        name: 'Cordia New', // ฟอนต์
                        size: 12 // ขนาดฟอนต์
                    };
                });
            });

            // บันทึกไฟล์ Excel
            workbook.xlsx.writeBuffer().then(function(buffer) {
                var blob = new Blob([buffer], {
                    type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
                });
                var link = document.createElement('a');
                link.href = URL.createObjectURL(blob);
                link.download = 'EN0203-รายงานติดตามใบเปลี่ยนแปลงนอก Scope.xlsx';
                link.click();
            });
        });
    </script>
</body>

</html>