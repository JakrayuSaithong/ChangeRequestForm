<!DOCTYPE html>
<html lang="en">
<?php
$ips            =    $_SERVER['HTTP_HOST'];
$cdn            =    "//" . $ips . "/cdn";
$assets         =    "assets";
$not_check_user = 1;

$auth_user_id = $_SESSION['ChangeRequest_user_id'];
?>

<head>
    <?php ?>
    <?php //include_once 'config/base.php'; 
    ?>
    <?php include_once 'layout/meta.php' ?>
    <?php include_once 'layout/css.php' ?>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />
    <title>เพิ่ม/แก้ไขสาเหตุ</title>

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
                                            เพิ่มสาเหตุ/แก้ไข
                                        </h1>
                                    </div>

                                    <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3 ">
                                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#staticBackdrop" id="AddCause">
                                            <i class="bi bi-clipboard-plus-fill fs-4"></i> เพิ่มสาเหตุ
                                        </button>
                                    </div>

                                </div>
                            </div>

                            <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="staticBackdropLabel">ข้อมูลสาเหตุ</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <label for="cause_name" class="col-form-label">ชื่อสาเหตุ:</label>
                                                <input type="text" class="form-control" id="cause_name">
                                                <input type="text" class="form-control d-none" id="cause_id">
                                            </div>
                                            <div class="mb-3">
                                                <label for="cause_type" class="col-form-label">ประเภท:</label>
                                                <select class="form-select" id="cause_type">
                                                    <option value="CHI">CHI</option>
                                                    <option value="CHO">CHO</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ปิด</button>
                                            <button type="button" class="btn btn-primary" id="SaveSubmit"><i class="bi bi-plus-circle-fill"></i> บันทึก</button>
                                            <button type="button" class="btn btn-primary" id="EditSubmit"><i class="bi bi-plus-circle-fill"></i> บันทึกแก้ไข</button>
                                        </div>
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
                                                <h3 class="fs-2hx text-dark mb-5">รายการสินค้า</h3>
                                            </div>
                                        </div>
                                        <div class="fs-5 fw-semibold text-gray-600">
                                            <div class="table-responsive">
                                                <table id="example" class="table table-striped display nowrap" style="width:100%">
                                                    <thead>
                                                        <tr>
                                                            <th class="text-center" style="font-size:16px;font-weight: 600;width:50px;">#</th>
                                                            <th class="text-center" style="font-size:16px;font-weight: 600;width:50px;">ชื่อ</th>
                                                            <th class="text-center" style="font-size:16px;font-weight: 600;width:50px;">ประเภท</th>
                                                            <th class="text-center" style="font-size:16px;font-weight: 600;width:50px;">ผู้ลงข้อมูล</th>
                                                            <th class="text-center" style="font-size:16px;font-weight: 600;width:50px;">วันที่ลงข้อมูล</th>
                                                            <th class="text-center" style="font-size:16px;font-weight: 600;width:100px;">Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php
                                                        $i = 1;
                                                        foreach (CauseList() as $key => $value) {
                                                        ?>
                                                            <tr>
                                                                <td class="text-center"><?php echo $i; ?></td>
                                                                <td class="text-center"><?php echo $value['Cause_Name']; ?></td>
                                                                <td class="text-center"><?php echo $value['Cause_Type']; ?></td>
                                                                <td class="text-center"><?php echo mydata($value['Edit_By'])['FullName']; ?></td>
                                                                <td class="text-center"><?php echo $value['Date_Edit']; ?></td>
                                                                <td class="text-center">
                                                                    <button class="btn btn-icon btn-warning" onclick="EditForm(<?php echo $value['Cause_ID'] ?>, 'Edit')"><i class="fa-solid fa-pen-to-square fs-4"></i></button>
                                                                    <button class="btn btn-icon btn-danger" onclick="EditForm(<?php echo $value['Cause_ID'] ?>, 'Delete')"><i class="bi bi-trash-fill fs-4"></i></button>
                                                                </td>
                                                            </tr>
                                                        <?php
                                                            $i++;
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
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            $("#EditSubmit").hide();

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

            $("#AddCause").click(function() {
                $("#cause_name").val('');
                $("#EditSubmit").hide();
                $("#SaveSubmit").show();
            });

            $("#SaveSubmit").click(function() {
                var auth = '<?php echo $auth_user_id; ?>';
                var cause_name = $("#cause_name").val();
                var cause_type = $("#cause_type").val();

                if (cause_name == '') {
                    Swal.fire(
                        'กรุณากรอกชื่อสาเหตุ',
                        '',
                        'warning'
                    )
                    return false;
                }

                var formData = new FormData();
                formData.append("auth", auth);
                formData.append("cause_name", cause_name);
                formData.append("cause_type", cause_type);

                $.ajax({
                    url: './AddCause_name',
                    method: "POST",
                    data: formData,
                    contentType: false,
                    cache: false,
                    processData: false,
                    beforeSend: function() {
                        Swal.fire({
                            html: '<h5>กำลังดำเนินการ กรุณารอสักครู่...</h5>',
                            showConfirmButton: false,
                            allowOutsideClick: false,
                            onBeforeOpen: () => {
                                Swal.showLoading()
                            },
                        });
                    },
                    success: function(data) {
                        Swal.close();
                        var string1 = JSON.stringify(data);
                        var data_arr = JSON.parse(string1);

                        if (data_arr.status == 'true') {
                            $("#loading").show();
                            Swal.fire(
                                data_arr.title,
                                data_arr.html,
                                data_arr.icon,
                            ).then((result) => {
                                window.location.reload();
                            })
                        } else {
                            Swal.fire(
                                data_arr.title,
                                data_arr.html,
                                data_arr.icon,
                            ).then((result) => {
                                window.location.reload();
                            })
                        }
                    }
                });

            });

            $("#EditSubmit").click(function() {
                var auth = '<?php echo $auth_user_id; ?>';
                var cause_name = $("#cause_name").val();
                var cause_type = $("#cause_type").val();
                var cause_id = $("#cause_id").val();

                if (cause_name == '') {
                    Swal.fire(
                        'กรุณากรอกชื่อสาเหตุ',
                        '',
                        'warning'
                    )
                    return false;
                }

                var formData = new FormData();
                formData.append("auth", auth);
                formData.append("cause_name", cause_name);
                formData.append("cause_type", cause_type);
                formData.append("cause_id", cause_id);

                $.ajax({
                    url: './EditCause',
                    method: "POST",
                    data: formData,
                    contentType: false,
                    cache: false,
                    processData: false,
                    beforeSend: function() {
                        Swal.fire({
                            html: '<h5>กำลังดำเนินการ กรุณารอสักครู่...</h5>',
                            showConfirmButton: false,
                            allowOutsideClick: false,
                            onBeforeOpen: () => {
                                Swal.showLoading()
                            },
                        });
                    },
                    success: function(data) {
                        Swal.close();
                        var string1 = JSON.stringify(data);
                        var data_arr = JSON.parse(string1);

                        if (data_arr.status == 'true') {
                            $("#loading").show();
                            Swal.fire(
                                data_arr.title,
                                data_arr.html,
                                data_arr.icon,
                            ).then((result) => {
                                window.location.reload();
                            })
                        } else {
                            Swal.fire(
                                data_arr.title,
                                data_arr.html,
                                data_arr.icon,
                            ).then((result) => {
                                window.location.reload();
                            })
                        }
                    }
                });

            });

        });

        function EditForm(Cause_ID, Action) {
            var auth = '<?php echo $auth_user_id; ?>';

            var formData = new FormData();
            formData.append("auth", auth);
            formData.append("Cause_ID", Cause_ID);
            if (Action == 'Edit') {
                $("#staticBackdrop").modal('show');

                $.ajax({
                    url: './SelectCause',
                    method: "POST",
                    data: formData,
                    contentType: false,
                    cache: false,
                    processData: false,
                    success: function(data) {
                        // console.log(data);
                        var string1 = JSON.stringify(data);
                        var data_arr = JSON.parse(string1);

                        $("#cause_id").val(data_arr.Cause_ID);
                        $("#cause_name").val(data_arr.Cause_Name);
                        $("#cause_type").val(data_arr.Cause_Type);
                        $("#EditSubmit").show();
                        $("#SaveSubmit").hide();

                    }
                });
            } else if (Action == 'Delete') {
                Swal.fire({
                    title: "คุณแน่ใจใช่หรือไม่?",
                    text: "คุณต้องการลบข้อมูลนี้ใช่หรือไม่",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "ใช่",
                    cancelButtonText: "ไม่"
                }).then((result) => {
                    if (result.isConfirmed) {
                        // console.log(id);
                        $.ajax({
                            url: './DelCause?token=' + token,
                            method: "POST",
                            data: formData,
                            contentType: false,
                            cache: false,
                            processData: false,
                            beforeSend: function() {
                                Swal.fire({
                                    html: '<h5>กำลังดำเนินการ กรุณารอสักครู่...</h5>',
                                    showConfirmButton: false,
                                    allowOutsideClick: false,
                                    onBeforeOpen: () => {
                                        Swal.showLoading()
                                    },
                                });
                            },
                            success: function(data) {
                                // console.log(data);
                                Swal.close();
                                var string1 = JSON.stringify(data);
                                var data_arr = JSON.parse(string1);

                                if (data_arr.status == 'true') {
                                    // window.location.reload();
                                    $("#loading").show();
                                    Swal.fire(
                                        data_arr.title,
                                        data_arr.html,
                                        data_arr.icon,
                                    ).then((result) => {
                                        window.location.reload();
                                    })
                                } else {
                                    Swal.fire(
                                        data_arr.title,
                                        data_arr.html,
                                        data_arr.icon,
                                    ).then((result) => {
                                        window.location.reload();
                                    })
                                }
                            }
                        });
                    }
                });
            }
        }
    </script>
</body>

</html>