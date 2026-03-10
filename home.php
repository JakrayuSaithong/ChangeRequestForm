<?php
$get_start = $_GET['start'] ?? (new DateTime('first day of this month'))->format('Y-m-d');
$get_end = $_GET['end'] ?? (new DateTime())->format('Y-m-d');

// $data = List_ChangeForm(''); //$_SESSION['ChangeRequest_code']
// $data_draft = List_ChangeForm_SaveDeaft($_SESSION['ChangeRequest_code']);

// $status_show = $_GET['status'] ?? '';

// if($_SESSION['ChangeRequest_code'] == '660500122'){
//     echo "<pre>";
//     print_r(List_ChangeForm('', $get_start, $get_end));
//     exit();
// }

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

    .stats-icon {
        height: 60px;
        width: 60px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
</style>

<body id="kt_body" data-kt-app-header-stacked="true" data-kt-app-header-primary-enabled="true" data-kt-app-header-secondary-enabled="false" data-kt-app-toolbar-enabled="true" class="app-default">
    <?php include_once 'layout/modechange.php'; ?>
    <!--begin::App-->
    <div class="d-flex flex-column flex-root app-root" id="kt_app_root">
        <!--begin::Page-->
        <div class="app-page  flex-column flex-column-fluid " id="kt_app_page">
            <?php include_once 'layout/navbar.php';
            ?>
            <!--begin::Wrapper-->
            <div class="app-wrapper  flex-column flex-row-fluid " id="kt_app_wrapper">
                <!--begin::Wrapper container-->
                <div class="container-sm d-flex flex-row p-0">
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
                                                <a href="index.php?DataE=<?php echo $_SESSION['DataE'] ?>" class="text-muted text-hover-primary">
                                                    หน้าแรก
                                                </a>
                                            </li>
                                        </ul>
                                    </div>

                                    <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3 d-none">
                                        <a type="button" class="btn btn-primary" href="https://innovation.asefa.co.th/ChangeRequestForm/InsertForm?DataE=<?php echo $_SESSION['DataE'] ?>">
                                            <i class="bi bi-clipboard-plus-fill fs-4"></i> เพิ่มใบขอเปลี่ยนแปลง
                                        </a>
                                    </div>

                                </div>
                            </div>
                            <div id="kt_app_content" class="app-content  flex-column-fluid ">
                                <div class="card">
                                    <!--begin::Body-->

                                    <!--begin::About-->
                                    <div class="mb-18" style="padding: 15px;">
                                        <!--begin::Wrapper-->
                                        <div class="mb-10">
                                            <!--begin::Top-->
                                            <!-- <div class="text-center mb-3">
                                                <h3 class="fs-2hx text-dark mb-5">รายการใบขอเปลี่ยนแปลง</h3>
                                            </div> -->

                                            <div class="row row-cols-2 row-cols-sm-2 row-cols-md-4 row-cols-xl-4 g-4">
                                                <!-- New -->
                                                <div class="col">
                                                    <div class="card h-100 border border-2" style="border-left: 6px solid #0d6efd !important;">
                                                        <a href="#" class="status-filter" data-status="New">
                                                            <div class="card-body px-4 py-4" style="color: #0d6efd !important;">
                                                                <div class="d-flex align-items-center">
                                                                    <div class="stats-icon p-3 rounded-3" style="background-color: #e7f1ff !important;">
                                                                        <i class="fa-solid fa-star" style="color: #0d6efd !important; font-size: 1.5rem !important;"></i>
                                                                    </div>
                                                                    <div class="ms-5">
                                                                        <div class="d-flex flex-column">
                                                                            <span class="card-title fw-semibold">New</span>
                                                                            <h2 class="card-text fw-bold" id="text_count_new"></h2>
                                                                            <span class="fw-semibold">แจ้งงานเข้ามาใหม่</span>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </a>
                                                    </div>
                                                </div>

                                                <!-- Draf -->
                                                <div class="col">
                                                    <div class="card h-100 border border-2" style="border-left: 6px solid #e59866 !important;">
                                                        <a href="#" class="status-filter" data-status="Draf">
                                                            <div class="card-body px-4 py-4" style="color: #e59866;">
                                                                <div class="d-flex align-items-center">
                                                                    <div class="stats-icon p-3 rounded-3" style="background-color: #fff0e3 !important;">
                                                                        <i class="fa-solid fa-code-pull-request" style="color: #e67e22 !important; font-size: 1.5rem !important;"></i>
                                                                    </div>
                                                                    <div class="ms-5">
                                                                        <div class="d-flex flex-column">
                                                                            <span class="card-title fw-semibold">Draf</span>
                                                                            <h2 class="card-text fw-bold" id="text_count_draf"></h2>
                                                                            <span class="fw-semibold">งานที่มีการกดรับ</span>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </a>
                                                    </div>
                                                </div>

                                                <!-- Rework -->
                                                <div class="col">
                                                    <div class="card h-100 border border-2" style="border-left: 6px solid #f1c40f !important;">
                                                        <a href="#" class="status-filter" data-status="Rework">
                                                            <div class="card-body px-4 py-4" style="color: #f1c40f !important;">
                                                                <div class="d-flex align-items-center">
                                                                    <div class="stats-icon p-3 rounded-3" style="background-color: #fff8dc !important;">
                                                                        <i class="fa-solid fa-repeat" style="color: #f1c40f !important; font-size: 1.5rem !important;"></i>
                                                                    </div>
                                                                    <div class="ms-5">
                                                                        <div class="d-flex flex-column">
                                                                            <span class="card-title fw-semibold">Rework</span>
                                                                            <h2 class="card-text fw-bold" id="text_count_rework"></h2>
                                                                            <span class="fw-semibold">งานที่มีการ Rework</span>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </a>
                                                    </div>
                                                </div>

                                                <!-- Review -->
                                                <div class="col">
                                                    <div class="card h-100 border border-2" style="border-left: 6px solid #9b59b6 !important;">
                                                        <a href="#" class="status-filter" data-status="Review">
                                                            <div class="card-body px-4 py-4" style="color: #9b59b6 !important;">
                                                                <div class="d-flex align-items-center">
                                                                    <div class="stats-icon p-3 rounded-3" style="background-color: #faecff !important;">
                                                                        <i class="fa-solid fa-clock" style="color: #9b59b6 !important; font-size: 1.5rem !important;"></i>
                                                                    </div>
                                                                    <div class="ms-5">
                                                                        <div class="d-flex flex-column">
                                                                            <span class="card-title fw-semibold">Review</span>
                                                                            <h2 class="card-text fw-bold" id="text_count_review"></h2>
                                                                            <span class="fw-semibold">รอเจ้าหน้าที่เทคนิค</span>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </a>
                                                    </div>
                                                </div>

                                                <!-- Check -->
                                                <div class="col">
                                                    <div class="card h-100 border border-2" style="border-left: 6px solid #0dcaf0 !important;">
                                                        <a href="#" class="status-filter" data-status="Check">
                                                            <div class="card-body px-4 py-4" style="color: #0dcaf0 !important;">
                                                                <div class="d-flex align-items-center">
                                                                    <div class="stats-icon p-3 rounded-3" style="background-color: #eafbff !important;">
                                                                        <i class="fa-solid fa-user-check" style="color: #0dcaf0 !important; font-size: 1.5rem !important;"></i>
                                                                    </div>
                                                                    <div class="ms-5">
                                                                        <div class="d-flex flex-column">
                                                                            <span class="card-title fw-semibold">Check</span>
                                                                            <h2 class="card-text fw-bold" id="text_count_check"></h2>
                                                                            <span class="fw-semibold">รอผจก.แผนก/ฝ่ายเทคนิค</span>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </a>
                                                    </div>
                                                </div>

                                                <!-- Recheck -->
                                                <div class="col">
                                                    <div class="card h-100 border border-2" style="border-left: 6px solid #05abb3 !important;">
                                                        <a href="#" class="status-filter" data-status="Recheck">
                                                            <div class="card-body px-4 py-4" style="color: #05abb3 !important;">
                                                                <div class="d-flex align-items-center">
                                                                    <div class="stats-icon p-3 rounded-3" style="background-color: #eafeff !important;">
                                                                        <i class="fa-solid fa-arrows-rotate" style="color: #05abb3 !important; font-size: 1.5rem !important;"></i>
                                                                    </div>
                                                                    <div class="ms-5">
                                                                        <div class="d-flex flex-column">
                                                                            <span class="card-title fw-semibold">Recheck</span>
                                                                            <h2 class="card-text fw-bold" id="text_count_recheck"></h2>
                                                                            <span class="fw-semibold">รอพนักงานขาย</span>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </a>
                                                    </div>
                                                </div>

                                                <!-- Approve -->
                                                <div class="col">
                                                    <div class="card h-100 border border-2" style="border-left: 6px solid #28b463 !important;">
                                                        <a href="#" class="status-filter" data-status="Approve">
                                                            <div class="card-body px-4 py-4" style="color: #28b463 !important;">
                                                                <div class="d-flex align-items-center">
                                                                    <div class="stats-icon p-3 rounded-3" style="background-color: #e9fff2 !important;">
                                                                        <i class="fa-solid fa-file-circle-check" style="color: #28b463 !important; font-size: 1.5rem !important;"></i>
                                                                    </div>
                                                                    <div class="ms-5">
                                                                        <div class="d-flex flex-column">
                                                                            <span class="card-title fw-semibold">Approve</span>
                                                                            <h2 class="card-text fw-bold" id="text_count_approve"><?php echo get_status_count('Approve') ?></h2>
                                                                            <span class="fw-semibold">รอผจก.แผนก/ฝ่ายขาย</span>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </a>
                                                    </div>
                                                </div>

                                                <!-- Close -->
                                                <div class="col">
                                                    <div class="card h-100 border border-2" style="border-left: 6px solid #7f8c8d !important;">
                                                        <a href="#" class="status-filter" data-status="Close">
                                                            <div class="card-body px-4 py-4" style="color: #7f8c8d !important;">
                                                                <div class="d-flex align-items-center">
                                                                    <div class="stats-icon p-3 rounded-3" style="background-color: #efefef !important;">
                                                                        <i class="fa-solid fa-briefcase" style="color: #7f8c8d !important; font-size: 1.5rem !important;"></i>
                                                                    </div>
                                                                    <div class="ms-5">
                                                                        <div class="d-flex flex-column">
                                                                            <span class="card-title fw-semibold">Close</span>
                                                                            <h2 class="card-text fw-bold" id="text_count_close"></h2>
                                                                            <span class="fw-semibold">งานที่ปิดจบเรียบร้อยแล้ว</span>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card">
                                            <div class="card-header p-4" style="align-items: center !important; min-height: 40px !important; background-color: rgb(13, 110, 253) !important;">
                                                <h5 class="m-0 font-weight-bold text-white">รายการงานแจ้ง</h5>
                                                <div class="col-lg-2">
                                                    <select class="form-select" id="urgentFilter">
                                                        <option value="urgent">งานด่วน</option>
                                                        <option value="all" selected>งานทั้งหมด</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="card-body border border-1" style="border-color: rgb(13, 110, 253) !important;">
                                                <div class="fs-5 fw-semibold text-gray-600">
                                                    <div class="table-responsive">
                                                        <table id="example" class="table table-striped display" style="width:100%; font-size: 12px !important;">
                                                            <thead>
                                                                <tr>
                                                                    <td>ID</td>
                                                                    <td>Mail</td>
                                                                    <td>งานด่วน</td>
                                                                    <td>Change No.</td>
                                                                    <td>Type</td>
                                                                    <td>Job No.</td>
                                                                    <td>Project Name</td>
                                                                    <td>Sales</td>
                                                                    <td>Wait</td>
                                                                    <td>Status</td>
                                                                    <td>Action</td>
                                                                </tr>
                                                            </thead>
                                                            <tbody>

                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
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
    <script>
        $(document).ready(function() {
            getCount_status();

            let selectedStatus = null;

            $('.status-filter').on('click', function(e) {
                e.preventDefault();
                selectedStatus = $(this).data('status');
                getCount_status();
                table.ajax.reload();
            });

            var table = $('#example').DataTable({
                serverSide: true,
                processing: true,
                responsive: true,
                ajax: {
                    url: "./list_change_form",
                    type: "POST",
                    data: function(d) {
                        d.DataE = "<?php echo $_GET['DataE']; ?>";
                        d.urgentFilter = $('#urgentFilter').val();
                        d.status = selectedStatus;
                    }
                },
                language: {
                    processing: '<i class="fa fa-spinner fa-spin fa-3x fa-fw text-primary"></i><span class="sr-only">กำลังโหลด...</span>'
                },
                scrollX: true,
                columns: [{
                        data: "CR_no"
                    },
                    {
                        data: "send_mail"
                    },
                    {
                        data: "urgentwork"
                    },
                    {
                        data: "doc_no"
                    },
                    {
                        data: "doc_type"
                    },
                    {
                        data: "jobno"
                    },
                    {
                        data: "project_name"
                    },
                    {
                        data: "sales_name"
                    },
                    {
                        data: "waiting_person"
                    },
                    {
                        data: "doc_status"
                    },
                    {
                        data: "action"
                    }
                ],
                columnDefs: [{
                        targets: [0, 3, 5, 7, 10],
                        className: "text-nowrap"
                    },
                    {
                        targets: [8],
                        className: "text-nowrap text-center"
                    },
                    {
                        targets: [1, 2, 9],
                        className: "text-center"
                    }
                ]
            });

            $('#urgentFilter').on('change', function() {
                getCount_status();
                table.ajax.reload();
            });

            async function getCount_status() {
                try {
                    const response = await fetch("./count_status");
                    const data = await response.json();

                    $.each(data, function(index, value) {
                        $('#text_count_' + index).text(value);
                    });

                } catch (error) {
                    console.error("Error:", error);
                }
            }

        });
    </script>
</body>

</html>