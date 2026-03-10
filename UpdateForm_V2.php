<?php
session_start();
$auth = $_SESSION['ChangeRequest_code'];

if (!isset($_SESSION['emplist'])) {
    $_SESSION['emplist'] = emplist();
}
$emplist = $_SESSION['emplist'];

$data_arr = List_ChangeFormByID($_GET['CR_no']);
// $file_arr = List_Files($_GET['CR_no']);

$CauseList = CauseList();
$CauseList_key = array_keys($CauseList);
$WA_arr = Search_WA($data_arr['jobno']);
$Tc_name_arr = Search_TC($data_arr['jobno']);
$SN_arr = Search_SN($data_arr['jobno']);

$apporve_arr = json_decode($data_arr['approve_status'] ?? '', true) ?? [];

$apporve_arr_1 = json_decode($data_arr['CR_Approve1'] ?? '', true) ?? [];
$apporve_arr_2 = json_decode($data_arr['CR_Approve2'] ?? '', true) ?? [];
$apporve_arr_3 = json_decode($data_arr['CR_Approve3'] ?? '', true) ?? [];
$apporve_arr_4 = json_decode($data_arr['CR_Approve4'] ?? '', true) ?? [];
$apporve_arr_5 = json_decode($data_arr['CR_Approve5'] ?? '', true) ?? [];

function createSelectOptions($emplist, $selectedValue)
{
    return array_map(function ($codemy) use ($selectedValue) {
        $selected = $codemy['Code'] == $selectedValue ? "selected" : "";
        return '<option value="' . $codemy['Code'] . '" ' . $selected . '>' . $codemy['FullName'] . '</option>';
    }, $emplist);
}

$approveOptions_1 = createSelectOptions($emplist, $apporve_arr_1['Approve_1']);
$approveOptions_2 = createSelectOptions($emplist, $apporve_arr_2['Approve_2']);
$approveOptions_3 = createSelectOptions($emplist, $apporve_arr_3['Approve_3']);
$approveOptions_4 = createSelectOptions($emplist, $apporve_arr_4['Approve_4']);
$approveOptions_5 = createSelectOptions($emplist, $apporve_arr_5['Approve_5']);

// echo "<pre>";
// print_r($SN_arr);
// echo "</pre>";
// exit();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php //$datatable = true; 
    ?>
    <?php //include_once 'config/base.php'; 
    ?>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker3.css">
    <?php include 'layout/meta.php' ?>
    <?php include 'layout/css.php' ?>

    <script src="https://jojosati.github.io/bootstrap-datepicker-thai/js/bootstrap-datepicker.js"></script>
    <script src="https://jojosati.github.io/bootstrap-datepicker-thai/js/bootstrap-datepicker-thai.js"></script>
    <script src="https://jojosati.github.io/bootstrap-datepicker-thai/js/locales/bootstrap-datepicker.th.js"></script>
    <title>เพิ่มใบเปลี่ยนแปลง</title>

</head>

<style>
    td {
        vertical-align: middle;
    }

    .select2-container--bootstrap5.select2-container--disabled .form-select {
        background-color: white;
        border-color: var(--bs-gray-300);
        color: var(--bs-gray-700);
    }

    .form-select:disabled {
        color: var(--bs-gray-700);
        background-color: #ffffff;
        border-color: var(--bs-gray-500);
    }

    input.form-control:disabled,
    textarea.form-control:disabled {
        background-color: white !important;
    }

    .select2-scrollable .select2-results__options {
        max-height: 200px;
        overflow-y: auto;
    }

    .select2-container .select2-selection--multiple {
        height: auto;
        max-height: 150px;
        overflow-y: auto;
    }

    .select2-container--bootstrap5 .select2-selection--multiple {
        align-items: flex-start !important;
    }
</style>

