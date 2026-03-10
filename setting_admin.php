<?php
$list_permission = list_permission('All');
$emplist = emplist();
$ListDivision = ListDivision();

// echo "<pre>";
// print_r($list_permission);
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
                                                <span>setting</span>
                                            </li>
                                        </ul>
                                    </div>

                                </div>
                            </div>
                            <div id="kt_app_content" class="app-content  flex-column-fluid ">

                                <div class="card">
                                    <div class="card-header">
                                        <div class="w-100 d-flex justify-content-between align-items-center">
                                            <h5 class="card-title mb-0">Setting</h5>
                                            <!-- <div class="d-flex align-items-center gap-3">
                                                <button type="button" class="btn btn-success btn-sm" id="export-excel"><i class="fas fa-file-excel fs-4 me-2"></i> Export Excel</button>
                                            </div> -->
                                        </div>

                                    </div>
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table class="table table-bordered table-hover" id="kt_datatable">
                                                <thead>
                                                    <tr>
                                                        <th>#</th>
                                                        <th>ชื่อ</th>
                                                        <th>จัดการ</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    $i = 1;
                                                    foreach ($list_permission as $key => $value) {
                                                    ?>
                                                        <tr>
                                                            <td><?php echo $i ?></td>
                                                            <td><?php echo $value['Per_Name'] ?></td>
                                                            <td>
                                                                <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#staticBackdrop" onclick="OpenModal('<?php echo $value['Per_ID'] ?>', '<?php echo $value['Per_Name'] ?>', '<?php echo $value['Per_Json'] ?>', '<?php echo $value['Per_Divi_Json'] ?>')">
                                                                    แก้ไข
                                                                </button>
                                                            </td>
                                                        </tr>
                                                    <?php
                                                        $i++;
                                                    } ?>
                                                </tbody>
                                            </table>
                                        </div>

                                        <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="staticBackdropLabel" data-id=""></h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <label for="per_edit" class="form-label">เลือกผู้ใช้งาน</label>
                                                        <select class="form-select" id="per_edit" multiple>
                                                            <?php
                                                            foreach ($emplist as $key => $value) {
                                                            ?>
                                                                <option value="<?php echo $value['Code'] ?>"><?php echo $value['FullName'] ?></option>
                                                            <?php
                                                            }
                                                            ?>
                                                        </select>

                                                        <label for="per_edit" class="form-label mt-3">เลือกแผนกที่ใช้งาน</label>
                                                        <select class="form-select" id="dep_edit" multiple>
                                                            <?php
                                                            foreach ($ListDivision as $key => $value) {
                                                            ?>
                                                                <option value="<?php echo $value['Code'] ?>"><?php echo $value['Code'] ?> - <?php echo $value['Name'] ?></option>
                                                            <?php
                                                            }
                                                            ?>
                                                        </select>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ปิด</button>
                                                        <button type="button" class="btn btn-primary" id="btn-save" data-bs-dismiss="modal">บันทึก</button>
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
            $('#per_edit').select2({
                placeholder: "กรุณาเลือกผู้ใช้งาน",
            });

            $('#dep_edit').select2({
                placeholder: "กรุณาเลือกแผนกที่ใช้งาน",
            });

            $("#btn-save").click(function() {
                var per_edit = $('#per_edit').val();
                var dep_edit = $('#dep_edit').val();
                var dataId = $('#staticBackdropLabel').attr('data-id');

                Swal.fire({
                    title: 'ยืนยันการบันทึก?',
                    text: "ยืนยันการบันทึก",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'ยืนยัน',
                    cancelButtonText: 'ยกเลิก'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: "./update_setting",
                            type: "POST",
                            data: {
                                dataId: dataId,
                                per_edit: JSON.stringify(per_edit),
                                dep_edit: JSON.stringify(dep_edit)
                            },
                            beforeSend: function() {
                                Swal.fire({
                                    title: 'กำลังบันทึกข้อมูล...',
                                    text: 'โปรดรอสักครู่',
                                    allowOutsideClick: false,
                                    didOpen: () => {
                                        Swal.showLoading();
                                    }
                                });
                            },
                            success: function(data) {
                                if (data.status == true) {
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'บันทึกข้อมูลเรียบร้อย',
                                        showConfirmButton: false,
                                        timer: 1500
                                    }).then(function() {
                                        location.reload();
                                    });
                                }
                                // location.reload();
                            }
                        });
                    }
                });
            });
        });

        function OpenModal(code, name, json, dep) {
            var selectedValues = json.split(', ');
            $('#per_edit').val(selectedValues).trigger('change');

            var selectedDep = dep.split(', ');
            $('#dep_edit').val(selectedDep).trigger('change');

            $('#staticBackdropLabel').text(name);
            $('#staticBackdropLabel').attr('data-id', code);
        }
    </script>
</body>

</html>