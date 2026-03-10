<!--begin::Header-->
<?php //include_once 'config/base.php'; 

$get_Waiting = get_Waiting($_SESSION['ChangeRequest_code']);

$session_keys = [
    $_SESSION['ChangeRequest_code'],
    $_SESSION['DivisionHeadID1'],
    $_SESSION['DivisionHeadID2']
];

$list_permission_emp = explode(", ", list_permission('Admin')[0]['Per_Json']);
$list_permission_divi = explode(", ", list_permission('Admin')[0]['Per_Divi_Json']);
$data_admin = array_merge($list_permission_emp, $list_permission_divi);

$ListPer_New = explode(", ", list_permission('New')[0]['Per_Json']);
$ListPer_Divi_New = explode(", ", list_permission('New')[0]['Per_Divi_Json']);
$data_New = array_merge($ListPer_New, $ListPer_Divi_New);

$ListPer_Review = explode(", ", list_permission('Review')[0]['Per_Json']);
$ListPer_Divi_Review = explode(", ", list_permission('Review')[0]['Per_Divi_Json']);
$data_Review = array_merge($ListPer_Review, $ListPer_Divi_Review);

$ListPer_Check = explode(", ", list_permission('Check')[0]['Per_Json']);
$ListPer_Divi_Check = explode(", ", list_permission('Check')[0]['Per_Divi_Json']);
$data_Check = array_merge($ListPer_Check, $ListPer_Divi_Check);

$ListPer_Aproved = explode(", ", list_permission('Aproved')[0]['Per_Json']);
$ListPer_Divi_Aproved = explode(", ", list_permission('Aproved')[0]['Per_Divi_Json']);
$data_Aproved = array_merge($ListPer_Aproved, $ListPer_Divi_Aproved);

$ListPer_Rework = explode(", ", list_permission('Rework')[0]['Per_Json']);
$ListPer_Divi_Rework = explode(", ", list_permission('Rework')[0]['Per_Divi_Json']);
$data_Rework = array_merge($ListPer_Rework, $ListPer_Divi_Rework);

$ListPer_Draft = explode(", ", list_permission('Draft')[0]['Per_Json']);
$ListPer_Divi_Draft = explode(", ", list_permission('Draft')[0]['Per_Divi_Json']);
$data_Draft = array_merge($ListPer_Draft, $ListPer_Divi_Draft);

$ListPer_Cancel = explode(", ", list_permission('Cancel')[0]['Per_Json']);
$ListPer_Divi_Cancel = explode(", ", list_permission('Cancel')[0]['Per_Divi_Json']);
$data_Cancel = array_merge($ListPer_Cancel, $ListPer_Divi_Cancel);

$ListPer_Close = explode(", ", list_permission('Close')[0]['Per_Json']);
$ListPer_Divi_Close = explode(", ", list_permission('Close')[0]['Per_Divi_Json']);
$data_Close = array_merge($ListPer_Close, $ListPer_Divi_Close);

$ListPer_Report = explode(", ", list_permission('Report')[0]['Per_Json']);
$ListPer_Divi_Report = explode(", ", list_permission('Report')[0]['Per_Divi_Json']);
$data_Report = array_merge($ListPer_Report, $ListPer_Divi_Report);

$ListPer_Print = explode(", ", list_permission('Print')[0]['Per_Json']);
$ListPer_Divi_Print = explode(", ", list_permission('Print')[0]['Per_Divi_Json']);
$data_Print = array_merge($ListPer_Print, $ListPer_Divi_Print);

// $data_admin = [
//     "660500122",
//     // "540411127"
// ];

$color_arr = array(
    'New' => "#0d6efd",
    'Draf' => "#e67e22",
    'Review' => "#af7ac5",
    'Rework' => "#f4d03f",
    'Check' => "#0dcaf0",
    'Recheck' => "#05abb3",
    'Approve' => "#2ecc71",
    'Close' => "#85929e",
    'Cancel' => "#ec7063",
    'Not Approve' => "#ec7063",
);