<body id="kt_body" data-kt-app-header-stacked="true" data-kt-app-header-primary-enabled="true" data-kt-app-header-secondary-enabled="false" data-kt-app-toolbar-enabled="true" class="app-default bg-light">
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
                                            เพิ่มใบขอเปลี่ยนแปลง
                                        </h1>
                                        <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                                            <li class="breadcrumb-item text-muted">
                                                <a href="index.php?DataE=<?php echo $_SESSION['DataE'] ?>" class="text-muted text-hover-primary">
                                                    หน้าแรก
                                                </a>
                                            </li>
                                            <li class="breadcrumb-item">
                                                <span> - </span>
                                            </li>
                                            <li class="breadcrumb-item text-muted">
                                                <span>เพิ่ม/แก้ไขข้อมูล</span>
                                            </li>
                                        </ul>
                                    </div>

                                </div>
                            </div>
                            <div id="kt_app_content" class="app-content  flex-column-fluid ">
                                <?php
                                $disabledAll = ($data_arr['doc_status'] ?? '') != 'Draf' && ($data_arr['doc_status'] ?? '') != 'Savedraft' && ($data_arr['doc_status'] ?? '') != 'Rework' && ($data_arr['doc_status'] ?? '') != 'Not Approve' && (!(is_array($session_keys) && is_array($data_admin) ? array_intersect($session_keys, $data_admin) : false) && !(is_array($session_keys) && is_array($data_Draft) ? array_intersect($session_keys, $data_Draft) : false)) ? 'disabled' : '';
                                // echo "<pre>";
                                // print_r($data_arr);
                                // print_r($file_arr);
                                // echo generateDocNo($data_arr['doc_type']);
                                // echo "</pre>";
                                // exit();
                                ?>
                                <div class="card mb-4 position-relative">
                                    <span class="badge bg-warning position-absolute top-0 end-0 fs-5 m-3" style="color: white; background-color: <?php echo $color_arr[$data_arr['doc_status']]; ?> !important;"><?php echo $data_arr['doc_status'] ?></span>
                                    <div class="border border-dark border-2" style="padding: 15px;">
                                        <div class="col-md-6">
                                            <span class="fs-5 fw-bold text-decoration-underline">ส่วนการแจ้งเปลี่ยนแปลง</span>
                                        </div>
                                        <div class="col-md-6 mt-4">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="urgentwork" value="1" <?php echo $data_arr['urgentwork'] == '1' ? 'checked' : '' ?> style="border: 2px solid #000;">
                                                <label class="fw-bold" for="flexCheckDefault">งานด่วน</label>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="row py-3">
                                                <div class="col-md-4 mt-4">
                                                    <label for="servicename" class="form-label text-primary required">ประเภทเอกสาร</label>
                                                    <!-- <input type="text" class="form-control text-start" disabled> -->
                                                    <select class="form-select" id="jobtype" <?php echo $disabledAll ?>>
                                                        <option value=""></option>
                                                        <option value="CHI" <?php echo $data_arr['doc_type'] == 'CHI' ? 'selected' : '' ?>>CHI</option>
                                                        <option value="CHO" <?php echo $data_arr['doc_type'] == 'CHO' ? 'selected' : '' ?>>CHO</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-4 mt-4">
                                                    <label for="form-select" class="form-label text-primary required">รหัสงาน</label>
                                                    <input type="text" class="form-control" value="<?php echo $data_arr['CR_no'] ?>" disabled>
                                                </div>
                                                <?php
                                                if ($data_arr['doc_status'] == 'Close' || $data_arr['doc_no'] != '') {
                                                ?>
                                                    <div class="col-md-4 mt-4">
                                                        <label for="form-select" class="form-label text-primary required">เลขที่ CHO - CHI</label>
                                                        <input type="text" class="form-control" style="background-color: #fff7c3 !important;" value="<?php echo $data_arr['doc_no'] ?>" id="chi_cho_no" disabled>
                                                    </div>
                                                <?php
                                                }
                                                ?>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="row py-3 justify-content-between">
                                                <div class="col-md-4 mt-4">
                                                    <label for="form-select" class="form-label text-primary required">Job No.</label>
                                                    <div class="input-group">
                                                        <input type="text" class="form-control" id="jobno" name="jobno" placeholder="ระบุ Job No." value="<?php echo strtoupper($data_arr['jobno']) ?>" <?php echo $disabledAll ?>>
                                                        <button class="btn btn-primary" type="button" id="searchjob" <?php echo $disabledAll ?>>
                                                            <i class="fa-solid fa-magnifying-glass"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                                <div class="col-md-4 mt-4">
                                                    <label for="servicename" class="form-label text-primary required">Project Name</label>
                                                    <input type="text" class="form-control text-start" id="projects" value="<?php echo $data_arr['project_name'] ?>" disabled>
                                                </div>
                                                <div class="col-md-4 mt-4">
                                                    <label for="servicename" class="form-label text-primary required">Sales Name</label>
                                                    <input type="text" class="form-control text-start" id="jobsale" value="<?php echo $data_arr['sales_name'] ?>" disabled>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="row py-3 justify-content-between">
                                                <div class="col-md-8 mt-4">
                                                    <label for="servicename" class="form-label text-primary required">Customer Name</label>
                                                    <input type="text" class="form-control text-start" id="jobcus" value="<?php echo $data_arr['customer_name'] ?>" disabled>
                                                </div>
                                                <div class="col-md-2 mt-4">
                                                    <label for="servicename" class="form-label text-primary required">Rev.</label>
                                                    <input type="text" class="form-control text-start" id="rev" value="<?php echo $data_arr['rev'] ?>" <?php echo $disabledAll ?>>
                                                </div>
                                                <div class="col-md-2 mt-4">
                                                    <label for="servicename" class="form-label text-primary required">บันทึกความผิดพลาด/NCR No.</label>
                                                    <input type="text" class="form-control text-start" id="ncr_no" value="<?php echo $data_arr['ncr_no'] ?>" <?php echo $disabledAll ?>>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12">

                                            <div class="row py-3 justify-content-between">
                                                <div class="col-md-4 mt-4">
                                                    <label for="servicename" class="form-label text-primary required">W/A</label>
                                                    <!-- <input type="text" class="form-control text-start" > -->
                                                    <select class="form-select selectable" id="WA" name="WA[]" multiple <?php echo $disabledAll ?>>
                                                        <option value="All">เลือกเลข WA ทั้งหมด</option>
                                                        <?php
                                                        $selectedWAs = array_map('trim', explode(',', $data_arr['wa_no'] ?? ''));
                                                        foreach ($WA_arr as $ky => $val) {
                                                            $selected_wa = in_array($val, $selectedWAs) ? "selected" : "";
                                                            echo '<option value="' . $val . '" ' . $selected_wa . '>' . $val . '</option>';
                                                        }
                                                        ?>
                                                    </select>
                                                </div>

                                                <div class="col-md-4 mt-4">
                                                    <label for="servicename" class="form-label text-primary required">Serial No.</label>
                                                    <!-- <input type="text" class="form-control text-start" id="SN" name="SN[]" > -->
                                                    <select class="form-select selectable" id="SN" name="SN[]" multiple <?php echo $disabledAll ?>>
                                                        <option value="All">เลือกเลข S/N ทั้งหมด</option>
                                                        <?php
                                                        $selectedSNs = array_map('trim', explode(',', $data_arr['sn_no'] ?? ''));
                                                        if (is_array($SN_arr)) {
                                                            foreach ($SN_arr as $kyy => $vall) {
                                                                $selected_sn = in_array($vall['item'] ?? '', $selectedSNs) ? "selected" : "";
                                                                echo '<option value="' . ($vall['item'] ?? '') . '" ' . $selected_sn . '>' . ($vall['PartNo'] ?? '') . '</option>';
                                                            }
                                                        }
                                                        ?>
                                                        <!-- <option value="-">-</option> -->
                                                    </select>
                                                </div>
                                                <div class="col-md-4 mt-4">
                                                    <label for="servicename" class="form-label text-primary required">TC Name</label>
                                                    <!-- <input type="text" class="form-control text-start" id="tc_name"> -->
                                                    <select class="form-select" name="tc_name[]" id="tc_name" multiple <?php echo $disabledAll ?>>
                                                        <?php
                                                        // foreach($emplist as $empno => $detail){
                                                        //     $selected = $data_arr['tc_name'] == $empno ? "selected" : "";
                                                        //     echo '<option value="'. $empno .'" '. $selected .'>'. $detail['FullName'] .'</option>';
                                                        // }
                                                        ?>
                                                        <?php
                                                        $tc_arr = json_decode($data_arr['tc_name'] ?? '', true) ?? [];
                                                        foreach ($Tc_name_arr as $key => $value) {
                                                            $selected = is_array($tc_arr) && in_array($value, $tc_arr) ? "selected" : "";
                                                            echo '<option value="' . $value . '" ' . $selected . '>' . $value . '</option>';
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12 mt-4">
                                            <label for="servicename" class="form-label text-primary required">Product</label>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="row justify-content-start">
                                                <?php
                                                $product_arr = json_decode($data_arr['product'] ?? '', true) ?? [];
                                                foreach (ProductList() as $key => $value) {
                                                    $selected = is_array($product_arr) && in_array($key, $product_arr) ? "checked" : "";
                                                    $lastProduct = is_array($product_arr) && !empty($product_arr) ? end($product_arr) : '';
                                                    $product_other = is_numeric($lastProduct) ? "" : $lastProduct;
                                                ?>
                                                    <div class="col-4 col-md-2 mt-4">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox" name="product_name" value="<?php echo $key; ?>" <?php echo $selected; ?> <?php echo $disabledAll ?>>
                                                            <label class="fw-bold" for="flexCheckDefault">
                                                                <?php echo $value['Product_Name']; ?>
                                                            </label>
                                                        </div>
                                                    </div>
                                                <?php
                                                }
                                                ?>
                                                <div class="col-4 col-md-2 mt-4">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" name="conclude" value="product" <?php echo (is_string($product_other) ? $product_other : '') != '' ? 'checked' : '' ?> <?php echo $disabledAll ?>>
                                                        <label class="fw-bold" for="flexCheckDefault">Other</label>
                                                    </div>
                                                </div>
                                                <div class="col-md-12 mt-4">
                                                    <div class="input-group" id="product_input" style="<?php echo (is_string($product_other) ? $product_other : '') != '' ? 'display:inline-flex' : 'display:none' ?>">
                                                        <span class="input-group-text">อื่นๆ</span>
                                                        <input type="text" class="form-control" id="product_other" value="<?php echo is_string($product_other) ? $product_other : ''; ?>" <?php echo $disabledAll ?>>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12 mt-4">
                                            <label for="servicename" class="form-label text-primary required">สถานะงาน</label>
                                        </div>
                                        <div class="col-md-12">
                                            <?php
                                            $status_job_arr = json_decode($data_arr['status_job'] ?? '', true) ?? [];
                                            ?>
                                            <div class="row justify-content-start">
                                                <div class="col-4 col-md-2 mt-4">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" name="work_status" value="เปิดงาน/สั่งอุปกรณ์" <?php echo is_array($status_job_arr) && in_array('เปิดงาน/สั่งอุปกรณ์', $status_job_arr) ? "checked" : ""; ?> <?php echo $disabledAll ?>>
                                                        <label class="fw-bold" for="flexCheckDefault">
                                                            เปิดงาน/สั่งอุปกรณ์
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-4 col-md-2 mt-4">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" name="work_status" value="ผลิตชิ้นงานเหล็ก" <?php echo is_array($status_job_arr) && in_array('ผลิตชิ้นงานเหล็ก', $status_job_arr) ? "checked" : ""; ?> <?php echo $disabledAll ?>>
                                                        <label class="fw-bold" for="flexCheckDefault">
                                                            ผลิตชิ้นงานเหล็ก
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-4 col-md-2 mt-4">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" name="work_status" value="ติดตั้งเหล็ก" <?php echo is_array($status_job_arr) && in_array('ติดตั้งเหล็ก', $status_job_arr) ? "checked" : ""; ?> <?php echo $disabledAll ?>>
                                                        <label class="fw-bold" for="flexCheckDefault">
                                                            ติดตั้งเหล็ก
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-4 col-md-2 mt-4">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" name="work_status" value="ติดตั้งไฟฟ้า" <?php echo is_array($status_job_arr) && in_array('ติดตั้งไฟฟ้า', $status_job_arr) ? "checked" : ""; ?> <?php echo $disabledAll ?>>
                                                        <label class="fw-bold" for="flexCheckDefault">
                                                            ติดตั้งไฟฟ้า
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-4 col-md-2 mt-4">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" name="work_status" value="Finish Goods" <?php echo is_array($status_job_arr) && in_array('Finish Goods', $status_job_arr) ? "checked" : ""; ?> <?php echo $disabledAll ?>>
                                                        <label class="fw-bold" for="flexCheckDefault">
                                                            Finish Goods
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12 mt-4">
                                            <label for="servicename" class="form-label text-primary required">สถานะผลิตภัณฑ์</label>
                                        </div>
                                        <div class="col-md-12">
                                            <?php
                                            $product_status_arr = json_decode($data_arr['status_product'] ?? '', true) ?? [];
                                            ?>
                                            <div class="row justify-content-start">
                                                <div class="col-4 col-md-2 mt-4">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" name="product_status" value="ตู้ในโรงงาน WIP" <?php echo is_array($product_status_arr) && in_array('ตู้ในโรงงาน WIP', $product_status_arr) ? "checked" : ""; ?> <?php echo $disabledAll ?>>
                                                        <label class="fw-bold" for="flexCheckDefault">
                                                            ตู้ในโรงงาน WIP
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-4 col-md-2 mt-4">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" name="product_status" value="ตู้ในโรงงาน FG" <?php echo is_array($product_status_arr) && in_array('ตู้ในโรงงาน FG', $product_status_arr) ? "checked" : ""; ?> <?php echo $disabledAll ?>>
                                                        <label class="fw-bold" for="flexCheckDefault">
                                                            ตู้ในโรงงาน FG
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-4 col-md-2 mt-4">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" name="product_status" value="ตู้นอกโรงงาน" <?php echo is_array($product_status_arr) && in_array('ตู้นอกโรงงาน', $product_status_arr) ? "checked" : ""; ?> <?php echo $disabledAll ?>>
                                                        <label class="fw-bold" for="flexCheckDefault">
                                                            ตู้นอกโรงงาน
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <hr class="mt-6 mb-6">
                                        <div class="col-6 col-md-2 mt-4">
                                            <button type="button" class="btn btn-success required" id="addRow-select" <?php echo $disabledAll ?>><i class="fa-solid fa-plus p-0 fs-6"></i> เพิ่มรายการ</button>
                                        </div>
                                        <div class="col-md-12" id="Card_Drop">
                                            <?php
                                            $data_detail = json_decode($data_arr['data_detail'], true);
                                            $RowSelect = 0;
                                            $disabled = $disabledAll;
                                            function convertToThaiYear($dateStr)
                                            {
                                                if (!$dateStr) return '';
                                                $parts = explode('/', $dateStr);
                                                if (count($parts) === 3) {
                                                    $day = $parts[0];
                                                    $month = $parts[1];
                                                    $year = (int)$parts[2];
                                                    if ($year < 2500) {
                                                        $year += 543;
                                                    }
                                                    return sprintf('%02d/%02d/%04d', $day, $month, $year);
                                                }
                                                return $dateStr;
                                            }
                                            foreach ($data_detail as $key => $value) {
                                                if (!empty($value['inputValue']['postponed'][0])) {
                                                    $value['inputValue']['postponed'][0] = convertToThaiYear($value['inputValue']['postponed'][0]);
                                                }
                                                if (!empty($value['inputValue']['postponed'][1])) {
                                                    $value['inputValue']['postponed'][1] = convertToThaiYear($value['inputValue']['postponed'][1]);
                                                }

                                                $card_html .= '
                                                        <div class="card border border-dark border-2 my-4" id="DetailSelect-' . $RowSelect . '">
                                                            <div class="card-body row">
                                                                <div class="col-8 col-md-4">
                                                                    <select class="form-select Problem" id="Problem-' . $RowSelect . '" ' . $disabled . '>
                                                                        <option value=""></option>
                                                                        <option value="Electrical" ' .  ($value['problem'] == "Electrical" ? "selected" : "") . '>อุปกรณ์ไฟฟ้า</option>
                                                                        <option value="Busbar" ' . ($value['problem'] == "Busbar" ? "selected" : "") . '>บัสบาร์</option>
                                                                        <option value="Ironwork" ' . ($value['problem'] == "Ironwork" ? "selected" : "") . '>งานเหล็ก</option>
                                                                        <option value="Model" ' . ($value['problem'] == "Model" ? "selected" : "") . '>แบบ</option>
                                                                        <option value="Closingsheet" ' . ($value['problem'] == "Closingsheet" ? "selected" : "") . '>ใบเปิดงาน</option>
                                                                    </select>
                                                                </div>
                                                                <div class="col-2">
                                                                    <button type="button" class="btn btn-danger text-center" onclick="DelRowSelect(' . $RowSelect . ')" ' . $disabled . '><i class="bi bi-trash-fill p-0 fs-4"></i></button>
                                                                </div>
                                                                <div class="col-md-12 mt-4 row">
                                                                    <div class="col-md-2 mt-4 row align-items-center">
                                                                        <div class="form-check col-md-1">
                                                                            <input class="form-check-input option-checkbox" type="checkbox" name="input_rio_' . $RowSelect . '" value="increase" ' . ($value['inputValue']['increase'][0] != "" ? "checked" : "") . ' ' . $disabled . '>
                                                                            <label class="fw-bold" for="flexCheckDefault">เพิ่ม</label>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-2 mt-4 row align-items-center">
                                                                        <div class="form-check col-md-1">
                                                                            <input class="form-check-input option-checkbox" type="checkbox" name="input_rio_' . $RowSelect . '" value="reduce" ' .  ($value['inputValue']['reduce'][0] != "" ? "checked" : "") . ' ' .  $disabled . '>
                                                                            <label class="fw-bold" for="flexCheckDefault">ลด</label>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-2 mt-4 row align-items-center">
                                                                        <div class="form-check col-md-1">
                                                                            <input class="form-check-input option-checkbox" type="checkbox" name="input_rio_' . $RowSelect . '" value="other"  ' .  ($value['inputValue']['other'][0] != "" ? "checked" : "") . ' ' .  $disabled . '>
                                                                            <label class="fw-bold" for="flexCheckDefault">อื่นๆ</label>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-12" id="input_rio_other_' . $RowSelect . '">
                                                                        <input type="text" class="form-control mt-2 option-input" ' . ($value['inputValue']['other'][0] != "" ? "style=display:block" : "style=display:none") . ' id="rio_other_' . $RowSelect . '" placeholder="อื่นๆ"  value="' . $value['inputValue']['other'][0] . '" ' .  $disabled . '/>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-12 mt-4">
                                                                    <label for="servicename" class="form-label text-primary required">สาเหตุ</label>
                                                                </div>
                                                                <div class="col-md-12">
                                                                    <div class="row justify-content-start">
                                                                ';
                                                $other = '';
                                                foreach (CauseList($data_arr['doc_type']) as $kk => $kvalue) {
                                                    $card_html .= '<div class="col-6 col-md-2 mt-4">
                                                                            <div class="form-check">
                                                                                <input class="form-check-input" type="checkbox" name="chk_' . $RowSelect . '" value="' . $kk . '" ' . (in_array($kk, $value['inputChk']) ? 'checked' : '') . ' ' .  $disabled . ' data-id="' . $kk . '_' . $RowSelect . '">
                                                                                <label class="fw-bold" for="flexCheckDefault">
                                                                                    ' . $kvalue['Cause_Name'] . '
                                                                                </label>
                                                                            </div>
                                                                        </div>';
                                                }
                                                foreach ($value['inputChk'] as $kk => $other) {
                                                    if (in_array($other, $CauseList_key)) {
                                                        $other = '';
                                                    } else {
                                                        $other = $other;
                                                    }
                                                }


                                                $card_html .= '
                                                                        <div class="col-6 col-md-2 mt-4">
                                                                            <div class="form-check">
                                                                                <input class="form-check-input" type="checkbox" name="chk_' . $RowSelect . '" value="" ' .  ($other != '' ? 'checked' : '') . ' ' .  $disabled . ' data-id="chk_other_' . $RowSelect . '">
                                                                                <label class="fw-bold" for="flexCheckDefault">
                                                                                    อื่นๆ
                                                                                </label>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="row mt-4">
                                                                    <div class="col-md-12 mt-4">
                                                                        <div class="input-group mb-3 chk_other" id="chk_other_' . $RowSelect . '" ' . ($other == '' ? 'style="display:none"' : '') . '>
                                                                            <span class="input-group-text">อื่นๆ</span>
                                                                            <input type="text" class="form-control" id="input_chk_other_' . $RowSelect . '" value="' . $other . '" ' .  $disabled . '>
                                                                        </div>
                                                                    </div>

                                                                    <div class="col-md-3 mt-4 chk_custom_' . $RowSelect . '" ' . ($value['inputValue']['postponed'][0] != '' ? 'style="display:block;"' : 'style="display:none;"') . '>
                                                                        <label for="servicename" class="form-label text-primary required datepicker chk_custom_' . $RowSelect . '">เลือนเป็นวันที่</label>
                                                                        <input type="text" class="form-control datepicker chk_custom_' . $RowSelect . '" id="input_chk_custom_' . $RowSelect . '" value="' . $value['inputValue']['postponed'][0] . '" ' .  $disabled . ' data-provide="datepicker" data-date-language="th-th">
                                                                    </div>

                                                                    <div class="col-md-3 mt-4 chk_custom_' . $RowSelect . '" ' . ($value['inputValue']['postponed'][1] != '' ? 'style="display:block;"' : 'style="display:none;"') . '>
                                                                        <label for="servicename" class="form-label text-primary required chk_custom_' . $RowSelect . '">กำหนดเสร็จ</label>
                                                                        <input type="text" class="form-control datepicker chk_custom_' . $RowSelect . '" id="input_chk_customlast_' . $RowSelect . '" value="' . $value['inputValue']['postponed'][1] . '" ' .  $disabled . ' data-provide="datepicker" data-date-language="th-th">
                                                                    </div>

                                                                    <div class="col-md-12 mt-4 chk_custom_' . $RowSelect . '" ' . ($value['inputValue']['postponed'][0] != '' ? 'style="display:block;"' : 'style="display:none;"') . '>
                                                                        <div class="input-group mb-3 chk_custom_' . $RowSelect . '">
                                                                            <span class="input-group-text">เนื่องจาก</span>
                                                                            <textarea class="form-control" id="textarea_chk_custom_' . $RowSelect . '">' . $value['inputValue']['postponed'][2] . '</textarea>
                                                                        </div>
                                                                    </div>

                                                                </div>
                                                            </div>
                                                        </div>
                                                    ';
                                                $RowSelect++;
                                            }

                                            echo $card_html;
                                            ?>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="col-md-12 mt-4">
                                                <div class="mb-3">
                                                    <label for="Textarea" class="form-label text-primary required">รายละเอียด</label>
                                                    <textarea class="form-control" id="details" <?php echo $disabledAll ?>><?php echo $data_arr['details']; ?></textarea>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12 col-md-12 row">
                                            <div class="col-12 col-md-6 mt-3">
                                                <input type="file" class="form-control" id="file_upload" name="file_upload[]" multiple accept="image/*,.pdf,.xlsx" <?php echo $disabledAll ?>>
                                            </div>

                                            <div class="col-12 col-md-6 mt-3">
                                                <div class="input-group mb-3">
                                                    <span class="input-group-text">มูลค่า</span>
                                                    <input type="number" min="0.00" class="form-control" id="cost" value="<?php echo $data_arr['cost']; ?>" <?php echo $disabledAll ?>>
                                                </div>
                                            </div>

                                        </div>
                                        <div class="col-12 col-md-12 d-flex flex-wrap">
                                            <div class="col-12 col-md-12 mb-3">
                                                <span class="fs-5 fw-bold">ไฟล์แนบ : </span>
                                            </div>
                                            <?php
                                            $files_arr = json_decode($data_arr['files'] ?? '', true) ?? [];
                                            foreach ($files_arr as $fileid => $file) {
                                                $filename = explode('/', $file['path_file'] ?? '');
                                            ?>
                                                <span class="badge bg-warning text-dark p-3 mx-2 my-2">
                                                    <a href="#" onclick="DelFile('<?php echo $fileid; ?>', '<?php echo $filename[2] ?? ''; ?>')" <?php echo $data_arr['doc_status'] != 'Savedraft' && $data_arr['doc_status'] != 'Draf' && $data_arr['doc_status'] != 'Rework' && (!(is_array($session_keys) && is_array($data_admin) ? array_intersect($session_keys, $data_admin) : false) || $data_arr['userCreate'] == $auth) ? 'style="display: none;"' : '' ?>>
                                                        <i class="bi bi-x-circle-fill fs-4 " style="color: red;"></i>
                                                    </a>
                                                    <a href="https://innovation.asefa.co.th/ChangeRequestForm/file/<?php echo $filename[2] ?? ''; ?>" class="ms-2 fs-7 text-dark" target="_blank">
                                                        <?php echo ($file['name_files'] ?? '') == '' ? ($filename[2] ?? '') : $file['name_files']; ?>
                                                    </a>
                                                </span>
                                            <?php
                                            }
                                            ?>
                                        </div>
                                        <div id="selected-problem">

                                        </div>

                                        <!-- Modal -->
                                        <div id="Modal-Select">

                                        </div>

                                        <hr class="mt-6 mb-6">
                                        <div class="col-md-12">
                                            <span class="fs-5 fw-bold text-decoration-underline">ส่วนของการวิเคราะห์ผลกระทบที่เกิดขึ้น</span>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="col-md-12 mt-4">
                                                <div class="mb-3">
                                                    <label for="Textarea" class="form-label text-primary required">ผลกระทบที่เกิดจากการเปลี่ยนแปลง</label>
                                                    <textarea class="form-control" id="effect" <?php echo $disabledAll ?>><?php echo $data_arr['job_remark']; ?></textarea>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <span class="fs-5 fw-bold text-decoration-underline">ประมาณการค่าใช้จ่ายที่เกิดขึ้น</span>
                                        </div>
                                        <div class="col-md-12 mb-4">
                                            <div class="row justify-content-start">
                                                <div class="col-md-4 mt-4">
                                                    <label for="servicename" class="form-label text-primary required">ค่าใช้จ่ายในครั้งนี้</label>
                                                    <input type="number" min="0.00" class="form-control text-start" id="expenses" value="<?php echo $data_arr['expenses']; ?>" <?php echo $disabledAll ?>>
                                                </div>
                                                <div class="col-md-4 mt-4">
                                                    <label for="servicename" class="form-label text-primary required">ค่าใช้จ่ายสะสมรวม</label>
                                                    <input type="number" min="0.00" class="form-control text-start" id="expenses_total" value="<?php echo $data_arr['expenses_total']; ?>" <?php echo $disabledAll ?>>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12 mt-4">
                                            <label for="servicename" class="form-label text-primary required">กำหนดให้ผู้เกี่ยวข้องดำเนินการ</label>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="row justify-content-start align-items-center">
                                                <?php
                                                $related_arr = json_decode($data_arr['related'], true);
                                                foreach (DivisionList() as $key => $value) {
                                                ?>
                                                    <div class="col-4 col-md-2 mt-4">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox" name="Divi_Select" value="<?php echo $value['Division_ID'] ?>" <?php echo in_array($value['Division_ID'], $related_arr['divitext']) ? "checked" : ""; ?> <?php echo $disabledAll ?>>
                                                            <label class="fw-bold" for="flexCheckDefault">
                                                                <?php echo $value['Division_Name'] ?>
                                                            </label>
                                                        </div>
                                                    </div>
                                                <?php
                                                }
                                                ?>
                                                <!-- <div class="col-10 col-md-6 mt-4">
                                                    <select class="form-select" name="divitext[]" id="divitext" multiple <?php echo $disabledAll ?>>
                                                        <?php
                                                        // foreach(DivisionList() as $key => $value){
                                                        //     $selected_divi = in_array($value['Division_ID'], $related_arr['divitext']) ? "selected" : "";
                                                        //     echo "<option value='". $value['Division_ID'] ."' " . $selected_divi . ">" . $value['Division_Name'] . "</option>";
                                                        // }
                                                        ?>
                                                    </select>
                                                </div> -->
                                                <div class="col-2 col-md-2 mt-4">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" name="conclude" id="divi_other" value="divitext_other" <?php echo $related_arr['divitext_other'] != '' ? "checked" : ""; ?> <?php echo $disabledAll ?>>
                                                        <label class="fw-bold" for="flexCheckDefault">
                                                            อื่นๆ
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-md-6 mt-4">
                                                    <div class="input-group" id="divitext_input" <?php echo $related_arr['divitext_other'] != '' ? 'style="display:inline-flex"' : 'style="display:none"' ?>>
                                                        <span class="input-group-text">อื่นๆ</span>
                                                        <input type="text" class="form-control" id="divitext_other" value="<?php echo $related_arr['divitext_other']; ?>">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div id="show_cho" <?php echo $data_arr['doc_type'] == 'CHO' ? 'style="display:block"' : 'style="display:none"' ?>>
                                            <hr class="mt-6 mb-6">
                                            <div class="col-md-12">
                                                <span class="fs-5 fw-bold text-decoration-underline">สรุปค่าใช้จ่ายที่เกิดขึ้น (โดยฝ่ายขาย)</span>
                                            </div>
                                            <div class="col-md-12">
                                                <?php
                                                $expensesummary_arr = json_decode($data_arr['expensesummary'], true);
                                                ?>
                                                <div class="row justify-content-start">
                                                    <div class="col-4 col-md-2 mt-4">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox" name="conclude" value="charge" <?php echo $expensesummary_arr['doc_charge'] != '' ? "checked" : ""; ?> <?php echo $disabledAll ?>>
                                                            <label class="fw-bold" for="flexCheckDefault">
                                                                คิดเงิน
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="col-4 col-md-2 mt-4">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox" name="conclude" value="newwork" <?php echo $expensesummary_arr['doc_newwork'] != '' ? "checked" : ""; ?> <?php echo $disabledAll ?>>
                                                            <label class="fw-bold" for="flexCheckDefault">
                                                                ใบเปิดงานเลขที่
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="col-4 col-md-2 mt-4">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox" name="conclude" value="nocharge" <?php echo $expensesummary_arr['inasmuch'] != '' ? "checked" : ""; ?> <?php echo $disabledAll ?>>
                                                            <label class="fw-bold" for="flexCheckDefault">
                                                                ไม่คิดเงิน
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-12">
                                                <div class="row justify-content-between">
                                                    <div class="col-md-6 mt-4" id="doc_charge_input" <?php echo $expensesummary_arr['doc_charge'] != '' ? 'style="display:inline-flex"' : 'style="display:none"' ?>>
                                                        <div class="input-group">
                                                            <span class="input-group-text">(คิดเงิน) จำนวนเงิน</span>
                                                            <input type="number" class="form-control" id="doc_charge" value="<?php echo $expensesummary_arr['doc_charge'] != '' ? $expensesummary_arr['doc_charge'] : ''; ?>" <?php echo $disabledAll ?>>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 mt-4" id="doc_newwork_input" <?php echo $expensesummary_arr['doc_newwork'] != '' ? 'style="display:inline-flex"' : 'style="display:none"' ?>>
                                                        <div class="input-group">
                                                            <span class="input-group-text">เลขที่</span>
                                                            <input type="text" class="form-control" id="doc_newwork" value="<?php echo $expensesummary_arr['doc_newwork'] != '' ? $expensesummary_arr['doc_newwork'] : ''; ?>" <?php echo $disabledAll ?>>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 mt-4">
                                                        <div class="input-group" id="inasmuch_input" <?php echo $expensesummary_arr['inasmuch'] != '' ? 'style="display:inline-flex"' : 'style="display:none"' ?>>
                                                            <span class="input-group-text">(ไม่คิดเงิน) เนื่องจาก</span>
                                                            <input type="text" class="form-control" id="inasmuch" value="<?php echo $expensesummary_arr['inasmuch'] != '' ? $expensesummary_arr['inasmuch'] : ''; ?>" <?php echo $disabledAll ?>>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- <div class="col-md-12">
                                                <div class="row justify-content-between">
                                                    <div class="col-md-6">
                                                        <div class="input-group mt-3" id="inasmuch_input" <?php echo $expensesummary_arr['concludeValue'] == 'nocharge' ? 'style="display:block"' : 'style="display:none"' ?>>
                                                            <span class="input-group-text">เนื่องจาก</span>
                                                            <input type="text" class="form-control" id="inasmuch" value="<?php echo $expensesummary_arr['concludeValue'] == 'nocharge' ? $expensesummary_arr['conclude'] : ''; ?>" <?php echo $disabledAll ?>>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div> -->
                                        </div>
                                        <div class="col-md-12">
                                            <div class="row justify-content-start align-items-center">
                                                <?php
                                                if ($data_arr['doc_status'] == 'Rework') {
                                                ?>
                                                    <div class="col-md-12 mt-4">
                                                        <label for="servicename" class="form-label text-primary required">สาเหตุการ Rework</label>
                                                        <textarea class="form-control" rows="3" disabled><?php echo $data_arr['reject_note']; ?></textarea>
                                                    </div>
                                                <?php
                                                }
                                                ?>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="row justify-content-start align-items-center">
                                                <?php
                                                if ($data_arr['doc_status'] == 'Cancel') {
                                                ?>
                                                    <div class="col-md-12 mt-4">
                                                        <label for="servicename" class="form-label text-primary required">สาเหตุการ Cancel</label>
                                                        <textarea class="form-control" rows="3" disabled><?php echo $data_arr['cancel_remark']; ?></textarea>
                                                    </div>
                                                <?php
                                                }
                                                ?>
                                            </div>
                                        </div>
                                        <?php
                                        $isAdmin   = !empty(array_intersect($session_keys, $data_admin));
                                        $isDraft   = !empty(array_intersect($session_keys, $data_Draft));
                                        $isPrint   = !empty(array_intersect($session_keys, $data_Print));
                                        $isCancel  = !empty(array_intersect($session_keys, $data_Cancel));

                                        $userCode  = $_SESSION['ChangeRequest_code'];
                                        $divHeadID = $_SESSION['DivisionHeadID2'] ?? '';

                                        $docNo     = $data_arr['CR_no'];
                                        $docType   = $data_arr['doc_type'];
                                        $docStatus = $data_arr['doc_status'];

                                        $output = '';
                                        $output .= '<div class="col-12 col-md-12"><div class="row py-3 justify-content-end"><div class="d-flex flex-end col-12 col-md-12 text-end gap-2">';

                                        switch ($docStatus) {

                                            case 'Savedraft':
                                                $output .= <<<HTML
                                                <button class="btn btn-info mt-4" type="button" onclick="EditData('Savedraft', '{$docNo}')">
                                                    <i class="fas fa-edit fs-4"></i> บันทึกการแก้ไข
                                                </button>
                                                <button class="btn btn-primary mt-4" type="button" onclick="EditData('New', '{$docNo}')">
                                                    <i class="fa-solid fa-floppy-disk fs-4"></i> แจ้งงาน
                                                </button>
                                                HTML;
                                                break;

                                            case 'New':
                                                if ($data_arr['userCreate'] == $userCode) {
                                                    $output .= <<<HTML
                                                    <button class="btn btn-danger mt-4" type="button" data-bs-toggle="modal" data-bs-target="#cancelnote">
                                                        <i class="fas fa-window-close fs-4"></i> Cancel
                                                    </button>
                                                    HTML;
                                                }
                                                if ($isAdmin || $isDraft) {
                                                    $output .= <<<HTML
                                                    <button class="btn btn-info mt-4" type="button" id="accepting"
                                                        onclick="Accepting('Draf', '{$docNo}', '{$docType}')">
                                                        <i class="fas fa-vote-yea fs-4"></i> รับงาน
                                                    </button>
                                                    HTML;
                                                }
                                                break;

                                            case 'Draf':
                                                if ($isAdmin || $isDraft) {
                                                    $output .= <<<HTML
                                                    <button class="btn btn-success mt-4" type="button" data-bs-toggle="modal" data-bs-target="#approve">
                                                        <i class="fas fa-user-clock fs-4"></i> ส่งตรวจสอบ
                                                    </button>
                                                    <button class="btn btn-info mt-4" type="button" onclick="EditData('Draf', '{$docNo}')">
                                                        <i class="fas fa-edit fs-4"></i> บันทึกการแก้ไข
                                                    </button>
                                                    HTML;
                                                }
                                                break;

                                            case 'Rework':
                                            case 'Not Approve':
                                                if ($isAdmin || $data_arr['userCreate'] == $userCode) {
                                                    $output .= <<<HTML
                                                    <button class="btn btn-info mt-4" type="button" onclick="EditData('{$docStatus}', '{$docNo}')">
                                                        <i class="fas fa-edit fs-4"></i> บันทึกการแก้ไข
                                                    </button>
                                                    <button class="btn btn-primary mt-4" type="button" onclick="EditData('New', '{$docNo}')">
                                                        <i class="fa-solid fa-floppy-disk fs-4"></i> แจ้งงาน
                                                    </button>
                                                    HTML;
                                                }
                                                break;

                                            case 'Review':
                                                if ($isAdmin || ($apporve_arr_1['status_approve'] == '0' && $userCode == $apporve_arr_1['Approve_1'])) {
                                                    $output .= <<<HTML
                                                    <button type="button" class="btn btn-info mt-4" data-bs-toggle="modal" data-bs-target="#rejectnote">
                                                        <i class="fas fa-undo-alt fs-4"></i> Rework
                                                    </button>
                                                    HTML;
                                                }
                                                if ($data_arr['userCreate'] == $userCode && $userCode != $apporve_arr_1['Approve_1']) {
                                                    $output .= <<<HTML
                                                    <button class="btn btn-danger mt-4" type="button" data-bs-toggle="modal" data-bs-target="#cancelnote">
                                                        <i class="fas fa-window-close fs-4"></i> Cancel
                                                    </button>
                                                    HTML;
                                                }
                                                if ($apporve_arr_1['status_approve'] == '0' && $userCode == $apporve_arr_1['Approve_1']) {
                                                    $output .= <<<HTML
                                                    <button class="btn btn-danger mt-4" type="button" data-bs-toggle="modal" data-bs-target="#cancelnote">
                                                        <i class="fas fa-window-close fs-4"></i> Cancel
                                                    </button>
                                                    <button class="btn btn-success mt-4" type="button"
                                                        onclick="Accepting('Check', '{$docNo}', '{$docType}', '1')">
                                                        <i class="fas fa-vote-yea fs-4"></i> Acknowledge
                                                    </button>
                                                    HTML;
                                                }
                                                break;

                                            case 'Check':
                                                if ($isAdmin || (($apporve_arr_1['status_approve'] == '1' && $apporve_arr_2['status_approve'] == '0') && $userCode == $apporve_arr_2['Approve_2'])) {
                                                    $output .= <<<HTML
                                                    <button type="button" class="btn btn-info mt-4" data-bs-toggle="modal" data-bs-target="#rejectnote">
                                                        <i class="fas fa-undo-alt fs-4"></i> Rework
                                                    </button>
                                                    HTML;
                                                }
                                                if ($data_arr['userCreate'] == $userCode && $userCode != $apporve_arr_2['Approve_2']) {
                                                    $output .= <<<HTML
                                                    <button class="btn btn-danger mt-4" type="button" data-bs-toggle="modal" data-bs-target="#cancelnote">
                                                        <i class="fas fa-window-close fs-4"></i> Cancel
                                                    </button>
                                                    HTML;
                                                }
                                                if (($apporve_arr_1['status_approve'] == '1' && $apporve_arr_2['status_approve'] == '0') && $userCode == $apporve_arr_2['Approve_2']) {
                                                    $status_doc = ($apporve_arr_3['Approve_3'] != '-' && $apporve_arr_3['Approve_3'] != '') ? 'Recheck' : 'Close';
                                                    $output .= <<<HTML
                                                    <button class="btn btn-danger mt-4" type="button" data-bs-toggle="modal" data-bs-target="#cancelnote">
                                                        <i class="fas fa-window-close fs-4"></i> Cancel
                                                    </button>
                                                    <button class="btn btn-success mt-4" type="button"
                                                        onclick="Accepting('{$status_doc}', '{$docNo}', '{$docType}', '2')">
                                                        <i class="fas fa-vote-yea fs-4"></i> Acknowledge
                                                    </button>
                                                    HTML;
                                                }
                                                break;

                                            case 'Recheck':
                                                // Rework buttons
                                                if ($isAdmin) {
                                                    $output .= <<<HTML
                                                    <button type="button" class="btn btn-info mt-4" data-bs-toggle="modal" data-bs-target="#rejectnote">
                                                        <i class="fas fa-undo-alt fs-4"></i> Rework
                                                    </button>
                                                    HTML;
                                                }
                                                if ($apporve_arr_3['status_approve'] == '0' && $userCode == $apporve_arr_3['Approve_3']) {
                                                    $output .= <<<HTML
                                                    <button type="button" class="btn btn-info mt-4" data-bs-toggle="modal" data-bs-target="#rejectnote">
                                                        <i class="fas fa-undo-alt fs-4"></i> Rework
                                                    </button>
                                                    <button class="btn btn-danger mt-4" type="button"
                                                        onclick="Accepting('Not Approve', '{$docNo}', '{$docType}', '3')">
                                                        <i class="fas fa-window-close fs-4"></i> Not Approve
                                                    </button>
                                                    <button class="btn btn-success mt-4" type="button"
                                                        onclick="Accepting('Approve', '{$docNo}', '{$docType}', '3')">
                                                        <i class="fas fa-check-circle fs-4"></i> Approve
                                                    </button>
                                                    HTML;
                                                }

                                                if (($apporve_arr_3['status_approve'] == '1' && $apporve_arr_4['status_approve'] == '0') && $userCode == $apporve_arr_4['Approve_4']) {
                                                    $output .= <<<HTML
                                                    <button type="button" class="btn btn-info mt-4" data-bs-toggle="modal" data-bs-target="#rejectnote">
                                                        <i class="fas fa-undo-alt fs-4"></i> Rework
                                                    </button>
                                                    <button class="btn btn-danger mt-4" type="button"
                                                        onclick="Accepting('Not Approve', '{$docNo}', '{$docType}', '4')">
                                                        <i class="fas fa-window-close fs-4"></i> Not Approve
                                                    </button>
                                                    <button class="btn btn-success mt-4" type="button"
                                                        onclick="Accepting('Approve', '{$docNo}', '{$docType}', '4')">
                                                        <i class="fas fa-check-circle fs-4"></i> Approve
                                                    </button>
                                                    HTML;
                                                }

                                                if (($apporve_arr_5['status_approve'] == '0' && $apporve_arr_4['status_approve'] == '1') && $userCode == $apporve_arr_5['Approve_5'] && $apporve_arr_5['Approve_5'] != '-') {
                                                    $output .= <<<HTML
                                                    <button type="button" class="btn btn-info mt-4" data-bs-toggle="modal" data-bs-target="#rejectnote">
                                                        <i class="fas fa-undo-alt fs-4"></i> Rework
                                                    </button>
                                                    <button class="btn btn-danger mt-4" type="button"
                                                        onclick="Accepting('Not Approve', '{$docNo}', '{$docType}', '5')">
                                                        <i class="fas fa-window-close fs-4"></i> Not Approve
                                                    </button>
                                                    <button class="btn btn-success mt-4" type="button"
                                                        onclick="Accepting('Close', '{$docNo}', '{$docType}', '5')">
                                                        <i class="fas fa-check-circle fs-4"></i> Approve
                                                    </button>
                                                    HTML;
                                                }
                                                break;

                                            case 'Approve':
                                                // Print and Approve continuation
                                                if ($isAdmin || $isPrint) {
                                                    if ($docType == 'CHI') {
                                                        $output .= <<<HTML
                                                        <a href="./Print_Form_CHI?CR_no={$docNo}" class="btn btn-primary mt-4" target="_blank">
                                                            <i class="bi bi-printer-fill fs-4"></i> พิมพ์
                                                        </a>
                                                        HTML;
                                                    } elseif ($docType == 'CHO') {
                                                        $output .= <<<HTML
                                                        <a href="./Print_Form_CHO?CR_no={$docNo}" class="btn btn-primary mt-4" target="_blank">
                                                            <i class="bi bi-printer-fill fs-4"></i> พิมพ์
                                                        </a>
                                                        HTML;
                                                    }
                                                }

                                                if (($apporve_arr_3['status_approve'] == '1' && $apporve_arr_4['status_approve'] == '0') && $userCode == $apporve_arr_4['Approve_4']) {
                                                    $status_doc = ($apporve_arr_5['Approve_5'] != '-' && $apporve_arr_5['Approve_5'] != '') ? 'Approve' : 'Close';
                                                    $output .= <<<HTML
                                                    <button class="btn btn-danger mt-4" type="button"
                                                        onclick="Accepting('Not Approve', '{$docNo}', '{$docType}', '4')">
                                                        <i class="fas fa-window-close fs-4"></i> Not Approve
                                                    </button>
                                                    <button class="btn btn-success mt-4" type="button"
                                                        onclick="Accepting('{$status_doc}', '{$docNo}', '{$docType}', '4')">
                                                        <i class="fas fa-check-circle fs-4"></i> Approve
                                                    </button>
                                                    HTML;
                                                }

                                                if (($apporve_arr_5['status_approve'] == '0' && $apporve_arr_4['status_approve'] == '1') && $userCode == $apporve_arr_5['Approve_5'] && $apporve_arr_5['Approve_5'] != '-') {
                                                    $output .= <<<HTML
                                                    <button class="btn btn-danger mt-4" type="button"
                                                        onclick="Accepting('Not Approve', '{$docNo}', '{$docType}', '5')">
                                                        <i class="fas fa-window-close fs-4"></i> Not Approve
                                                    </button>
                                                    <button class="btn btn-success mt-4" type="button"
                                                        onclick="Accepting('Close', '{$docNo}', '{$docType}', '5')">
                                                        <i class="fas fa-check-circle fs-4"></i> Approve
                                                    </button>
                                                    HTML;
                                                }
                                                break;

                                            case 'Close':
                                                if ($divHeadID == '651' || $userCode == '650200036') {
                                                    if (empty($data_arr['send_mail'])) {
                                                        $output .= <<<HTML
                                                        <button class="btn btn-success mt-4" type="button" id="sendMail">
                                                            <i class="fa-solid fa-envelope-circle-check"></i> Send Mail
                                                        </button>
                                                        HTML;
                                                    }
                                                }
                                                if ($isAdmin || $isPrint) {
                                                    if ($docType == 'CHI') {
                                                        $output .= <<<HTML
                                                        <a href="./Print_Form_CHI?CR_no={$docNo}" class="btn btn-primary mt-4" target="_blank">
                                                            <i class="bi bi-printer-fill fs-4"></i> พิมพ์
                                                        </a>
                                                        HTML;
                                                    } elseif ($docType == 'CHO') {
                                                        $output .= <<<HTML
                                                        <a href="./Print_Form_CHO?CR_no={$docNo}" class="btn btn-primary mt-4" target="_blank">
                                                            <i class="bi bi-printer-fill fs-4"></i> พิมพ์
                                                        </a>
                                                        HTML;
                                                    }
                                                }
                                                if ($isDraft || $isAdmin) {
                                                    $output .= <<<HTML
                                                    <button class="btn btn-info mt-4" type="button" onclick="EditData('Close', '{$docNo}')">
                                                        <i class="fas fa-edit fs-4"></i> บันทึกการแก้ไข
                                                    </button>
                                                    HTML;
                                                }
                                                break;
                                        }

                                        if (($isCancel || $isAdmin) && $docStatus != 'Cancel') {
                                            $output .= <<<HTML
                                            <button class="btn btn-danger mt-4" type="button" data-bs-toggle="modal" data-bs-target="#cancelnote">
                                                <i class="fas fa-window-close fs-4"></i> Cancel
                                            </button>
                                            HTML;
                                        }

                                        if ($docStatus == 'Cancel') {
                                            $DataE = $_SESSION['DataE'];
                                            $output .= <<<HTML
                                            <a class="btn btn-warning mt-4"
                                            href="https://innovation.asefa.co.th/ChangeRequestForm/copyForm?DataE={$DataE}&CR_no={$docNo}">
                                                <i class="fa fa-copy"></i> Copy
                                            </a>
                                            HTML;
                                        }

                                        $output .= '</div></div></div>';

                                        echo $output;
                                        ?>


                                        <div class="modal fade" id="rejectnote" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="rejectnoteLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="rejectnoteLabel">Rowork</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="mb-3">
                                                            <label for="message-text" class="col-form-label">สาเหตุการ Rework :</label>
                                                            <textarea class="form-control" id="rework_note" rows="3"></textarea>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ปิด</button>
                                                        <button type="button" class="btn btn-primary" onclick="Accepting('Rework', '<?php echo $data_arr['CR_no'] ?>', '<?php echo $data_arr['doc_type'] ?>')">Rework</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="modal fade" id="cancelnote" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="cancelLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Cancel</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="mb-3">
                                                            <label for="message-text" class="col-form-label">สาเหตุการ Cancel :</label>
                                                            <textarea class="form-control" id="cancel_note" rows="3"></textarea>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ปิด</button>
                                                        <button type="button" class="btn btn-primary" onclick="Accepting('Cancel', '<?php echo $data_arr['CR_no'] ?>', '<?php echo $data_arr['doc_type'] ?>')">Cancel</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="modal fade" id="approve" tabindex="-1" aria-labelledby="approveLabel" aria-hidden="true" data-bs-keyboard="false">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="approveLabel">เลือกผู้ตรวจสอบ และ เลือกผู้อนุมัติ</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="col-12">
                                                            <label for="servicename" class="form-label text-primary">ผู้ตรวจสอบคนที่ 1 (เจ้าหน้าที่เทคนิค)</label>
                                                            <select id="approveSelect_1" class="form-select mb-4 select_2">
                                                                <option value=""></option>
                                                                <?php echo implode('', $approveOptions_1); ?>
                                                                <option value="-">-</option>
                                                            </select>
                                                        </div>

                                                        <div class="col-12">
                                                            <label for="servicename" class="form-label text-primary">ผู้ตรวจสอบคนที่ 2 (ผจก.แผนก/ฝ่ายเทคนิค)</label>
                                                            <select id="approveSelect_2" class="form-select mb-4 select_2">
                                                                <option value=""></option>
                                                                <?php echo implode('', $approveOptions_2); ?>
                                                                <option value="-">-</option>
                                                            </select>
                                                        </div>

                                                        <div class="col-12">
                                                            <label for="servicename" class="form-label text-primary">ผู้อนุมัติคนที่ 1 (พนักงานขาย)</label>
                                                            <select id="approveSelect_3" class="form-select mb-4 select_2">
                                                                <option value=""></option>
                                                                <?php echo implode('', $approveOptions_3); ?>
                                                                <option value="-">-</option>
                                                            </select>
                                                        </div>

                                                        <div class="col-12">
                                                            <label for="servicename" class="form-label text-primary">ผู้อนุมัติคนที่ 2 (ผจก.แผนก/ฝ่ายขาย)</label>
                                                            <select id="approveSelect_4" class="form-select mb-4 select_2">
                                                                <option value=""></option>
                                                                <?php echo implode('', $approveOptions_4); ?>
                                                                <option value="-">-</option>
                                                            </select>
                                                        </div>

                                                        <div class="col-12">
                                                            <label for="servicename" class="form-label text-primary">ประธานเจ้าหน้าที่บริหาร (อ้างอิงอำนาจอนุมัติฝ่ายขาย)</label>
                                                            <select id="approveSelect_5" class="form-select mb-4 select_2">
                                                                <option value=""></option>
                                                                <?php echo implode('', $approveOptions_5); ?>
                                                                <option value="-">-</option>
                                                            </select>
                                                        </div>

                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ปิด</button>
                                                        <?php
                                                        // if(($data_arr['doc_status'] == 'New') && (array_intersect($session_keys, $data_admin) || array_intersect($session_keys, $data_Draft))){
                                                        ?>
                                                        <!-- <button type="button" class="btn btn-primary" onclick="Accepting('Review', '<?php //echo $data_arr['CR_no'] 
                                                                                                                                            ?>', '<?php //echo $data_arr['doc_type'] 
                                                                                                                                                    ?>', '0')">ส่งตรวจสอบ</button> -->
                                                        <?php
                                                        // }
                                                        ?>

                                                        <?php
                                                        if (($data_arr['doc_status'] == 'Draf') && (array_intersect($session_keys, $data_admin) || array_intersect($session_keys, $data_Draft))) {
                                                        ?>
                                                            <button type="button" class="btn btn-primary" onclick="EditData('Review', '<?php echo $data_arr['CR_no'] ?>', '0')">ส่งตรวจสอบ</button>
                                                        <?php
                                                        }
                                                        ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>


                            <div class="row row-cols-1 row-cols-md-2 row-cols-xl-2 g-4">
                                <?php
                                if ($data_arr['doc_status'] != '' && $data_arr['doc_status'] != 'Savedraft' && $data_arr['doc_status'] != 'Rework' && $data_arr['doc_status'] != 'Cancel') {
                                    $status_time = Get_Time_Status($data_arr['CR_no'], 'Review');
                                    if ($status_time['Status_User'] == '' || $data_arr['doc_status'] == 'Not Approve') {
                                        $status_time = Get_Time_Status($data_arr['CR_no'], 'Recheck');
                                    }
                                ?>
                                    <div class="card mt-4 hover-card">
                                        <h4 class="card-header bg-success text-white d-flex align-items-center" style="min-height: 50px;">
                                            <div>
                                                <i class="bi bi-check-circle-fill me-2 fs-3 text-white"></i>
                                                ผู้แจ้ง
                                            </div>
                                        </h4>
                                        <div class="card-body row p-4 px-8">
                                            <div class="col-12 col-md-6">
                                                <div class="fs-6">แจ้งโดย : <?php echo mydata($data_arr['userCreate'])['FullName']; ?></div>
                                            </div>
                                            <div class="col-12 col-md-6">
                                                <div class="fs-6">วันที่แจ้ง : <?php echo $data_arr['dateCreate']; ?></div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="card mt-4 hover-card">
                                        <h4 class="card-header <?php echo $status_time['Status_User'] == '' || $data_arr['doc_status'] == 'Not Approve' ? 'bg-warning' : 'bg-success'; ?> text-white d-flex align-items-center" style="min-height: 50px;">
                                            <div>
                                                <i class="bi <?php echo $status_time['Status_User'] == '' || $data_arr['doc_status'] == 'Not Approve' ? 'bi-hourglass-split' : 'bi-check-circle-fill'; ?> me-2 fs-3 text-white"></i>
                                                AdminTC
                                            </div>
                                        </h4>
                                        <div class="card-body row p-4 px-8">
                                            <div class="col-12 col-md-6">
                                                <div class="fs-6">อนุมัติโดย : <?php echo $status_time['Status_User'] == '' || $data_arr['doc_status'] == 'Not Approve' ? 'รออนุมัติ' : mydata($status_time['Status_User'])['FullName'];; ?></div>
                                            </div>
                                            <div class="col-12 col-md-6">
                                                <div class="fs-6">วันที่ : <?php echo $status_time['Status_Date'] == '' || $data_arr['doc_status'] == 'Not Approve' ? 'รออนุมัติ' : $status_time['Status_Date']; ?></div>
                                            </div>
                                        </div>
                                    </div>
                                <?php
                                }
                                ?>

                                <?php
                                if ($apporve_arr_1 != '' && $data_arr['doc_status'] != 'Savedraft' && $data_arr['doc_status'] != 'Rework' && $data_arr['doc_status'] != 'Not Approve' && $data_arr['doc_status'] != 'Cancel') {
                                    $status_classes = [
                                        '0' => 'bg-warning',
                                        '1' => 'bg-success',
                                        '2' => 'bg-danger',
                                    ];

                                    $status_icons = [
                                        '0' => 'bi-hourglass-split',
                                        '1' => 'bi-check-circle-fill',
                                        '2' => 'bi-x-circle-fill',
                                    ];

                                    $status_texts = [
                                        '0' => 'รออนุมัติ',
                                        '0.1' => 'รอตรวจสอบ',
                                        '1' => 'อนุมัติแล้ว',
                                        '2' => 'ยกเลิก',
                                    ];
                                ?>

                                    <?php
                                    if ($apporve_arr_1 != null && $apporve_arr_1['Approve_1'] != '-' && $apporve_arr_1['Approve_1'] != '') {
                                    ?>
                                        <div class="card mt-4 hover-card">
                                            <h4 class="card-header <?php echo $status_classes[$apporve_arr_1['status_approve']]; ?> text-white d-flex justify-content-between align-items-center" style="min-height: 50px;">
                                                <div>
                                                    <i class="bi <?php echo $status_icons[$apporve_arr_1['status_approve']]; ?> me-2 fs-3 text-white"></i>
                                                    <?php echo $status_texts[$apporve_arr_1['status_approve'] . ".1"]; ?> (เจ้าหน้าที่เทคนิค)
                                                </div>
                                                <?php if (is_array($session_keys) && is_array($data_admin) && array_intersect($session_keys, $data_admin) && $apporve_arr_1['status_approve'] == '0') { ?>
                                                    <button type="button" class="btn btn-sm btn-light text-primary fw-bold shadow-sm rounded-pill px-3" title="เปลี่ยนคนอนุมัติ" onclick="openEditApprover('CR_Approve1', 'Approve_1', '<?php echo $apporve_arr_1['Approve_1']; ?>')">
                                                        <i class="fas fa-edit"></i> เปลี่ยนผู้อนุมัติ
                                                    </button>
                                                <?php } ?>
                                            </h4>
                                            <div class="card-body row p-4 px-8">
                                                <div class="col-12 col-md-6">
                                                    <div class="fs-6">ตรวจสอบโดย : <?php echo mydata($apporve_arr_1['Approve_1'])['FullName']; ?></div>
                                                </div>
                                                <div class="col-12 col-md-6">
                                                    <div class="fs-6">วันที่ : <?php echo $apporve_arr_1['status_approve'] == '1' || $apporve_arr_1['status_approve'] == '2' ? $apporve_arr_1['date_approve'] : 'รอตรวจสอบ'; ?></div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php
                                    }
                                    ?>

                                    <?php
                                    if ($apporve_arr_2 != null && $apporve_arr_2['Approve_2'] != '-' && $apporve_arr_2['Approve_2'] != '') {
                                    ?>
                                        <div class="card mt-4 hover-card">
                                            <h4 class="card-header <?php echo $status_classes[$apporve_arr_2['status_approve']]; ?> text-white d-flex justify-content-between align-items-center" style="min-height: 50px;">
                                                <div>
                                                    <i class="bi <?php echo $status_icons[$apporve_arr_2['status_approve']]; ?> me-2 fs-3 text-white"></i>
                                                    <?php echo $status_texts[$apporve_arr_2['status_approve'] . ".1"]; ?> (ผจก.แผนก/ฝ่ายเทคนิค)
                                                </div>
                                                <?php if (is_array($session_keys) && is_array($data_admin) && array_intersect($session_keys, $data_admin) && $apporve_arr_2['status_approve'] == '0') { ?>
                                                    <button type="button" class="btn btn-sm btn-light text-primary fw-bold shadow-sm rounded-pill px-3" title="เปลี่ยนคนอนุมัติ" onclick="openEditApprover('CR_Approve2', 'Approve_2', '<?php echo $apporve_arr_2['Approve_2']; ?>')">
                                                        <i class="fas fa-edit"></i> เปลี่ยนผู้อนุมัติ
                                                    </button>
                                                <?php } ?>
                                            </h4>
                                            <div class="card-body row p-4 px-8">
                                                <div class="col-12 col-md-6">
                                                    <div class="fs-6">ตรวจสอบโดย : <?php echo mydata($apporve_arr_2['Approve_2'])['FullName']; ?></div>
                                                </div>
                                                <div class="col-12 col-md-6">
                                                    <div class="fs-6">วันที่ : <?php echo $apporve_arr_2['status_approve'] == '1' || $apporve_arr_2['status_approve'] == '2' ? $apporve_arr_2['date_approve'] : 'รอตรวจสอบ'; ?></div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php
                                    }
                                    ?>

                                    <?php
                                    if ($apporve_arr_3 != null && $apporve_arr_3['Approve_3'] != '-' && $apporve_arr_3['Approve_3'] != '') {
                                    ?>
                                        <div class="card mt-4 hover-card">
                                            <h4 class="card-header <?php echo $status_classes[$apporve_arr_3['status_approve']]; ?> text-white d-flex justify-content-between align-items-center" style="min-height: 50px;">
                                                <div>
                                                    <i class="bi <?php echo $status_icons[$apporve_arr_3['status_approve']]; ?> me-2 fs-3 text-white"></i>
                                                    <?php echo $status_texts[$apporve_arr_3['status_approve']]; ?> (พนักงานขาย)
                                                </div>
                                                <?php if (is_array($session_keys) && is_array($data_admin) && array_intersect($session_keys, $data_admin) && $apporve_arr_3['status_approve'] == '0') { ?>
                                                    <button type="button" class="btn btn-sm btn-light text-primary fw-bold shadow-sm rounded-pill px-3" title="เปลี่ยนคนอนุมัติ" onclick="openEditApprover('CR_Approve3', 'Approve_3', '<?php echo $apporve_arr_3['Approve_3']; ?>')">
                                                        <i class="fas fa-edit"></i> เปลี่ยนผู้อนุมัติ
                                                    </button>
                                                <?php } ?>
                                            </h4>
                                            <div class="card-body row p-4 px-8">
                                                <div class="col-12 col-md-6">
                                                    <div class="fs-6">อนุมัติโดย : <?php echo mydata($apporve_arr_3['Approve_3'])['FullName']; ?></div>
                                                </div>
                                                <div class="col-12 col-md-6">
                                                    <div class="fs-6">วันที่ : <?php echo $apporve_arr_3['status_approve'] == '1' || $apporve_arr_3['status_approve'] == '2' ? $apporve_arr_3['date_approve'] : 'รออนุมัติ'; ?></div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php
                                    }
                                    ?>


                                    <?php
                                    if ($apporve_arr_4 != null && $apporve_arr_4['Approve_4'] != '-' && $apporve_arr_4['Approve_4'] != '') {
                                    ?>
                                        <div class="card mt-4 hover-card">
                                            <h4 class="card-header <?php echo $status_classes[$apporve_arr_4['status_approve']]; ?> text-white d-flex justify-content-between align-items-center" style="min-height: 50px;">
                                                <div>
                                                    <i class="bi <?php echo $status_icons[$apporve_arr_4['status_approve']]; ?> me-2 fs-3 text-white"></i>
                                                    <?php echo $status_texts[$apporve_arr_4['status_approve']]; ?> (ผจก.แผนก/ฝ่ายขาย)
                                                </div>
                                                <?php if (is_array($session_keys) && is_array($data_admin) && array_intersect($session_keys, $data_admin) && $apporve_arr_4['status_approve'] == '0') { ?>
                                                    <button type="button" class="btn btn-sm btn-light text-primary fw-bold shadow-sm rounded-pill px-3" title="เปลี่ยนคนอนุมัติ" onclick="openEditApprover('CR_Approve4', 'Approve_4', '<?php echo $apporve_arr_4['Approve_4']; ?>')">
                                                        <i class="fas fa-edit"></i> เปลี่ยนผู้อนุมัติ
                                                    </button>
                                                <?php } ?>
                                            </h4>
                                            <div class="card-body row p-4 px-8">
                                                <div class="col-12 col-md-6">
                                                    <div class="fs-6">อนุมัติโดย : <?php echo mydata($apporve_arr_4['Approve_4'])['FullName']; ?></div>
                                                </div>
                                                <div class="col-12 col-md-6">
                                                    <div class="fs-6">วันที่ : <?php echo $apporve_arr_4['status_approve'] == '1' || $apporve_arr_4['status_approve'] == '2' ? $apporve_arr_4['date_approve'] : 'รออนุมัติ'; ?></div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php
                                    }
                                    ?>

                                    <?php
                                    if ($apporve_arr_5 != null && $apporve_arr_5['Approve_5'] != '-' && $apporve_arr_5['Approve_5'] != '') {
                                    ?>
                                        <div class="card mt-4 hover-card">
                                            <h4 class="card-header <?php echo $status_classes[$apporve_arr_5['status_approve']]; ?> text-white d-flex justify-content-between align-items-center" style="min-height: 50px;">
                                                <div>
                                                    <i class="bi <?php echo $status_icons[$apporve_arr_5['status_approve']]; ?> me-2 fs-3 text-white"></i>
                                                    <?php echo $status_texts[$apporve_arr_5['status_approve']]; ?> (ประธานเจ้าหน้าที่บริหาร)
                                                </div>
                                                <?php if (is_array($session_keys) && is_array($data_admin) && array_intersect($session_keys, $data_admin) && $apporve_arr_5['status_approve'] == '0') { ?>
                                                    <button type="button" class="btn btn-sm btn-light text-primary fw-bold shadow-sm rounded-pill px-3" title="เปลี่ยนคนอนุมัติ" onclick="openEditApprover('CR_Approve5', 'Approve_5', '<?php echo $apporve_arr_5['Approve_5']; ?>')">
                                                        <i class="fas fa-edit"></i> เปลี่ยนผู้อนุมัติ
                                                    </button>
                                                <?php } ?>
                                            </h4>
                                            <div class="card-body row p-4 px-8">
                                                <div class="col-12 col-md-4">
                                                    <div class="fs-6">อนุมัติโดย : <?php echo mydata($apporve_arr_5['Approve_5'])['FullName']; ?></div>
                                                </div>
                                                <div class="col-12 col-md-4">
                                                    <div class="fs-6">วันที่ : <?php echo $apporve_arr_5['status_approve'] == '1' || $apporve_arr_5['status_approve'] == '2' ? $apporve_arr_5['date_approve'] : 'รออนุมัติ'; ?></div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php
                                    }
                                    ?>

                                <?php
                                }
                                ?>

                            </div>

                            <!-- Modal for editing approver -->
                            <div class="modal fade" id="editApproverModal" tabindex="-1" aria-hidden="true" data-bs-keyboard="false">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">เปลี่ยนคนอนุมัติ</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <input type="hidden" id="editApprover_CR_no" value="<?php echo $data_arr['CR_no']; ?>">
                                            <input type="hidden" id="editApprover_field">
                                            <input type="hidden" id="editApprover_jsonKey">
                                            <div class="col-12 text-start">
                                                <label class="form-label text-primary mb-2">เลือกผู้อนุมัติคนใหม่</label>
                                                <select id="editApprover_select" class="form-select" style="width: 100%;">
                                                    <option value=""></option>
                                                    <?php echo implode('', createSelectOptions($emplist, '')); ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ปิด</button>
                                            <button type="button" class="btn btn-primary" onclick="saveNewApprover()">บันทึก</button>
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
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>

    <script>
        var RowSelect = <?php echo count($data_detail) + 1 ?>;

        $(document).ready(function() {
            setTimeout(function() {
                tadjust_all();
            }, 300);

            $('#jobtype').select2({
                placeholder: "กรุณาเลือกปรเภทเอกสาร"
            });

            $('#JobNo').select2({
                placeholder: "กรุณาเลือก Job"
            });

            $('.select_2').select2({
                placeholder: "กรุณาเลือกรายชื่อ",
                dropdownParent: $('#approve'),
                dropdownAutoWidth: true,
                width: '100%'
            });

            $('#WA').select2({
                placeholder: "กรุณาเลือก WA",
                width: '100%',
                dropdownAutoWidth: true,
                dropdownCssClass: 'select2-scrollable'
            });

            $('#SN').select2({
                placeholder: "กรุณาเลือก S/N",
                width: '100%',
                dropdownAutoWidth: true,
                dropdownCssClass: 'select2-scrollable'
            });

            $('#tc_name').select2({
                placeholder: "กรุณาเลือกชื่อ TC"
            });

            $('.Problem').select2({
                placeholder: "กรุณาเลือกรายการ",
                allowClear: true
            });

            $('#divitext').select2({
                placeholder: "แนผกที่เกี่ยวข้อง",
            })

            $("#jobtype").on("change", function() {
                var jobtype = $(this).val();

                if (jobtype == 'CHO') {
                    $("#show_cho").show();
                } else {
                    $("#show_cho").hide();
                }
            });

            // var RowSelect = <?php echo count($data_detail) + 1 ?>;
            $("#addRow-select").on("click", function() {
                var jobtype = $("#jobtype").val();

                if (jobtype == '') {
                    Swal.fire({
                        title: 'กรุณาเลือกปรเภทเอกสารก่อน',
                        icon: 'warning'
                    });

                    return false;
                }

                if (jobtype == 'CHI') {
                    var html_list = '';

                    <?php
                    foreach (CauseList('CHI') as $key => $value) {
                    ?>
                        html_list += `
                        <div class="col-md-2 mt-4" style="width: 20%;">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="chk_${RowSelect}" value="<?php echo $key ?>" data-id="<?php echo $key ?>_${RowSelect}">
                                <label class="fw-bold" for="flexCheckDefault">
                                    <?php echo $value['Cause_Name'] ?>
                                </label>
                            </div>
                        </div>
                    `;
                    <?php
                    }
                    ?>
                } else if (jobtype == 'CHO') {
                    var html_list = '';

                    <?php
                    foreach (CauseList('CHO') as $key => $value) {
                    ?>
                        html_list += `
                        <div class="col-md-2 mt-4" style="width: 20%;">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="chk_${RowSelect}" value="<?php echo $key ?>" data-id="<?php echo $key ?>_${RowSelect}">
                                <label class="fw-bold" for="flexCheckDefault">
                                    <?php echo $value['Cause_Name'] ?>
                                </label>
                            </div>
                        </div>
                    `;
                    <?php
                    }
                    ?>
                }

                var htmlSelect = `
                <div class="card border border-dark border-2 my-4" id="DetailSelect-${RowSelect}">
                    <div class="card-body row">
                        <div class="col-4">
                            <select class="form-select Problem" id="Problem-${RowSelect}">
                                <option value=""></option>
                                <option value="Electrical">อุปกรณ์ไฟฟ้า</option>
                                <option value="Busbar">บัสบาร์</option>
                                <option value="Ironwork">งานเหล็ก</option>
                                <option value="Model">แบบ</option>
                                <option value="Closingsheet">ใบเปิดงาน</option>
                            </select>
                        </div>
                        <div class="col-2">
                            <button type="button" class="btn btn-danger text-center" onclick="DelRowSelect(${RowSelect})"><i class="bi bi-trash-fill p-0 fs-4"></i></button>
                        </div>
                        <div class="col-md-12 mt-4 row">
                            <div class="col-md-2 mt-4 row align-items-center">
                                <div class="form-check col-md-1">
                                    <input class="form-check-input option-checkbox" type="checkbox" name="input_rio_${RowSelect}" value="increase" >
                                    <label class="fw-bold" for="flexCheckDefault">เพิ่ม</label>
                                </div>
                            </div>
                            <div class="col-md-2 mt-4 row align-items-center">
                                <div class="form-check col-md-1">
                                    <input class="form-check-input option-checkbox" type="checkbox" name="input_rio_${RowSelect}" value="reduce" >
                                    <label class="fw-bold" for="flexCheckDefault">ลด</label>
                                </div>
                            </div>
                            <div class="col-md-2 mt-4 row align-items-center">
                                <div class="form-check col-md-1">
                                    <input class="form-check-input option-checkbox" type="checkbox" name="input_rio_${RowSelect}" value="other" >
                                    <label class="fw-bold" for="flexCheckDefault">อื่นๆ</label>
                                </div>
                            </div>
                            <div class="col-md-12" id="input_rio_other_${RowSelect}">
                                <input type="text" class="form-control mt-2 option-input" style="display:none;" id="rio_other_${RowSelect}" placeholder="อื่นๆ" />
                            </div>
                        </div>
                        <div class="col-md-12 mt-4">
                            <label for="servicename" class="form-label text-primary required">สาเหตุ</label>
                        </div>
                        <div class="col-md-12">
                            <div class="row justify-content-start">
                                ${html_list}
                                <div class="col-md-2 mt-4" style="width: 20%;">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="chk_${RowSelect}" value="" data-id="chk_other_${RowSelect}">
                                        <label class="fw-bold" for="flexCheckDefault">
                                            อื่นๆ
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-4">
                            <div class="col-md-12 mt-4">
                                <div class="input-group mb-3 chk_other" id="chk_other_${RowSelect}">
                                    <span class="input-group-text">อื่นๆ</span>
                                    <input type="text" class="form-control" id="input_chk_other_${RowSelect}">
                                </div>
                            </div>

                            <div class="col-md-3 mt-4">
                                <label for="servicename" class="form-label text-primary required chk_custom_${RowSelect}">เลือนเป็นวันที่</label>
                                <input type="text" class="form-control datepicker chk_custom_${RowSelect}" id="input_chk_custom_${RowSelect}" autocomplete="off" data-provide="datepicker" data-date-language="th-th">
                            </div>

                            <div class="col-md-3 mt-4" style="display:none;">
                                <label for="servicename" class="form-label text-primary required chk_custom_${RowSelect}">กำหนดเสร็จ</label>
                                <input type="text" class="form-control datepicker chk_custom_${RowSelect}" id="input_chk_customlast_${RowSelect}" autocomplete="off" data-provide="datepicker" data-date-language="th-th">
                            </div>

                            <div class="col-md-12 mt-4">
                                <div class="input-group mb-3 chk_custom_${RowSelect}">
                                    <span class="input-group-text">เนื่องจาก</span>
                                    <textarea class="form-control" id="textarea_chk_custom_${RowSelect}"></textarea>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
                `;

                $("#Card_Drop").append(htmlSelect);
                initDatepicker();

                $(".input_rio").hide();
                $(".chk_other").hide();
                $(`.chk_custom_${RowSelect}`).hide();

                $('.Problem').select2({
                    placeholder: "กรุณาเลือกรายการ",
                    allowClear: true
                });

                RowSelect++;
            });

            function initDatepicker() {
                $('.datepicker').datepicker({
                    format: 'dd/mm/yyyy',
                    todayBtn: true,
                    language: 'th',
                    thaiyear: true,
                    autoclose: true
                });
            }

            initDatepicker();

            $("#jobtype").change(function() {
                for (var i = 0; i <= RowSelect; i++) {
                    $("#DetailSelect-" + i).remove();
                }
            });

            $(".Problem").change(function() {
                var selectId = $(this).attr('id').split('-')[1];
                console.log(selectId);
            });

            $(document).on("change", ".option-checkbox", function() {
                const $this = $(this);
                const rowId = $this.attr('name').split('_')[2];

                if ($this.val() === "other" && $this.is(":checked")) {
                    $(`#rio_other_${rowId}`).show();
                } else if ($this.val() === "other") {
                    $(`#rio_other_${rowId}`).hide();
                    $(`#rio_other_${rowId}`).val('');
                }
            });

            $('#Modal-Select').on('change', 'input[type="radio"]', function() {
                var name = $(this).attr('name');
                var rowSelectId = name.split('_')[2];
                var inputOtherId = '#input_rio_other_' + rowSelectId;

                if ($(this).val() == '') {
                    $(inputOtherId).show();
                } else {
                    $(inputOtherId).hide();
                }
            });

            $('#Card_Drop').on('change', 'input[type="checkbox"]', function() {
                var name = $(this).attr('name');
                var rowSelectId = $(this).attr('data-id').split('_');
                var inputOtherId = '#' + $(this).attr('data-id');

                console.log(rowSelectId);

                var hasEmptyChecked = $('input[name="' + name + '"][value=""]:checked').length > 0;

                if (hasEmptyChecked) {
                    $(inputOtherId).show();
                } else {
                    $(inputOtherId).hide();
                }

                if (rowSelectId[0] == '18') {
                    var inputCustomId = '.chk_custom_' + rowSelectId[1];

                    if ($(this).is(':checked')) {
                        $(inputCustomId).show();
                    } else {
                        $(inputCustomId).hide();
                    }
                }
            });

            $('input[name="conclude"]').on('change', function() {
                const selectedValue = $(this).val();

                if ($(this).is(':checked')) {
                    if (selectedValue === 'nocharge') {
                        $('#inasmuch_input').show();
                    } else if (selectedValue === 'newwork') {
                        $('#doc_newwork_input').show();
                    } else if (selectedValue === 'charge') {
                        $('#doc_charge_input').show();
                        $('#doc_newwork_input').show();
                        $('input[name="conclude"][value="newwork"]').prop('checked', true);
                    } else if (selectedValue === 'divitext_other') {
                        $('#divitext_input').show();
                    } else if (selectedValue === 'product') {
                        $('#product_input').show();
                    }
                } else {
                    if (selectedValue === 'nocharge') {
                        $('#inasmuch_input').hide();
                        $('#inasmuch').val('');
                    } else if (selectedValue === 'newwork') {
                        $('#doc_newwork_input').hide();
                        $('#doc_newwork').val('');
                    } else if (selectedValue === 'charge') {
                        $('#doc_charge_input').hide();
                        $('#doc_newwork_input').hide();
                        $('#doc_charge').val('');
                        $('#doc_newwork').val('');
                        $('input[name="conclude"][value="newwork"]').prop('checked', false);
                    } else if (selectedValue === 'divitext_other') {
                        $('#divitext_input').hide();
                        $('#divitext_other').val('');
                    } else if (selectedValue === 'product') {
                        $('#product_input').hide();
                        $('#product_other').val('');
                    }
                }
            });

            $('.selectable').change(function() {
                var selectedOptions = $(this).val();

                if (selectedOptions && selectedOptions.includes('All')) {
                    $(this).find('option[value="All"]').prop('selected', false);

                    $(this).find('option').each(function() {
                        if ($(this).val() !== 'All') {
                            $(this).prop('selected', true);
                        }
                    });

                    $(this).trigger('change');
                }
            });

            $('#searchjob').on('click', function() {
                var jobno = $('#jobno').val();
                // console.log(jobno);

                var searchJob = $.ajax({
                    url: 'searchjob',
                    type: 'POST',
                    data: {
                        jobno: jobno,
                        action: 'searchjob'
                    }
                });
                var search = $.ajax({
                    url: 'searchjob',
                    type: 'POST',
                    data: {
                        jobno: jobno,
                        action: 'search'
                    }
                });
                var searchSn = $.ajax({
                    url: 'searchjob',
                    type: 'POST',
                    data: {
                        jobno: jobno,
                        action: 'searchsn'
                    }
                });

                $.when(searchJob, search, searchSn).done(function(responseJob, responseSearch, responseSn) {
                    try {
                        var dataJob = JSON.parse(responseJob[0]);
                        if (dataJob.error) {
                            console.log('Error:', dataJob.error);
                            $('#projects').val('');
                            $('#jobcus').val('');
                            $('#jobsale').val('');
                            $('#cost').val('');
                        } else {
                            $('#projects').val(dataJob.JobName);
                            $('#jobcus').val(dataJob.CustomerName);
                            $('#jobsale').val(dataJob.SaleName);
                            $('#cost').val(dataJob.Cost);
                        }
                    } catch (e) {
                        console.log('JSON parsing error:', e);
                    }

                    try {
                        var dataSearch = JSON.parse(responseSearch[0]);
                        if (dataSearch.error) {
                            console.log('Error:', dataSearch.error);
                        } else {
                            $('#WA').empty();
                            if (Array.isArray(dataSearch.Doc_No)) {
                                $('#WA').append(new Option('เลือกเลข WA ทั้งหมด', 'All'));
                                dataSearch.Doc_No.forEach(function(item) {
                                    $('#WA').append(new Option(item, item));
                                });
                            }
                            $('#WA').append(new Option('-', '-'));
                            $('#WA').val(null);
                            $('#WA').select2({
                                placeholder: 'กรุณาเลือก WA',
                                allowClear: true
                            });

                            $('#tc_name').empty();
                            if (Array.isArray(dataSearch.tc_name)) {
                                dataSearch.tc_name.forEach(function(item) {
                                    $('#tc_name').append(new Option(item, item));
                                })
                            }
                            $('#tc_name').append(new Option('-', '-'));
                            $('#tc_name').val(null);
                            $('#tc_name').select2({
                                placeholder: 'กรุณาเลือกชื่อ TC',
                                allowClear: true
                            });
                        }
                    } catch (e) {
                        console.log('JSON parsing error:', e);
                    }

                    // console.log(responseSn);
                    $('#SN').empty();
                    $('#SN').append(responseSn[0]);
                    $('#SN').select2({
                        placeholder: 'กรุณาเลือก S/N',
                        allowClear: true
                    });

                }).fail(function(xhr, status, error) {
                    console.log('Error:', error);
                });
            });

            $("#sendMail").on("click", function() {
                var cr_no = '<?php echo $data_arr['CR_no'] ?>';

                Swal.fire({
                    title: "คุณแน่ใจใช่หรือไม่?",
                    text: "ท่านได้ทำการส่งอีเมลไปยังผู้ที่เกี่ยวข้องแล้ว",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "ใช่",
                    cancelButtonText: "ไม่"
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: 'https://innovation.asefa.co.th/ChangeRequestForm/send_mail',
                            type: 'POST',
                            data: {
                                cr_no: cr_no
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
                            success: function(response) {
                                Swal.close();
                                if (response.status == true) {
                                    Swal.fire(
                                        'บันทึกข้อมูลสำเร็จ',
                                        '',
                                        'success'
                                    ).then(function() {
                                        location.reload();
                                    });
                                } else {
                                    Swal.fire('Error', 'เกิดข้อผิดพลาดในการบัรทึกข้อมูล', 'error');
                                    console.log(response);
                                }

                            },
                            error: function(xhr, status, error) {
                                console.log(error);
                                Swal.close();
                                Swal.fire('Error', 'ไม่สามารถบันทึกข้อมูลได้', 'error');
                            }
                        });
                    }
                })
            });

        });

        function OpenModal(id) {
            var Problem = $(`#Problem-${id} option:selected`).text();
            if (Problem == '') {
                Swal.fire(
                    'กรุณาเลือกประเภท',
                    '',
                    'warning'
                )
                return false;
            }

            $('#DetailSelect-' + id).modal({
                backdrop: true,
                keyboard: true
            });

            $("#DetailSelect-" + id).modal('show');
            $("#DetailSelect-Label-" + id).text(Problem);
        }

        function DelRowSelect(id) {
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
                    $("#DetailSelect-" + id).remove();
                }
            });
        }

        function DelFile(id, filename) {
            // console.log(id);

            Swal.fire({
                title: "คุณแน่ใจใช่หรือไม่?",
                text: "คุณต้องการลบไฟล์นี้ใช่หรือไม่",
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
                        url: './delfile',
                        type: 'POST',
                        data: {
                            file_id: id,
                            filename: filename
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
                        success: function(response) {
                            // console.log(response);
                            var data = JSON.parse(response);
                            Swal.close();
                            if (data.status == true) {
                                Swal.fire(
                                    'ลบไฟล์สำเร็จ',
                                    '',
                                    'success'
                                ).then(function() {
                                    location.reload();
                                });
                            } else {
                                Swal.fire('Error', 'เกิดข้อผิดพลาดในการบันทึกข้อมูล', 'error');
                            }

                        },
                        error: function(xhr, status, error) {
                            console.log(error);
                            Swal.close();
                            Swal.fire('Error', 'ไม่สามารถลบไฟล์ได้', 'error');
                        }
                    });
                }
            });
        }

        function openEditApprover(field, jsonKey, currentEmp) {
            $('#editApprover_field').val(field);
            $('#editApprover_jsonKey').val(jsonKey);
            $('#editApprover_select').val(currentEmp);

            if (!$('#editApprover_select').hasClass('select2-hidden-accessible')) {
                $('#editApprover_select').select2({
                    dropdownParent: $('#editApproverModal'),
                    width: '100%',
                    placeholder: "กรุณาเลือกผู้อนุมัติ"
                });
            }
            $('#editApprover_select').trigger('change');
            $('#editApproverModal').modal('show');
        }

        function saveNewApprover() {
            var cr_no = $('#editApprover_CR_no').val();
            var field = $('#editApprover_field').val();
            var jsonKey = $('#editApprover_jsonKey').val();
            var newEmp = $('#editApprover_select').val();

            if (!newEmp) {
                Swal.fire('คำเตือน', 'กรุณาเลือกผู้อนุมัติคนใหม่', 'warning');
                return false;
            }

            Swal.fire({
                title: 'กำลังบันทึกข้อมูล...',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            $.ajax({
                url: './ChangeApprover',
                type: 'POST',
                data: {
                    CR_no: cr_no,
                    field: field,
                    jsonKey: jsonKey,
                    newEmp: newEmp
                },
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        Swal.fire('บันทึกสำเร็จ', 'เปลี่ยนผู้อนุมัติเรียบร้อยแล้ว', 'success').then(() => {
                            location.reload();
                        });
                    } else {
                        Swal.fire('Error', response.error || 'เกิดข้อผิดพลาดในการบันทึกข้อมูล', 'error');
                    }
                },
                error: function() {
                    Swal.fire('Error', 'ไม่สามารถเชื่อมต่อเซิร์ฟเวอร์ได้', 'error');
                }
            });
        }

        function Accepting(status, cr_no, doc_type, cr_approve = '') {
            var rework_note = $('#rework_note').val();
            var cancel_note = $('#cancel_note').val();

            var mess = '';

            var approveSelect_1 = $('#approveSelect_1').val();
            var approveSelect_2 = $('#approveSelect_2').val();
            var approveSelect_3 = $('#approveSelect_3').val();
            var approveSelect_4 = $('#approveSelect_4').val();
            var approveSelect_5 = $('#approveSelect_5').val();

            // console.log(status, cr_approve);
            if (status == 'Draf' && cr_approve == '') {
                mess = 'คุณต้องการรับงานนี้ใช่หรือไม่';
            } else if (status == 'Rework' && cr_approve == '') {
                if (rework_note == '') {
                    Swal.fire(
                        'กรุณากรอกรายละเอียดการ Rework',
                        '',
                        'warning'
                    )

                    return false;
                }
                mess = 'คุณต้องการ Rework งานนี้ใช่หรือไม่';
            } else if (status == 'Review' && cr_approve == '0') {
                mess = 'คุณต้องการส่งตรวจสอบงานนี้ใช่หรือไม่';

                if (
                    (approveSelect_1 !== '' && approveSelect_2 === '') ||
                    (approveSelect_1 === '' && approveSelect_2 !== '') ||
                    (approveSelect_3 !== '' && approveSelect_4 === '') ||
                    (approveSelect_3 === '' && approveSelect_4 !== '')
                ) {
                    Swal.fire(
                        'กรุณาเลือกผู้ตรวจสอบและผู้อนุมัติให้ครบคู่',
                        '',
                        'warning'
                    );
                    return false;
                }

                if (
                    approveSelect_1 === '' &&
                    approveSelect_2 === '' &&
                    approveSelect_3 === '' &&
                    approveSelect_4 === ''
                ) {
                    Swal.fire(
                        'กรุณาเลือกผู้ตรวจสอบและผู้อนุมัติอย่างน้อยหนึ่งคู่',
                        '',
                        'warning'
                    );
                    return false;
                }

                if (approveSelect_1 == '' && approveSelect_2 == '') {
                    status = 'Recheck';
                }
            } else if (status == 'Review' && cr_approve == '1') {
                mess = 'คุณต้องการ Approve งานนี้ใช่หรือไม่';
            } else if (status == 'Check' || status == 'Recheck') {
                mess = 'คุณต้องการ Acknowledge งานนี้ใช่หรือไม่';
            } else if (status == 'Approve' || status == 'Close') {
                mess = 'คุณต้องการ Approve งานนี้ใช่หรือไม่';
            } else if (status == 'Not Approve') {
                mess = 'คุณไม่ต้องการ Approve งานนี้ใช่หรือไม่';
            } else if (status == 'Cancel') {
                if (cancel_note == '') {
                    Swal.fire(
                        'กรุณากรอกรายละเอียดการ Cancel',
                        '',
                        'warning'
                    )

                    return false;
                }

                mess = 'คุณต้องการ Cancel งานนี้ใช่หรือไม่';
            }

            // console.log(approveSelect_1, approveSelect_2, approveSelect_3, approveSelect_4, approveSelect_5);

            Swal.fire({
                title: "คุณแน่ใจใช่หรือไม่?",
                text: mess,
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "ใช่",
                cancelButtonText: "ไม่"
            }).then((result) => {
                if (result.isConfirmed) {
                    // console.log(status);

                    $.ajax({
                        url: './changestatus',
                        type: 'POST',
                        data: {
                            cr_no: cr_no,
                            status: status,
                            doc_type: doc_type,
                            rework_note: rework_note,
                            cancel_note: cancel_note,
                            cr_approve: cr_approve,
                            approveSelect_1: approveSelect_1,
                            approveSelect_2: approveSelect_2,
                            approveSelect_3: approveSelect_3,
                            approveSelect_4: approveSelect_4,
                            approveSelect_5: approveSelect_5
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
                        success: function(response) {
                            console.log(response);
                            Swal.close();
                            var data = JSON.parse(response);
                            if (data.status == true && data.status_cr == 'Draf' && data.cr_approve == '') {
                                Swal.fire(
                                    'คุณรับงานนี้เรียบร้อยแล้ว',
                                    '',
                                    'success'
                                ).then(function() {
                                    location.reload();
                                });
                            } else if (data.status == true && data.status_cr == 'Review' && data.cr_approve == '0') {
                                Swal.fire(
                                    'คุณส่งตรวจสอบงานนี้เรียบร้อยแล้ว',
                                    '',
                                    'success'
                                ).then(function() {
                                    location.reload();
                                });
                            } else if (data.status == true && data.status_cr == 'Review' && data.cr_approve == '1') {
                                Swal.fire(
                                    'คุณ Approve งานนี้เรียบร้อยแล้ว',
                                    '',
                                    'success'
                                ).then(function() {
                                    location.reload();
                                });
                            } else if (data.status == true && data.status_cr == 'Rework' && data.cr_approve == '') {
                                Swal.fire(
                                    'คุณ Rework งานนี้เรียบร้อยแล้ว',
                                    '',
                                    'success'
                                ).then(function() {
                                    location.reload();
                                });
                            } else if (data.status == true && data.status_cr == 'Check' || data.status_cr == 'Recheck') {
                                Swal.fire(
                                    'คุณตรวจสอบงานนี้เรียบร้อยแล้ว',
                                    '',
                                    'success'
                                ).then(function() {
                                    location.reload();
                                });
                            } else if (data.status == true && data.status_cr == 'Approve' || data.status_cr == 'Close') {
                                Swal.fire(
                                    'คุณ Approve งานนี้เรียบร้อยแล้ว',
                                    '',
                                    'success'
                                ).then(function() {
                                    location.reload();
                                });
                            } else if (data.status == true && data.status_cr == 'Not Approve') {
                                Swal.fire(
                                    'คุณไม่ Approve งานนี้เรียบร้อยแล้ว',
                                    '',
                                    'success'
                                ).then(function() {
                                    location.reload();
                                });
                            } else if (data.status == true && data.status_cr == 'Cancel') {
                                Swal.fire(
                                    'คุณ Cancel งานนี้เรียบร้อยแล้ว',
                                    '',
                                    'success'
                                ).then(function() {
                                    location.reload();
                                });
                            } else {
                                Swal.fire('Error', 'เกิดข้อผิดพลาด กรุณาติดต่อผู้ดูแล', 'error');
                            }

                        },
                        error: function(xhr, status, error) {
                            console.log(error);
                            Swal.close();
                            Swal.fire('Error', 'กรุณาติดต่อผู้ดูแล', 'error');
                        }
                    });
                }
            });
        }

        function EditData(status, cr_no, cr_approve = '') {
            // var RowSelect = <?php echo count($data_detail) + 1 ?>;
            var DataE = '<?php echo $_SESSION['DataE']; ?>';
            var user = '<?php echo $_SESSION['ChangeRequest_code']; ?>';
            var jobtype = $("#jobtype").val();
            var chi_cho_no = $("#chi_cho_no").val() == '' || $("#chi_cho_no").val() == undefined ? '' : $("#chi_cho_no").val();
            var jobno = $("#jobno").val();
            var wa = $("#WA").val();
            var sn = $("#SN").val();
            var projects = $("#projects").val();
            var jobsale = $("#jobsale").val();
            var jobcus = $("#jobcus").val();
            var rev = $("#rev").val();
            var ncr_no = $("#ncr_no").val();
            var details = $("#details").val();
            var tc_name = $("#tc_name").val();
            var product_other = $("#product_other").val();
            var urgentwork = $("#urgentwork").is(":checked") ? $("#urgentwork").val() : "0";

            var approveSelect_1 = $('#approveSelect_1').val();
            var approveSelect_2 = $('#approveSelect_2').val();
            var approveSelect_3 = $('#approveSelect_3').val();
            var approveSelect_4 = $('#approveSelect_4').val();
            var approveSelect_5 = $('#approveSelect_5').val();

            var selectedProducts = [];
            $('input[name="product_name"]:checked').each(function() {
                selectedProducts.push($(this).val());
            });
            selectedProducts.push(product_other);

            var statusjob = [];
            $('input[name="work_status"]:checked').each(function() {
                statusjob.push($(this).val());
            });

            var statusproduct = [];
            $('input[name="product_status"]:checked').each(function() {
                statusproduct.push($(this).val());
            });

            var JsonData = [];

            for (var i = 0; i < window.RowSelect; i++) {
                var Problem = $("#Problem-" + i).val();

                if (Problem == '') {
                    Swal.fire(
                        'กรุณาเลือกประเภท',
                        '',
                        'warning'
                    )
                    return false;
                }

                if (Problem != '' && Problem != undefined) {
                    // var inputRioValue = [];
                    var inputValue = {
                        increase: [],
                        reduce: [],
                        other: [],
                        postponed: []
                    };
                    var inputChk = [];
                    $('input[name="input_rio_' + i + '"]:checked').each(function() {
                        var value = $(this).val();
                        if (value == "other") {
                            return false;
                        }
                        inputValue[value].push(value);
                    });
                    // inputValue['increase'].push($(`#rio_increase_` + i).val());
                    // inputValue['reduce'].push($(`#rio_reduce_` + i).val());
                    inputValue['other'].push($(`#rio_other_` + i).val());

                    $(`input[name="chk_${i}"]:checked`).each(function() {
                        if ($(this).val() == '') {
                            inputChk.push($("#input_chk_other_" + i).val());
                        } else if ($(this).val() == '18') {
                            inputValue['postponed'].push($(`#input_chk_custom_${i}`).val());
                            inputValue['postponed'].push($(`#input_chk_customlast_${i}`).val());
                            inputValue['postponed'].push($(`#textarea_chk_custom_${i}`).val());
                            inputChk.push($(this).val());
                        } else {
                            inputChk.push($(this).val());
                        }
                    });

                    // console.log(inputValue, inputChk);

                    if (inputValue == '' || inputChk.length === 0 || inputChk.some(item => item === '')) {
                        Swal.fire(
                            'กรุณากรอกรายละเอียดให้ครบถ้วน',
                            '',
                            'warning'
                        )
                        return false;
                    }

                    JsonData.push({
                        problem: Problem,
                        // inputRio: inputRioValue,
                        inputValue: inputValue,
                        inputChk: inputChk
                    });

                }
            }

            var files = $("#file_upload")[0].files;
            var cost = $("#cost").val();
            var effect = $("#effect").val();
            var expenses = $("#expenses").val();
            var expenses_total = $("#expenses_total").val();

            // console.log(JsonData);

            if (status == 'Review') {
                if (
                    ((approveSelect_1 !== '' && approveSelect_1 !== '-') && (approveSelect_2 === '' || approveSelect_2 === '-')) ||
                    ((approveSelect_1 === '' && approveSelect_1 === '-') && (approveSelect_2 !== '' || approveSelect_2 !== '-')) ||
                    ((approveSelect_3 !== '' && approveSelect_3 !== '-') && (approveSelect_4 === '' || approveSelect_4 === '-')) ||
                    ((approveSelect_3 === '' && approveSelect_3 === '-') && (approveSelect_4 !== '' || approveSelect_4 !== '-'))
                ) {
                    Swal.fire(
                        'กรุณาเลือกผู้ตรวจสอบและผู้อนุมัติให้ครบคู่',
                        '',
                        'warning'
                    );
                    return false;
                }

                if (
                    (approveSelect_1 === '' || approveSelect_1 == '-') &&
                    (approveSelect_2 === '' || approveSelect_2 == '-') &&
                    (approveSelect_3 === '' || approveSelect_3 == '-') &&
                    (approveSelect_4 === '' || approveSelect_4 == '-')
                ) {
                    Swal.fire(
                        'กรุณาเลือกผู้ตรวจสอบและผู้อนุมัติอย่างน้อยหนึ่งคู่',
                        '',
                        'warning'
                    );
                    return false;
                }

                if ((approveSelect_1 == '' || approveSelect_1 == '-') && (approveSelect_2 == '' || approveSelect_2 == '-')) {
                    status = 'Recheck';
                }
            }

            if (jobtype == '') {
                Swal.fire(
                    'กรุณาเลือกประเภทเอกสาร',
                    '',
                    'warning'
                )
                return false;
            }

            if (jobno == '') {
                Swal.fire(
                    'กรุณาเลือก Job No',
                    '',
                    'warning'
                )
                return false;
            }

            if (wa == '') {
                Swal.fire(
                    'กรุณาเลือก W/A',
                    '',
                    'warning'
                )
                return false;
            }

            if (sn == '') {
                Swal.fire(
                    'กรุณาเลือก S/N',
                    '',
                    'warning'
                )
                return false;
            }

            if (tc_name == '') {
                Swal.fire(
                    'กรุณาเลือก TC Name',
                    '',
                    'warning'
                )
                return false;
            }

            if (rev == '') {
                Swal.fire(
                    'กรุณากรอก Rev',
                    '',
                    'warning'
                )
                return false;
            }

            if (ncr_no == '') {
                Swal.fire(
                    'กรุณากรอก NCR No',
                    '',
                    'warning'
                )
                return false;
            }

            if (details == '') {
                Swal.fire(
                    'กรุณากรอกรายละเอียด',
                    '',
                    'warning'
                )
                return false;
            }

            if (selectedProducts == '') {
                Swal.fire(
                    'กรุณาเลือก Product อย่างน้อย 1 อย่าง',
                    '',
                    'warning'
                )
                return false;
            }

            if (statusjob == '') {
                Swal.fire(
                    'กรุณาเลือกสถานะงานอย่างน้อย 1 อย่าง',
                    '',
                    'warning'
                )
                return false;
            }

            if (statusproduct == '') {
                Swal.fire(
                    'กรุณาเลือกสถานะผลิตภัณฑ์อย่างน้อย 1 อย่าง',
                    '',
                    'warning'
                )
                return false;
            }

            if (JsonData.length == 0) {
                Swal.fire(
                    'กรุณาเพิ่มรายการอย่างน้อย 1 รายการ',
                    '',
                    'warning'
                )
                return false;
            }

            if (cost == '') {
                Swal.fire(
                    'กรุณากรอกมูลค่า',
                    '',
                    'warning'
                )
                return false;
            }

            if (effect == '') {
                Swal.fire(
                    'กรุณากรอกผลกระทบที่เกิดจากการเปลี่ยนแปลง',
                    '',
                    'warning'
                )
                return false;
            }

            if (expenses == '') {
                Swal.fire(
                    'กรุณากรอกค่าใช้จ่ายในครั้งนี้',
                    '',
                    'warning'
                )
                return false;
            }

            if (expenses_total == '') {
                Swal.fire(
                    'กรุณากรอกค่าใช้จ่ายสะสมรวม',
                    '',
                    'warning'
                )
                return false;
            }

            var concludeValue = $('input[name="conclude"]:checked').val();

            // var divitext = $("#divitext").val();
            var diviselect = [];
            var divitext_other = $("#divitext_other").val();
            $('input[name="Divi_Select"]:checked').each(function() {
                diviselect.push($(this).val());
            });
            var diviData = {
                divitext: diviselect,
                divitext_other: divitext_other
            }

            if (diviselect == '' || diviselect == '[]') {
                Swal.fire(
                    'กรุณาเลือกผู้เกี่ยวข้องดำเนินการอย่าง 1 รายการ',
                    '',
                    'warning'
                )
                return false;
            }

            if (jobtype == "CHO") {
                var chargeAmount = $("#doc_charge").val();
                var doc_newwork = $("#doc_newwork").val();
                var inasmuch = $("#inasmuch").val();
                var sellData = {
                    doc_charge: chargeAmount,
                    doc_newwork: doc_newwork,
                    inasmuch: inasmuch
                };

                if (chargeAmount == '' && doc_newwork == '' && inasmuch == '') {
                    Swal.fire(
                        'กรุณาเลือกข้อมูลสรุปค่าใช้จ่ายที่เกิดขึ้น',
                        '',
                        'warning'
                    )
                    return false;
                }

                if (sellData['doc_charge'] == '' && sellData['doc_newwork'] == '' && sellData['inasmuch'] == '') {
                    Swal.fire(
                        'กรุณากรอกข้อมูลสรุปค่าใช้จ่ายที่เกิดขึ้น',
                        '',
                        'warning'
                    )
                    return false;
                }

                // if (diviselect == '') {
                //     Swal.fire(
                //         'กรุณาเลือกผู้เกี่ยวข้องดำเนินการอย่าง 1 รายการ',
                //         '',
                //         'warning'
                //     )
                //     return false;
                // }
            }

            $('.datepicker').each(function() {
                let val = $(this).val();
                if (val && val.includes('/')) {
                    const [day, month, year_th] = val.split('/');
                    const year_en = parseInt(year_th) - 543;
                    $(this).val(`${day}/${month}/${year_en}`);
                }
            });

            var formData = new FormData();
            formData.append('user', user);
            formData.append('cr_no', cr_no);
            formData.append('chi_cho_no', chi_cho_no);
            formData.append('status', status);
            formData.append('jobtype', jobtype);
            formData.append('jobno', jobno);
            formData.append('wa', wa);
            formData.append('sn', sn);
            formData.append('projects', projects);
            formData.append('jobsale', jobsale);
            formData.append('jobcus', jobcus);
            formData.append('rev', rev);
            formData.append('ncr_no', ncr_no);
            formData.append('details', details);
            formData.append('tc_name', JSON.stringify(tc_name));
            formData.append('selectedProducts', JSON.stringify(selectedProducts));
            formData.append('statusjob', JSON.stringify(statusjob));
            formData.append('statusproduct', JSON.stringify(statusproduct));
            formData.append('JsonData', JSON.stringify(JsonData));
            formData.append('cost', cost);
            formData.append('effect', effect);
            formData.append('expenses', expenses);
            formData.append('expenses_total', expenses_total);
            formData.append('approveSelect_1', approveSelect_1);
            formData.append('approveSelect_2', approveSelect_2);
            formData.append('approveSelect_3', approveSelect_3);
            formData.append('approveSelect_4', approveSelect_4);
            formData.append('approveSelect_5', approveSelect_5);
            formData.append('cr_approve', cr_approve);
            formData.append('sellData', JSON.stringify(sellData) ?? '');
            formData.append('diviselect', JSON.stringify(diviData) ?? '');
            formData.append('urgentwork', urgentwork ?? '0');

            // ตรวจสอบไฟล์ก่อนส่ง (Client-side validation)
            if (files.length > 0) {
                var fileValidation = validateFileUpload(files);
                if (!fileValidation.valid) {
                    Swal.fire('ไฟล์ไม่อนุญาต', fileValidation.error, 'error');
                    return false;
                }
            }

            for (var i = 0; i < files.length; i++) {
                formData.append('file_upload[]', files[i]);
            }

            // console.log(approveSelect_1, approveSelect_2, approveSelect_3, approveSelect_4, approveSelect_5);

            Swal.fire({
                title: "คุณแน่ใจใช่หรือไม่?",
                text: "คุณต้องการบันทึกข้อมูลนี้ใช่หรือไม่",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "ใช่",
                cancelButtonText: "ไม่"
            }).then((result) => {
                if (result.isConfirmed) {
                    // console.log(status);
                    $.ajax({
                        url: './editdata',
                        type: 'POST',
                        data: formData,
                        contentType: false,
                        cache: false,
                        processData: false,
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
                        success: function(response) {
                            console.log(response);
                            Swal.close();
                            var data = JSON.parse(response);
                            Swal.close();
                            if (data.status == true && (data.status_cr == 'New')) {
                                Swal.fire(
                                    'แจ้งงานเรียบร้อย',
                                    '',
                                    'success'
                                ).then(function() {
                                    window.location.href = './ViewForm?DataE=' + DataE + "&CR_no=" + cr_no;
                                });
                            } else if (data.status == true && (data.status_cr == 'Savedraft' || data.status_cr == 'Draf' || data.status_cr == 'Rework' || data.status_cr == 'Approve' || data.status_cr == 'Close')) {
                                Swal.fire(
                                    'บันทึกแก้ไขข้อมูลเรียบร้อย',
                                    '',
                                    'success'
                                ).then(function() {
                                    window.location.href = './ViewForm?DataE=' + DataE + "&CR_no=" + cr_no;
                                });
                            } else if (data.status == true && (data.status_cr == 'Review' || data.status_cr == 'Recheck')) {
                                Swal.fire(
                                    'ส่งตรวจสอบเรียบร้อย',
                                    '',
                                    'success'
                                ).then(function() {
                                    window.location.href = './?DataE=' + DataE;
                                });
                            } else {
                                Swal.fire('Error', 'เกิดข้อผิดพลาดในการบันทึกข้อมูล', 'error');
                            }
                        },
                        error: function(xhr, status, error) {
                            console.log(error);
                            Swal.close();
                            Swal.fire('Error', 'ไม่สามารถบันทึกข้อมูลได้', 'error');
                        }
                    });
                }
            });
        }

        function tadjust_all() {
            $('textarea').each(function() {
                var thid = $(this).attr('id');
                if (thid) {
                    tadjust(thid);
                }
            });
        }

        function tadjust(thid) {
            if (thid.indexOf('Date') > -1) {
                a = $('#' + thid).prop('scrollHeight');
                //alert(thid+'='+a);
                if (a > 44) {
                    $('#' + thid).css('height', '');
                    a = $('#' + thid).prop('scrollHeight');
                    $('#' + thid).css('height', a + 'px');
                } else {
                    $('#' + thid).css('height', '27px');
                }
            } else {
                $('#' + thid).css('height', '');
                a = $('#' + thid).prop('scrollHeight');
                $('#' + thid).css('height', a + 'px');
            }
        }
        /**
         * ตรวจสอบไฟล์ที่เลือกก่อนอัปโหลด (Client-side)
         * ป้องกัน: dangerous extensions, double extensions, file size
         */
        function validateFileUpload(files) {
            var allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp', 'pdf', 'xlsx'];
            var dangerousExtensions = [
                'php', 'phtml', 'php3', 'php4', 'php5', 'php7', 'php8', 'phar',
                'exe', 'dll', 'so', 'sh', 'bat', 'cmd', 'com', 'msi',
                'js', 'jse', 'vbs', 'vbe', 'wsf', 'wsh',
                'jsp', 'jspx', 'asp', 'aspx', 'asa', 'asax',
                'cgi', 'pl', 'py', 'pyc', 'rb',
                'htaccess', 'htpasswd', 'ini', 'config'
            ];
            var maxFileSize = 50 * 1024 * 1024; // 50MB

            for (var i = 0; i < files.length; i++) {
                var fileName = files[i].name.toLowerCase();
                var fileSize = files[i].size;
                var parts = fileName.split('.');
                var ext = parts.length > 1 ? parts[parts.length - 1] : '';

                // ตรวจ double extension (เช่น shell.php.jpg)
                for (var j = 0; j < parts.length - 1; j++) {
                    if (dangerousExtensions.indexOf(parts[j]) !== -1) {
                        return {
                            valid: false,
                            error: 'ไฟล์ "' + files[i].name + '" มีนามสกุลอันตราย (.' + parts[j] + ')'
                        };
                    }
                }

                // ตรวจ extension สุดท้าย
                if (dangerousExtensions.indexOf(ext) !== -1) {
                    return {
                        valid: false,
                        error: 'ไฟล์ "' + files[i].name + '" มีนามสกุลที่ไม่อนุญาต (.' + ext + ')'
                    };
                }
                if (allowedExtensions.indexOf(ext) === -1) {
                    return {
                        valid: false,
                        error: 'ไฟล์ "' + files[i].name + '" มีนามสกุลที่ไม่อนุญาต (.' + ext + ') อนุญาตเฉพาะ: ' + allowedExtensions.join(', ')
                    };
                }

                // ตรวจขนาดไฟล์
                if (fileSize > maxFileSize) {
                    return {
                        valid: false,
                        error: 'ไฟล์ "' + files[i].name + '" มีขนาดใหญ่เกินไป (สูงสุด 50MB)'
                    };
                }
            }

            return {
                valid: true,
                error: ''
            };
        }
    </script>
</body>

</html>