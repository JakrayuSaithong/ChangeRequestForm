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
                                        <div class="mt-4 col-12 col-md-6 fs-5 fw-semibold text-gray-600">
                                            <label for="jon_no_select" class="form-label"><i class="fas fa-file-alt fs-4"></i> Job No.</label>
                                            <select class="form-select" id="jon_no_select" name="jon_no_select[]" multiple>
                                                <?php foreach ($select_job as $key => $value) { ?>
                                                    <option value="<?php echo $value; ?>"><?php echo $value; ?></option>
                                                <?php } ?>
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
            $('#jon_no_select').select2({
                placeholder: "Select Job No.",
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

                // สร้าง Workbook และ Worksheet
                const ExcelJS = window.ExcelJS;
                const workbook = new ExcelJS.Workbook();
                const worksheet = workbook.addWorksheet('Report');

                // console.log(worksheet);

                worksheet.getColumn(1).width = 11.33; // คอลัมน์ A
                worksheet.getColumn(2).width = 13.00; // คอลัมน์ B
                worksheet.getColumn(3).width = 14.00; // คอลัมน์ C
                worksheet.getColumn(4).width = 21.00; // คอลัมน์ D
                worksheet.getColumn(5).width = 37.00; // คอลัมน์ E
                worksheet.getColumn(6).width = 12.83; // คอลัมน์ F
                worksheet.getColumn(7).width = 10.67; // คอลัมน์ G
                worksheet.getColumn(8).width = 9.00; // คอลัมน์ H
                worksheet.getColumn(9).width = 9.00; // คอลัมน์ I
                worksheet.getColumn(10).width = 9.00; // คอลัมน์ J
                worksheet.getColumn(11).width = 9.00; // คอลัมน์ K
                worksheet.getColumn(12).width = 9.00; // คอลัมน์ L
                worksheet.getColumn(13).width = 9.00; // คอลัมน์ M
                worksheet.getColumn(14).width = 9.00; // คอลัมน์ N
                worksheet.getColumn(15).width = 9.00; // คอลัมน์ O
                worksheet.getColumn(16).width = 6.83; // คอลัมน์ P
                worksheet.getColumn(17).width = 13.00; // คอลัมน์ Q

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
                    name: 'Microsoft Sans Serif',
                    size: 8
                }

                var alignmentStyle = {
                    vertical: 'middle',
                    horizontal: 'center'
                }

                worksheet.mergeCells('A1:Q1');
                worksheet.getCell('A1').value = 'EN0102-สรุปตาม Job No.';
                worksheet.getCell('A1').alignment = {
                    horizontal: 'left',
                    vertical: 'middle'
                };
                worksheet.getCell('A1').font = {
                    name: 'Microsoft Sans Serif',
                    size: 12,
                    bold: true
                };

                worksheet.mergeCells('A2:Q2');
                worksheet.getCell('A2').value = `วันที่ : ${date_start} - ${date_end}`;
                worksheet.getCell('A2').alignment = {
                    horizontal: 'left',
                    vertical: 'middle'
                };
                worksheet.getCell('A2').font = {
                    name: 'Microsoft Sans Serif',
                    size: 9,
                    bold: true
                };

                worksheet.mergeCells('A3:A4');
                worksheet.getCell('A3').value = `วันที่`;
                worksheet.getCell('A3').alignment = alignmentStyle;
                worksheet.getCell('A3').font = fontStyle;
                worksheet.getCell('A3').border = borderStyle;

                worksheet.mergeCells('B3:B4');
                worksheet.getCell('B3').value = `W/A`;
                worksheet.getCell('B3').alignment = alignmentStyle;
                worksheet.getCell('B3').font = fontStyle;
                worksheet.getCell('B3').border = borderStyle;

                worksheet.mergeCells('C3:C4');
                worksheet.getCell('C3').value = `เลขที่ ป/ป`;
                worksheet.getCell('C3').alignment = alignmentStyle;
                worksheet.getCell('C3').font = fontStyle;
                worksheet.getCell('C3').border = borderStyle;

                worksheet.mergeCells('D3:D4');
                worksheet.getCell('D3').value = `เนื่องจาก`;
                worksheet.getCell('D3').alignment = alignmentStyle;
                worksheet.getCell('D3').font = fontStyle;
                worksheet.getCell('D3').border = borderStyle;

                worksheet.mergeCells('E3:E4');
                worksheet.getCell('E3').value = `รายละเอียด`;
                worksheet.getCell('E3').alignment = alignmentStyle;
                worksheet.getCell('E3').font = fontStyle;
                worksheet.getCell('E3').border = borderStyle;

                worksheet.mergeCells('F3:G3');
                worksheet.getCell('F3').value = `มูลค่าการเปลี่ยนแปลง(รวมสุทธิ)`;
                worksheet.getCell('F3').alignment = alignmentStyle;
                worksheet.getCell('F3').font = fontStyle;
                worksheet.getCell('F3').border = borderStyle;

                worksheet.getCell('F4').value = `CHI`;
                worksheet.getCell('F4').alignment = alignmentStyle;
                worksheet.getCell('F4').font = fontStyle;
                worksheet.getCell('F4').border = borderStyle;

                worksheet.getCell('G4').value = `CHO`;
                worksheet.getCell('G4').alignment = alignmentStyle;
                worksheet.getCell('G4').font = fontStyle;
                worksheet.getCell('G4').border = borderStyle;

                worksheet.mergeCells('H3:P3');
                worksheet.getCell('H3').value = `หัวข้อเปลี่ยนแปลง`;
                worksheet.getCell('H3').alignment = alignmentStyle;
                worksheet.getCell('H3').font = fontStyle;
                worksheet.getCell('H3').border = borderStyle;

                worksheet.getCell('H4').value = `อุปกรณ์ไฟฟ้า`;
                worksheet.getCell('H4').alignment = alignmentStyle;
                worksheet.getCell('H4').font = fontStyle;
                worksheet.getCell('H4').border = borderStyle;

                worksheet.getCell('I4').value = `จากลูกค้า`;
                worksheet.getCell('I4').alignment = alignmentStyle;
                worksheet.getCell('I4').font = fontStyle;
                worksheet.getCell('I4').border = borderStyle;

                worksheet.getCell('J4').value = `เครื่องจักร/เครื่องมือ/อุปกรณ์`;
                worksheet.getCell('J4').alignment = alignmentStyle;
                worksheet.getCell('J4').font = fontStyle;
                worksheet.getCell('J4').border = borderStyle;

                worksheet.getCell('K4').value = `งานเหล็ก`;
                worksheet.getCell('K4').alignment = alignmentStyle;
                worksheet.getCell('K4').font = fontStyle;
                worksheet.getCell('K4').border = borderStyle;

                worksheet.getCell('L4').value = `พนง ทำงานผิดพลาด`;
                worksheet.getCell('L4').alignment = alignmentStyle;
                worksheet.getCell('L4').font = fontStyle;
                worksheet.getCell('L4').border = borderStyle;

                worksheet.getCell('M4').value = `สภาพแวดล้อม`;
                worksheet.getCell('M4').alignment = alignmentStyle;
                worksheet.getCell('M4').font = fontStyle;
                worksheet.getCell('M4').border = borderStyle;

                worksheet.getCell('N4').value = `แบบ`;
                worksheet.getCell('N4').alignment = alignmentStyle;
                worksheet.getCell('N4').font = fontStyle;
                worksheet.getCell('N4').border = borderStyle;

                worksheet.getCell('O4').value = `วัตถุดิบไม่ได้คุณภาพ`;
                worksheet.getCell('O4').alignment = alignmentStyle;
                worksheet.getCell('O4').font = fontStyle;
                worksheet.getCell('O4').border = borderStyle;

                worksheet.getCell('P4').value = `อื่นๆ`;
                worksheet.getCell('P4').alignment = alignmentStyle;
                worksheet.getCell('P4').font = fontStyle;
                worksheet.getCell('P4').border = borderStyle;

                worksheet.getCell('Q3').value = ``;
                worksheet.getCell('Q3').alignment = alignmentStyle;
                worksheet.getCell('Q3').font = fontStyle;
                worksheet.getCell('Q3').border = borderStyle;

                worksheet.getCell('Q4').value = `หมายเหตุ`;
                worksheet.getCell('Q4').alignment = alignmentStyle;
                worksheet.getCell('Q4').font = fontStyle;
                worksheet.getCell('Q4').border = borderStyle;

                const table = document.getElementById('kt_datatable');
                const rows = [];
                for (let i = 0, row; row = table.rows[i]; i++) {
                    const rowData = [];
                    for (let j = 0, col; col = row.cells[j]; j++) {
                        let cellText = col.innerText.trim();
                        cellText = cellText.replace(/<br\s*\/?>/g, '\n');
                        rowData.push({
                            value: cellText,
                            colspan: col.colSpan || 1,
                            rowspan: col.rowSpan || 1,
                            className: col.className
                        });
                    }
                    rows.push(rowData);
                }

                rows.forEach((rowData, rowIndex) => {
                    let currentRow = worksheet.addRow([]);

                    let colIndex = 1;
                    rowData.forEach(cell => {
                        const currentCell = currentRow.getCell(colIndex);
                        currentCell.value = cell.value;

                        // const cellElement = table.rows[rowIndex]?.cells[colIndex - 1];
                        // const cellClass = cellElement?.className || '';

                        if (cell.colspan > 1 || cell.rowspan > 1 || cell.className.includes('col-titel') || cell.className.includes('col-sum') || cell.className.includes('col-footer')) {
                            const startCell = worksheet.getCell(currentRow.number, colIndex);
                            const endCell = worksheet.getCell(currentRow.number + cell.rowspan - 1, colIndex + cell.colspan - 1);
                            worksheet.mergeCells(startCell.address + ':' + endCell.address);

                            // startCell.fill = {
                            //     type: 'pattern',
                            //     pattern: 'solid',
                            //     fgColor: { argb: 'AFEEEE' }
                            // };
                            if (cell.className.includes('col-sum')) {
                                // currentCell.fill = {
                                //     type: 'pattern',
                                //     pattern: 'solid',
                                //     fgColor: { argb: 'F0F8FF' }
                                // };
                            } else if (cell.className.includes('col-titel')) {
                                currentCell.fill = {
                                    type: 'pattern',
                                    pattern: 'solid',
                                    fgColor: {
                                        argb: 'AFEEEE'
                                    }
                                };
                            } else if (cell.className.includes('col-footer')) {
                                currentCell.border = borderStyle;
                                currentCell.fill = {
                                    type: 'pattern',
                                    pattern: 'solid',
                                    fgColor: {
                                        argb: 'EEE697'
                                    }
                                };
                            }
                            startCell.font = {
                                bold: true,
                                name: 'Microsoft Sans Serif',
                                size: 8
                            };
                            if (colIndex === 6 || colIndex === 7) {
                                startCell.alignment = {
                                    vertical: 'top',
                                    horizontal: 'right',
                                    wrapText: true
                                };
                            } else if (colIndex >= 8 && colIndex <= 16) {
                                startCell.alignment = {
                                    vertical: 'top',
                                    horizontal: 'center',
                                    wrapText: true
                                };
                            } else {
                                startCell.alignment = {
                                    vertical: 'middle',
                                    horizontal: 'left',
                                    wrapText: true
                                };
                            }
                        } else {
                            currentCell.border = borderStyle;
                            currentCell.font = {
                                name: 'Microsoft Sans Serif',
                                size: 8
                            };
                            if (colIndex === 6 || colIndex === 7) {
                                currentCell.alignment = {
                                    vertical: 'top',
                                    horizontal: 'right',
                                    wrapText: true
                                };
                            } else if (colIndex >= 8 && colIndex <= 16) {
                                currentCell.alignment = {
                                    vertical: 'top',
                                    horizontal: 'center',
                                    wrapText: true
                                };
                            } else {
                                currentCell.alignment = {
                                    vertical: 'top',
                                    horizontal: 'left',
                                    wrapText: true
                                };
                            }
                        }

                        colIndex += cell.colspan;
                    });
                });


                // worksheet.columns.forEach((column) => {
                //     column.width = column.values.reduce((maxWidth, val) => {
                //         const length = val ? val.toString().length : 10; // ความยาวขั้นต่ำ
                //         return Math.max(maxWidth, length);
                //     }, 10);
                // });

                workbook.xlsx.writeBuffer().then((buffer) => {
                    const blob = new Blob([buffer], {
                        type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
                    });

                    const now = new Date();
                    const formattedDate = now.toISOString().replace(/[-T:.]/g, '').slice(0, 14);

                    const link = document.createElement('a');
                    link.href = URL.createObjectURL(blob);
                    link.download = `EN0102-asf-${formattedDate}.xlsx`;
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

            $('#search-job').click(function() {
                var job_no = $('#jon_no_select').val();
                var date_start = $('#date_start').val();
                var date_end = $('#date_end').val();

                // console.log(job_no, date_start, date_end);

                if (job_no.length == 0) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Please select at least one Job No.'
                    });
                    return false;
                }

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
                    url: './select_report?token=<?php echo $_SESSION['token'] ?>',
                    type: 'POST',
                    data: {
                        job_no: job_no,
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
                        var qty_chi_cho = 0;
                        var chi_sum_all = 0;
                        var cho_sum_all = 0;
                        var qty_Electrical = 0;
                        var qty_Ironwork = 0;
                        var qty_Model = 0;
                        var qty_Closingsheet = 0;
                        var qty_doc_type = 0;
                        var qty_ncr_no = 0;

                        for (const [key, value] of Object.entries(response)) {
                            // console.log(`Job: ${key}`);

                            html_table += `
                                <tr>
                                    <td colspan="2" class="col-titel">${key}</td>
                                    <td colspan="15" class="col-titel">${value[0].project_name}</td>
                                </tr>
                            `;

                            var chi_sum = 0;
                            var cho_sum = 0;

                            value.forEach((item, index) => {
                                var dateCreate = item.dateCreate.split(' ');
                                var data_detail = JSON.parse(item.data_detail);
                                var expensesummary = item.expensesummary ? JSON.parse(item.expensesummary) : '';
                                // console.log(data_detail);

                                var wa_no_html = item.wa_no.split(',').map(wa => `<span>${wa}</span>`).join('<br>');

                                if (item.doc_type == 'CHI') {
                                    chi_sum += parseFloat(item.expenses) || 0.00;
                                    chi_sum_all += parseFloat(item.expenses) || 0.00;
                                }
                                if (item.doc_type == 'CHO') {
                                    cho_sum += parseFloat(item.expenses) || 0.00;
                                    cho_sum_all += parseFloat(item.expenses) || 0.00;
                                }

                                data_detail.find(item => item.problem == 'Electrical') ? qty_Electrical++ : '';
                                item.doc_type == 'CHO' ? qty_doc_type++ : '';
                                data_detail.find(item => item.problem === 'Ironwork') ? qty_Ironwork++ : '';
                                item.ncr_no != '' && item.ncr_no != '-' ? qty_ncr_no++ : '';
                                data_detail.find(item => item.problem === 'Model') ? qty_Model++ : '';
                                data_detail.find(item => item.problem === 'Closingsheet') ? qty_Closingsheet++ : '';

                                html_table += `
                                    <tr>
                                        <td class="text-center" style="width: 120px !important;">${dateCreate[0]}</td>
                                        <td class="text-nowrap text-center" style="width: 100px !important;">${wa_no_html}</td>
                                        <td class="text-nowrap text-center" style="width: 120px !important;">${item.doc_no}</td>
                                        <td class="text-nowrap text-center" style="width: 250px !important;">${item.rev}</td>
                                        <td style="width: 250px !important;">${item.details.replace(/\r?\n/g, '<br>')}</td>
                                        <td class="text-center" style="width: 80px !important;">${item.doc_type == 'CHI' ? formatNumberFromString(item.expenses) : '0.00' }</td>
                                        <td class="text-center" style="width: 80px !important;">${item.doc_type == 'CHO' ? formatNumberFromString(item.expenses) : '0.00' }</td>
                                        <td class="text-center" style="width: 60px !important;">${data_detail.find(item => item.problem == 'Electrical') ? 'Y' : ''}</td>
                                        <td class="text-center" style="width: 60px !important;">${item.doc_type == 'CHO' ? 'Y' : '' }</td>
                                        <td class="text-center" style="width: 60px !important;"></td>
                                        <td class="text-center" style="width: 60px !important;">${data_detail.find(item => item.problem === 'Ironwork') ? 'Y' : ''}</td>
                                        <td class="text-center" style="width: 60px !important;">${item.ncr_no != '' && item.ncr_no != '-' ? 'Y' : ''}</td>
                                        <td class="text-center" style="width: 60px !important;"></td>
                                        <td class="text-center" style="width: 60px !important;">${data_detail.find(item => item.problem === 'Model') ? 'Y' : ''}</td>
                                        <td class="text-center" style="width: 60px !important;"></td>
                                        <td class="text-center" style="width: 60px !important;">${data_detail.find(item => item.problem === 'Closingsheet') ? 'Y' : ''}</td>
                                        <td class="text-center" style="width: 80px !important;">
                                            ${expensesummary.doc_charge != '' && expensesummary.doc_charge != undefined ? expensesummary.doc_charge + '<br>' : ''}
                                            ${expensesummary.doc_newwork != '' && expensesummary.doc_newwork != undefined ? expensesummary.doc_newwork + '<br>' : ''}
                                            ${expensesummary.inasmuch != '' && expensesummary.inasmuch != undefined ? expensesummary.inasmuch + '<br>' : ''}
                                        </td>
                                    </tr>
                                `;
                            });

                            html_table += `
                                <tr>
                                    <td class="col-sum" colspan="2">จำนวน</td>
                                    <td class="col-sum" colspan="3">${value.length}</td>
                                    <td class="col-sum text-center" colspan="1">${formatNumberFromString(chi_sum.toFixed(2))}</td>
                                    <td class="col-sum text-center" colspan="1">${formatNumberFromString(cho_sum.toFixed(2))}</td>
                                    <td class="col-sum" colspan="1"></td>
                                    <td class="col-sum" colspan="1"></td>
                                    <td class="col-sum" colspan="1"></td>
                                    <td class="col-sum" colspan="1"></td>
                                    <td class="col-sum" colspan="1"></td>
                                    <td class="col-sum" colspan="1"></td>
                                    <td class="col-sum" colspan="1"></td>
                                    <td class="col-sum" colspan="1"></td>
                                    <td class="col-sum" colspan="1"></td>
                                    <td class="col-sum" colspan="1"></td>
                                </tr>
                            `;

                            qty_chi_cho += value.length;

                        }

                        html_table += `
                            <tr>
                                <td class="col-footer">จำนวนทั้งหมด</td>
                                <td class="col-footer"></td>
                                <td class="col-footer">${qty_chi_cho}</td>
                                <td class="col-footer"></td>
                                <td class="col-footer"></td>
                                <td class="col-footer">${formatNumberFromString(chi_sum_all)}</td>
                                <td class="col-footer">${formatNumberFromString(cho_sum_all)}</td>
                                <td class="col-footer">${qty_Electrical}</td>
                                <td class="col-footer">${qty_doc_type}</td>
                                <td class="col-footer"></td>
                                <td class="col-footer">${qty_Ironwork}</td>
                                <td class="col-footer">${qty_ncr_no}</td>
                                <td class="col-footer"></td>
                                <td class="col-footer">${qty_Model}</td>
                                <td class="col-footer"></td>
                                <td class="col-footer">${qty_Closingsheet}</td>
                                <td class="col-footer"></td>
                            </tr>
                        `;

                        $('#kt_datatable tbody').html(html_table);

                        Swal.close();
                    }
                });
            });

            function formatNumberFromString(text) {
                const number = parseFloat(text);
                if (isNaN(number)) {
                    return text;
                }

                return number.toLocaleString('en-US', {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                });
            }
        });
    </script>
</body>

</html>