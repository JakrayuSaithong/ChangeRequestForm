<?php
$data = get_Waiting($_SESSION['ChangeRequest_code']); //$_SESSION['ChangeRequest_code']

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
            <?php include_once 'layout/navbar.php'; ?>
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
                                            หน้ารอการอนุมัติ
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
                                                    <!-- <div class="table-responsive"> -->
                                                    <table id="example" class="table table-striped display" style="width:100%">
                                                        <thead>
                                                            <tr>
                                                                <td>ID</td>
                                                                <td>Mail</td>
                                                                <td>งานด่วน</td>
                                                                <td>Type</td>
                                                                <td>Job No.</td>
                                                                <td>Project Name</td>
                                                                <td>Sales</td>
                                                                <td>Wait</td>
                                                                <td>Status</td>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php
                                                            foreach ($data as $key => $value) {
                                                                $status_time = Get_Time_Status($value['CR_no'], $value['doc_status']);
                                                            ?>
                                                                <tr>
                                                                    <td class="text-nowrap"><a href="./ViewForm?DataE=<?php echo $_GET['DataE'] ?>&CR_no=<?php echo $value['CR_no'] ?>" target="_blank"><?php echo $value['CR_no'] ?></a></td>
                                                                    <td><?php echo $value['send_mail'] == '1' ? '<i class="fa-solid fa-square-check fs-2 text-success"></i>' : '' ?></td>
                                                                    <td><?php echo $value['urgentwork'] == '1' ? '<i class="fa-solid fa-square-check fs-2 text-danger"></i>' : '' ?></td>
                                                                    <td class="text-center"><?php echo $value['doc_type'] ?></td>
                                                                    <td class="text-nowrap"><?php echo strtoupper($value['jobno']) ?></td>
                                                                    <td><?php echo $value['project_name'] ?></td>
                                                                    <td class="text-nowrap">
                                                                        <?php
                                                                        $array_sales = explode(" ", $value['sales_name']);
                                                                        $array_sales = explode("-", $array_sales[0]);
                                                                        echo "Team " . $array_sales[0] . " ( " . $array_sales[1] . " ) ";
                                                                        ?>
                                                                    </td>
                                                                    <td class="text-center text-nowrap">
                                                                        <?php
                                                                        $apporve_arr_1 = json_decode($value['CR_Approve1'], true);
                                                                        $apporve_arr_2 = json_decode($value['CR_Approve2'], true);
                                                                        $apporve_arr_3 = json_decode($value['CR_Approve3'], true);
                                                                        $apporve_arr_4 = json_decode($value['CR_Approve4'], true);
                                                                        $apporve_arr_5 = json_decode($value['CR_Approve5'], true);

                                                                        if ($value['doc_status'] == 'New') {
                                                                            echo 'Admin TC';
                                                                        } elseif ($value['doc_status'] == 'Draf') {
                                                                            // echo mydata($status_time['Status_User'])['FirstName'];
                                                                            echo 'Admin TC';
                                                                        } elseif ($value['doc_status'] == 'Rework') {
                                                                            echo mydata($value['userCreate'])['FirstName'];
                                                                        } elseif (($value['doc_status'] == 'Review')) {
                                                                            echo mydata($apporve_arr_1['Approve_1'])['FirstName'];
                                                                        } elseif (($value['doc_status'] == 'Check')) {
                                                                            echo mydata($apporve_arr_2['Approve_2'])['FirstName'];
                                                                        } elseif (($value['doc_status'] == 'Recheck')) {
                                                                            echo mydata($apporve_arr_3['Approve_3'])['FirstName'];
                                                                        } elseif (($value['doc_status'] == 'Approve') && $apporve_arr_4['status_approve'] == '0') {
                                                                            echo mydata($apporve_arr_4['Approve_4'])['FirstName'];
                                                                        } elseif ($value['doc_status'] == 'Approve' && $apporve_arr_5 != null && $apporve_arr_5['Approve_5'] != '-' && $apporve_arr_5['Approve_5'] != '') {
                                                                            echo mydata($apporve_arr_5['Approve_5'])['FirstName'];
                                                                        } elseif ($value['doc_status'] == 'Not Approve') {
                                                                            echo mydata($value['userCreate'])['FirstName'];
                                                                        }
                                                                        ?>
                                                                    </td>
                                                                    <td class="text-center">
                                                                        <span class="badge bg-warning fs-6" style="color: white; background-color: <?php echo $color_arr[$value['doc_status']] ?> !important;">
                                                                            <?php echo $value['doc_status'] ?>
                                                                        </span>
                                                                        <br>
                                                                    </td>
                                                                </tr>
                                                            <?php
                                                            }
                                                            ?>
                                                        </tbody>
                                                    </table>
                                                    <!-- </div> -->
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php include_once 'layout/scoreup.php'; ?>
                        <?php //include_once 'layout/footer.php'; 
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include_once 'layout/js.php' ?>
    <script>
        $(document).ready(function() {
            var table = $('#example').DataTable({
                responsive: true,
                buttons: [],
                pageLength: 10,
                sort: false,
                scrollX: true,
                "lengthMenu": [
                    [10, 25, 50, 100, 500, 1000, -1],
                    [10, 25, 50, 100, 500, 1000, "All", "งานด่วน"]
                ],
            });

            $('#urgentFilter').on('change', function() {
                var selected = $(this).val();

                if (selected === "urgent") {
                    table.rows().every(function() {
                        var data = this.data();
                        var col3 = data[2];
                        if (!col3 || col3.trim() === "") {
                            $(this.node()).hide();
                        } else {
                            $(this.node()).show();
                        }
                    });

                    table.page.len(-1).draw();
                } else {
                    table.rows().every(function() {
                        $(this.node()).show();
                    });

                    table.page.len(10).draw();
                }
            });


        });
    </script>
</body>

</html>