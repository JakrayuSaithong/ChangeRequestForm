<?php
session_start();
ini_set('display_errors', '0');
ini_set('display_startup_errors', '0');
error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);
// session_destroy();

include_once    'route.php';
include_once    'config/base.php';
// include_once    'sendMail.php';

/**
 * ตรวจสอบความปลอดภัยของไฟล์ที่ upload
 * ไม่เช็ค MIME, ไม่ใช้ isValidXlsxStructure
 * ตรวจแบบ “เบา + ลด false negative”:
 *  - กัน double extension (php, js, exe ฯลฯ)
 *  - allow-list extension
 *  - รูป: getimagesize()
 *  - PDF: หา %PDF- ในช่วงต้นไฟล์ (รองรับ whitespace/BOM)
 *  - XLSX: ตรวจแค่ ZIP signature "PK" (ไม่เปิด/ไล่ไฟล์ใน zip)
 *
 * @param array $file
 * @return array
 */
function validateUploadedFile(array $file): array
{
    $allowedExtensions = [
        'jpg',
        'jpeg',
        'png',
        'gif',
        'bmp',
        'webp',
        'pdf',
        'xlsx',
    ];

    $dangerousExtensions = [
        'php',
        'phtml',
        'php3',
        'php4',
        'php5',
        'php7',
        'php8',
        'phar',
        'exe',
        'dll',
        'so',
        'sh',
        'bat',
        'cmd',
        'com',
        'msi',
        'js',
        'jse',
        'vbs',
        'vbe',
        'wsf',
        'wsh',
        'jsp',
        'jspx',
        'asp',
        'aspx',
        'asa',
        'asax',
        'cgi',
        'pl',
        'py',
        'pyc',
        'rb',
        'htaccess',
        'htpasswd',
        'ini',
        'config'
    ];

    $maxFileSize = 50 * 1024 * 1024; // 50MB

    $fileName  = (string)($file['name'] ?? '');
    $fileSize  = (int)($file['size'] ?? 0);
    $tmpPath   = (string)($file['tmp_name'] ?? '');
    $fileError = (int)($file['error'] ?? UPLOAD_ERR_NO_FILE);

    if ($fileError !== UPLOAD_ERR_OK) {
        return ['valid' => false, 'error' => 'Upload error: ' . $fileError, 'safeName' => '', 'extension' => ''];
    }

    if ($tmpPath === '' || !is_uploaded_file($tmpPath)) {
        return ['valid' => false, 'error' => 'ไฟล์ไม่ถูกต้อง', 'safeName' => '', 'extension' => ''];
    }

    if ($fileSize <= 0) {
        return ['valid' => false, 'error' => 'ไฟล์ไม่ถูกต้อง', 'safeName' => '', 'extension' => ''];
    }

    if ($fileSize > $maxFileSize) {
        return ['valid' => false, 'error' => 'ไฟล์มีขนาดใหญ่เกินไป (สูงสุด 50MB)', 'safeName' => '', 'extension' => ''];
    }

    $fileName = str_replace("\0", '', $fileName);
    $fileNameLower = strtolower($fileName);

    // 1) กัน double extension / dangerous anywhere
    foreach ($dangerousExtensions as $dangerous) {
        if (
            preg_match('/\.' . preg_quote($dangerous, '/') . '\./i', $fileNameLower) ||
            preg_match('/\.' . preg_quote($dangerous, '/') . '$/i', $fileNameLower)
        ) {
            return ['valid' => false, 'error' => 'นามสกุลไฟล์ไม่อนุญาต: .' . $dangerous, 'safeName' => '', 'extension' => ''];
        }
    }

    // 2) ตรวจ extension สุดท้าย
    $extension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
    if ($extension === '' || !in_array($extension, $allowedExtensions, true)) {
        return ['valid' => false, 'error' => 'นามสกุลไฟล์ไม่อนุญาต: .' . $extension, 'safeName' => '', 'extension' => ''];
    }

    // 3) ตรวจตามชนิดไฟล์ (ไม่ใช้ MIME)
    $imageExts = ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp'];
    if (in_array($extension, $imageExts, true)) {
        $info = @getimagesize($tmpPath);
        if ($info === false) {
            return ['valid' => false, 'error' => 'ไฟล์ไม่ใช่รูปภาพที่ถูกต้อง', 'safeName' => '', 'extension' => $extension];
        }
    }

    if ($extension === 'pdf') {
        $pdfCheck = isValidPdf($tmpPath);
        if ($pdfCheck !== true) {
            return ['valid' => false, 'error' => $pdfCheck, 'safeName' => '', 'extension' => $extension];
        }
    }

    if ($extension === 'xlsx') {
        // เบาสุด: แค่ตรวจว่าเป็น ZIP container (xlsx เป็น zip)
        $sig = @file_get_contents($tmpPath, false, null, 0, 4);
        if ($sig === false || substr($sig, 0, 2) !== "PK") {
            return ['valid' => false, 'error' => 'ไฟล์ Excel (.xlsx) ไม่ถูกต้อง (ไม่ใช่ ZIP)', 'safeName' => '', 'extension' => $extension];
        }
    }

    // 4) safe filename
    $safeName = preg_replace('/[^a-zA-Z0-9._-]/', '_', $fileName);
    $safeName = preg_replace('/_+/', '_', $safeName);
    $safeName = ltrim($safeName, '.');

    return [
        'valid' => true,
        'error' => '',
        'safeName' => $safeName,
        'extension' => $extension
    ];
}

/**
 * ตรวจว่าเป็น PDF จริงแบบ tolerant:
 * - อนุญาต whitespace/BOM นำหน้า
 * - ต้องพบ %PDF- ภายใน 1024 bytes แรก
 *
 * @return true|string
 */
function isValidPdf(string $tmpPath)
{
    $chunk = @file_get_contents($tmpPath, false, null, 0, 2048);
    if ($chunk === false || $chunk === '') {
        return 'ไฟล์ไม่ใช่ PDF ที่ถูกต้อง (อ่านไฟล์ไม่ได้)';
    }

    $pos = strpos($chunk, '%PDF-');
    if ($pos === false || $pos > 1024) {
        return 'ไฟล์ไม่ใช่ PDF ที่ถูกต้อง (ไม่พบ %PDF- ใกล้ต้นไฟล์)';
    }

    return true;
}

