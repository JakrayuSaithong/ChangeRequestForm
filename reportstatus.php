<?php
$select_job = select_job_project();
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

</head>

<style>
    td {
        vertical-align: middle;
    }

    .row .card {
        transition: transform 0.3s ease-out, box-shadow 0.3s ease-out;
    }

    .row .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.12);
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
                                        <div class="mt-4 col-12 col-md-3 fs-5 fw-semibold text-gray-600">
                                            <label for="status_select" class="form-label"><i class="fas fa-file-alt fs-4"></i> Status</label>
                                            <select class="form-select" id="status_select" name="status_select[]" multiple>
                                                <option value="New">New</option>
                                                <option value="Draf">Draf</option>
                                                <option value="Rework">Rework</option>
                                                <option value="Not Approve">Not Approve</option>
                                                <option value="Review">Review</option>
                                                <option value="Check">Check</option>
                                                <option value="Recheck">Recheck</option>
                                                <option value="Approve">Approve</option>
                                                <option value="Cancel">Cancel</option>
                                            </select>
                                        </div>

                                        <div class="mt-4 col-12 col-md-3 fs-5 fw-semibold text-gray-600">
                                            <label for="team_select" class="form-label"><i class="fas fa-file-alt fs-4"></i> Team</label>
                                            <select class="form-select" id="team_select" name="team_select[]" multiple>
                                                <option value="A">Team A</option>
                                                <option value="B">Team B</option>
                                                <option value="C">Team C</option>
                                                <option value="D">Team D</option>
                                                <option value="E">Team E</option>
                                                <option value="F">Team F</option>
                                                <option value="G">Team G</option>
                                                <option value="H">Team H</option>
                                                <option value="I">Team I</option>
                                                <option value="J">Team J</option>
                                                <option value="K">Team K</option>
                                                <option value="L">Team L</option>
                                                <option value="M">Team M</option>
                                                <option value="N">Team N</option>
                                                <option value="O">Team O</option>
                                                <option value="อาซีฟา">อาซีฟา</option>
                                            </select>
                                        </div>

                                        <div class="col-12 col-md-4 fs-5 fw-semibold text-gray-600 row">
                                            <div class="mt-4 col-6 fs-5 fw-semibold text-gray-600">
                                                <label for="jon_no_select" class="form-label"><i class="fa-solid fa-clock fs-4"></i> Date Start</label>
                                                <input type="date" name="date_start" id="date_start" class="form-control" value="<?php echo date('Y-m-d') ?>">
                                            </div>

                                            <div class="mt-4 col-6 fs-5 fw-semibold text-gray-600">
                                                <label for="jon_no_select" class="form-label"><i class="fa-solid fa-clock-rotate-left fs-4"></i> Date End</label>
                                                <input type="date" name="date_end" id="date_end" class="form-control" value="<?php echo date('Y-m-d') ?>">
                                            </div>
                                        </div>

                                        <div class="mt-4 col-12 col-md-2 d-flex align-items-end justify-content-center">
                                            <button type="button" id="search" class="btn btn-primary"><i class="fas fa-search fs-4 me-2"></i> Search</button>
                                        </div>
                                    </div>
                                </div>

                                <div class="card w-100">
                                    <div class="card-header">
                                        <div class="w-100 d-flex justify-content-between align-items-center">
                                            <h5 class="card-title mb-0">ตารางรายงาน</h5>
                                            <div class="d-flex align-items-center gap-3">
                                                <button type="button" class="btn btn-success btn-sm" id="export-excel"><i class="fas fa-file-excel fs-4 me-2"></i> Export Excel</button>
                                            </div>
                                        </div>

                                    </div>
                                    <div class="card-body w-100">
                                        <div class="text-center" id="no-data">
                                            <i class="fa-solid fa-magnifying-glass-chart" style="font-size: 35px;"></i>
                                            <h3 class="mt-4">ไม่มีข้อมูล</h3>
                                        </div>

                                        <div class="table-responsive w-100">
                                            <table class="table table-bordered table-hover border-primary w-100" id="kt_datatable" style="display: none;">
                                                <tbody>

                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>

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
    <script src="https://cdn.jsdelivr.net/npm/exceljs/dist/exceljs.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#status_select, #team_select').select2({
                placeholder: "Select",
                allowClear: true
            });

            $('#export-excel').click(function() {
                if ($('#kt_datatable tbody tr').length === 0) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'ไม่มีข้อมูลในตาราง',
                        text: 'กรุณาค้นหาข้อมูลก่อน Export'
                    });
                    return;
                }

                var date_start = $('#date_start').val();
                var date_end = $('#date_end').val();

                const ExcelJS = window.ExcelJS;
                const workbook = new ExcelJS.Workbook();
                const worksheet = workbook.addWorksheet('Report');

                worksheet.getColumn(1).width = 14.00; // คอลัมน์ A
                worksheet.getColumn(2).width = 9.70; // คอลัมน์ B
                worksheet.getColumn(3).width = 11.20; // คอลัมน์ C
                worksheet.getColumn(4).width = 56.20; // คอลัมน์ D
                worksheet.getColumn(5).width = 17.50; // คอลัมน์ E
                worksheet.getColumn(6).width = 12.00; // คอลัมน์ F
                worksheet.getColumn(7).width = 10.00; // คอลัมน์ G

                var borderStyle = {
                    top: {
                        style: 'thin'
                    },
                    left: {
                        style: 'thin'
                    },
                    bottom: {
                        style: 'thin'
                    },
                    right: {
                        style: 'thin'
                    }
                };

                var fontStyle = {
                    name: 'Angsana New',
                    size: 16
                }

                var alignmentStyle = {
                    vertical: 'middle',
                    horizontal: 'center'
                }

                worksheet.mergeCells('A1:G1');
                worksheet.getCell('A1').value = 'รายงานใบขอเปลี่ยนแปลง';
                worksheet.getCell('A1').alignment = {
                    horizontal: 'center',
                    vertical: 'middle'
                };
                worksheet.getCell('A1').font = {
                    name: 'Angsana New',
                    size: 16
                };
                worksheet.getCell('A1').border = borderStyle;

                worksheet.mergeCells('A2');
                worksheet.getCell('A2').value = `ID`;
                worksheet.getCell('A2').alignment = alignmentStyle;
                worksheet.getCell('A2').font = fontStyle;
                worksheet.getCell('A2').border = borderStyle;

                worksheet.mergeCells('B2');
                worksheet.getCell('B2').value = `Type`;
                worksheet.getCell('B2').alignment = alignmentStyle;
                worksheet.getCell('B2').font = fontStyle;
                worksheet.getCell('B2').border = borderStyle;

                worksheet.mergeCells('C2');
                worksheet.getCell('C2').value = `Job No.`;
                worksheet.getCell('C2').alignment = alignmentStyle;
                worksheet.getCell('C2').font = fontStyle;
                worksheet.getCell('C2').border = borderStyle;

                worksheet.mergeCells('D2');
                worksheet.getCell('D2').value = `Project Name`;
                worksheet.getCell('D2').alignment = alignmentStyle;
                worksheet.getCell('D2').font = fontStyle;
                worksheet.getCell('D2').border = borderStyle;

                worksheet.mergeCells('E2');
                worksheet.getCell('E2').value = `Sales`;
                worksheet.getCell('E2').alignment = alignmentStyle;
                worksheet.getCell('E2').font = fontStyle;
                worksheet.getCell('E2').border = borderStyle;

                worksheet.mergeCells('F2');
                worksheet.getCell('F2').value = `Wait`;
                worksheet.getCell('F2').alignment = alignmentStyle;
                worksheet.getCell('F2').font = fontStyle;
                worksheet.getCell('F2').border = borderStyle;

                worksheet.mergeCells('G2');
                worksheet.getCell('G2').value = `Status`;
                worksheet.getCell('G2').alignment = alignmentStyle;
                worksheet.getCell('G2').font = fontStyle;
                worksheet.getCell('G2').border = borderStyle;

                worksheet.getCell('A1').fill = {
                    type: 'pattern',
                    pattern: 'solid',
                    fgColor: {
                        argb: '67C9CA'
                    }
                };

                const headerFill = {
                    type: 'pattern',
                    pattern: 'solid',
                    fgColor: {
                        argb: 'F1CDB1'
                    }
                };

                ['A2', 'B2', 'C2', 'D2', 'E2', 'F2', 'G2'].forEach(cell => {
                    worksheet.getCell(cell).fill = headerFill;
                });


                const table = document.getElementById('kt_datatable');
                const tableRows = table.querySelectorAll('tbody tr');

                tableRows.forEach((row, index) => {
                    const cells = row.querySelectorAll('td');
                    const excelRow = worksheet.getRow(index + 3);

                    cells.forEach((cell, cellIndex) => {
                        const cellValue = cell.innerText.trim();
                        const excelCell = excelRow.getCell(cellIndex + 1);
                        console.log(excelCell);
                        excelCell.value = cellValue;
                        excelCell.font = fontStyle;
                        excelCell.border = borderStyle;

                        if (cellIndex === 3) {
                            excelCell.alignment = {
                                vertical: 'middle',
                                horizontal: 'left'
                            };
                        } else {
                            excelCell.alignment = alignmentStyle;
                        }
                    });
                    excelRow.commit();
                });


                workbook.xlsx.writeBuffer().then((buffer) => {
                    const blob = new Blob([buffer], {
                        type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
                    });

                    const now = new Date();
                    const formattedDate = now.toISOString().replace(/[-T:.]/g, '').slice(0, 14);

                    const link = document.createElement('a');
                    link.href = URL.createObjectURL(blob);
                    link.download = `ReportStatus.xlsx`;
                    document.body.appendChild(link);
                    link.click();
                    document.body.removeChild(link);
                }).catch((err) => {
                    console.error('Error creating Excel file:', err);
                    Swal.fire({
                        icon: 'error',
                        title: 'เกิดข้อผิดพลาด',
                        text: 'ไม่สามารถสร้างไฟล์ Excel ได้'
                    });
                });

            });

            $('#search').click(function() {
                var status_select = $('#status_select').val();
                var team_select = $('#team_select').val();
                var date_start = $('#date_start').val();
                var date_end = $('#date_end').val();

                // console.log(status_select, date_start, date_end);

                if (date_start != '' && date_end == '') {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Please select Date End.'
                    });
                    return false;
                }

                if (date_start == '' && date_end != '') {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Please select Date Start.'
                    });
                    return false;
                }

                $.ajax({
                    url: './ReportStatusApi',
                    type: 'POST',
                    data: {
                        status_select: status_select,
                        team_select: team_select,
                        date_start: date_start,
                        date_end: date_end
                    },
                    beforeSend: function() {
                        Swal.fire({
                            title: 'กำลังค้นหาข้อมูล...',
                            text: 'โปรดรอสักครู่',
                            allowOutsideClick: false,
                            didOpen: () => {
                                Swal.showLoading();
                            }
                        });
                    },
                    success: function(response) {
                        console.log(response);

                        if (response == null) {
                            $('#no-data').show();
                            $('#kt_datatable').hide();

                            Swal.fire({
                                icon: 'warning',
                                title: 'ไม่พบข้อมูล',
                                text: 'ไม่พบข้อมูลที่ต้องการค้นหา'
                            });

                            return false;
                        } else {
                            $('#no-data').hide();
                            $('#kt_datatable').show();
                        }

                        var html_table = '';

                        for (const [key, value] of Object.entries(response)) {
                            let array_sales = value['sales_name'].split(" ");
                            let targetPart = array_sales[0];

                            if (!targetPart.includes("-") && array_sales.length > 1) {
                                targetPart = array_sales[1];
                            }

                            let parts = targetPart.split("-");

                            let teamText = "Team " + parts[0];
                            if (parts.length > 1 && parts[1]) {
                                teamText += " ( " + parts[1] + " )";
                            }

                            html_table += `
                                <tr>
                                    <td class="text-center">${value['CR_no']}</td>
                                    <td class="text-center">${value['doc_type']}</td>
                                    <td class="text-center">${value['jobno']}</td>
                                    <td class="text-start">${value['project_name']}</td>
                                    <td class="text-center">${teamText}</td>
                                    <td class="text-center">${value['wait']}</td>
                                    <td class="text-center">${value['doc_status']}</td>
                                </tr>
                            `;
                        }

                        $('#kt_datatable tbody').html(html_table);

                        Swal.close();
                    }
                });
            });
        });
    </script>
</body>

</html>