// if ($_SESSION['ChangeRequest_code'] == '660500122') {
//     echo "<pre>";
//     print_r($session_keys);
//     print_r($data_admin);
//     exit;
// }
?>

<style>
    .fa-bell {
        cursor: pointer;
        transition: font-size 0.3s ease;
    }

    .fa-bell:hover {
        font-size: 28px !important;
    }
</style>

<div id="kt_app_header" class="app-header ">

    <!--begin::Header primary-->
    <div class="app-header-primary " data-kt-sticky="true" data-kt-sticky-name="app-header-primary-sticky" data-kt-sticky-offset="{default: 'false', lg: '300px'}">

        <!--begin::Header primary container-->
        <div class="app-container  container-xxl d-flex align-items-stretch justify-content-between ">
            <!--begin::Logo and search-->
            <div class="d-flex flex-grow-1 flex-lg-grow-0">
                <!--begin::Logo wrapper-->
                <div class="d-flex align-items-center me-7" id="kt_app_header_logo_wrapper">
                    <!--begin::Header toggle-->
                    <button class="d-lg-none btn btn-icon btn-flex btn-color-gray-600 btn-active-color-primary w-35px h-35px ms-n2 me-2" id="kt_app_header_menu_toggle">
                        <i class="ki-outline ki-abstract-14 fs-2"></i> </button>
                    <!--end::Header toggle-->

                    <!--begin::Logo-->
                    <a href="index.php?DataE=<?php echo $_SESSION['DataE'] ?>&pageH=1" class="d-flex align-items-center me-5">
                        <img alt="Logo" src="assets/media/logos/Left - Blue.png" class="d-sm-none d-inline" style="height: 30px;" />
                        <img alt="Logo" src="assets/media/logos/Left - Blue.png" class="h-lg-40px theme-light-show d-none d-sm-inline" />
                        <img alt="Logo" src="assets/media/logos/Left - Blue.png" class="h-lg-40px theme-dark-show d-none d-sm-inline" />
                    </a>
                    <!--end::Logo-->
                </div>
                <!--end::Logo wrapper-->
                <!--begin::Menu wrapper-->
                <div class="app-header-menu app-header-mobile-drawer align-items-stretch" data-kt-drawer="true" data-kt-drawer-name="app-header-menu" data-kt-drawer-activate="{default: true, lg: false}" data-kt-drawer-overlay="true" data-kt-drawer-width="250px" data-kt-drawer-direction="start" data-kt-drawer-toggle="#kt_app_header_menu_toggle" data-kt-swapper="true" data-kt-swapper-mode="{default: 'append', lg: 'prepend'}" data-kt-swapper-parent="{default: '#kt_app_body', lg: '#kt_app_header_wrapper'}">
                    <!--begin::Menu-->
                    <div class=" menu  
                             menu-rounded 
                             menu-active-bg 
                             menu-state-primary 
                             menu-column 
                             menu-lg-row 
                             menu-title-gray-700 
                             menu-icon-gray-500 
                             menu-arrow-gray-500 
                             menu-bullet-gray-500 
                             my-5 my-lg-0 align-items-stretch fw-semibold px-2 px-lg-0" id="kt_app_header_menu" data-kt-menu="true">
                        <a href="./?DataE=<?php echo $_SESSION['DataE'] ?>" data-kt-menu-placement="bottom-start" data-kt-menu-offset="-200,0" class="menu-item 
                        <?php if (@$page == 1) {
                            echo 'here show';
                        } ?>">
                            <span class="menu-link py-3">
                                <span class="menu-title fs-4"><i class="menu-title fas fa-home fs-3 me-2"></i> หน้าแรก</span>
                                <span class="menu-arrow d-lg-none"></span>
                            </span>
                        </a>

                        <?php
                        if (array_intersect($session_keys, $data_admin) || array_intersect($session_keys, $data_New)) {
                        ?>

                            <a href="./InsertForm?DataE=<?php echo $_SESSION['DataE'] ?>" data-kt-menu-placement="bottom-start" data-kt-menu-offset="-200,0" class="menu-item 
                        <?php if (@$page == 2) {
                                echo 'here show';
                            } ?>">
                                <span class="menu-link py-3">
                                    <span class="menu-title fs-4"><i class="menu-title fas fa-clipboard-list fs-3 me-2"></i>เพิ่มใบเปลี่ยนแปลง</span>
                                    <span class="menu-arrow d-lg-none"></span>
                                </span>
                            </a>

                        <?php
                        }
                        ?>

                        <!-- <div data-kt-menu-trigger="{default: 'click', lg: 'hover'}" data-kt-menu-placement="bottom-start" class="menu-item menu-lg-down-accordion menu-sub-lg-down-indention me-0 me-lg-2 <?php echo @$page == 4 ? 'here' : '' ?>">
                            <span class="menu-link py-3"><span class="menu-title fs-4"><i class="menu-title fas fa-th-list fs-3 me-2"></i> รายการตามสถานะ</span><span class="menu-arrow d-lg-none"></span></span>
                            <div class="menu-sub menu-sub-lg-down-accordion menu-sub-lg-dropdown px-lg-2 py-lg-4 w-lg-200px">
                                <div class="menu-item">
                                    <a class="menu-link py-3" href="https://innovation.asefa.co.th/ChangeRequestForm/list_status?DataE=<?php //echo $_SESSION['ChangeRequest_token'] 
                                                                                                                                        ?>&status=New" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-dismiss="click" data-bs-placement="right" data-bs-original-title="Check out over 200 in-house components, plugins and ready for use solutions" data-kt-initialized="1">
                                        <span class="menu-icon"><i class="fas fa-laptop-medical fa-fw fs-3"></i></span>
                                        <span class="menu-title fs-5">รอทบทวน</span>
                                    </a>
                                </div>
                                <div class="menu-item">
                                    <a class="menu-link py-3" href="https://innovation.asefa.co.th/ChangeRequestForm/list_status?DataE=<?php //echo $_SESSION['ChangeRequest_token'] 
                                                                                                                                        ?>&status=Draf" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-dismiss="click" data-bs-placement="right" data-bs-original-title="Check out over 200 in-house components, plugins and ready for use solutions" data-kt-initialized="1">
                                        <span class="menu-icon"><i class="fas fa-wrench fa-fw fs-3"></i></span>
                                        <span class="menu-title fs-5">รับงานแก้ไข</span>
                                    </a>
                                </div>
                                <div class="menu-item">
                                    <a class="menu-link py-3" href="https://innovation.asefa.co.th/ChangeRequestForm/list_status?DataE=<?php //echo $_SESSION['ChangeRequest_token'] 
                                                                                                                                        ?>&status=Review" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-dismiss="click" data-bs-placement="right" data-bs-original-title="Check out over 200 in-house components, plugins and ready for use solutions" data-kt-initialized="1">
                                        <span class="menu-icon"><i class="fas fa-file-signature fa-fw fs-3"></i></span>
                                        <span class="menu-title fs-5">รอตรวจสอบ</span>
                                    </a>
                                </div>
                                <div class="menu-item">
                                    <a class="menu-link py-3" href="https://innovation.asefa.co.th/ChangeRequestForm/list_status?DataE=<?php //echo $_SESSION['ChangeRequest_token'] 
                                                                                                                                        ?>&status=Check" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-dismiss="click" data-bs-placement="right" data-bs-original-title="Check out over 200 in-house components, plugins and ready for use solutions" data-kt-initialized="1">
                                        <span class="menu-icon"><i class="fas fa-check-circle fa-fw fs-3"></i></span>
                                        <span class="menu-title fs-5">รออนุมัติ</span>
                                    </a>
                                </div>
                            </div>
                        </div> -->

                        <?php
                        if (array_intersect($session_keys, $data_admin)) {
                        ?>
                            <div data-kt-menu-trigger="{default: 'click', lg: 'hover'}" data-kt-menu-placement="bottom-start" class="menu-item menu-lg-down-accordion menu-sub-lg-down-indention me-0 me-lg-2 <?php echo @$page == 3 ? 'here' : '' ?>">
                                <span class="menu-link py-3"><span class="menu-title fs-4"><i class="menu-title fa-solid fa-gear fs-3 me-2"></i> Settings</span><span class="menu-arrow d-lg-none"></span></span>
                                <div class="menu-sub menu-sub-lg-down-accordion menu-sub-lg-dropdown px-lg-2 py-lg-4 w-lg-200px">
                                    <div class="menu-item">
                                        <a class="menu-link py-3" href="https://innovation.asefa.co.th/ChangeRequestForm/productlist?DataE=<?php echo $_SESSION['DataE'] ?>" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-dismiss="click" data-bs-placement="right" data-bs-original-title="Check out over 200 in-house components, plugins and ready for use solutions" data-kt-initialized="1">
                                            <span class="menu-icon"><i class="fa-solid fa-gear fa-fw fs-3"></i></span>
                                            <span class="menu-title fs-5">เพิ่มสินค้า</span>
                                        </a>
                                    </div>
                                    <div class="menu-item">
                                        <a class="menu-link py-3" href="https://innovation.asefa.co.th/ChangeRequestForm/causelist?DataE=<?php echo $_SESSION['DataE'] ?>" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-dismiss="click" data-bs-placement="right" data-bs-original-title="Check out over 200 in-house components, plugins and ready for use solutions" data-kt-initialized="1">
                                            <span class="menu-icon"><i class="fa-solid fa-gear fa-fw fs-3"></i></span>
                                            <span class="menu-title fs-5">เพิ่มสาเหตุ</span>
                                        </a>
                                    </div>
                                    <div class="menu-item">
                                        <a class="menu-link py-3" href="https://innovation.asefa.co.th/ChangeRequestForm/divisionlist?DataE=<?php echo $_SESSION['DataE'] ?>" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-dismiss="click" data-bs-placement="right" data-bs-original-title="Check out over 200 in-house components, plugins and ready for use solutions" data-kt-initialized="1">
                                            <span class="menu-icon"><i class="fa-solid fa-gear fa-fw fs-3"></i></span>
                                            <span class="menu-title fs-5">เพิ่มแผนกผู้เกี่ยวข้อง</span>
                                        </a>
                                    </div>
                                    <div class="menu-item">
                                        <a class="menu-link py-3" href="https://innovation.asefa.co.th/ChangeRequestForm/setting_admin?DataE=<?php echo $_SESSION['DataE'] ?>" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-dismiss="click" data-bs-placement="right" data-bs-original-title="Check out over 200 in-house components, plugins and ready for use solutions" data-kt-initialized="1">
                                            <span class="menu-icon"><i class="fa-solid fa-gear fa-fw fs-3"></i></span>
                                            <span class="menu-title fs-5">กำหนดสิทธิ์</span>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        <?php
                        }
                        ?>

                        <?php
                        if (array_intersect($session_keys, $data_admin) || array_intersect($session_keys, $data_Report)) {
                        ?>
                            <div data-kt-menu-trigger="{default: 'click', lg: 'hover'}" data-kt-menu-placement="bottom-start" class="menu-item menu-lg-down-accordion menu-sub-lg-down-indention me-0 me-lg-2 <?php echo @$page == 4 ? 'here' : '' ?>">
                                <span class="menu-link py-3"><span class="menu-title fs-4"><i class="menu-title fa-solid fa-gear fs-3 me-2"></i> รายงาน</span><span class="menu-arrow d-lg-none"></span></span>
                                <div class="menu-sub menu-sub-lg-down-accordion menu-sub-lg-dropdown px-lg-2 py-lg-4 w-lg-450px">
                                    <div class="menu-item">
                                        <a href="./Report?DataE=<?php echo $_SESSION['DataE'] ?>" data-kt-menu-placement="bottom-start" data-kt-menu-offset="-200,0" class="menu-item 
                                        <?php if (@$page == 4) {
                                            echo 'here show';
                                        } ?>">
                                            <span class="menu-link py-3">
                                                <span class="menu-title fs-4"><i class=" fas fa-newspaper fs-3" style="margin-right: 15px;"></i>EN0102-สรุปตาม JOB.</span>
                                                <span class="menu-arrow d-lg-none"></span>
                                            </span>
                                        </a>
                                    </div>
                                    <div class="menu-item">
                                        <a href="./ReportRev?DataE=<?php echo $_SESSION['DataE'] ?>" data-kt-menu-placement="bottom-start" data-kt-menu-offset="-200,0" class="menu-item 
                                        <?php if (@$page == 8) {
                                            echo 'here show';
                                        } ?>">
                                            <span class="menu-link py-3">
                                                <span class="menu-title fs-4"><i class=" fas fa-newspaper fs-3" style="margin-right: 15px;"></i>รายงานบันทึกการเปลี่ยนแปลง</span>
                                                <span class="menu-arrow d-lg-none"></span>
                                            </span>
                                        </a>
                                    </div>
                                    <div class="menu-item">
                                        <a href="./Report2?DataE=<?php echo $_SESSION['DataE'] ?>" data-kt-menu-placement="bottom-start" data-kt-menu-offset="-200,0" class="menu-item 
                                            <?php if (@$page == 5) {
                                                echo 'here show';
                                            } ?>">
                                            <span class="menu-link py-3">
                                                <span class="menu-title fs-4"><i class=" fas fa-newspaper fs-3" style="margin-right: 15px;"></i>EN0203-รายงานติดตามใบเปลี่ยนแปลงนอก Scope</span>
                                                <span class="menu-arrow d-lg-none"></span>
                                            </span>
                                        </a>
                                    </div>
                                    <div class="menu-item">
                                        <a href="./ReportAll?DataE=<?php echo $_SESSION['DataE'] ?>" data-kt-menu-placement="bottom-start" data-kt-menu-offset="-200,0" class="menu-item 
                                            <?php if (@$page == 6) {
                                                echo 'here show';
                                            } ?>">
                                            <span class="menu-link py-3">
                                                <span class="menu-title fs-4"><i class=" fas fa-newspaper fs-3" style="margin-right: 15px;"></i>รายงานแสดงข้อมูลใบเปลี่ยนแปลง(ป/ป)</span>
                                                <span class="menu-arrow d-lg-none"></span>
                                            </span>
                                        </a>
                                    </div>
                                    <div class="menu-item">
                                        <a href="./ReportStatus?DataE=<?php echo $_SESSION['DataE'] ?>" data-kt-menu-placement="bottom-start" data-kt-menu-offset="-200,0" class="menu-item 
                                            <?php if (@$page == 7) {
                                                echo 'here show';
                                            } ?>">
                                            <span class="menu-link py-3">
                                                <span class="menu-title fs-4"><i class=" fas fa-newspaper fs-3" style="margin-right: 15px;"></i>รายงานใบขอเปลี่ยนแปลงตามสถานะ</span>
                                                <span class="menu-arrow d-lg-none"></span>
                                            </span>
                                        </a>
                                    </div>

                                </div>
                            </div>
                            <!--  -->



                        <?php
                        }
                        ?>

                    </div>
                    <!--end::Menu-->
                </div>
                <!--end::Menu wrapper-->
            </div>
            <!--end::Logo and search-->


            <!--begin::Navbar-->
            <div class="app-navbar flex-shrink-0">

                <?php
                //if($_SESSION['ChangeRequest_user_id'] == '2776'){
                ?>
                <div class="app-navbar-item mx-3">
                    <a href="./WaitView?DataE=<?php echo $_SESSION['DataE'] ?>">
                        <i class="fa-solid fa-bell fs-1 position-relative" style="color: #f7dc6f;">
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger text-white"><?php echo count($get_Waiting); ?> </span>
                        </i>
                    </a>
                </div>
                <?php
                //}
                ?>
                <!--begin::User menu-->
                <div class="app-navbar-item ms-3 ms-lg-9" id="kt_header_user_menu_toggle">
                    <!--begin::Menu wrapper-->
                    <div class="d-flex align-items-center" data-kt-menu-trigger="{default: 'click', lg: 'hover'}" data-kt-menu-attach="parent" data-kt-menu-placement="bottom-end">
                        <!--begin:Info-->
                        <div class="text-end d-none d-sm-flex flex-column justify-content-center me-3">
                            <span class="" class="text-gray-500 fs-8 fw-bold">สวัสดี</span>
                            <a href="#" class="text-gray-800 text-hover-primary fs-7 fw-bold d-block"><?php echo $_SESSION['ChangeRequest_name']; ?></a>
                        </div>
                        <!--end:Info-->

                        <!--begin::User-->
                        <div class="cursor-pointer symbol symbol symbol-circle symbol-35px symbol-md-40px">
                            <?php if (get_headers($_SESSION['ChangeRequest_image']) != "HTTP/1.1 404 Not Found") {
                            ?>
                                <img class src="<?php echo $_SESSION['ChangeRequest_image']; ?>" alt="user" />
                            <?php
                            } else {
                            ?>
                                <img class src="<?php echo $_SESSION['ChangeRequest_image']; ?>" alt="user" />
                            <?php
                            }
                            ?>
                            <div class="position-absolute translate-middle bottom-0 mb-1 start-100 ms-n1 bg-success rounded-circle h-8px w-8px"></div>
                        </div>
                        <!--end::User-->
                    </div>

                    <!--begin::User account menu-->
                    <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg menu-state-color fw-semibold py-4 fs-6 w-275px" data-kt-menu="true">
                        <div class="menu-item px-5" data-kt-menu-trigger="{default: 'click', lg: 'hover'}" data-kt-menu-placement="left-start" data-kt-menu-offset="-15px, 0">
                            <a href="#" class="menu-link px-5">
                                <span class="menu-title position-relative">
                                    Mode

                                    <span class="ms-5 position-absolute translate-middle-y top-50 end-0">
                                        <i class="ki-outline ki-night-day theme-light-show fs-2"></i> <i class="ki-outline ki-moon theme-dark-show fs-2"></i> </span>
                                </span>
                            </a>

                            <!--begin::Menu-->
                            <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-title-gray-700 menu-icon-gray-500 menu-active-bg menu-state-color fw-semibold py-4 fs-base w-150px" data-kt-menu="true" data-kt-element="theme-mode-menu">
                                <!--begin::Menu item-->
                                <div class="menu-item px-3 my-0">
                                    <a href="#" class="menu-link px-3 py-2" data-kt-element="mode" data-kt-value="light">
                                        <span class="menu-icon" data-kt-element="icon">
                                            <i class="ki-outline ki-night-day fs-2"></i> </span>
                                        <span class="menu-title">
                                            Light
                                        </span>
                                    </a>
                                </div>
                                <!--end::Menu item-->

                                <!--begin::Menu item-->
                                <div class="menu-item px-3 my-0">
                                    <a href="#" class="menu-link px-3 py-2" data-kt-element="mode" data-kt-value="dark">
                                        <span class="menu-icon" data-kt-element="icon">
                                            <i class="ki-outline ki-moon fs-2"></i> </span>
                                        <span class="menu-title">
                                            Dark
                                        </span>
                                    </a>
                                </div>
                                <!--end::Menu item-->
                            </div>
                            <!--end::Menu-->

                        </div>
                        <!--end::Menu item-->


                        <!--begin::Menu item-->
                        <!-- <div class="menu-item px-5">
                            <a href="https://innovation.asefa.co.th/authen/signout.php" class="menu-link px-5">
                                Sign Out
                            </a>
                        </div> -->
                        <!--end::Menu item-->
                    </div>
                </div>
                <div class="app-navbar-item d-lg-none ms-2 me-n3" title="Show header menu">
                    <div class="btn btn-icon btn-color-gray-500 btn-active-color-primary w-35px h-35px" id="kt_app_header_menu_toggle">
                        <i class="ki-outline ki-text-align-left fs-1"></i>
                    </div>
                </div>
                <!--end::Header menu toggle-->
            </div>
            <!--end::Navbar-->
        </div>
    </div>

</div>
<!--end::Header-->