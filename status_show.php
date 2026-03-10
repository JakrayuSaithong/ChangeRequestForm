<?php
$data = List_ChangeForm(''); //$_SESSION['ChangeRequest_code']

$status_show = $_GET['status'];

$status_show_arr['New'] = 'รอทบทวน';
$status_show_arr['Draf'] = 'รับงานแก้ไข';
$status_show_arr['Review'] = 'รอตรวจสอบ';
$status_show_arr['Check'] = 'รออนุมัติ';

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
                <div class="app-container  container-xxl d-flex flex-row flex-column-fluid ">
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
                                <div class="card">
                                    <!--begin::Body-->

                                    <!--begin::About-->
                                    <div class="mb-18" style="padding: 15px;">
                                        <!--begin::Wrapper-->
                                        <div class="mb-10">
                                            <!--begin::Top-->
                                            <div class="text-center mb-3">
                                                <h3 class="fs-2hx text-dark mb-5">รายการใบขอเปลี่ยนแปลง (<?php echo $status_show_arr[$status_show] ?>)</h3>
                                            </div>
                                        </div>
                                        <div class="fs-5 fw-semibold text-gray-600">
                                            <div class="table-responsive">
                                                <table id="example" class="table table-striped display nowrap" style="width:100%">
                                                    <thead>
                                                        <tr>
                                                            <td>ลำดับ</td>
                                                            <td class="text-center">สถานะ</td>
                                                            <td class="text-center">วันที่ขอ</td>
                                                            <td class="text-center">ผู้อนุมัติ</td>
                                                            <td>รหัสงาน</td>
                                                            <td>ประเภทเอกสาร</td>
                                                            <td>Jon No.</td>
                                                            <td>ชื่อโครงการ</td>
                                                            <td>sale</td>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php
                                                        $i = 1;
                                                        foreach ($data as $key => $value) {
                                                            if ($value['doc_status'] == $status_show) {
                                                                $status_time = Get_Time_Status($value['CR_no'], $value['doc_status']);
                                                        ?>
                                                                <tr>
                                                                    <td><?php echo $i ?></td>
                                                                    <td class="text-center">
                                                                        <span class="badge bg-warning fs-6" style="color: white; background-color: <?php echo $color_arr[$value['doc_status']] ?> !important;"><?php echo $value['doc_status'] ?></span><br>
                                                                    </td>
                                                                    <td class="text-center">
                                                                        <?php if ($status_time != null) { ?>
                                                                            <?php echo $status_time['Status_Date'] ?>
                                                                        <?php } ?>
                                                                    </td>
                                                                    <td class="text-center">
                                                                        <?php if ($status_time != null) { ?>
                                                                            <?php echo mydata($status_time['Status_User'])['FirstName'] ?>
                                                                        <?php } ?>
                                                                    </td>
                                                                    <td><a href="./ViewForm?token=<?php echo $_GET['token'] ?>&CR_no=<?php echo $value['CR_no'] ?>" target="_blank"><?php echo $value['CR_no'] ?></a></td>
                                                                    <td class="text-center"><?php echo $value['doc_type'] ?></td>
                                                                    <td><?php echo $value['jobno'] ?></td>
                                                                    <td><?php echo $value['project_name'] ?></td>
                                                                    <td><?php echo $value['sales_name'] ?></td>
                                                                </tr>
                                                        <?php
                                                                $i++;
                                                            }
                                                        }
                                                        ?>
                                                    </tbody>
                                                </table>
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
            var table = $('#example').DataTable({

                lengthChange: false,
                buttons: [],
                pageLength: 10,
                sort: false,
                " lengthMenu": [
                    [10, 25, 50, 100, 500, 1000, -1],
                    [10, 25, 50, 100, 500, 1000, "All"]
                ],
            });
            table.buttons().container().appendTo('#example_wrapper .col-md-6:eq(0)');
        });
    </script>
</body>

</html>