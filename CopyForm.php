<?php
$auth = $_SESSION['ChangeRequest_code'];

$emplist = emplist();

$data_arr = List_ChangeFormByID($_GET['CR_no']);
$file_arr = List_Files($_GET['CR_no']);

$CauseList = CauseList();
$CauseList_key = array_keys($CauseList);
$WA_arr = Search_WA($data_arr['jobno']);
$Tc_name_arr = Search_TC($data_arr['jobno']);
$SN_arr = Search_SN($data_arr['jobno']);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php //$datatable = true; 
    ?>
    <?php //include_once 'config/base.php'; 
    ?>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker3.css">
    <?php include_once 'layout/meta.php' ?>
    <?php include_once 'layout/css.php' ?>
    <!-- <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" /> -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />
    <script src="https://jojosati.github.io/bootstrap-datepicker-thai/js/bootstrap-datepicker.js"></script>
    <script src="https://jojosati.github.io/bootstrap-datepicker-thai/js/bootstrap-datepicker-thai.js"></script>
    <script src="https://jojosati.github.io/bootstrap-datepicker-thai/js/locales/bootstrap-datepicker.th.js"></script>
    <title>หน้าแรก</title>

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
                                <div class="card mb-4 position-relative">
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
                                                    <select class="form-select" id="jobtype">
                                                        <option value=""></option>
                                                        <option value="CHI" <?php echo $data_arr['doc_type'] == 'CHI' ? 'selected' : '' ?>>CHI</option>
                                                        <option value="CHO" <?php echo $data_arr['doc_type'] == 'CHO' ? 'selected' : '' ?>>CHO</option>
                                                    </select>
                                                </div>
                                                <?php
                                                if ($data_arr['doc_status'] == 'Approve') {
                                                ?>
                                                    <div class="col-md-4 mt-4">
                                                        <label for="form-select" class="form-label text-primary required">เลขที่ CHO - CHI</label>
                                                        <input type="text" class="form-control" style="background-color: #fff7c3 !important;" value="<?php echo $data_arr['doc_no'] ?>" disabled>
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
                                                        <input type="text" class="form-control" id="jobno" name="jobno" placeholder="ระบุ Job No." value="<?php echo $data_arr['jobno'] ?>">
                                                        <button class="btn btn-primary" type="button" id="searchjob">
                                                            <i class="fa-solid fa-magnifying-glass"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                                <div class="col-md-4 mt-4">
                                                    <label for="servicename" class="form-label text-primary required">Project Name</label>
                                                    <input type="text" class="form-control text-start" id="projects" value="<?php echo $data_arr['project_name'] ?>">
                                                </div>
                                                <div class="col-md-4 mt-4">
                                                    <label for="servicename" class="form-label text-primary required">Sales Name</label>
                                                    <input type="text" class="form-control text-start" id="jobsale" value="<?php echo $data_arr['sales_name'] ?>">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="row py-3 justify-content-between">
                                                <div class="col-md-8 mt-4">
                                                    <label for="servicename" class="form-label text-primary required">Customer Name</label>
                                                    <input type="text" class="form-control text-start" id="jobcus" value="<?php echo $data_arr['customer_name'] ?>">
                                                </div>
                                                <div class="col-md-2 mt-4">
                                                    <label for="servicename" class="form-label text-primary required">Rev.</label>
                                                    <input type="text" class="form-control text-start" id="rev" value="<?php echo $data_arr['rev'] ?>">
                                                </div>
                                                <div class="col-md-2 mt-4">
                                                    <label for="servicename" class="form-label text-primary required">บันทึกความผิดพลาด/NCR No.</label>
                                                    <input type="text" class="form-control text-start" id="ncr_no" value="<?php echo $data_arr['ncr_no'] ?>">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="row py-3 justify-content-between">
                                                <div class="col-md-4 mt-4">
                                                    <label for="servicename" class="form-label text-primary required">W/A</label>
                                                    <!-- <input type="text" class="form-control text-start" > -->
                                                    <select class="form-select selectable" id="WA" name="WA[]" multiple>
                                                        <option value="All">เลือกเลข WA ทั้งหมด</option>
                                                        <?php
                                                        $selectedWAs = array_map('trim', explode(',', $data_arr['wa_no']));
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
                                                    <select class="form-select selectable" id="SN" name="SN[]" multiple>
                                                        <option value="All">เลือกเลข S/N ทั้งหมด</option>
                                                        <?php
                                                        $selectedSNs = array_map('trim', explode(',', $data_arr['sn_no']));
                                                        foreach ($SN_arr as $kyy => $vall) {
                                                            $selected_sn = in_array($vall['item'], $selectedSNs) ? "selected" : "";
                                                            echo '<option value="' . $vall['item'] . '" ' . $selected_sn . '>' . $vall['PartNo'] . '</option>';
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                                <div class="col-md-4 mt-4">
                                                    <label for="servicename" class="form-label text-primary required">TC Name</label>
                                                    <!-- <input type="text" class="form-control text-start" id="tc_name"> -->
                                                    <select class="form-select" name="tc_name[]" id="tc_name" multiple>
                                                        <?php
                                                        // foreach($emplist as $empno => $detail){
                                                        //     $selected = $data_arr['tc_name'] == $empno ? "selected" : "";
                                                        //     echo '<option value="'. $empno .'" '. $selected .'>'. $detail['FullName'] .'</option>';
                                                        // }
                                                        ?>
                                                        <?php
                                                        $tc_arr = json_decode($data_arr['tc_name'], true);
                                                        foreach ($Tc_name_arr as $key => $value) {

                                                            $selected = in_array($value, $tc_arr) ? "selected" : "";
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
                                                $product_arr = json_decode($data_arr['product'], true);
                                                foreach (ProductList() as $key => $value) {
                                                    $selected = in_array($key, $product_arr) ? "checked" : "";
                                                    $product_other = is_numeric(end($product_arr)) ? "" : $product_arr;
                                                ?>
                                                    <div class="col-4 col-md-2 mt-4">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox" name="product_name" value="<?php echo $key; ?>" <?php echo $selected; ?>>
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
                                                        <input class="form-check-input" type="checkbox" name="conclude" value="product" <?php echo end($product_other) != '' ? 'checked' : '' ?>>
                                                        <label class="fw-bold" for="flexCheckDefault">Other</label>
                                                    </div>
                                                </div>
                                                <div class="col-md-12 mt-4">
                                                    <div class="input-group" id="product_input" style="<?php echo end($product_other) != '' ? 'display:inline-flex' : 'display:none' ?>">
                                                        <span class="input-group-text">อื่นๆ</span>
                                                        <input type="text" class="form-control" id="product_other" value="<?php echo end($product_other); ?>">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12 mt-4">
                                            <label for="servicename" class="form-label text-primary required">สถานะงาน</label>
                                        </div>
                                        <div class="col-md-12">
                                            <?php
                                            $status_job_arr = json_decode($data_arr['status_job'], true);
                                            ?>
                                            <div class="row justify-content-start">
                                                <div class="col-4 col-md-2 mt-4">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" name="work_status" value="เปิดงาน/สั่งอุปกรณ์" <?php echo in_array('เปิดงาน/สั่งอุปกรณ์', $status_job_arr) ? "checked" : ""; ?>>
                                                        <label class="fw-bold" for="flexCheckDefault">
                                                            เปิดงาน/สั่งอุปกรณ์
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-4 col-md-2 mt-4">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" name="work_status" value="ผลิตชิ้นงานเหล็ก" <?php echo in_array('ผลิตชิ้นงานเหล็ก', $status_job_arr) ? "checked" : ""; ?>>
                                                        <label class="fw-bold" for="flexCheckDefault">
                                                            ผลิตชิ้นงานเหล็ก
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-4 col-md-2 mt-4">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" name="work_status" value="ติดตั้งเหล็ก" <?php echo in_array('ติดตั้งเหล็ก', $status_job_arr) ? "checked" : ""; ?>>
                                                        <label class="fw-bold" for="flexCheckDefault">
                                                            ติดตั้งเหล็ก
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-4 col-md-2 mt-4">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" name="work_status" value="ติดตั้งไฟฟ้า" <?php echo in_array('ติดตั้งไฟฟ้า', $status_job_arr) ? "checked" : ""; ?>>
                                                        <label class="fw-bold" for="flexCheckDefault">
                                                            ติดตั้งไฟฟ้า
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-4 col-md-2 mt-4">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" name="work_status" value="Finish Goods" <?php echo in_array('Finish Goods', $status_job_arr) ? "checked" : ""; ?>>
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
                                            $product_status_arr = json_decode($data_arr['status_product'], true);
                                            ?>
                                            <div class="row justify-content-start">
                                                <div class="col-4 col-md-2 mt-4">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" name="product_status" value="ตู้ในโรงงาน WIP" <?php echo in_array('ตู้ในโรงงาน WIP', $product_status_arr) ? "checked" : ""; ?>>
                                                        <label class="fw-bold" for="flexCheckDefault">
                                                            ตู้ในโรงงาน WIP
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-4 col-md-2 mt-4">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" name="product_status" value="ตู้ในโรงงาน FG" <?php echo in_array('ตู้ในโรงงาน FG', $product_status_arr) ? "checked" : ""; ?>>
                                                        <label class="fw-bold" for="flexCheckDefault">
                                                            ตู้ในโรงงาน FG
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-4 col-md-2 mt-4">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" name="product_status" value="ตู้นอกโรงงาน" <?php echo in_array('ตู้นอกโรงงาน', $product_status_arr) ? "checked" : ""; ?>>
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
                                                    <textarea class="form-control" id="details"><?php echo $data_arr['details']; ?></textarea>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12 col-md-12 row">
                                            <div class="col-12 col-md-6 mt-3">
                                                <input type="file" class="form-control" id="file_upload" name="file_upload[]" multiple accept="image/*,.pdf,.xlsx">
                                            </div>

                                            <div class="col-12 col-md-6 mt-3">
                                                <div class="input-group mb-3">
                                                    <span class="input-group-text">มูลค่า</span>
                                                    <input type="number" min="0.00" class="form-control" id="cost" value="<?php echo $data_arr['cost']; ?>">
                                                </div>
                                            </div>

                                        </div>
                                        <div class="col-12 col-md-12 d-flex flex-wrap">
                                            <div class="col-12 col-md-12 mb-3">
                                                <span class="fs-5 fw-bold">ไฟล์แนบ : </span>
                                            </div>
                                            <?php
                                            foreach ($file_arr as $fileid => $file) {
                                                $filename = explode('/', $file['path_file']);
                                            ?>
                                                <span class="badge bg-warning text-dark p-3 mx-2 my-2">
                                                    <a href="#" onclick="DelFile('<?php echo $fileid; ?>', '<?php echo $filename[2]; ?>')" <?php echo $data_arr['doc_status'] != 'Draf' && $data_arr['doc_status'] != 'Rework' ? 'style="display: none;"' : '' ?>>
                                                        <i class="bi bi-x-circle-fill fs-4 " style="color: red;"></i>
                                                    </a>
                                                    <a href="https://innovation.asefa.co.th/ChangeRequestForm/file/<?php echo $filename[2]; ?>" class="ms-2 fs-7 text-dark" target="_blank">
                                                        <?php echo $filename[2]; ?>
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
                                                    <textarea class="form-control" id="effect"><?php echo $data_arr['job_remark']; ?></textarea>
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
                                                    <input type="number" min="0.00" class="form-control text-start" id="expenses" value="<?php echo $data_arr['expenses']; ?>">
                                                </div>
                                                <div class="col-md-4 mt-4">
                                                    <label for="servicename" class="form-label text-primary required">ค่าใช้จ่ายสะสมรวม</label>
                                                    <input type="number" min="0.00" class="form-control text-start" id="expenses_total" value="<?php echo $data_arr['expenses_total']; ?>">
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
                                                            <input class="form-check-input" type="checkbox" name="Divi_Select" value="<?php echo $value['Division_ID'] ?>" <?php echo in_array($value['Division_ID'], $related_arr['divitext']) ? "checked" : ""; ?>>
                                                            <label class="fw-bold" for="flexCheckDefault">
                                                                <?php echo $value['Division_Name'] ?>
                                                            </label>
                                                        </div>
                                                    </div>
                                                <?php
                                                }
                                                ?>
                                                <!-- <div class="col-10 col-md-6 mt-4">
                                                    <select class="form-select" name="divitext[]" id="divitext" multiple >
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
                                                        <input class="form-check-input" type="checkbox" name="conclude" id="divi_other" value="divitext_other" <?php echo $related_arr['divitext_other'] != '' ? "checked" : ""; ?>>
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
                                                            <input class="form-check-input" type="checkbox" name="conclude" value="charge" <?php echo $expensesummary_arr['doc_charge'] != '' ? "checked" : ""; ?>>
                                                            <label class="fw-bold" for="flexCheckDefault">
                                                                คิดเงิน
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="col-4 col-md-2 mt-4">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox" name="conclude" value="newwork" <?php echo $expensesummary_arr['doc_newwork'] != '' ? "checked" : ""; ?>>
                                                            <label class="fw-bold" for="flexCheckDefault">
                                                                เปิดงานใหม่
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="col-4 col-md-2 mt-4">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox" name="conclude" value="nocharge" <?php echo $expensesummary_arr['inasmuch'] != '' ? "checked" : ""; ?>>
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
                                                            <input type="number" class="form-control" id="doc_charge" value="<?php echo $expensesummary_arr['doc_charge'] != '' ? $expensesummary_arr['doc_charge'] : ''; ?>">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 mt-4" id="doc_newwork_input" <?php echo $expensesummary_arr['doc_newwork'] != '' ? 'style="display:inline-flex"' : 'style="display:none"' ?>>
                                                        <div class="input-group">
                                                            <span class="input-group-text">(เปิดงานใหม่) เลขที่</span>
                                                            <input type="text" class="form-control" id="doc_newwork" value="<?php echo $expensesummary_arr['doc_newwork'] != '' ? $expensesummary_arr['doc_newwork'] : ''; ?>">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 mt-4">
                                                        <div class="input-group" id="inasmuch_input" <?php echo $expensesummary_arr['inasmuch'] != '' ? 'style="display:inline-flex"' : 'style="display:none"' ?>>
                                                            <span class="input-group-text">(ไม่คิดเงิน) เนื่องจาก</span>
                                                            <input type="text" class="form-control" id="inasmuch" value="<?php echo $expensesummary_arr['inasmuch'] != '' ? $expensesummary_arr['inasmuch'] : ''; ?>">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- <div class="col-md-12">
                                                <div class="row justify-content-between">
                                                    <div class="col-md-6">
                                                        <div class="input-group mt-3" id="inasmuch_input" <?php echo $expensesummary_arr['concludeValue'] == 'nocharge' ? 'style="display:block"' : 'style="display:none"' ?>>
                                                            <span class="input-group-text">เนื่องจาก</span>
                                                            <input type="text" class="form-control" id="inasmuch" value="<?php echo $expensesummary_arr['concludeValue'] == 'nocharge' ? $expensesummary_arr['conclude'] : ''; ?>" >
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
                                                        <textarea class="form-control" disabled><?php echo $data_arr['reject_note']; ?></textarea>
                                                    </div>
                                                <?php
                                                }
                                                ?>
                                            </div>
                                        </div>
                                        <div class="col-12 col-md-12">
                                            <div class="row py-3 justify-content-end">
                                                <div class="col-12 col-md-12 text-end">
                                                    <a class="btn btn-warning mt-4" href="https://innovation.asefa.co.th/ChangeRequestForm/?DataE=<?php echo $_GET['DataE'] ?>"><i class="fa-solid fa-arrow-left fs-6"></i> ย้อนกลับ</a>
                                                    <button class="btn btn-info mt-4" type="button" id="savedraf" data-type="Savedraft"><i class="fa-solid fa-floppy-disk p-0 fs-6"></i> Save Draf</button>
                                                    <button class="btn btn-primary mt-4" type="button" id="submit" data-type="New"><i class="fa-solid fa-plus p-0 fs-6"></i> บันทึก</button>
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
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>
    <!-- <script src="https://raw.githubusercontent.com/earthchie/jqueryui-thai-datepicker/master/jquery.ui.datepicker-th.js"></script> -->
    <script>
        var RowSelect = <?php echo count($data_detail) + 1 ?>;


        $(document).ready(function() {
            var DataE = '<?php echo $_SESSION['DataE']; ?>';

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
                    console.log(inputCustomId);

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
                        $('#doc_charge').val('');
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

            $("#submit, #savedraf").click(function() {
                var user = '<?php echo $_SESSION['ChangeRequest_code']; ?>';
                var jobtype = $("#jobtype").val();
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
                var datetype = $(this).attr('data-type');
                var urgentwork = $("#urgentwork").is(":checked") ? $("#urgentwork").val() : "0";

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

                for (var i = 0; i < RowSelect; i++) {
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
                            other: []
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
                            // console.log($(this).val());

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

                if (selectedProducts == '' && product_other == '') {
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

                // if ($('#table_de tbody tr').length == 0) {
                //     Swal.fire(
                //         'กรุณาเพิ่มรายการอย่างน้อย 1 รายการ',
                //         '',
                //         'warning'
                //     )
                //     return false;
                // }

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
                // var diviData = {
                //     divitext: divitext,
                //     divitext_other: divitext_other
                // }
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

                var formData = new FormData();
                formData.append('user', user);
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
                formData.append('sellData', JSON.stringify(sellData) ?? '');
                formData.append('diviselect', JSON.stringify(diviData) ?? '');
                formData.append('datetype', datetype ?? '');
                formData.append('urgentwork', urgentwork ?? '');

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
                        $.ajax({
                            url: './insertcr',
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
                                var data = JSON.parse(response);
                                Swal.close();
                                if (data.status == true) {
                                    Swal.fire(
                                        'บันทึกข้อมูลสำเร็จ',
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

                for (var j = 0; j < parts.length - 1; j++) {
                    if (dangerousExtensions.indexOf(parts[j]) !== -1) {
                        return {
                            valid: false,
                            error: 'ไฟล์ "' + files[i].name + '" มีนามสกุลอันตราย (.' + parts[j] + ')'
                        };
                    }
                }

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