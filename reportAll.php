<?php
$select_job = select_job_project();
$CauseList = json_encode(CauseList(), JSON_UNESCAPED_UNICODE);


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
    #example th {
        text-wrap: nowrap;

    }

    table.dataTable>thead>tr>th:not(.sorting_disabled),
    table.dataTable>thead>tr>td:not(.sorting_disabled) {
        text-align: start;
        padding: 9;

    }

    td {
        vertical-align: top;
        text-align: start;


    }

    th:nth-child(1) {
        width: 150px;
        /* ปรับความกว้างตามต้องการ */
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

    /* ทำให้ Loading อยู่ตรงกลาง */
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

                                <div class="table-responsive w-100 bg-white">

                                    <div class="p-1 rounded">
                                        <div class="">
                                            <button id="exportExcel" class="btn btn-sm btn-success">Excel <i class="fa-solid fa-file-excel"></i></button>
                                        </div>

                                        <table id="example" class="table table-hover mt-2 w-full text-sm text-left p-1 
                                        rtl:text-right text-gray-500 dark:text-gray-400">
                                            <thead class="text-xs table-light">

                                                <tr style="text-wrap: nowrap;">
                                                    <td rowspan="2">รหัสงาน</td>
                                                    <td rowspan="2">ประเภทเอกสาร</td>
                                                    <td rowspan="2">Job No</td>
                                                    <td rowspan="2">โครงการ</td>
                                                    <td rowspan="2">Sales Name</td>
                                                    <td rowspan="2">Customer Name</td>
                                                    <td rowspan="2">rev</td>
                                                    <td rowspan="2">บันทึกความผิดพลาด/NCR</td>
                                                    <td rowspan="2">W/A</td>
                                                    <td rowspan="2">Serial No</td>
                                                    <td rowspan="2">TC</td>
                                                    <td rowspan="2">product</td>
                                                    <td rowspan="2">สถานะงาน</td>
                                                    <td rowspan="2">สถานะผลิตภัณฑ์</td>
                                                    <td colspan="5" class="text-center">หัวข้อการเปลี่ยนแปลง</td>
                                                    <td rowspan="2">สาเหตุ</td>
                                                    <td rowspan="2">รายละเอียด</td>
                                                    <td rowspan="2">มูลค่า</td>
                                                    <td rowspan="2">ผลกระทบที่เกิดจากการเปลี่ยนแปลง</td>
                                                    <td rowspan="2">ค่าใช้จ่ายในครั้งนี้</td>
                                                    <td rowspan="2">ค่าใช้จ่ายสะสมรวม</td>
                                                    <td rowspan="2">กำหนดให้ผู้เกี่ยวข้องดำเนินการ</td>
                                                    <td colspan="3" class="text-center">สรุปค่าใช้จ่ายที่เกิดขึ้น (โดยฝ่ายขาย)</td>

                                                </tr>

                                                <tr style="text-wrap: nowrap;">

                                                    <td>อุปกรณ์ไฟฟ้า</td> <!-- รวม 4 คอลัมน์ -->
                                                    <td>แบบ</td>
                                                    <td>บัสบาร์</td>
                                                    <td>เหล็ก</td>
                                                    <td>ใบเปิดงาน</td>

                                                    <td>คิดเงิน</td>
                                                    <td>เปิดงานใหม่</td>
                                                    <td>ไม่คิดเงินลูกค้า</td>
                                                </tr>


                                            </thead>

                                            <tbody id="">

                                            </tbody>

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
    <!-- SweetAlert2 CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


    <script>
        $('#jon_no_select').select2({
            placeholder: "Select Job No.",
            allowClear: true
        });

        const CauseList = JSON.parse('<?php echo $CauseList; ?>');
        // console.log(CauseList);

        $(document).ready(function() {
            $("#loadingOverlay").hide(); // ซ่อน Overlay เมื่อโหลดเสร็จ

            //ส่งข้อมูลไป select
            $.ajax({
                url: "./Report203",
                type: 'GET',
                success: function(response) {
                    // console.log(response);
                    let item = response;

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


        $("#exportExcel").on("click", function() {

            let workbook = new ExcelJS.Workbook();
            let worksheet = workbook.addWorksheet("Report");


            // หัวข้อแถวที่ 1
            let header1 = [
                "รหัสงาน", "ประเภทเอกสาร", "Job No", "โครงการ", "Sales Name", "Customer Name",
                "rev", "บันทึกความผิดพลาด/NCR", "W/A", "Serial No", "TC", "product",
                "สถานะงาน", "สถานะผลิตภัณฑ์", "หัวข้อการเปลี่ยนแปลง", "", "", "", "",
                "สาเหตุ", "รายละเอียด", "มูลค่า", "ผลกระทบที่เกิดจากการเปลี่ยนแปลง",
                "ค่าใช้จ่ายในครั้งนี้", "ค่าใช้จ่ายสะสมรวม", "กำหนดให้ผู้เกี่ยวข้องดำเนินการ",
                "สรุปค่าใช้จ่ายที่เกิดขึ้น (โดยฝ่ายขาย)", "", ""
            ];

            // หัวข้อแถวที่ 2
            let header2 = [
                "", "", "", "", "", "", "", "", "", "", "", "", "", "",
                "อุปกรณ์ไฟฟ้า", "แบบ", "บัสบาร์", "เหล็ก", "ใบเปิดงาน",
                "", "", "", "", "", "", "",
                "คิดเงิน", "เปิดงานใหม่", "ไม่คิดเงินลูกค้า"
            ];

            let startDate = $('#date_start').val(); // Get start date
            let endDate = $('#date_end').val(); // Get end date

            let alldate = ["ตั้งแต่ " + startDate + " - " + endDate]; // ใช้ array เพื่อให้แยกคอลัมน์ใน Excel

            // เพิ่มหัวข้อทั้งสองแถวลงใน Excel
            worksheet.addRow([]);
            worksheet.addRow(['รายงานแสดงข้อมูลใบเปลี่ยนแปลง(ป/ป)']);
            worksheet.addRow(alldate);
            worksheet.addRow(header1);
            worksheet.addRow(header2);


            for (let col = 1; col <= 14; col++) { // A ถึง N = 14 คอลัมน์
                let colLetter = String.fromCharCode(64 + col); // สร้างตัวอักษรจากหมายเลขคอลัมน์ (A-N)
                worksheet.mergeCells(`${colLetter}4:${colLetter}5`);
                worksheet.getCell(`${colLetter}4`).border = {
                    top: {
                        style: 'thin',
                        color: {
                            argb: '000000'
                        }
                    },
                    left: {
                        style: 'thin',
                        color: {
                            argb: '000000'
                        }
                    },
                    bottom: {
                        style: 'thin',
                        color: {
                            argb: '000000'
                        }
                    },
                    right: {
                        style: 'thin',
                        color: {
                            argb: '000000'
                        }
                    }
                };
            }

            for (let col = 20; col <= 26; col++) { // T ถึง Z = คอลัมน์ที่ 20 ถึง 26
                let colLetter = String.fromCharCode(64 + col); // สร้างตัวอักษรจากหมายเลขคอลัมน์ (T-Z)
                worksheet.mergeCells(`${colLetter}4:${colLetter}5`);
                worksheet.getCell(`${colLetter}4`).border = {
                    top: {
                        style: 'thin',
                        color: {
                            argb: '000000'
                        }
                    },
                    left: {
                        style: 'thin',
                        color: {
                            argb: '000000'
                        }
                    },
                    bottom: {
                        style: 'thin',
                        color: {
                            argb: '000000'
                        }
                    },
                    right: {
                        style: 'thin',
                        color: {
                            argb: '000000'
                        }
                    }
                };
            }
            // รวมเซลล์ให้ตรงกับตาราง HTML
            worksheet.mergeCells("O4:S4");
            worksheet.mergeCells("AA4:AC4");

            worksheet.getCell("O4").border = {
                top: {
                    style: 'thin',
                    color: {
                        argb: '000000'
                    }
                },
                left: {
                    style: 'thin',
                    color: {
                        argb: '000000'
                    }
                },
                bottom: {
                    style: 'thin',
                    color: {
                        argb: '000000'
                    }
                },
                right: {
                    style: 'thin',
                    color: {
                        argb: '000000'
                    }
                }
            };

            worksheet.getCell("AA4").border = {
                top: {
                    style: 'thin',
                    color: {
                        argb: '000000'
                    }
                },
                left: {
                    style: 'thin',
                    color: {
                        argb: '000000'
                    }
                },
                bottom: {
                    style: 'thin',
                    color: {
                        argb: '000000'
                    }
                },
                right: {
                    style: 'thin',
                    color: {
                        argb: '000000'
                    }
                }
            };

            // เพิ่ม border สำหรับเซลล์ในแถว 5 ตั้งแต่ O5 ถึง S5
            for (let col = 15; col <= 19; col++) { // O5 ถึง S5 คือคอลัมน์ 15 ถึง 19
                let colLetter = String.fromCharCode(64 + col); // สร้างตัวอักษรจากหมายเลขคอลัมน์ (O-S)
                worksheet.getCell(`${colLetter}5`).border = {
                    top: {
                        style: 'thin',
                        color: {
                            argb: '000000'
                        }
                    },
                    left: {
                        style: 'thin',
                        color: {
                            argb: '000000'
                        }
                    },
                    bottom: {
                        style: 'thin',
                        color: {
                            argb: '000000'
                        }
                    },
                    right: {
                        style: 'thin',
                        color: {
                            argb: '000000'
                        }
                    }
                };
            }



            for (let rowNumber = 1; rowNumber <= 5; rowNumber++) {
                worksheet.getRow(rowNumber).font = {
                    name: 'TH Sarabun New',
                    bold: false,
                    size: 12
                };
            }

            worksheet.getCell("AA5").border = {
                top: {
                    style: 'thin',
                    color: {
                        argb: '000000'
                    }
                },
                left: {
                    style: 'thin',
                    color: {
                        argb: '000000'
                    }
                },
                bottom: {
                    style: 'thin',
                    color: {
                        argb: '000000'
                    }
                },
                right: {
                    style: 'thin',
                    color: {
                        argb: '000000'
                    }
                }
            };
            worksheet.getCell("AB5").border = {
                top: {
                    style: 'thin',
                    color: {
                        argb: '000000'
                    }
                },
                left: {
                    style: 'thin',
                    color: {
                        argb: '000000'
                    }
                },
                bottom: {
                    style: 'thin',
                    color: {
                        argb: '000000'
                    }
                },
                right: {
                    style: 'thin',
                    color: {
                        argb: '000000'
                    }
                }
            };
            worksheet.getCell("AC5").border = {
                top: {
                    style: 'thin',
                    color: {
                        argb: '000000'
                    }
                },
                left: {
                    style: 'thin',
                    color: {
                        argb: '000000'
                    }
                },
                bottom: {
                    style: 'thin',
                    color: {
                        argb: '000000'
                    }
                },
                right: {
                    style: 'thin',
                    color: {
                        argb: '000000'
                    }
                }
            };

            // จัดตำแหน่งหัวข้อแถวที่ 1 และ 2 ให้กึ่งกลาง
            worksheet.getRow(4).eachCell(function(cell) {
                cell.alignment = {
                    vertical: 'middle', // จัดแนวตั้งให้กึ่งกลาง
                    horizontal: 'center' // จัดแนวนอนให้กึ่งกลาง
                };
            });

            worksheet.getRow(5).eachCell(function(cell) {
                cell.alignment = {
                    vertical: 'middle', // จัดแนวตั้งให้กึ่งกลาง
                    horizontal: 'center' // จัดแนวนอนให้กึ่งกลาง
                };
            });

            // ดึงข้อมูลจาก tbody ของตารางที่แสดงในหน้าเว็บ
            $("#example tbody tr").each(function() {
                let rowData = [];
                $(this).find("td").each(function(index, td) {
                    let cellText = $(td).html(); // ใช้ .html() แทน .text()

                    // แปลง <br> เป็น \n ในข้อมูล
                    cellText = cellText.replace(/<br>/g, "\n");

                    // แปลงค่าที่ได้เพื่อแสดงเป็นหลายบรรทัด
                    rowData.push(cellText); // เก็บข้อมูลในแต่ละแถว
                });

                // เพิ่มแถวข้อมูลลงใน Excel
                worksheet.addRow(rowData);
            });

            // ปรับความกว้างของคอลัมน์
            worksheet.columns.forEach(column => {
                column.width = 20;
            });

            worksheet.eachRow(function(row, rowNumber) {
                if (rowNumber <= 5) {
                    return;
                }

                row.eachCell(function(cell, colNumber) {
                    cell.alignment = {
                        wrapText: true, // เปิดใช้งาน wrapText เพื่อแสดงข้อความหลายบรรทัด
                        vertical: 'top', // จัดตำแหน่งข้อความให้ชิดด้านบน
                        horizontal: 'left' // จัดตำแหน่งข้อความให้ชิดด้านซ้าย

                    };
                    cell.font = {
                        name: 'TH Sarabun New', // ฟอนต์
                        size: 11 // ขนาดฟอนต์
                    };
                    cell.border = {
                        top: {
                            style: 'thin',
                            color: {
                                argb: '000000'
                            }
                        },
                        left: {
                            style: 'thin',
                            color: {
                                argb: '000000'
                            }
                        },
                        bottom: {
                            style: 'thin',
                            color: {
                                argb: '000000'
                            }
                        },
                        right: {
                            style: 'thin',
                            color: {
                                argb: '000000'
                            }
                        }
                    };
                });
            });

            // บันทึกไฟล์เป็น Excel และให้ดาวน์โหลด
            workbook.xlsx.writeBuffer().then(function(buffer) {
                let blob = new Blob([buffer], {
                    type: "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet"
                });
                let link = $("<a>").attr({
                    href: URL.createObjectURL(blob),
                    download: "รายงานแสดงข้อมูลใบเปลี่ยนแปลง(ป/ป).xlsx"
                })[0];

                link.click();
            });
        });




        $('#search-job').on('click', function() {
            let selectedJobs = $('#jon_no_select').val(); // Get selected job numbers
            let startDate = $('#date_start').val(); // Get start date
            let endDate = $('#date_end').val(); // Get end date

            // // ตรวจสอบว่าได้เลือก Job หรือไม่
            if (startDate == '') {
                Swal.fire({
                    icon: 'warning',
                    text: 'กรุณาเลือก StartDate',
                    confirmButtonText: 'ตกลง'
                });
                return; // หยุดการทำงานต่อ
            }

            if (endDate == '') {
                Swal.fire({
                    icon: 'warning',
                    text: 'กรุณาเลือก DateEnd',
                    confirmButtonText: 'ตกลง'
                });
                return; // หยุดการทำงานต่อ
            }

            var division = <?php echo json_encode(DivisionList()); ?>;
            // console.log(division);

            // ดึงข้อมูลมาแสดง
            $.ajax({
                url: "./Report_totel",
                type: "POST",
                data: {
                    selectedJobs: selectedJobs,
                    startDate: startDate,
                    endDate: endDate
                },
                beforeSend: function() {
                    $("#loadingOverlay").show(); // แสดง Loading ก่อนส่งคำขอ
                },
                success: function(response) {
                    $("#loadingOverlay").hide(); // ซ่อน Loading เมื่อโหลดเสร็จ
                    // console.log(response);

                    var value = response.data;

                    var tbody = '';

                    // $.each(response.data, function(key, array) {


                    $.each(response.data, function(index, row) {
                        var DetailIron = "";
                        var DetailElec = "";
                        var DetailModel = "";
                        var DetailBusbar = "";
                        var DetailClosingsheet = "";
                        // แปลงข้อมูล

                        // ตัวที่1 row.tc_name
                        let tc_nameString = JSON.parse(row.tc_name);
                        if (Array.isArray(tc_nameString)) { //ตรวจสอบว่าเป็น Array ถ้าใช่ true ไม่ใช่ false
                            tc_name = tc_nameString.join(", ");
                        } else {
                            tc_name = row.tc_name; // กรณีที่เป็น string อยู่แล้ว
                        }

                        // ตัวที่ 2 status_product
                        let status_product = JSON.parse(row.status_product);

                        // ตัวที่ 3 row.products
                        let products = "";
                        if (Array.isArray(row.products) && row.products.length > 1) {
                            // ถ้ามีหลายค่าในอาร์เรย์ ให้ใช้ .join("<br>") แทรก <br> ระหว่างค่า
                            products = row.products.join("<br>");
                        } else {
                            // ถ้ามีค่าหนึ่งเดียว หรือไม่ใช่อาร์เรย์ ใช้ค่าเดิม
                            products = row.products;
                        }


                        let status_job = "";
                        if (Array.isArray(row.status_job) && row.status_job.length > 1) {
                            // ถ้ามีหลายค่าในอาร์เรย์ ให้ใช้ .join("<br>") แทรก <br> ระหว่างค่า
                            status_job = row.status_job.join("<br>");
                        } else {
                            // ถ้ามีค่าหนึ่งเดียว หรือไม่ใช่อาร์เรย์ ใช้ค่าเดิม
                            status_job = row.status_job;
                        }



                        var date_arr = JSON.parse(row.data_detail);
                        var Ironwork = "";
                        var Electrical = "";
                        var Model = "";
                        var Busbar = "";
                        var Closingsheet = "";
                        var reason = "";
                        var relatedarray = row.related.divitext;
                        var relatedother = row.related.divitext_other;

                        var relateds = "";
                        relatedarray.forEach(function(item, key) {
                            // console.log(division[item]['Division_Name']);
                            relateds += division[item]['Division_Name'] + "<br>";
                        });

                        if (relatedother) {
                            relateds += relatedother + "<br>";
                        }

                        if (relateds.length > 0) {
                            relateds = relateds.slice(0, -4);
                        }


                        date_arr.forEach(function(item, key) {
                            let inputValue = item.inputValue;
                            // reason = item.inputChk;
                            // let inputChk = row.data_detail[0].inputChk != null ? row.data_detail[0].inputChk : '';

                            if (item.problem == "Ironwork") {
                                Ironwork = item.problem;
                                if (inputValue.increase.length > 0 && inputValue.reduce.length > 0) {
                                    DetailIron = "เพิ่ม,ลด";
                                } else if (inputValue.increase.length > 0) {
                                    DetailIron = "เพิ่ม";
                                } else if (inputValue.reduce.length > 0) {
                                    DetailIron = "ลด";
                                } else if (inputValue.other.length > 0) {
                                    DetailIron = inputValue.other;
                                }

                            } else if (item.problem == "Electrical") {
                                Electrical = item.problem;

                                if (inputValue.increase.length > 0 && inputValue.reduce.length > 0) {
                                    DetailElec = "เพิ่ม,ลด";
                                } else if (inputValue.increase.length > 0) {
                                    DetailElec = "เพิ่ม";
                                } else if (inputValue.reduce.length > 0) {
                                    DetailElec = "ลด";
                                } else if (inputValue.other.length > 0) {
                                    DetailElec = inputValue.other;
                                }
                            } else if (item.problem == "Model") {
                                Model = item.problem;

                                if (inputValue.increase.length > 0 && inputValue.reduce.length > 0) {
                                    DetailModel = "เพิ่ม,ลด";
                                } else if (inputValue.increase.length > 0) {
                                    DetailModel = "เพิ่ม";
                                } else if (inputValue.reduce.length > 0) {
                                    DetailModel = "ลด";
                                } else if (inputValue.other.length > 0) {
                                    DetailModel = inputValue.other;
                                }
                            } else if (item.problem == "Busbar") {
                                Busbar = item.problem;

                                if (inputValue.increase.length > 0 && inputValue.reduce.length > 0) {
                                    DetailBusbar = "เพิ่ม,ลด";
                                } else if (inputValue.increase.length > 0) {
                                    DetailBusbar = "เพิ่ม";
                                } else if (inputValue.reduce.length > 0) {
                                    DetailBusbar = "ลด";
                                } else if (inputValue.other.length > 0) {
                                    DetailBusbar = inputValue.other;
                                }

                            } else if (item.problem == "Closingsheet") {
                                Closingsheet = item.problem;

                                if (inputValue.increase.length > 0 && inputValue.reduce.length > 0) {
                                    DetailClosingsheet = "เพิ่ม,ลด";
                                } else if (inputValue.increase.length > 0) {
                                    DetailClosingsheet = "เพิ่ม";
                                } else if (inputValue.reduce.length > 0) {
                                    DetailClosingsheet = "ลด";
                                } else if (inputValue.other.length > 0) {
                                    DetailClosingsheet = inputValue.other;
                                }
                            }

                        });

                        date_arr[0]['inputChk'].forEach(function(item, key) {
                            reason += CauseList[item] != undefined ? CauseList[item]['Cause_Name'] + "<br>" : item + "<br>";
                        });

                        tbody += `
                            <tr>
                                <td>${row.CR_no}</td>
                                <td>${row.doc_no}</td>
                                <td>${row.jobno}</td>
                                <td>${row.project_name}</td>
                                <td>${row.sales_name}</td>
                                <td>${row.customer_name}</td>
                                <td>${row.rev}</td>
                                <td>${row.ncr_no}</td>
                              <td>${row.wa_no.split(',').join('<br>')}</td>
                               <td>${Array.isArray(row.serial_info) && row.serial_info.length > 1 ? row.serial_info.join("<br>") : row.serial_info[0]}</td>
                                <td>${tc_name}</td>
                                <td>${products}</td>
                                <td>${status_job}</td>
                                <td>${status_product}</td>
                                <td>${DetailElec}</td>
                                <td>${DetailModel}</td>
                                <td>${DetailBusbar}</td>
                                <td>${DetailIron}</td>
                                <td>${DetailClosingsheet}</td>
                                <td>${reason}</td>
                                <td>${row.details}</td>
                                <td>${row.cost}</td>
                                <td>${row.job_remark}</td>
                                <td>${row.expenses}</td>
                                <td>${row.expenses_total}</td>
                                <td>${relateds}</td>
                                <td>${row.expensesummary && row.expensesummary.doc_charge ? row.expensesummary.doc_charge : '-'}</td>
                                <td>${row.expensesummary && row.expensesummary.doc_newwork ? row.expensesummary.doc_newwork : '-'}</td>
                                <td>${row.expensesummary && row.expensesummary.inasmuch ? row.expensesummary.inasmuch : '-'}</td>

                            </tr>`;
                    });
                    // });

                    $('#example').DataTable().destroy();
                    $("#example tbody").html(tbody);
                    $('#example').DataTable({
                        "ordering": false,
                        "pageLength": 10,
                        "lengthMenu": [
                            [10, 50, 100, 1000, -1],
                            [10, 50, 100, 1000, "ทั้งหมด"]
                        ]
                    });
                    // table.ajax.reload();
                }
            });
        });
    </script>
</body>

</html>