if (isset($_GET['DataE'])) {

    // if ($_GET['token'] <> $_SESSION['token']) {
    //     session_unset();     // unset $_SESSION variable for the run-time 
    // session_destroy();   // destroy session data in storage
    //     session_start();
    // }

    $JsonText = decryptIt($_GET['DataE']);
    $JSOnArr = json_decode($JsonText, true);
    $now = time();

    // ตรวจสอบ session timeout (1 ชั่วโมง = 3600 วินาที)
    $dataTime = (is_array($JSOnArr) && isset($JSOnArr['date_U'])) ? (int)$JSOnArr['date_U'] : 0;
    if (($now - $dataTime) > 21600) {
        session_unset();
        session_destroy();

        echo "
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset='UTF-8'>
            <title>Session Expired</title>
            <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
        </head>
        <body>
        <script>
            Swal.fire({
                icon: 'warning',
                title: 'หมดเวลาการใช้งาน',
                text: 'Session หมดอายุแล้ว กรุณาเข้าสู่ระบบใหม่',
                confirmButtonText: 'ตกลง',
                allowOutsideClick: false,
                allowEscapeKey: false
            }).then(() => {
                window.close();
                window.location.href = 'about:blank';
            });
        </script>
        </body>
        </html>
        ";
        exit();
    }

    // if ($JSOnArr['auth_user_name'] != '660500122' && $JSOnArr['auth_user_name'] != '660700186' && $JSOnArr['auth_user_name'] != '670100002' && $JSOnArr['auth_user_name'] != '500811071') {
    //     echo "กำลังแก้ไขข้อมูล";
    //     exit();
    // }

    // $_SESSION['token'] = $_GET['token'];
    // $token = $_GET['token'];
    // $post    =    ['token' => $token];
    // $uri    =    "https://innovation.asefa.co.th/applications/token/authtoken";

    // $ch = curl_init();
    // curl_setopt($ch, CURLOPT_URL, $uri);
    // curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    // curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    // curl_setopt($ch, CURLOPT_POST, 1);
    // curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
    // curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    // curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    // $result = curl_exec($ch);
    // curl_close($ch);

    // $decode_result = json_decode($result, true);
    // $Users_Username = $decode_result['DATA']['Users_Username'];
    $Users_Username = $JSOnArr['auth_user_name'];


    if ($Users_Username) {
        $get_emp_detail = "https://innovation.asefa.co.th/applications/ds/emp_list_code";
        $chs = curl_init();
        curl_setopt($chs, CURLOPT_URL, $get_emp_detail);
        curl_setopt($chs, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($chs, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($chs, CURLOPT_SSL_VERIFYPEER, false);

        curl_setopt($chs, CURLOPT_POST, 1);
        curl_setopt($chs, CURLOPT_POSTFIELDS, ["emp_code" => $Users_Username]);
        $emp = curl_exec($chs);
        curl_close($chs);

        $empdata   =   json_decode($emp);

        $_SESSION['ChangeRequest_login'] = "UserLogin";
        $_SESSION['ChangeRequest_user_id'] = $empdata[0]->id_ref;
        $_SESSION['ChangeRequest_code'] = $empdata[0]->emp_code;
        $_SESSION['ChangeRequest_name'] = $empdata[0]->emp_FirstName;
        $_SESSION['ChangeRequest_image'] = $empdata[0]->emp_Image;
        $_SESSION['ChangeRequest_mail'] = $empdata[0]->emp_Email;
        $_SESSION['DivisionCode'] = $empdata[0]->DivisionCode;
        $_SESSION['DivisionHead1'] = $empdata[0]->DivisionHead1;
        $_SESSION['DivisionHead2'] = $empdata[0]->DivisionHead2;
        $_SESSION['DivisionHeadID1'] = $empdata[0]->DivisionHeadID1;
        $_SESSION['DivisionHeadID2'] = $empdata[0]->DivisionHeadID2;
        $_SESSION['DivisionNameTH'] = $empdata[0]->DivisionNameTH;
        $_SESSION['DataE'] = $_GET['DataE'];
    }

    // echo "<pre>";
    // print_r($_SESSION);
    // echo "</pre>";
} else if (isset($_SESSION['ChangeRequest_login'])) {
}

$route->add('/', function () { {
        $page = 1;
        include("home.php");
    }
});

$route->add('/list_status', function () { {
        $page = 4;
        include("status_show.php");
    }
});

$route->add('/InsertForm', function () { {
        $page = 2;
        include("InsertForm.php");
    }
});

$route->add('/ViewForm', function () { {
        $page = 2;
        include("UpdateForm_V2.php");
    }
});

$route->add('/ViewForm_test', function () { {
        $page = 2;
        include("UpdateForm_V2_test.php");
    }
});

$route->add('/ViewFormTest', function () { {
        $page = 2;
        include("UpdateForm.php");
    }
});

$route->add('/WaitView', function () { {
        include("WaitView.php");
    }
});

$route->add('/copyForm', function () { {
        $page = 2;
        include("CopyForm.php");
    }
});

$route->add('/productlist', function () { {
        $page = 3;
        include("product.php");
    }
});

$route->add('/causelist', function () { {
        $page = 3;
        include("cause.php");
    }
});

$route->add('/divisionlist', function () { {
        $page = 3;
        include("division.php");
    }
});

$route->add('/Print_Form_CHI', function () { {
        $page = 3;
        include("pdf_chi.php");
    }
});

$route->add('/Print_Form_CHO', function () { {
        $page = 3;
        include("pdf_cho.php");
    }
});

$route->add('/setting_admin', function () { {
        $page = 3;
        include("setting_admin.php");
    }
});

$route->add('/Report', function () { {
        $page = 4;
        include("report.php");
    }
});

$route->add('/Report2', function () { {
        $page = 5;
        include("report2.php");
    }
});

$route->add('/ReportAll', function () { {
        $page = 6;
        include("reportAll.php");
    }
});

$route->add('/ReportRev', function () { {
        $page = 8;
        include("reportRev.php");
    }
});

$route->add('/ReportStatus', function () { {
        $page = 7;
        include("reportstatus.php");
    }
});

$route->add('/AddProduct', function () { {
        ob_clean();
        header_remove();
        header("Content-type: application/json; charset=utf-8");

        $auth = $_POST['auth'];
        $product_name = $_POST['product_name'];

        echo AddProduct($auth, $product_name);
    }
});

$route->add('/ChangeApprover', function () { {
        ob_clean();
        header_remove();
        header("Content-type: application/json; charset=utf-8");

        global $konnext_DB64;

        $CR_no = $_POST['CR_no'] ?? '';
        $field = $_POST['field'] ?? '';
        $jsonKey = $_POST['jsonKey'] ?? '';
        $newEmp = $_POST['newEmp'] ?? '';

        $allowedFields = ['CR_Approve1', 'CR_Approve2', 'CR_Approve3', 'CR_Approve4', 'CR_Approve5'];
        if (!in_array($field, $allowedFields)) {
            echo json_encode(['success' => false, 'error' => 'Invalid field']);
            exit;
        }

        if (empty($CR_no) || empty($newEmp) || empty($jsonKey)) {
            echo json_encode(['success' => false, 'error' => 'Missing required fields']);
            exit;
        }

        $jsonValue = json_encode([
            $jsonKey => $newEmp,
            'status_approve' => '0',
            'date_approve' => ''
        ], JSON_UNESCAPED_UNICODE);

        $sql = "UPDATE [ITService].[dbo].[CR_Approve] SET $field = ? WHERE CR_no = ?";
        $params = [$jsonValue, $CR_no];

        $stmt = sqlsrv_query($konnext_DB64, $sql, $params);

        if ($stmt === false) {
            echo json_encode(['success' => false, 'error' => 'Database error']);
        } else {
            echo json_encode(['success' => true]);
        }
    }
});

$route->add('/Report203', function () { {
        ob_clean();
        header_remove();
        header("Content-type: application/json; charset=utf-8");

        global $konnext_DB64;


        // SQL query
        $sql = "SELECT DISTINCT jobno FROM CR_ChangeForm WHERE doc_no <> ''";

        // Execute the query
        $stmt = sqlsrv_query($konnext_DB64, $sql);

        // ดึงข้อมูลจากผลลัพธ์
        $data = array();
        while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
            $data[] = $row;
        }

        echo json_encode(['success' => true, 'data' => $data]);
        // echo json_encode(['success' => true]);
        // // ปิดการเชื่อมต่อ
        // sqlsrv_close($konnext_DB64);
    }
});

// แก้ไขอันนี้ด้วย
$route->add('/Report_totel', function () {
    ob_clean();
    header_remove();
    header("Content-type: application/json; charset=utf-8");

    global $konnext_DB64;
    global $connection_ASF_VIEW;

    $selectedJobs = $_POST['selectedJobs'] ?? [];
    $startDate = $_POST['startDate'] . " 00:00:00";
    $endDate = $_POST['endDate'] . " 23:59:59";

    $whereJob = '';
    if (!empty($selectedJobs)) {
        $placeholders = "'" . implode("','", $selectedJobs) . "'";
        $whereJob = " AND cc.jobno IN ($placeholders)";
    }

    $sql = "
        SELECT 
            cc.*, 
            cs.Status_Date
        FROM CR_ChangeForm cc
        LEFT JOIN (
            SELECT CR_no, MIN(Status_Date) AS MaxStatusDate
            FROM CR_StatusList
            WHERE Status_Name = 'Close'
            GROUP BY CR_no
        ) latest_status ON cc.CR_no = latest_status.CR_no
        LEFT JOIN CR_StatusList cs
        ON cc.CR_no = cs.CR_no AND cs.Status_Date = latest_status.MaxStatusDate
        WHERE cc.doc_no <> '' 
        AND (cs.Status_Date >= ? AND cs.Status_Date <= ?)
        $whereJob
        ORDER BY 
        CASE 
            WHEN doc_no LIKE 'CHI-%' THEN 1 
            WHEN doc_no LIKE 'CHO-%' THEN 2 
            ELSE 3 
        END,
        CAST(RIGHT(doc_no, CHARINDEX('-', REVERSE(doc_no)) - 1) AS INT);
    ";

    $params = [$startDate, $endDate];
    $query = sqlsrv_query($konnext_DB64, $sql, $params);

    $data = [];
    $allProductIDs = [];
    $allDivisionIDs = [];
    $allSNs = [];

    while ($row = sqlsrv_fetch_array($query, SQLSRV_FETCH_ASSOC)) {
        $row['related'] = json_decode($row['related'], true);
        $row['status_job'] = json_decode($row['status_job'], true);
        $row['expensesummary'] = json_decode($row['expensesummary'], true);
        $data_detail = json_decode($row['data_detail'], true);

        $productIDs = array_filter(
            json_decode($row['product'], true),
            function ($id) {
                return is_numeric($id);
            }
        );
        $row['product_ids'] = $productIDs;
        $allProductIDs = array_merge($allProductIDs, $productIDs);

        if (!empty($row['sn_no'])) {
            $allSNs[] = $row['sn_no'];
        }

        // $data[$row['jobno']][] = $row;
        $data[] = $row;
    }

    $productNames = [];
    if (!empty($allProductIDs)) {
        $placeholders = implode(',', array_fill(0, count($allProductIDs), '?'));
        $query_product = "SELECT Product_ID, Product_Name FROM CR_Product WHERE Product_ID IN ($placeholders)";
        $stmt_product = sqlsrv_query($konnext_DB64, $query_product, $allProductIDs);
        while ($product = sqlsrv_fetch_array($stmt_product, SQLSRV_FETCH_ASSOC)) {
            $productNames[$product['Product_ID']] = $product['Product_Name'];
        }
    }

    $serialParts = [];
    if (!empty($allSNs)) {
        $placeholders = implode(',', array_fill(0, count($allSNs), '?'));
        $query_sn = "SELECT item, PartNo FROM [ASF_VIEW].dbo.vw_SerialServiceJob WHERE CAST(item AS VARCHAR) IN ($placeholders)";
        $stmt_sn = sqlsrv_query($connection_ASF_VIEW, $query_sn, $allSNs);
        while ($sn = sqlsrv_fetch_array($stmt_sn, SQLSRV_FETCH_ASSOC)) {
            $serialParts[$sn['item']] = $sn['PartNo'];
        }
    }

    // foreach ($data as &$jobs) {
    //     foreach ($jobs as &$row) {
    //         $row['products'] = array_map(fn($id) => $productNames[$id] ?? '', $row['product_ids']);
    //         if (!empty($row['sn_no'])) {
    //             $row['serial_info'] = [$serialParts[$row['sn_no']] ?? ''];
    //         }
    //     }
    // }

    foreach ($data as &$row) {
        $row['products'] = array_map(function ($id) use ($productNames) {
            return isset($productNames[$id]) ? $productNames[$id] : '';
        }, $row['product_ids']);
        if (!empty($row['sn_no'])) {
            $row['serial_info'] = [$serialParts[$row['sn_no']] ?? ''];
        }
    }

    echo json_encode(["data" => $data, "success" => true]);
});

$route->add('/Report_Api2', function () { {
        ob_clean();
        header_remove();
        header("Content-type: application/json; charset=utf-8");

        global $konnext_DB64;


        $jobNo = isset($_POST['jobNo']) ? $_POST['jobNo'] : '';
        $startDate = isset($_POST['startDate']) ? $_POST['startDate'] : '';
        $endDate = isset($_POST['endDate']) ? $_POST['endDate'] : '';

        if ($startDate) {
            $startDate = date('Y-m-d', strtotime($startDate));
        }
        if ($endDate) {
            $endDate = date('Y-m-d', strtotime($endDate));
        }

        if (!is_array($jobNo) && $jobNo != '') {
            $jobNo = explode(',', $jobNo);
        }

        $sql = "SELECT
            CR_no,
            doc_no,
            jobno,
            wa_no,
            doc_status,
            project_name,
            details,
            job_remark,
            expenses,
            expensesummary
        FROM CR_ChangeForm 
        WHERE doc_no <> ''
        ";
        $params = [];

        // กรองวันที่ (ถ้ามีค่า startDate และ endDate)
        // if ($startDate && $endDate) {
        //     $sql .= " AND [dateCreate] BETWEEN '" . $startDate . " 00:00:00' AND '" . $endDate . " 23:59:59'";
        // }

        if (!empty($jobNo)) {
            $placeholders = implode(',', array_fill(0, count($jobNo), '?'));
            $sql .= " AND jobno IN ($placeholders)";
            $params = array_merge($params, $jobNo);
        }

        $sql .= "
        ORDER BY 
            CASE 
                WHEN [doc_no] LIKE 'CHI-%' THEN 1 
                WHEN [doc_no] LIKE 'CHO-%' THEN 2 
                ELSE 3 
            END,
        CAST(RIGHT([doc_no], CHARINDEX('-', REVERSE([doc_no])) - 1) AS INT);
        ";

        $stmt = sqlsrv_query($konnext_DB64, $sql, $params);
        if ($stmt === false) {
            die(json_encode(["error" => sqlsrv_errors()], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
        }

        $allData = [];
        $startDate = strtotime($_POST['startDate'] . " 00:00:00");
        $endDate = strtotime($_POST['endDate'] . " 23:59:59");


        while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
            $DateNew = Get_Time_Status($row['CR_no'], 'Close')['Status_Date'];
            $row['Status_Date'] = $DateNew;

            $DateNe = strtotime($row['Status_Date']);
            if ($DateNe >= $startDate && $DateNe <= $endDate) {
                $row['__timestamp'] = $DateNe;
                $allData[] = $row;
            }
        }

        usort($allData, function ($a, $b) {
            return $a['__timestamp'] <=> $b['__timestamp'];
        });

        $data = array_map(function ($item) {
            unset($item['__timestamp']);
            return $item;
        }, $allData);

        echo json_encode(['data' => $data], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

        sqlsrv_close($konnext_DB64);
    }
});

$route->add('/Report_Api2_Edit', function () {
    ob_clean();
    header_remove();
    header("Content-type: application/json; charset=utf-8");

    global $konnext_DB64;

    $jobNo = isset($_POST['jobNo']) ? $_POST['jobNo'] : '';
    $startDate = $_POST['startDate'] != '' ? $_POST['startDate'] . ' 00:00:00' : '';
    $endDate = $_POST['endDate'] != '' ? $_POST['endDate'] . ' 23:59:59' : '';

    if (!is_array($jobNo) && $jobNo != '') {
        $jobNo = explode(',', $jobNo);
    }

    $jobNoList = '';
    if (!empty($jobNo)) {
        $quoted = array_map(function ($item) {
            return "'" . str_replace("'", "''", trim($item)) . "'";
        }, $jobNo);
        $jobNoList = implode(',', $quoted);
    }

    $sql = "
        SELECT
            cc.CR_no,
            cc.doc_no,
            cc.jobno,
            cc.wa_no,
            cc.doc_status,
            cc.project_name,
            cc.details,
            cc.job_remark,
            cc.expenses,
            cc.expensesummary,
            cs.Status_Date
        FROM CR_ChangeForm cc
        LEFT JOIN (
            SELECT CR_no, MIN(Status_Date) AS MaxStatusDate
            FROM CR_StatusList
            WHERE Status_Name = 'Close'
            GROUP BY CR_no
        ) latest_status ON cc.CR_no = latest_status.CR_no
        LEFT JOIN CR_StatusList cs
            ON cc.CR_no = cs.CR_no AND cs.Status_Date = latest_status.MaxStatusDate
        WHERE cc.doc_no <> ''
    ";

    if ($startDate != '' && $endDate != '') {
        $sql .= " AND (cs.Status_Date >= '$startDate' AND cs.Status_Date <= '$endDate')";
    }

    if ($jobNoList !== '') {
        $sql .= " AND cc.jobno IN ($jobNoList)";
    }

    $sql .= "
        ORDER BY 
            CASE 
                WHEN [doc_no] LIKE 'CHI-%' THEN 1 
                WHEN [doc_no] LIKE 'CHO-%' THEN 2 
                ELSE 3 
            END,
        CAST(RIGHT([doc_no], CHARINDEX('-', REVERSE([doc_no])) - 1) AS INT);
    ";

    $stmt = sqlsrv_query($konnext_DB64, $sql);
    if ($stmt === false) {
        die(json_encode(["error" => sqlsrv_errors()], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    }

    $data = [];
    while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
        $data[] = $row;
    }

    echo json_encode(['data' => $data], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

    sqlsrv_close($konnext_DB64);
});

$route->add('/ReportStatusApi', function () { {
        ob_clean();
        header_remove();
        header("Content-type: application/json; charset=utf-8");

        global $konnext_DB64;

        $status_select = $_POST['status_select'] ?? [];
        $team_select = $_POST['team_select'] ?? [];
        $date_start = $_POST['date_start'] ?? '';
        $date_end = $_POST['date_end'] ?? '';


        $sql = "
            SELECT 
                CC.[CR_no]
                ,CC.[doc_type]
                ,CC.[jobno]
                ,CC.[project_name]
                ,CC.[sales_name]
                ,CC.[doc_status]
                ,CC.[userCreate]
                ,CA.[CR_Approve1]
                ,CA.[CR_Approve2]
                ,CA.[CR_Approve3]
                ,CA.[CR_Approve4]
                ,CA.[CR_Approve5]
            FROM CR_ChangeForm CC
            LEFT JOIN [ITService].[dbo].[CR_Approve] CA
            ON CC.[CR_no] = CA.[CR_no]
            WHERE (CC.[doc_status] <> 'Close' AND CC.[doc_status] <> 'Savedraft')
        ";

        if (!empty($status_select)) {
            $escaped = array_map(function ($status) {
                return "'" . addslashes($status) . "'";
            }, $status_select);

            $sql .= " AND CC.[doc_status] IN (" . implode(",", $escaped) . ")";
        }

        if (!empty($team_select)) {
            $likeConditions = array_map(function ($team) {
                $safeValue = str_replace("'", "''", $team);
                return "CC.[sales_name] LIKE '%" . $safeValue . "%'";
            }, $team_select);
            $sql .= " AND (" . implode(" OR ", $likeConditions) . ")";
        }

        if (!empty($date_start) && !empty($date_end)) {
            $sql .= " AND (CC.[dateCreate] BETWEEN '" . $date_start . " 00:00:00' AND '" . $date_end . " 23:59:59')";
        }

        $sql .= " 
        ORDER BY 
            CASE 
                WHEN CC.[CR_no] LIKE 'CHI-%' THEN 1 
                WHEN CC.[CR_no] LIKE 'CHO-%' THEN 2 
                ELSE 3 
            END,
        CAST(RIGHT(CC.[CR_no], CHARINDEX('-', REVERSE(CC.[CR_no])) - 1) AS INT);
        ";

        $query = sqlsrv_query($konnext_DB64, $sql);
        while ($row = sqlsrv_fetch_array($query, SQLSRV_FETCH_ASSOC)) {
            $apporve_arr_1 = json_decode($row['CR_Approve1'], true);
            $apporve_arr_2 = json_decode($row['CR_Approve2'], true);
            $apporve_arr_3 = json_decode($row['CR_Approve3'], true);
            $apporve_arr_4 = json_decode($row['CR_Approve4'], true);
            $apporve_arr_5 = json_decode($row['CR_Approve5'], true);

            if ($row['doc_status'] == 'New') {
                $wait = 'Admin TC';
            } elseif ($row['doc_status'] == 'Draf') {
                $wait = 'Admin TC';
            } elseif ($row['doc_status'] == 'Rework') {
                $wait = mydata($row['userCreate'])['FirstName'];
            } elseif (($row['doc_status'] == 'Review')) {
                $wait = mydata($apporve_arr_1['Approve_1'])['FirstName'];
            } elseif (($row['doc_status'] == 'Check')) {
                $wait = mydata($apporve_arr_2['Approve_2'])['FirstName'];
            } elseif (($row['doc_status'] == 'Recheck')) {
                $wait = mydata($apporve_arr_3['Approve_3'])['FirstName'];
            } elseif (($row['doc_status'] == 'Approve') && $apporve_arr_4['status_approve'] == '0') {
                $wait = mydata($apporve_arr_4['Approve_4'])['FirstName'];
            } elseif ($row['doc_status'] == 'Approve' && $apporve_arr_5 != null && $apporve_arr_5['Approve_5'] != '-' && $apporve_arr_5['Approve_5'] != '') {
                $wait = mydata($apporve_arr_5['Approve_5'])['FirstName'];
            } elseif ($row['doc_status'] == 'Not Approve') {
                $wait = mydata($row['userCreate'])['FirstName'];
            }
            $row = array_merge($row, ['wait' => $wait ?? '-']);
            $data[] = $row;
        }

        echo json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    }
});

$route->add('/AddCause_name', function () { {
        ob_clean();
        header_remove();
        header("Content-type: application/json; charset=utf-8");

        $auth = $_POST['auth'];
        $cause_name = $_POST['cause_name'];
        $cause_type = $_POST['cause_type'];

        echo AddCause($auth, $cause_name, $cause_type);
    }
});

$route->add('/AddDivision', function () { {
        ob_clean();
        header_remove();
        header("Content-type: application/json; charset=utf-8");

        $auth = $_POST['auth'];
        $division_id = $_POST['division_id'];
        $division_name = $_POST['division_name'];

        echo AddDivision($auth, $division_id, $division_name);
    }
});

$route->add('/SelectProduct', function () { {
        ob_clean();
        header_remove();
        header("Content-type: application/json; charset=utf-8");

        $auth = $_POST['auth'];
        $Product_ID = $_POST['Product_ID'];

        echo SelectProduct($Product_ID);
    }
});

$route->add('/SelectCause', function () { {
        ob_clean();
        header_remove();
        header("Content-type: application/json; charset=utf-8");

        $auth = $_POST['auth'];
        $Cause_ID = $_POST['Cause_ID'];

        echo SelectCause($Cause_ID);
    }
});

$route->add('/SelectDivision', function () { {
        ob_clean();
        header_remove();
        header("Content-type: application/json; charset=utf-8");

        $auth = $_POST['auth'];
        $Division_ID = $_POST['Division_ID'];

        echo SelectDivision($Division_ID);
    }
});

$route->add('/DelProduct', function () { {
        ob_clean();
        header_remove();
        header("Content-type: application/json; charset=utf-8");

        $auth = $_POST['auth'];
        $Product_ID = $_POST['Product_ID'];

        echo DelProduct($auth, $Product_ID);
    }
});

$route->add('/DelCause', function () { {
        ob_clean();
        header_remove();
        header("Content-type: application/json; charset=utf-8");

        $auth = $_POST['auth'];
        $Cause_ID = $_POST['Cause_ID'];

        echo DelCause($auth, $Cause_ID);
    }
});

$route->add('/DelDivision', function () { {
        ob_clean();
        header_remove();
        header("Content-type: application/json; charset=utf-8");

        $auth = $_POST['auth'];
        $Division_ID = $_POST['Division_ID'];

        echo DelDivision($auth, $Division_ID);
    }
});

$route->add('/EditProduct', function () { {
        ob_clean();
        header_remove();
        header("Content-type: application/json; charset=utf-8");

        $auth = $_POST['auth'];
        $product_name = $_POST['product_name'];
        $Product_ID = $_POST['product_id'];

        echo EditProduct($auth, $Product_ID, $product_name);
    }
});

$route->add('/EditCause', function () { {
        ob_clean();
        header_remove();
        header("Content-type: application/json; charset=utf-8");

        $auth = $_POST['auth'];
        $cause_name = $_POST['cause_name'];
        $cause_type = $_POST['cause_type'];
        $cause_id = $_POST['cause_id'];

        echo EditCause($auth, $cause_id, $cause_name, $cause_type);
    }
});

$route->add('/EditDivision', function () { {
        ob_clean();
        header_remove();
        header("Content-type: application/json; charset=utf-8");

        $auth = $_POST['auth'];
        $division_name = $_POST['division_name'];
        $division_ID = $_POST['division_ID'];
        $division_code = $_POST['division_code'];

        echo EditDivision($auth, $division_code, $division_name, $division_ID);
    }
});

$route->add('/searchjob', function () { {
        global $konnext_DB64;
        global $connection_ASF_VIEW;

        if (isset($_POST['jobno']) && $_POST['action'] == 'searchjob') {
            $jobno = $_POST['jobno'];

            $sql_job = "
                SELECT 
                        Used_For	AS JobName,
                        cust.Thai_Name AS CustomerName,
                        SAL.[Name] + ' ' + SAL.Surname AS SaleName,
                        FORMAT(ts.Other, 'F2') AS Other,
                        FORMAT(ts.TotalPrice, 'F2') AS Cost,
                        FORMAT(ts.Bill_Amount, 'F2') AS BillAmount
                FROM  [cd-XPSQL-ASF7].dbo.Transection1		ts
                LEFT OUTER JOIN [cd-XPSQL-ASF7].dbo.Contact		cust	ON cust.Contact_ID		=		ts.Customer_ID
                LEFT OUTER JOIN [cd-XPSQL-ASF7].dbo.Man_Info	SAL		ON	SAL.ManID			=		ts.MAN_ID
                WHERE	ts.Doc_Type	=	'DO'
                AND		ts.Del		<>	'Y'
                AND		ts.Doc_No	= '" . $jobno . "'
                GROUP BY Used_For, cust.Thai_Name, SAL.[Name] + ' ' + SAL.Surname, ts.Other, ts.TotalPrice, ts.Bill_Amount
            ";

            // echo  $sql_job;
            // $params = array($jobno);
            $query = sqlsrv_query($konnext_DB64, $sql_job);

            if ($query === false) {
                echo json_encode(['error' => 'Query failed']);
                exit();
            }

            $row = sqlsrv_fetch_array($query, SQLSRV_FETCH_ASSOC);

            if ($row) {
                $datajob = [
                    'JobName' => $row['JobName'],
                    'CustomerName' => $row['CustomerName'],
                    'SaleName' => $row['SaleName'],
                    'Other' => $row['Other'],
                    'Cost' => $row['Cost'],
                    'BillAmount' => $row['BillAmount']
                ];
            } else {
                $datajob = ['error' => 'No data found'];
            }

            echo json_encode($datajob, true);
            exit;
        }

        if (isset($_POST['jobno']) && $_POST['action'] == 'search') {
            $jobno = $_POST['jobno'];

            $sql = "
                SELECT Doc_No, Lot_No
                FROM [cd-XPSQL-ASF7].dbo.Transection
                WHERE Doc_Type = 'WA'
                AND PO_No = ?
                GROUP BY Doc_No, Lot_No
            ";

            $stmt = sqlsrv_query($konnext_DB64, $sql, array($jobno));

            if ($stmt === false) {
                echo json_encode(['error' => 'Query failed']);
                exit();
            }

            $data = [];
            $tc_data = [];
            while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                $data[] = $row['Doc_No'];
                if ($row['Lot_No'] != '') {
                    if (!in_array($row['Lot_No'], $tc_data)) {
                        $tc_data[] = $row['Lot_No'];
                    }
                }
            }

            echo json_encode(['Doc_No' => $data, 'tc_name' => $tc_data], true);
            exit;
        }

        if (isset($_POST['jobno']) && $_POST['action'] == 'searchsn') {
            $jobno = $_POST['jobno'];

            // $sqlsn = "
            //     SELECT PartNo, item
            //     FROM [cd-XPSQL-ASF7].dbo.Transection1_Sub
            //     WHERE Doc_Type = 'DO'
            //     AND Doc_No = ?
            // ";
            $sqlsn = "
                SELECT PartNo, MAX(item) AS item
                FROM [ASF_VIEW].dbo.vw_SerialServiceJob
                WHERE job_no = ?
                GROUP BY PartNo
            ";

            $stmtsn = sqlsrv_query($connection_ASF_VIEW, $sqlsn, array($jobno));

            if ($stmtsn === false) {
                echo json_encode(['error' => 'Query failed']);
                exit();
            }

            $options = '<option value="All">เลือกเลข S/N ทั้งหมด</option>';
            while ($row = sqlsrv_fetch_array($stmtsn, SQLSRV_FETCH_ASSOC)) {
                $options .= '<option value="' . ($row['item']) . '">' . ($row['PartNo']) . '</option>';
            }
            // $options .= '<option value="-">-</option>';

            echo $options;
            exit;
        }
    }
});

$route->add('/searchjobvisit', function () { {
        global $konnext_DB64;
        global $connection_ASF_VIEW;

        if (isset($_POST['jobno']) && $_POST['action'] == 'searchjob') {
            $jobno = $_POST['jobno'];

            $sql_job = "
                SELECT 
                        Used_For	AS JobName,
                        cust.Thai_Name AS CustomerName,
                        SAL.[Name] + ' ' + SAL.Surname AS SaleName,
                        SAL.ManID AS SaleID,
                        FORMAT(ts.Other, 'F2') AS Other,
                        FORMAT(ts.TotalPrice, 'F2') AS Cost,
                        ts.Users,
                        SAL.Dep_ID
                FROM  [cd-XPSQL-ASF7].dbo.Transection1		ts
                LEFT OUTER JOIN [cd-XPSQL-ASF7].dbo.Contact		cust	ON cust.Contact_ID		=		ts.Customer_ID
                LEFT OUTER JOIN [cd-XPSQL-ASF7].dbo.Man_Info	SAL		ON	SAL.ManID			=		ts.MAN_ID
                WHERE	ts.Doc_Type	=	'DO'
                AND		ts.Del		<>	'Y'
                AND		ts.Doc_No	= '" . $jobno . "'
                GROUP BY Used_For, cust.Thai_Name, SAL.[Name] + ' ' + SAL.Surname, SAL.ManID, ts.Other, SAL.Dep_ID, ts.TotalPrice, ts.Users
            ";

            // echo  $sql_job;
            // $params = array($jobno);
            $query = sqlsrv_query($konnext_DB64, $sql_job);

            if ($query === false) {
                echo json_encode(['error' => 'Query failed']);
                exit();
            }

            $row = sqlsrv_fetch_array($query, SQLSRV_FETCH_ASSOC);

            if ($row) {
                $datajob = $row;
            } else {
                $datajob = ['error' => 'No data found'];
            }

            echo json_encode($datajob, true);
            exit;
        }

        if (isset($_POST['jobno']) && $_POST['action'] == 'search') {
            $jobno = $_POST['jobno'];

            $sql = "
                SELECT Doc_No, Lot_No
                FROM [cd-XPSQL-ASF7].dbo.Transection
                WHERE Doc_Type = 'WA'
                AND PO_No = ?
                GROUP BY Doc_No, Lot_No
            ";

            $stmt = sqlsrv_query($konnext_DB64, $sql, array($jobno));

            if ($stmt === false) {
                echo json_encode(['error' => 'Query failed']);
                exit();
            }

            $data = [];
            $tc_data = [];
            while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                $data[] = $row['Doc_No'];
                if ($row['Lot_No'] != '') {
                    if (!in_array($row['Lot_No'], $tc_data)) {
                        $tc_data[] = $row['Lot_No'];
                    }
                }
            }

            echo json_encode(['Doc_No' => $data, 'tc_name' => $tc_data], true);
            exit;
        }

        if (isset($_POST['jobno']) && $_POST['action'] == 'searchsn') {
            $jobno = $_POST['jobno'];

            // $sqlsn = "
            //     SELECT PartNo, item
            //     FROM [cd-XPSQL-ASF7].dbo.Transection1_Sub
            //     WHERE Doc_Type = 'DO'
            //     AND Doc_No = ?
            // ";
            $sqlsn = "
                SELECT PartNo, MAX(item) AS item
                FROM [ASF_VIEW].dbo.vw_SerialServiceJob
                WHERE job_no = ?
                GROUP BY PartNo
            ";

            $stmtsn = sqlsrv_query($connection_ASF_VIEW, $sqlsn, array($jobno));

            if ($stmtsn === false) {
                echo json_encode(['error' => 'Query failed']);
                exit();
            }

            $options = '<option value="All">เลือกเลข S/N ทั้งหมด</option>';
            while ($row = sqlsrv_fetch_array($stmtsn, SQLSRV_FETCH_ASSOC)) {
                $options .= '<option value="' . ($row['item']) . '">' . ($row['PartNo']) . '</option>';
            }
            $options .= '<option value="-">-</option>';

            echo $options;
            exit;
        }
    }
});


$route->add('/insertcr', function () { {
        global $konnext_DB64;
        // ob_clean();
        // header_remove();
        // header("Content-type: application/json; charset=utf-8");

        $data_admin = [
            // "660500122",
            // "540411127",
            '641200170',
            '640500080',
            '670100002',
            '660700186',
            '500811071'
        ];

        $jobtype = $_POST['jobtype'] ?? '';
        $user = $_POST['user'] ?? '';
        $jobno = strtoupper($_POST['jobno']) ?? '';
        $wa = $_POST['wa'] ?? '';
        $sn = $_POST['sn'] ?? '';
        $projects = $_POST['projects'] ?? '';
        $jobsale = $_POST['jobsale'] ?? '';
        $jobcus = $_POST['jobcus'] ?? '';
        $rev = $_POST['rev'] ?? '';
        $ncr_no = $_POST['ncr_no'] ?? '';
        $details = $_POST['details'] ?? '';
        $tc_name = $_POST['tc_name'] ?? '';

        $selectedProducts = $_POST['selectedProducts'] ?? '';
        $statusjob = $_POST['statusjob'] ?? '';
        $statusproduct = $_POST['statusproduct'] ?? '';
        $JsonData = $_POST['JsonData'] ?? '';
        $sellData = $_POST['sellData'] ?? '';
        $diviselect = $_POST['diviselect'] ?? '';

        $cost = $_POST['cost'] ?? '';
        $effect = $_POST['effect'] ?? '';
        $expenses = $_POST['expenses'] ?? '';
        $expenses_total = $_POST['expenses_total'] ?? '';

        $datetype = $_POST['datetype'] ?? '';
        $urgentwork = $_POST['urgentwork'] ?? '';

        $files = $_FILES['file_upload'] ?? '';

        $CRNo = generateCRNo();

        if ($user == '') {
            echo json_encode(['status' => false, 'message' => 'กรุณาเข้าสู่ระบบใหม่อีกครั้ง'], true);
            exit;
        }

        $sql = "
        INSERT INTO CR_ChangeForm (
            CR_no, doc_type, doc_no, jobno, wa_no, sn_no,
            project_name, sales_name, customer_name, tc_name,
            product, status_job, status_product, data_detail,
            cost, job_remark, expenses, expenses_total,
            expensesummary, related, doc_status, userCreate,
            dateCreate, userEdit, dateEdit, reject_note,
            rev, ncr_no, details, urgentwork
        )
        VALUES (
            ?, ?, ?, ?, ?, ?,
            ?, ?, ?, ?,
            ?, ?, ?, ?,
            ?, ?, ?, ?,
            ?, ?, ?, ?,
            ?, ?, ?, ?,
            ?, ?, ?, ?
        )
        ";

        $params = [
            $CRNo,
            $jobtype,
            '',
            $jobno,
            $wa,
            $sn,
            $projects,
            $jobsale,
            $jobcus,
            $tc_name,
            $selectedProducts,
            $statusjob,
            $statusproduct,
            $JsonData,
            $cost,
            $effect,
            $expenses,
            $expenses_total,
            $sellData,
            $diviselect,
            $datetype,
            $user,
            date("Y-m-d H:i:s"),
            $user,
            date("Y-m-d H:i:s"),
            '',
            $rev,
            $ncr_no,
            $details,
            $urgentwork
        ];

        $query  =    sqlsrv_query($konnext_DB64, $sql, $params);
        update_list_status($CRNo, $datetype, $_SESSION['ChangeRequest_code']);

        if ($query) {
            if (!empty($_FILES['file_upload']['name'][0])) {
                $uploadDir = './file/';
                $allSuccess = true;

                for ($i = 0; $i < count($_FILES['file_upload']['name']); $i++) {
                    // สร้าง single file array สำหรับ validation
                    $singleFile = [
                        'name' => $_FILES['file_upload']['name'][$i],
                        'tmp_name' => $_FILES['file_upload']['tmp_name'][$i],
                        'size' => $_FILES['file_upload']['size'][$i],
                        'error' => $_FILES['file_upload']['error'][$i]
                    ];

                    // ตรวจสอบความปลอดภัยของไฟล์
                    $validation = validateUploadedFile($singleFile);
                    if (!$validation['valid']) {
                        echo json_encode(['status' => false, 'error' => $validation['error']], JSON_UNESCAPED_UNICODE);
                        exit;
                    }

                    $fileName = $singleFile['name'];
                    $fileExtension = $validation['extension'];
                    $newFileName = $CRNo . '_' . date('Ymd_His') . "_{$i}." . $fileExtension;
                    $filePath = $uploadDir . $newFileName;

                    if (move_uploaded_file($singleFile['tmp_name'], $filePath)) {
                        $insertFileSql = "
                        INSERT INTO CR_Files (CR_no, path_file, name_files)
                        VALUES ('" . $CRNo . "', '" . $filePath . "', '" . addslashes($fileName) . "')
                    ";
                        $fileQuery = sqlsrv_query($konnext_DB64, $insertFileSql);
                        $errors = sqlsrv_errors();

                        if ($errors || !$fileQuery) {
                            // print_r($errors);
                            $allSuccess = false;
                            echo json_encode(['status' => false, 'error' => $errors], true);
                        }
                    } else {
                        $allSuccess = false;
                        echo json_encode(['status' => false, 'error' => 'ไม่สามารถบันทึกไฟล์ได้'], JSON_UNESCAPED_UNICODE);
                    }
                }

                if ($datetype == 'New') {
                    if ($allSuccess) {
                        // $titelnoti = "แจ้งเตือนขอเปลี่ยนแปลง (" . $CRNo . ")";
                        // $message = mydata($user)['FullName'] . "\nสร้างเอกสารขอเปลี่ยนแปลง" . "\nเมื่อ " . date("Y-m-d H:i:s");
                        // foreach ($data_admin as $key => $usernoti) {
                        //     $token_noti = mydata($usernoti)['TokenMD5'];
                        //     $url = "https://innovation.asefa.co.th/ChangeRequestForm/ViewForm?token=" . $token_noti . "&CR_no=" . $CRNo;
                        //     Notify($titelnoti, $message, $usernoti, $url);
                        // }
                        echo json_encode(['status' => true], true);
                    } else {
                        echo json_encode(['status' => false], true);
                    }
                } else {
                    echo json_encode(['status' => true], true);
                }
            } else {
                echo json_encode(['status' => true], true);
            }
        } else {
            echo json_encode(['status' => false, 'error' => sqlsrv_errors()], true);
        }
    }
});

$route->add('/delfile', function () { {
        // ob_clean();
        // header_remove();
        // header("Content-type: application/json; charset=utf-8");

        global $konnext_DB64;

        $file_id = $_POST['file_id'];
        $filename = $_POST['filename'];

        $sql = "
        DELETE FROM CR_Files
        WHERE file_id = '" . $file_id . "'
    ";
        $query  =    sqlsrv_query($konnext_DB64, $sql);
        if ($query) {
            if (unlink("./file/" . $filename)) {
                echo json_encode(['status' => true], true);
            } else {
                echo json_encode(['status' => false], true);
            }
        } else {
            echo json_encode(['status' => false], true);
        }
    }
});

$route->add('/changestatus', function () { {
        // ob_clean();
        // header_remove();
        // header("Content-type: application/json; charset=utf-8");

        global $konnext_DB64;

        $cr_no = $_POST['cr_no'];
        $status = $_POST['status'];
        $doc_type = $_POST['doc_type'];
        $rework_note = $_POST['rework_note'];
        $cancel_note = $_POST['cancel_note'];
        $cr_approve = $_POST['cr_approve'];

        $approveSelect_1 = $_POST['approveSelect_1'] ?? '';
        $approveSelect_2 = $_POST['approveSelect_2'] ?? '';
        $approveSelect_3 = $_POST['approveSelect_3'] ?? '';
        $approveSelect_4 = $_POST['approveSelect_4'] ?? '';
        $approveSelect_5 = $_POST['approveSelect_5'] ?? '';

        if ($cr_approve == '0') {
            delete_approve($cr_no);

            $sqll = "
            INSERT INTO CR_Approve
            (
                [CR_no]
                ,[CR_Approve1]
                ,[CR_Approve2]
                ,[CR_Approve3]
                ,[CR_Approve4]
                ,[CR_Approve5]
            )
            VALUES
            (
                '" . $cr_no . "'
                , '" . json_encode(['Approve_1' => $approveSelect_1, 'status_approve' => '0', 'date_approve' => '']) . "'
                , '" . json_encode(['Approve_2' => $approveSelect_2, 'status_approve' => '0', 'date_approve' => '']) . "'
                , '" . json_encode(['Approve_3' => $approveSelect_3, 'status_approve' => '0', 'date_approve' => '']) . "'
                , '" . json_encode(['Approve_4' => $approveSelect_4, 'status_approve' => '0', 'date_approve' => '']) . "'
                , '" . json_encode(['Approve_5' => $approveSelect_5, 'status_approve' => '0', 'date_approve' => '']) . "'
            )
            ";

            $query  =    sqlsrv_query($konnext_DB64, $sqll);

            // $titelnoti = "แจ้งเตือนขอเปลี่ยนแปลง (" . $cr_no . ")";

            // $token = mydata($approveSelect_1)['TokenMD5'];
            // $message = 'เลขที่เอกสาร ' . $cr_no . "\nส่งคำขอทบทวน" . "\n**กรุณาตรวจสอบอีกครั้ง**";
            // $url = "https://innovation.asefa.co.th/ChangeRequestForm/ViewForm?token=" . $token . "&CR_no=" . $cr_no;
            // Notify($titelnoti, $message, $approveSelect_1, $url);
        }

        if ($status == 'Close') {
            $noti_close_user = [
                '630600043',
                '601100172',
            ];

            $DocNo = generateDocNo($doc_type);
            if ($cr_no == 'CR-6902339') {
                $DocNo = 'CHO-69020208';
            } else if ($cr_no == 'CR-6902343') {
                $DocNo = 'CHI-69020155';
            } else if ($cr_no == 'CR-6902334') {
                $DocNo = 'CHI-69020156';
            } else if ($cr_no == 'CR-6902333') {
                $DocNo = 'CHI-69020157';
            } else if ($cr_no == 'CR-6902344') {
                $DocNo = 'CHO-69020210';
            } else if ($cr_no == 'CR-6902345') {
                $DocNo = 'CHO-69020209';
            } else if ($cr_no == 'CR-6902347') {
                $DocNo = 'CHO-69020211';
            } else if ($cr_no == 'CR-6902346') {
                $DocNo = 'CHO-69020207';
            } else if ($cr_no == 'CR-6902354') {
                $DocNo = 'CHI-69020158';
            } else if ($cr_no == 'CR-6902357') {
                $DocNo = 'CHO-69020212';
            } else if ($cr_no == 'CR-6902336') {
                $DocNo = 'CHI-69020159';
            } else if ($cr_no == 'CR-6902358') {
                $DocNo = 'CHI-69020154';
            } else if ($cr_no == 'CR-6902316') {
                $DocNo = 'CHI-69020160';
            } else if ($cr_no == 'CR-6902363') {
                $DocNo = 'CHI-69020166';
            } else if ($cr_no == 'CR-6902336') {
                $DocNo = 'CHI-69020159';
            }
            // else {
            //     $DocNo = generateDocNo($doc_type);
            // }
            $sql = "
            UPDATE CR_ChangeForm
            SET 
                doc_no      = '" . $DocNo . "',
                doc_status  = '" . $status . "'
            WHERE CR_no = '" . $cr_no . "'
            ";
            $query  =    sqlsrv_query($konnext_DB64, $sql);
            update_list_status($cr_no, $status, $_SESSION['ChangeRequest_code']);

            $titelnoti = 'แจ้งเตือนขอเปลี่ยนแปลง (' . $cr_no . ')';
            $message = 'เลขที่เอกสาร ' . $cr_no . "\nได้ทำการปิดเอกสารเรียบร้อยแล้ว" . "\n**กรุณาตรวจสอบอีกครั้ง**";

            if ($status == 'Not Approve') {
                $status_approve = '2';
            } else {
                $status_approve = '1';
            }

            if ($cr_approve == '2') {
                Update_Status_Approve($cr_no, $approveSelect_2, 'Approve_2', $status_approve);
            } elseif ($cr_approve == '4') {
                Update_Status_Approve($cr_no, $approveSelect_4, 'Approve_4', $status_approve);
            } elseif ($cr_approve == '5') {
                Update_Status_Approve($cr_no, $approveSelect_5, 'Approve_5', $status_approve);
            }

            if ($query) {

                // foreach ($noti_close_user as $key => $usernoti) {
                //     $token = mydata($usernoti)['TokenMD5'];
                //     $url = "https://innovation.asefa.co.th/ChangeRequestForm/ViewForm?token=" . $token . "&CR_no=" . $cr_no;

                //     Notify($titelnoti, $message, $usernoti, $url);
                // }

                echo json_encode(['status' => true, 'status_cr' => $status, 'doc_no' => $DocNo, 'cr_approve' => $cr_approve], true);
            } else {
                echo json_encode(['status' => false, 'status_cr' => $status, 'doc_no' => $DocNo, 'error' => sqlsrv_errors()], true);
            }
        } else {
            $sql = "
            UPDATE CR_ChangeForm
            SET  
                doc_status  = '" . $status . "',
                reject_note = '" . $rework_note . "',
                cancel_remark = '" . $cancel_note . "'
            WHERE CR_no = '" . $cr_no . "'
            ";
            $query  =    sqlsrv_query($konnext_DB64, $sql);
            update_list_status($cr_no, $status, $_SESSION['ChangeRequest_code']);

            if ($query) {
                if ($status == 'Not Approve') {
                    $status_approve = '2';
                } else {
                    $status_approve = '1';
                }

                // $titelnoti = "แจ้งเตือนขอเปลี่ยนแปลง (" . $cr_no . ")";

                if ($cr_approve == '1') {
                    Update_Status_Approve($cr_no, $approveSelect_1, 'Approve_1', $status_approve);
                    //     $token = mydata($approveSelect_2)['TokenMD5'];
                    //     $message = 'เลขที่เอกสาร ' . $cr_no . "\nเทคนิคได้ทำการตรวจสอบเรียบร้อยแล้ว" . "\n**กรุณาตรวจสอบอีกครั้ง**";
                    //     $url = "https://innovation.asefa.co.th/ChangeRequestForm/ViewForm?token=" . $token . "&CR_no=" . $cr_no;
                    //     Notify($titelnoti, $message, $approveSelect_2, $url);
                } elseif ($cr_approve == '2') {
                    Update_Status_Approve($cr_no, $approveSelect_2, 'Approve_2', $status_approve);
                    //     $token = mydata($approveSelect_3)['TokenMD5'];
                    //     $message = 'เลขที่เอกสาร ' . $cr_no . "\nผจก.เทคนิคได้ทำการตรวจสอบเรียบร้อยแล้ว" . "\n**กรุณาตรวจสอบอีกครั้งก่อนอนุมัติ**";
                    //     $url = "https://innovation.asefa.co.th/ChangeRequestForm/ViewForm?token=" . $token . "&CR_no=" . $cr_no;
                    //     Notify($titelnoti, $message, $approveSelect_3, $url);
                } elseif ($cr_approve == '3') {
                    Update_Status_Approve($cr_no, $approveSelect_3, 'Approve_3', $status_approve);
                    //     $token = mydata($approveSelect_4)['TokenMD5'];
                    //     $message = 'เลขที่เอกสาร ' . $cr_no . "\nขายได้ทำการตรวจสอบเรียบร้อยแล้ว" . "\n**กรุณาตรวจสอบอีกครั้งก่อนอนุมัติ**";
                    //     $url = "https://innovation.asefa.co.th/ChangeRequestForm/ViewForm?token=" . $token . "&CR_no=" . $cr_no;
                    //     Notify($titelnoti, $message, $approveSelect_4, $url);
                } elseif ($cr_approve == '4' && ($approveSelect_5 != '-' || $approveSelect_5 != '')) {
                    Update_Status_Approve($cr_no, $approveSelect_4, 'Approve_4', $status_approve);
                    //     $token = mydata($approveSelect_5)['TokenMD5'];
                    //     $message = 'เลขที่เอกสาร ' . $cr_no . "\nผจก.ขายได้ทำการตรวจสอบเรียบร้อยแล้ว" . "\n**กรุณาตรวจสอบอีกครั้งก่อนอนุมัติ**";
                    //     $url = "https://innovation.asefa.co.th/ChangeRequestForm/ViewForm?token=" . $token . "&CR_no=" . $cr_no;
                    //     Notify($titelnoti, $message, $approveSelect_5, $url);
                }
                echo json_encode(['status' => true, 'status_cr' => $status, 'cr_approve' => $cr_approve], true);
            } else {
                echo json_encode(['status' => false, 'status_cr' => $status, 'error' => sqlsrv_errors()], JSON_UNESCAPED_UNICODE);
            }
        }
    }
});

$route->add('/editdata', function () { {
        // ob_clean();
        // header_remove();
        // header("Content-type: application/json; charset=utf-8");

        global $konnext_DB64;

        $jobtype = $_POST['jobtype'] ?? '';
        $cr_no = $_POST['cr_no'] ?? '';
        $chi_cho_no = $_POST['chi_cho_no'] ?? '';
        $status = $_POST['status'] ?? '';
        $user = $_POST['user'] ?? '';
        $jobno = $_POST['jobno'] ?? '';
        $wa = $_POST['wa'] ?? '';
        $sn = $_POST['sn'] ?? '';
        $projects = $_POST['projects'] ?? '';
        $jobsale = $_POST['jobsale'] ?? '';
        $jobcus = $_POST['jobcus'] ?? '';
        $rev = $_POST['rev'] ?? '';
        $ncr_no = $_POST['ncr_no'] ?? '';
        $details = $_POST['details'] ?? '';
        $tc_name = $_POST['tc_name'] ?? '';

        $selectedProducts = $_POST['selectedProducts'] ?? '';
        $statusjob = $_POST['statusjob'] ?? '';
        $statusproduct = $_POST['statusproduct'] ?? '';
        $JsonData = $_POST['JsonData'] ?? '';
        $sellData = $_POST['sellData'] ?? '';
        $diviselect = $_POST['diviselect'] ?? '';

        $cost = $_POST['cost'] ?? '';
        $effect = $_POST['effect'] ?? '';
        $expenses = $_POST['expenses'] ?? '';
        $expenses_total = $_POST['expenses_total'] ?? '';

        $cr_approve = $_POST['cr_approve'] ?? '';
        $urgentwork = $_POST['urgentwork'] ?? 0;

        $approveSelect_1 = $_POST['approveSelect_1'] ?? '';
        $approveSelect_2 = $_POST['approveSelect_2'] ?? '';
        $approveSelect_3 = $_POST['approveSelect_3'] ?? '';
        $approveSelect_4 = $_POST['approveSelect_4'] ?? '';
        $approveSelect_5 = $_POST['approveSelect_5'] ?? '';

        $data_admin = [
            // "660500122",
            // "540411127",
            '641200170',
            '640500080',
            '670100002',
            '660700186',
            '500811071'
        ];

        if ($cr_approve == '0') {
            delete_approve($cr_no);

            $sqll = "
            INSERT INTO CR_Approve
            (
                [CR_no]
                ,[CR_Approve1]
                ,[CR_Approve2]
                ,[CR_Approve3]
                ,[CR_Approve4]
                ,[CR_Approve5]
            )
            VALUES
            (
                '" . $cr_no . "'
                , '" . json_encode(['Approve_1' => $approveSelect_1, 'status_approve' => '0', 'date_approve' => '']) . "'
                , '" . json_encode(['Approve_2' => $approveSelect_2, 'status_approve' => '0', 'date_approve' => '']) . "'
                , '" . json_encode(['Approve_3' => $approveSelect_3, 'status_approve' => '0', 'date_approve' => '']) . "'
                , '" . json_encode(['Approve_4' => $approveSelect_4, 'status_approve' => '0', 'date_approve' => '']) . "'
                , '" . json_encode(['Approve_5' => $approveSelect_5, 'status_approve' => '0', 'date_approve' => '']) . "'
            )
        ";

            $query  =    sqlsrv_query($konnext_DB64, $sqll);

            // $titelnoti = "แจ้งเตือนขอเปลี่ยนแปลง (" . $cr_no . ")";

            // $token = mydata($approveSelect_1 == '' || $approveSelect_1 == '-' ? $approveSelect_3 : $approveSelect_1)['TokenMD5'];
            // $message = 'เลขที่เอกสาร ' . $cr_no . "\nส่งคำขอทบทวน" . "\n**กรุณาตรวจสอบอีกครั้ง**";
            // $url = "https://innovation.asefa.co.th/ChangeRequestForm/ViewForm?token=" . $token . "&CR_no=" . $cr_no;
            // Notify($titelnoti, $message, $approveSelect_1, $url);
        }

        $sql = "
            UPDATE CR_ChangeForm SET
                doc_type = ?,
                doc_no = ?,
                jobno = ?,
                wa_no = ?,
                sn_no = ?,
                project_name = ?,
                sales_name = ?,
                customer_name = ?,
                tc_name = ?,
                product = ?,
                status_job = ?,
                status_product = ?,
                data_detail = ?,
                cost = ?,
                job_remark = ?,
                expenses = ?,
                expenses_total = ?,
                expensesummary = ?,
                related = ?,
                doc_status = ?,
                userEdit = ?,
                dateEdit = ?,
                rev = ?,
                ncr_no = ?,
                details = ?,
                urgentwork = ?
            WHERE CR_no = ?
        ";

        $params = [
            $jobtype,
            $chi_cho_no,
            $jobno,
            $wa,
            $sn,
            $projects,
            $jobsale,
            $jobcus,
            $tc_name,
            $selectedProducts,
            $statusjob,
            $statusproduct,
            $JsonData,
            $cost,
            $effect,
            $expenses,
            $expenses_total,
            $sellData,
            $diviselect,
            $status,
            $user,
            date("Y-m-d H:i:s"),
            $rev,
            $ncr_no,
            $details,
            $urgentwork,
            $cr_no
        ];

        $query  =    sqlsrv_query($konnext_DB64, $sql, $params) or die(print_r(sqlsrv_errors()));
        update_list_status($cr_no, $status, $_SESSION['ChangeRequest_code']);
        file_put_contents('sn.log', $cr_no . ' = ' . $sn . "\n", FILE_APPEND);

        if ($query) {
            if (!empty($_FILES['file_upload']['name'][0])) {
                $uploadDir = './file/';
                $allSuccess = true;

                for ($i = 0; $i < count($_FILES['file_upload']['name']); $i++) {
                    // สร้าง single file array สำหรับ validation
                    $singleFile = [
                        'name' => $_FILES['file_upload']['name'][$i],
                        'tmp_name' => $_FILES['file_upload']['tmp_name'][$i],
                        'size' => $_FILES['file_upload']['size'][$i],
                        'error' => $_FILES['file_upload']['error'][$i]
                    ];

                    // ตรวจสอบความปลอดภัยของไฟล์
                    $validation = validateUploadedFile($singleFile);
                    if (!$validation['valid']) {
                        echo json_encode(['status' => false, 'status_cr' => $status, 'error' => $validation['error']], JSON_UNESCAPED_UNICODE);
                        exit;
                    }

                    $fileName = $singleFile['name'];
                    $fileExtension = $validation['extension'];
                    $newFileName = $cr_no . '_' . date('Ymd_His') . "_{$i}." . $fileExtension;
                    $filePath = $uploadDir . $newFileName;

                    if (move_uploaded_file($singleFile['tmp_name'], $filePath)) {
                        $insertFileSql = "INSERT INTO CR_Files (CR_no, path_file, name_files) VALUES (?, ?, ?)";
                        $fileParams = [$cr_no, $filePath, $fileName];
                        $fileQuery = sqlsrv_query($konnext_DB64, $insertFileSql, $fileParams);
                        $errors = sqlsrv_errors();

                        if ($errors || !$fileQuery) {
                            // print_r($errors);
                            $allSuccess = false;
                            echo json_encode(['status' => false, 'status_cr' => $status, 'error' => $errors], true);
                        }
                    } else {
                        $allSuccess = false;
                        echo json_encode(['status' => false, 'status_cr' => $status, 'error' => 'ไม่สามารถบันทึกไฟล์ได้'], JSON_UNESCAPED_UNICODE);
                    }
                }

                if ($allSuccess) {
                    if ($status == 'New') {
                        if ($allSuccess) {
                            // $titelnoti = "แจ้งเตือนขอเปลี่ยนแปลง (" . $cr_no . ")";
                            // $message = mydata($user)['FullName'] . "\nสร้างเอกสารขอเปลี่ยนแปลง" . "\nเมื่อ " . date("Y-m-d H:i:s");
                            // foreach ($data_admin as $key => $usernoti) {
                            //     $token_noti = mydata($usernoti)['TokenMD5'];
                            //     $url = "https://innovation.asefa.co.th/ChangeRequestForm/ViewForm?token=" . $token_noti . "&CR_no=" . $cr_no;
                            //     Notify($titelnoti, $message, $usernoti, $url);
                            // }
                            echo json_encode(['status' => true], true);
                        } else {
                            echo json_encode(['status' => false], true);
                        }
                    }

                    echo json_encode(['status' => true, 'status_cr' => $status], true);
                } else {
                    echo json_encode(['status' => false, 'status_cr' => $status], true);
                }
            } else {
                echo json_encode(['status' => true, 'status_cr' => $status, 'ddd' => $urgentwork], true);
            }
        } else {
            echo json_encode(['status' => false, 'error' => sqlsrv_errors()], true);
        }
    }
});

$route->add('/select_report', function () { {
        ob_clean();
        header_remove();
        header("Content-type: application/json; charset=utf-8");

        global $konnext_DB64;

        $job_no = $_POST['job_no'] ?? '';
        $date_start = $_POST['date_start'] ?? '';
        $date_end = $_POST['date_end'] ?? '';

        foreach ($job_no as $key => $value) {
            $sql = "
            SELECT [CR_ID]
                ,CC.[CR_no]
                ,CC.[userCreate]
                ,CC.[dateCreate]
                ,CC.[doc_type]
                ,CC.[doc_no]
                ,CC.[jobno]
                ,CC.[wa_no]
                ,CC.[sn_no]
                ,CC.[project_name]
                ,CC.[sales_name]
                ,CC.[customer_name]
                ,CC.[tc_name]
                ,CC.[product]
                ,CC.[status_job]
                ,CC.[status_product]
                ,CC.[data_detail]
                ,CC.[cost]
                ,CC.[job_remark]
                ,CC.[expenses]
                ,CC.[expenses_total]
                ,CC.[expensesummary]
                ,CC.[related]
                ,CC.[doc_status]
                ,CC.[reject_note]
                ,CC.[rev]
                ,CC.[ncr_no]
                ,CC.[details]
                ,CA.[CR_Approve1]
                ,CA.[CR_Approve2]
                ,CA.[CR_Approve3]
                ,CA.[CR_Approve4]
                ,CA.[CR_Approve5]
            FROM [ITService].[dbo].[CR_ChangeForm] CC
            LEFT JOIN [ITService].[dbo].[CR_Approve] CA
            ON CC.[CR_no] = CA.[CR_no]
            WHERE CC.[jobno] = '" . $value . "'
            AND CC.[doc_status] = 'Close'
        ";

            if ($date_start != '' && $date_end != '') {
                $sql .= " AND CC.[dateCreate] BETWEEN '" . $date_start . " 00:00:00' AND '" . $date_end . " 23:59:59'";
            }

            $sql .= "
            ORDER BY 
                CASE 
                    WHEN CC.[doc_no] LIKE 'CHI-%' THEN 1 
                    WHEN CC.[doc_no] LIKE 'CHO-%' THEN 2 
                    ELSE 3 
                END,
            CAST(RIGHT(CC.[doc_no], CHARINDEX('-', REVERSE(CC.[doc_no])) - 1) AS INT);
            ";

            $query = sqlsrv_query($konnext_DB64, $sql);
            while ($row = sqlsrv_fetch_array($query, SQLSRV_FETCH_ASSOC)) {
                $data[$row['jobno']][] = $row;
            }
        }

        echo json_encode($data, true);
    }
});

$route->add('/select_report_rev', function () { {
        ob_clean();
        header_remove();
        header("Content-type: application/json; charset=utf-8");

        global $konnext_DB64;

        $job_no = $_POST['job_no'] ?? '';
        $date_start = $_POST['date_start'] ?? '';
        $date_end = $_POST['date_end'] ?? '';

        foreach ($job_no as $key => $value) {
            $sql = "
            SELECT [CR_ID]
                ,CC.[CR_no]
                ,CC.[userCreate]
                ,CC.[dateCreate]
                ,CC.[doc_type]
                ,CC.[doc_no]
                ,CC.[jobno]
                ,CC.[wa_no]
                ,CC.[sn_no]
                ,CC.[project_name]
                ,CC.[sales_name]
                ,CC.[customer_name]
                ,CC.[tc_name]
                ,CC.[product]
                ,CC.[status_job]
                ,CC.[status_product]
                ,CC.[data_detail]
                ,CC.[cost]
                ,CC.[job_remark]
                ,CC.[expenses]
                ,CC.[expenses_total]
                ,CC.[expensesummary]
                ,CC.[related]
                ,CC.[doc_status]
                ,CC.[reject_note]
                ,CC.[rev]
                ,CC.[ncr_no]
                ,CC.[details]
                ,CA.[CR_Approve1]
                ,CA.[CR_Approve2]
                ,CA.[CR_Approve3]
                ,CA.[CR_Approve4]
                ,CA.[CR_Approve5]
            FROM [ITService].[dbo].[CR_ChangeForm] CC
            LEFT JOIN [ITService].[dbo].[CR_Approve] CA
            ON CC.[CR_no] = CA.[CR_no]
            WHERE CC.[jobno] = '" . $value . "'
            AND CC.[doc_status] != 'Savedraft'
        ";

            if ($date_start != '' && $date_end != '') {
                $sql .= " AND CC.[dateCreate] BETWEEN '" . $date_start . " 00:00:00' AND '" . $date_end . " 23:59:59'";
            }

            $sql .= "
            ORDER BY 
                CASE 
                    WHEN CC.[doc_no] LIKE 'CHI-%' THEN 1 
                    WHEN CC.[doc_no] LIKE 'CHO-%' THEN 2 
                    ELSE 3 
                END,
                TRY_CAST(
                    CASE 
                        WHEN CHARINDEX('-', CC.[doc_no]) > 0 
                        THEN RIGHT(CC.[doc_no], LEN(CC.[doc_no]) - CHARINDEX('-', CC.[doc_no]))
                        ELSE NULL
                    END 
                AS INT)
            ";

            $query = sqlsrv_query($konnext_DB64, $sql);
            while ($row = sqlsrv_fetch_array($query, SQLSRV_FETCH_ASSOC)) {
                $data[$row['jobno']][] = $row;
            }
        }

        echo json_encode($data, true);
    }
});

$route->add('/update_setting', function () { {
        ob_clean();
        header_remove();
        header("Content-type: application/json; charset=utf-8");

        global $konnext_DB64;

        $dataId = $_POST['dataId'];
        $per_edit = json_decode($_POST['per_edit'], true);
        $dep_edit = json_decode($_POST['dep_edit'], true);
        $result = implode(", ", $per_edit);
        $dep = implode(", ", $dep_edit);


        $sql = "
        UPDATE CR_Permission
        SET 
            Per_Json = '" . $result . "',
            Per_Divi_Json = '" . $dep . "',
            userupdate = '" . $_SESSION['ChangeRequest_code'] . "',
            dateupdate = '" . date("Y-m-d H:i:s") . "'
        WHERE 
            Per_ID = '" . $dataId . "';
    ";

        $query = sqlsrv_query($konnext_DB64, $sql);

        if ($query) {
            echo json_encode(['status' => true], true);
        } else {
            echo json_encode(['status' => false, "error" => sqlsrv_errors()], true);
        }
    }
});

$route->add('/generateDocNo', function () { {
        ob_clean();
        header_remove();
        header("Content-type: application/json; charset=utf-8");

        $type = $_POST['type'];

        echo $DocNo = generateDocNo($type);
    }
});

$route->add('/sendMailAPI', function () {
    ob_clean();
    header_remove();
    header("Content-type: application/json; charset=utf-8");

    $rawData = file_get_contents("php://input");
    $data = json_decode($rawData, true);

    if (isset($data['data'])) {
        $value = $data['data'];
        $name = $data['name'];
        $skull = $data['skull'];

        $status = sendMail($value, $name, $skull);

        if ($status) {
            echo json_encode(["status" => "success", "message" => "Email sent successfully", "email" => $value], JSON_UNESCAPED_UNICODE);
        } else {
            echo json_encode(["status" => "error", "message" => "Failed to send email", "email" => $value, "error" => $status], JSON_UNESCAPED_UNICODE);
        }
    } else {
        http_response_code(400);
        echo json_encode(["status" => "error", "message" => "Invalid JSON"]);
    }
});

$route->add('/send_mail', function () {
    ob_clean();
    header_remove();
    header("Content-type: application/json; charset=utf-8");

    global $konnext_DB64;

    $cr_no = $_POST['cr_no'] ?? '';

    $sql = "
        UPDATE CR_ChangeForm
        SET 
            [send_mail] = '1'
        WHERE 
            [CR_no] = '" . $cr_no . "';
    ";

    $query = sqlsrv_query($konnext_DB64, $sql);
    if ($query) {
        echo json_encode(['status' => true], true);
    } else {
        echo json_encode(['status' => false, "error" => sqlsrv_errors()], true);
    }
});

$route->add('/get_Waiting', function () {
    ob_clean();
    header_remove();
    header("Content-type: application/json; charset=utf-8");

    global $konnext_DB64;

    $emp = $_POST['emp'] ?? '';

    if ($emp == '') {
        echo json_encode(['status' => false, 'message' => 'please login'], true);
        exit();
    }

    $data_admin = [
        '641200170',
        '640500080',
        '670100002',
        '660700186',
        '500811071'
    ];

    $sql = "
        SELECT CC.[CR_ID]
            ,CC.[CR_no]
            ,CC.[doc_type]
            ,CC.[doc_no]
            ,CC.[jobno]
            ,CC.[project_name]
            ,CC.[sales_name]
            ,CC.[doc_status]
            ,CC.[userCreate]
            ,CC.[dateCreate]
            ,CC.[urgentwork]
            ,CC.[send_mail]
            ,CA.[CR_Approve1]
            ,CA.[CR_Approve2]
            ,CA.[CR_Approve3]
            ,CA.[CR_Approve4]
            ,CA.[CR_Approve5]
        FROM [ITService].[dbo].[CR_ChangeForm] CC
        LEFT JOIN [ITService].[dbo].[CR_Approve] CA
        ON CC.[CR_no] = CA.[CR_no]
        WHERE (CC.[doc_status] != 'Savedraft' AND CC.[doc_status] != 'Close' AND CC.[doc_status] != 'Cancel')
        ORDER BY CASE 
            CC.[doc_status]
            WHEN 'Not Approve' THEN 1
            WHEN 'Reject' THEN 2
            WHEN 'Rework' THEN 3
            WHEN 'New' THEN 4
            WHEN 'Draf' THEN 5
            WHEN 'Review' THEN 6
            WHEN 'Check' THEN 7
            WHEN 'Recheck' THEN 8
            WHEN 'Approve' THEN 9
            WHEN 'Close' THEN 10
            ELSE 11
        END,
        CAST(REPLACE(CC.[CR_no], 'CR-', '') AS INT) DESC
    ";

    $query = sqlsrv_query($konnext_DB64, $sql);
    $data = [];

    while ($row = sqlsrv_fetch_array($query, SQLSRV_FETCH_ASSOC)) {
        $approve_arr_1 = json_decode($row['CR_Approve1'], true);
        $approve_arr_2 = json_decode($row['CR_Approve2'], true);
        $approve_arr_3 = json_decode($row['CR_Approve3'], true);
        $approve_arr_4 = json_decode($row['CR_Approve4'], true);
        $approve_arr_5 = json_decode($row['CR_Approve5'], true);

        $status = $row['doc_status'];

        if (
            in_array($status, ['New', 'Draf']) &&
            in_array($emp, $data_admin)
        ) {
            $data[] = $row;
        } elseif (
            in_array($status, ['Not Approve', 'Reject', 'Rework']) &&
            $emp === $row['userCreate']
        ) {
            $data[] = $row;
        } elseif (
            $status === 'Review' &&
            is_array($approve_arr_1) &&
            ($approve_arr_1['Approve_1'] ?? '') === $emp &&
            ($approve_arr_1['status_approve'] ?? '') == '0'
        ) {
            $data[] = $row;
        } elseif (
            $status === 'Check' &&
            is_array($approve_arr_2) &&
            ($approve_arr_2['Approve_2'] ?? '') === $emp &&
            ($approve_arr_2['status_approve'] ?? '') == '0'
        ) {
            $data[] = $row;
        } elseif (
            $status === 'Recheck' &&
            is_array($approve_arr_3) &&
            ($approve_arr_3['Approve_3'] ?? '') === $emp &&
            ($approve_arr_3['status_approve'] ?? '') == '0'
        ) {
            $data[] = $row;
        } elseif (
            $status === 'Approve' &&
            is_array($approve_arr_4) &&
            ($approve_arr_4['Approve_4'] ?? '') === $emp &&
            ($approve_arr_4['status_approve'] ?? '') == '0'
        ) {
            $data[] = $row;
        } elseif (
            $status === 'Approve' &&
            ($approve_arr_5 != '') &&
            ($approve_arr_5['Approve_5'] ?? '') === $emp &&
            ($approve_arr_5['status_approve'] ?? '') == '0'
        ) {
            $data[] = $row;
        }
    }

    echo json_encode($data, JSON_UNESCAPED_UNICODE);
});

$route->add('/count_status', function () {
    ob_clean();
    header_remove();
    header("Content-type: application/json; charset=utf-8");

    $status = [
        "new" => get_status_count('New'),
        "draf" => get_status_count('Draf'),
        "rework" => get_status_count('Rework'),
        "review" => get_status_count('Review'),
        "check" => get_status_count('Check'),
        "recheck" => get_status_count('Recheck'),
        "approve" => get_status_count('Approve'),
        "close" => get_status_count('Close')
    ];

    echo json_encode($status, JSON_UNESCAPED_UNICODE);
});

$route->add('/list_change_form', function () {
    ob_clean();
    header_remove();
    header("Content-type: application/json; charset=utf-8");

    $session_keys = [
        $_SESSION['ChangeRequest_code'],
        $_SESSION['DivisionHeadID1'],
        $_SESSION['DivisionHeadID2']
    ];

    $list_permission_emp = explode(", ", list_permission('Admin')[0]['Per_Json']);
    $list_permission_divi = explode(", ", list_permission('Admin')[0]['Per_Divi_Json']);
    $data_admin = array_merge($list_permission_emp, $list_permission_divi);

    $ListPer_Print = explode(", ", list_permission('Print')[0]['Per_Json']);
    $ListPer_Divi_Print = explode(", ", list_permission('Print')[0]['Per_Divi_Json']);
    $data_Print = array_merge($ListPer_Print, $ListPer_Divi_Print);


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

    global $konnext_DB64;

    $draw = $_POST['draw'] ?? 0;
    $start = $_POST['start'] ?? 0;
    $length = $_POST['length'] ?? 10;
    $searchValue = $_POST['search']['value'] ?? '';
    $orderColumnIndex = $_POST['order'][0]['column'] ?? 0;
    $orderDir = $_POST['order'][0]['dir'] ?? 'asc';
    $urgentFilter = $_POST['urgentFilter'] ?? 'all';
    $status = $_POST['status'] ?? null;

    $columns = [
        "CC.CR_no",
        "CC.send_mail",
        "CC.urgentwork",
        "CC.doc_no",
        "CC.doc_type",
        "CC.jobno",
        "CC.project_name",
        "CC.sales_name",
        "CC.CR_no",
        "CC.doc_status",
        "CC.CR_no"
    ];

    $orderBy = $columns[$orderColumnIndex] ?? "CC.CR_ID";

    $searchCondition = "";
    $params = [];
    if ($searchValue != '') {
        $searchCondition = " AND (
            CC.CR_no LIKE ? OR
            CC.doc_no LIKE ? OR
            CC.jobno LIKE ? OR
            CC.project_name LIKE ?
        )";
        $params = array_fill(0, 4, "%$searchValue%");
    }

    if ($urgentFilter === 'urgent') {
        $searchCondition .= " AND CC.urgentwork = 1";
    }

    if ($status) {
        $searchCondition .= " AND CC.doc_status = ?";
        $params[] = $status;
    }

    $sqlCount = "SELECT COUNT(*) as total FROM CR_ChangeForm CC WHERE CC.doc_status != 'Savedraft' $searchCondition";
    $stmtCount = sqlsrv_query($konnext_DB64, $sqlCount, $params);
    $totalFiltered = sqlsrv_fetch_array($stmtCount)['total'];

    $sql = "
        SELECT TOP ($length) * FROM (
            SELECT 
                CC.CR_ID, CC.CR_no, CC.doc_type, CC.doc_no, CC.jobno,
                CC.project_name, CC.sales_name, CC.doc_status,
                CC.userCreate, CC.dateCreate, CC.urgentwork, CC.send_mail,
                CA.CR_Approve1, CA.CR_Approve2, CA.CR_Approve3, CA.CR_Approve4, CA.CR_Approve5,

                ROW_NUMBER() OVER (
                    ORDER BY 
                        CASE CC.doc_status
                            WHEN 'Savedraft' THEN 1
                            WHEN 'Not Approve' THEN 2
                            WHEN 'Rework' THEN 3
                            WHEN 'New' THEN 4
                            WHEN 'Draf' THEN 5
                            WHEN 'Review' THEN 6
                            WHEN 'Check' THEN 7
                            WHEN 'Recheck' THEN 8
                            WHEN 'Approve' THEN 9
                            WHEN 'Close' THEN 10
                            ELSE 11
                        END,
                        TRY_CAST(REPLACE(CC.CR_no, 'CR-', '') AS INT) DESC
                ) AS RowNum
            FROM [ITService].[dbo].[CR_ChangeForm] CC
            LEFT JOIN [ITService].[dbo].[CR_Approve] CA ON CC.CR_no = CA.CR_no
            WHERE 
                (
                    CC.doc_status != 'Savedraft'
                    OR (CC.doc_status = 'Savedraft' AND CC.userCreate = '" . $_SESSION['ChangeRequest_code'] . "')
                )
                $searchCondition
        ) AS A
        WHERE A.RowNum > $start
    ";


    $stmt = sqlsrv_query($konnext_DB64, $sql, $params);
    $data = [];
    $action_html = "";

    while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
        $can_print = (
            array_intersect($session_keys, $data_admin) ||
            array_intersect($session_keys, $data_Print) ||
            count($data_Print) == 0
        );

        if ($can_print) {
            if (($row['doc_status'] == 'Close' || $row['doc_status'] == 'Approve') && $row['doc_type'] == 'CHI') {
                $action_html = '<a type="button" href="./Print_Form_CHI?CR_no=' . $row['CR_no'] . '" class="btn btn-primary p-3" target="_blank">
                    <i class="bi bi-printer-fill fs-4"></i> พิมพ์</a>';
            }

            if (($row['doc_status'] == 'Close' || $row['doc_status'] == 'Approve') && $row['doc_type'] == 'CHO') {
                $action_html = '<a type="button" href="./Print_Form_CHO?CR_no=' . $row['CR_no'] . '" class="btn btn-primary p-3" target="_blank">
                    <i class="bi bi-printer-fill fs-4"></i> พิมพ์</a>';
            }
        }

        $apporve_arr_1 = json_decode($row['CR_Approve1'], true);
        $apporve_arr_2 = json_decode($row['CR_Approve2'], true);
        $apporve_arr_3 = json_decode($row['CR_Approve3'], true);
        $apporve_arr_4 = json_decode($row['CR_Approve4'], true);
        $apporve_arr_5 = json_decode($row['CR_Approve5'], true);

        if ($row['doc_status'] == 'New') {
            $wait = 'Admin TC';
        } elseif ($row['doc_status'] == 'Draf') {
            // $wait = mydata($status_time['Status_User'])['FirstName'];
            $wait = 'Admin TC';
        } elseif ($row['doc_status'] == 'Rework') {
            $wait = mydata($row['userCreate'])['FirstName'];
        } elseif (($row['doc_status'] == 'Review') && $apporve_arr_1['status_approve'] == '0') {
            $wait = mydata($apporve_arr_1['Approve_1'])['FirstName'];
        } elseif (($row['doc_status'] == 'Check') && $apporve_arr_2['status_approve'] == '0') {
            $wait = mydata($apporve_arr_2['Approve_2'])['FirstName'];
        } elseif (($row['doc_status'] == 'Recheck') && $apporve_arr_3['status_approve'] == '0') {
            $wait = mydata($apporve_arr_3['Approve_3'])['FirstName'];
        } elseif (($row['doc_status'] == 'Approve') && $apporve_arr_4['status_approve'] == '0') {
            $wait = mydata($apporve_arr_4['Approve_4'])['FirstName'];
        } elseif ($row['doc_status'] == 'Approve' && $apporve_arr_5 != null && $apporve_arr_5['Approve_5'] != '-' && $apporve_arr_5['Approve_5'] != '') {
            $wait = mydata($apporve_arr_5['Approve_5'])['FirstName'];
        } elseif ($row['doc_status'] == 'Not Approve') {
            $wait = mydata($row['userCreate'])['FirstName'];
        } else {
            $wait = '';
        }

        $data[] = [
            "CR_no" => '<a href="./ViewForm?DataE=' . $_SESSION['DataE'] . '&CR_no=' . $row['CR_no'] . '" target="_blank">' . $row['CR_no'] . '</a>',
            "send_mail" => $row['send_mail'] == '1' ? '<i class="fa-solid fa-square-check fs-2 text-success"></i>' : '',
            "urgentwork" => $row['urgentwork'] == '1' ? '<i class="fa-solid fa-square-check fs-2 text-danger"></i>' : '',
            "doc_no" => $row['doc_no'],
            "doc_type" => $row['doc_type'],
            "jobno" => strtoupper($row['jobno']),
            "project_name" => $row['project_name'],
            "sales_name" => 'Team ' . explode("-", explode(" ", $row['sales_name'])[0])[0] . ' ( ' . explode("-", explode(" ", $row['sales_name'])[0])[1] . ' )',
            "waiting_person" => $wait,
            "doc_status" => '<span class="badge bg-warning" style="color: white; background-color: ' . $color_arr[$row['doc_status']] . ' !important;">' . $row['doc_status'] . '</span>',
            "action" => $action_html
        ];
    }

    echo json_encode([
        "draw" => intval($draw),
        "recordsTotal" => $totalFiltered,
        "recordsFiltered" => $totalFiltered,
        "data" => $data
    ]);
});

// $route->add('/list_change_form_test', function () {
//     ob_clean();
//     header_remove();
//     header("Content-type: application/json; charset=utf-8");

//     $session_keys = [
//         $_SESSION['ChangeRequest_code'],
//         $_SESSION['DivisionHeadID1'],
//         $_SESSION['DivisionHeadID2']
//     ];

//     $list_permission_emp = explode(", ", list_permission('Admin')[0]['Per_Json']);
//     $list_permission_divi = explode(", ", list_permission('Admin')[0]['Per_Divi_Json']);
//     $data_admin = array_merge($list_permission_emp, $list_permission_divi);

//     $ListPer_Print = explode(", ", list_permission('Print')[0]['Per_Json']);
//     $ListPer_Divi_Print = explode(", ", list_permission('Print')[0]['Per_Divi_Json']);
//     $data_Print = array_merge($ListPer_Print, $ListPer_Divi_Print);


//     $color_arr = array(
//         'New' => "#0d6efd",
//         'Draf' => "#e67e22",
//         'Review' => "#af7ac5",
//         'Rework' => "#f4d03f",
//         'Check' => "#0dcaf0",
//         'Recheck' => "#05abb3",
//         'Approve' => "#2ecc71",
//         'Close' => "#85929e",
//         'Cancel' => "#ec7063",
//         'Not Approve' => "#ec7063",
//     );

//     global $konnext_DB64;

//     $draw = $_POST['draw'];
//     $start = $_POST['start'];
//     $length = $_POST['length'];
//     $searchValue = $_POST['search']['value'] ?? '';
//     $orderColumnIndex = $_POST['order'][0]['column'] ?? 0;
//     $orderDir = $_POST['order'][0]['dir'] ?? 'asc';
//     $urgentFilter = $_POST['urgentFilter'] ?? 'all';
//     $status = $_POST['status'] ?? null;

//     $columns = [
//         "CC.CR_no",
//         "CC.send_mail",
//         "CC.urgentwork",
//         "CC.doc_no",
//         "CC.doc_type",
//         "CC.jobno",
//         "CC.project_name",
//         "CC.sales_name",
//         "CC.CR_no",
//         "CC.doc_status",
//         "CC.CR_no"
//     ];

//     $orderBy = $columns[$orderColumnIndex] ?? "CC.CR_ID";

//     $searchCondition = "";
//     $params = [];
//     if ($searchValue != '') {
//         $searchCondition = " AND (
//             CC.CR_no LIKE ? OR
//             CC.doc_no LIKE ? OR
//             CC.jobno LIKE ? OR
//             CC.project_name LIKE ?
//         )";
//         $params = array_fill(0, 4, "%$searchValue%");
//     }

//     if ($urgentFilter === 'urgent') {
//         $searchCondition .= " AND CC.urgentwork = 1";
//     }

//     if ($status) {
//         $searchCondition .= " AND CC.doc_status = ?";
//         $params[] = $status;
//     }

//     $sqlCount = "SELECT COUNT(*) as total FROM CR_ChangeForm CC WHERE CC.doc_status != 'Savedraft' $searchCondition";
//     $stmtCount = sqlsrv_query($konnext_DB64, $sqlCount, $params);
//     $totalFiltered = sqlsrv_fetch_array($stmtCount)['total'];

//     $sql = "
//         SELECT TOP ($length) * FROM (
//             SELECT 
//                 CC.CR_ID, CC.CR_no, CC.doc_type, CC.doc_no, CC.jobno,
//                 CC.project_name, CC.sales_name, CC.doc_status,
//                 CC.userCreate, CC.dateCreate, CC.urgentwork, CC.send_mail,
//                 CA.CR_Approve1, CA.CR_Approve2, CA.CR_Approve3, CA.CR_Approve4, CA.CR_Approve5,

//                 ROW_NUMBER() OVER (
//                     ORDER BY 
//                         CASE CC.doc_status
//                             WHEN 'Savedraft' THEN 1
//                             WHEN 'Not Approve' THEN 2
//                             WHEN 'Rework' THEN 3
//                             WHEN 'New' THEN 4
//                             WHEN 'Draf' THEN 5
//                             WHEN 'Review' THEN 6
//                             WHEN 'Check' THEN 7
//                             WHEN 'Recheck' THEN 8
//                             WHEN 'Approve' THEN 9
//                             WHEN 'Close' THEN 10
//                             ELSE 11
//                         END,
//                         TRY_CAST(REPLACE(CC.CR_no, 'CR-', '') AS INT) DESC
//                 ) AS RowNum
//             FROM [ITService].[dbo].[CR_ChangeForm] CC
//             LEFT JOIN [ITService].[dbo].[CR_Approve] CA ON CC.CR_no = CA.CR_no
//             WHERE 
//                 (
//                     CC.doc_status != 'Savedraft'
//                     OR (CC.doc_status = 'Savedraft' AND CC.userCreate = '" . $_SESSION['ChangeRequest_code'] . "')
//                 )
//                 $searchCondition
//         ) AS A
//         WHERE A.RowNum > $start
//     ";


//     $stmt = sqlsrv_query($konnext_DB64, $sql, $params);
//     $data = [];

//     while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
//         $can_print = (
//             array_intersect($session_keys, $data_admin) ||
//             array_intersect($session_keys, $data_Print) ||
//             count($data_Print) == 0
//         );

//         if ($can_print) {
//             if (($row['doc_status'] == 'Close' || $row['doc_status'] == 'Approve') && $row['doc_type'] == 'CHI') {
//                 $action_html = '<a type="button" href="./Print_Form_CHI?CR_no=' . $row['CR_no'] . '" class="btn btn-primary p-3" target="_blank">
//                     <i class="bi bi-printer-fill fs-4"></i> พิมพ์</a>';
//             }

//             if (($row['doc_status'] == 'Close' || $row['doc_status'] == 'Approve') && $row['doc_type'] == 'CHO') {
//                 $action_html = '<a type="button" href="./Print_Form_CHO?CR_no=' . $row['CR_no'] . '" class="btn btn-primary p-3" target="_blank">
//                     <i class="bi bi-printer-fill fs-4"></i> พิมพ์</a>';
//             }
//         }

//         $apporve_arr_1 = json_decode($row['CR_Approve1'], true);
//         $apporve_arr_2 = json_decode($row['CR_Approve2'], true);
//         $apporve_arr_3 = json_decode($row['CR_Approve3'], true);
//         $apporve_arr_4 = json_decode($row['CR_Approve4'], true);
//         $apporve_arr_5 = json_decode($row['CR_Approve5'], true);

//         if ($row['doc_status'] == 'New') {
//             $wait = 'Admin TC';
//         } elseif ($row['doc_status'] == 'Draf') {
//             // $wait = mydata($status_time['Status_User'])['FirstName'];
//             $wait = 'Admin TC';
//         } elseif ($row['doc_status'] == 'Rework') {
//             $wait = mydata($row['userCreate'])['FirstName'];
//         } elseif (($row['doc_status'] == 'Review') && $apporve_arr_1['status_approve'] == '0') {
//             $wait = mydata($apporve_arr_1['Approve_1'])['FirstName'];
//         } elseif (($row['doc_status'] == 'Check') && $apporve_arr_2['status_approve'] == '0') {
//             $wait = mydata($apporve_arr_2['Approve_2'])['FirstName'];
//         } elseif (($row['doc_status'] == 'Recheck') && $apporve_arr_3['status_approve'] == '0') {
//             $wait = mydata($apporve_arr_3['Approve_3'])['FirstName'];
//         } elseif (($row['doc_status'] == 'Approve') && $apporve_arr_4['status_approve'] == '0') {
//             $wait = mydata($apporve_arr_4['Approve_4'])['FirstName'];
//         } elseif ($row['doc_status'] == 'Approve' && $apporve_arr_5 != null && $apporve_arr_5['Approve_5'] != '-' && $apporve_arr_5['Approve_5'] != '') {
//             $wait = mydata($apporve_arr_5['Approve_5'])['FirstName'];
//         } elseif ($row['doc_status'] == 'Not Approve') {
//             $wait = mydata($row['userCreate'])['FirstName'];
//         } else {
//             $wait = '';
//         }

//         $data[] = [
//             "CR_no" => '<a href="./ViewForm_test?DataE=' . $_SESSION['DataE'] . '&CR_no=' . $row['CR_no'] . '" target="_blank">' . $row['CR_no'] . '</a>',
//             "send_mail" => $row['send_mail'] == '1' ? '<i class="fa-solid fa-square-check fs-2 text-success"></i>' : '',
//             "urgentwork" => $row['urgentwork'] == '1' ? '<i class="fa-solid fa-square-check fs-2 text-danger"></i>' : '',
//             "doc_no" => $row['doc_no'],
//             "doc_type" => $row['doc_type'],
//             "jobno" => strtoupper($row['jobno']),
//             "project_name" => $row['project_name'],
//             "sales_name" => 'Team ' . explode("-", explode(" ", $row['sales_name'])[0])[0] . ' ( ' . explode("-", explode(" ", $row['sales_name'])[0])[1] . ' )',
//             "waiting_person" => $wait,
//             "doc_status" => '<span class="badge bg-warning" style="color: white; background-color: ' . $color_arr[$row['doc_status']] . ' !important;">' . $row['doc_status'] . '</span>',
//             "action" => $action_html
//         ];
//     }

//     echo json_encode([
//         "draw" => intval($draw),
//         "recordsTotal" => $totalFiltered,
//         "recordsFiltered" => $totalFiltered,
//         "data" => $data
//     ]);
// });

$route->submit();
