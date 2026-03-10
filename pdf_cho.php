<?php

session_start();
include "config/base.php";
include('../cdn/TCPDF-main/config/tcpdf_config_alt.php');
include('../cdn/TCPDF-main/tcpdf.php');

$CR_no = $_GET['CR_no'];

$data_arr = List_ChangeFormByID($_GET['CR_no']);
$file_arr = List_Files($_GET['CR_no']);

$WA_arr = Search_WA($data_arr['jobno']);
$Tc_name_arr = Search_TC($data_arr['jobno']);
$SN_arr = Search_SN($data_arr['jobno']);

$status_time = Get_Time_Status($_GET['CR_no'], 'Close');

//==============================================
$selectedSNs = [];
$selectSNs = array_map('trim', explode(',', $data_arr['sn_no']));

$SN_arr = array_filter($SN_arr, function ($sn) {
    return $sn !== "-";
});

$selectSNs = array_filter($selectSNs, function ($sn) {
    return $sn !== "-";
});

foreach ($SN_arr as $key => $value) {
    if (!in_array($value['item'], $selectSNs)) {
        continue;
    }

    $selectedSNs[] = $value['PartNo'];
}
$SN_chunked = array_chunk($selectedSNs, 4);
$data_arr_sn = [];
foreach ($SN_chunked as $group_sn) {
    $data_arr_sn[] = implode(', ', $group_sn);
}

if (count($selectedSNs) == count($SN_arr)) {
    // $displayText = "{$selectedSNs[0]}, {$selectedSNs[1]} (" . (count($selectedSNs) - 2) . ")";
    $displayText = "All";
} elseif (count($selectedSNs) > 2) {
    $displayText = "ตามรายละเอียด";
} else {
    $displayText = implode(', ', $selectedSNs);
}

//==============================================

//==============================================
$selectWA = array_map('trim', explode(',', $data_arr['wa_no']));
$selectWA  = array_filter($selectWA, function ($wa) {
    return $wa !== "-";
});
$chunked = array_chunk($selectWA, 12);
$data_arr_wa = [];
foreach ($chunked as $group_wa) {
    $data_arr_wa[] = implode(', ', $group_wa);
}

if (count($selectWA) == count($WA_arr)) {
    // $displayText = "{$selectedSNs[0]}, {$selectedSNs[1]} (" . (count($selectedSNs) - 2) . ")";
    $WA_arrText = "All";
} elseif (count($selectWA) > 2) {
    // $WA_arrText = "{$selectWA[0]}, {$selectWA[1]} (" . (count($selectWA) - 2) . ")";
    $WA_arrText = "ตามรายละเอียด";
} else {
    $WA_arrText = implode(', ', $selectWA);
}

//==============================================

$product_arr = json_decode($data_arr['product'], true);
$product_list = ProductList();
$division_list = DivisionList();

foreach ($product_arr as $key => $value) {
    $product_select[$value] = $value;
}

$product_other = is_numeric(end($product_arr)) ? "" : $product_arr;

function truncateText($text, $length = 15)
{
    if (mb_strlen($text, 'UTF-8') <= $length) {
        return $text;
    }

    $truncated = mb_substr($text, 0, $length, 'UTF-8');

    $lastSpace = mb_strrpos($truncated, ' ', 0, 'UTF-8');
    if ($lastSpace !== false) {
        $truncated = mb_substr($truncated, 0, $lastSpace, 'UTF-8');
    }

    return $truncated . '...';
}

// if ($_SESSION['ChangeRequest_user_id'] == '2776') {
//     echo "<pre>";
//     print_r($file_arr);
//     exit();
// }

// create new PDF document
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
$pdf->setCreator(PDF_CREATOR);
$pdf->setAuthor('ASEFA');
$pdf->setTitle('PDF ใบเปลี่ยนแปลง');
$pdf->setSubject('TCPDF Tutorial');
$pdf->setKeywords('TCPDF, PDF, example, test, guide');

// set default header data
// $pdf->setHeaderData(PDF_HEADER_LOGO, 15, "บริษัท อาซีฟา จำกัด มหาชน", "Asefa Public Company limited", array(0,64,245), array(0,64,118));
// $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
// $pdf->setFooterData(array(0,64,0), array(0,64,128));

// set header and footer fonts
// $pdf->setHeaderFont(array("thsarabun", 'B', 11));
// $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// set default monospaced font
$pdf->setDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins
// $pdf->setMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
// $pdf->setHeaderMargin(PDF_MARGIN_HEADER);
// $pdf->setFooterMargin(PDF_MARGIN_FOOTER);

// $pdf->SetMargins(PDF_MARGIN_LEFT,10,PDF_MARGIN_RIGHT);
// $pdf->SetMargins(10,5,10, true);
$pdf->SetMargins(7, 5, 7, true);
$pdf->setHeaderMargin(2);
$pdf->setFooterMargin(2);
$pdf->SetPrintHeader(false);
$pdf->setPrintFooter(false);

// set auto page breaks
// $pdf->setAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
$pdf->setAutoPageBreak(TRUE, 5);


// set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

// set some language-dependent strings (optional)
if (@file_exists(dirname(__FILE__) . '/lang/eng.php')) {
    require_once(dirname(__FILE__) . '/lang/eng.php');
    $pdf->setLanguageArray($l);
}

// ---------------------------------------------------------

$dotted = '.....................................................';

// set default font subsetting mode
$pdf->setFontSubsetting(true);

// Set font
// dejavusans is a UTF-8 Unicode font, if you only need to
// print standard ASCII chars, you can use core fonts like
// helvetica or times to reduce file size.
$pdf->setFont('thsarabun', '', 11, '', true);

// Add a page
// This method has several options, check the source code documentation for more information.
// $pdf->AddPage('L'); L คือแนวนอน
$pdf->AddPage('P', 'A4');

// $pdf->SetPageSize(210, 297);

$pdf->Ln(3);

// $html = '<div style="text-align:center; font-size: 11px;"><b>บริษัท อาซีฟา จำกัด (มหาชน)</b></div>';

// $pdf->writeHTML($html, true, false, true, false, '');

$pdf->SetLineWidth(0.3);
$pdf->SetFont('thsarabun', 'B', 11);
$pdf->Cell('', 5, 'บริษัท อาซีฟา จำกัด (มหาชน)', 0, 0, 'C');
$pdf->Ln(5);

$pdf->SetLineWidth(0.3);
$pdf->SetFont('thsarabun', 'B', 11);
$pdf->Cell('', 5, 'ใบขอเปลี่ยนแปลงนอก Scope', 'LRT', 0, 'C');
$pdf->Ln(5);

$pdf->SetLineWidth(0.3);
$pdf->SetFont('thsarabun', 'B', 11);
$pdf->Cell('', 5, '(ใช้สำหรับการเปลี่ยนแปลงที่เกิดจากภายนอกบริษัท)', 'LRB', 0, 'C');
$pdf->Ln(5);

$pdf->SetLineWidth(0.3);
$pdf->SetFont('thsarabun', 'BU', 10);
$pdf->Cell('', 5, 'ส่วนการแจ้งเปลี่ยนแปลง', 'RL', 0, 'L');
$pdf->Ln(5);

$datetime = new DateTime($status_time['Status_Date']);
$datetime_system = explode('/', $datetime->format('d/m/Y'));
$datetime_system[2] = $datetime_system[2] + 543;

$pdf->SetLineWidth(0.3);
$pdf->SetFont('thsarabun', 'B', 10);
$pdf->Cell(75, 6, 'CHO            ' . $data_arr['doc_no'], 'L', 0, 'L');
$pdf->Line($pdf->GetX() - 68, $pdf->GetY() + 5, $pdf->GetX(), $pdf->GetY() + 5, 'dotted');
$pdf->Cell(75, 6, 'วันที่รับเข้าระบบ                   ' . $datetime_system[0] . '/' . $datetime_system[1] . '/' . $datetime_system[2], '', 0, 'L');
$pdf->Line($pdf->GetX() - 58, $pdf->GetY() + 5, $pdf->GetX(), $pdf->GetY() + 5, 'dotted');
$pdf->Cell(46, 6, '', 'R', 0, 'L');
$pdf->Ln(6);

$pdf->SetLineWidth(0.3);
$pdf->SetFont('thsarabun', 'B', 10);
$pdf->Cell(70, 6, 'Job No.         ' . $data_arr['jobno'], 'L', 0, 'L');
$pdf->Line($pdf->GetX() - 60, $pdf->GetY() + 5, $pdf->GetX(), $pdf->GetY() + 5, 'dotted');
$pdf->Cell(50, 6, 'W/A    ' . $WA_arrText, 0, 0, 'L');
$pdf->Line($pdf->GetX() - 43, $pdf->GetY() + 5, $pdf->GetX(), $pdf->GetY() + 5, 'dotted');
$pdf->Cell(76, 6, 'Serial No.    ' . $displayText, 'R', 0, 'L');
$pdf->Line($pdf->GetX() - 60, $pdf->GetY() + 5, $pdf->GetX() - 7, $pdf->GetY() + 5, 'dotted');
$pdf->Ln(6);

$pdf->SetLineWidth(0.3);
$pdf->SetFont('thsarabun', 'B', 10);
$pdf->Cell(120, 6, 'Project Name           ' . $data_arr['project_name'], 'L', 0, 'L');
$pdf->Line($pdf->GetX() - 97, $pdf->GetY() + 5, $pdf->GetX(), $pdf->GetY() + 5, 'dotted');
$pdf->Cell(76, 6, 'Sales Name            ' . $data_arr['sales_name'], 'R', 0, 'L');
$pdf->Line($pdf->GetX() - 60, $pdf->GetY() + 5, $pdf->GetX() - 7, $pdf->GetY() + 5, 'dotted');
$pdf->Ln(6);

$pdf->SetLineWidth(0.3);
$pdf->SetFont('thsarabun', 'B', 10);
$pdf->Cell(120, 6, 'Customer Name       ' . $data_arr['customer_name'], 'L', 0, 'L');
$pdf->Line($pdf->GetX() - 97, $pdf->GetY() + 5, $pdf->GetX(), $pdf->GetY() + 5, 'dotted');
$pdf->Cell(76, 6, '', 'R', 0, 'L');
$pdf->Ln(6);

$pdf->SetLineWidth(0.3);
$pdf->SetFont('thsarabun', 'B', 10);
$pdf->Cell(40, 5, 'Product', 'L', 0, 'L');
$pdf->SetFont('thsarabun', 0, 10);
$pdf->Image($product_select[1] != '' ? './icon/check-box.jpg' : './icon/square.jpg', $pdf->GetX() + 2, $pdf->GetY() + 1, 4, 4);
$pdf->Cell(30, 5, '        ' . $product_list[1]['Product_Name'], 0, 0, 'L');
$pdf->Image($product_select[2] != '' ? './icon/check-box.jpg' : './icon/square.jpg', $pdf->GetX() + 2, $pdf->GetY() + 1, 4, 4);
$pdf->Cell(30, 5, '        ' . $product_list[2]['Product_Name'], 0, 0, 'L');
$pdf->Image($product_select[3] != '' ? './icon/check-box.jpg' : './icon/square.jpg', $pdf->GetX() + 2, $pdf->GetY() + 1, 4, 4);
$pdf->Cell(30, 5, '        ' . $product_list[3]['Product_Name'], 0, 0, 'L');
$pdf->Image($product_select[4] != '' ? './icon/check-box.jpg' : './icon/square.jpg', $pdf->GetX() + 2, $pdf->GetY() + 1, 4, 4);
$pdf->Cell(30, 5, '        ' . $product_list[4]['Product_Name'], 0, 0, 'L');
$pdf->Image($product_select[5] != '' ? './icon/check-box.jpg' : './icon/square.jpg', $pdf->GetX() + 2, $pdf->GetY() + 1, 4, 4);
$pdf->Cell(36, 5, '        ' . $product_list[5]['Product_Name'], 'R', 0, 'L');
$pdf->Ln(5);

$pdf->SetLineWidth(0.3);
$pdf->SetFont('thsarabun', 0, 10);
$pdf->Cell(40, 5, '', 'L', 0, 'L');
$pdf->Image($product_select[6] != '' ? './icon/check-box.jpg' : './icon/square.jpg', $pdf->GetX() + 2, $pdf->GetY() + 1, 4, 4);
$pdf->Cell(30, 5, '        ' . $product_list[6]['Product_Name'], 0, 0, 'L');
$pdf->Image($product_select[7] != '' ? './icon/check-box.jpg' : './icon/square.jpg', $pdf->GetX() + 2, $pdf->GetY() + 1, 4, 4);
$pdf->Cell(30, 5, '        ' . $product_list[7]['Product_Name'], 0, 0, 'L');
$pdf->Image($product_select[8] != '' ? './icon/check-box.jpg' : './icon/square.jpg', $pdf->GetX() + 2, $pdf->GetY() + 1, 4, 4);
$pdf->Cell(30, 5, '        ' . $product_list[8]['Product_Name'], 0, 0, 'L');
$pdf->Image($product_select[9] != '' ? './icon/check-box.jpg' : './icon/square.jpg', $pdf->GetX() + 2, $pdf->GetY() + 1, 4, 4);
$pdf->Cell(30, 5, '        ' . $product_list[9]['Product_Name'], 0, 0, 'L');
$pdf->Image(end($product_other) != '' ? './icon/check-box.jpg' : './icon/square.jpg', $pdf->GetX() + 2, $pdf->GetY() + 1, 4, 4);
$pdf->Cell(36, 5, '        Other    ' . end($product_other), 'R', 0, 'L');
$pdf->Line($pdf->GetX() - 21, $pdf->GetY() + 5, $pdf->GetX() - 7, $pdf->GetY() + 5, 'dotted');
$pdf->Ln(5);

$status_job_arr = json_decode($data_arr['status_job'], true);
$pdf->SetLineWidth(0.3);
$pdf->SetFont('thsarabun', 'B', 10);
$pdf->Cell(40, 6, 'สถานะงาน', 'L', 0, 'L');
$pdf->SetFont('thsarabun', 0, 10);
$pdf->Image(in_array('เปิดงาน/สั่งอุปกรณ์', $status_job_arr) ? "./icon/check-box.jpg" : "./icon/square.jpg", $pdf->GetX() + 2, $pdf->GetY() + 1, 4, 4);
$pdf->Cell(30, 6, '        เปิดงาน/สั่งอุปกรณ์', 0, 0, 'L');
$pdf->Image(in_array('ผลิตชิ้นงานเหล็ก', $status_job_arr) ? "./icon/check-box.jpg" : "./icon/square.jpg", $pdf->GetX() + 2, $pdf->GetY() + 1, 4, 4);
$pdf->Cell(30, 6, '        ผลิตชิ้นงานเหล็ก', 0, 0, 'L');
$pdf->Image(in_array('ติดตั้งเหล็ก', $status_job_arr) ? "./icon/check-box.jpg" : "./icon/square.jpg", $pdf->GetX() + 2, $pdf->GetY() + 1, 4, 4);
$pdf->Cell(30, 6, '        ติดตั้งเหล็ก', 0, 0, 'L');
$pdf->Image(in_array('ติดตั้งไฟฟ้า', $status_job_arr) ? "./icon/check-box.jpg" : "./icon/square.jpg", $pdf->GetX() + 2, $pdf->GetY() + 1, 4, 4);
$pdf->Cell(30, 6, '        ติดตั้งไฟฟ้า', 0, 0, 'L');
$pdf->Image(in_array('Finish Goods', $status_job_arr) ? "./icon/check-box.jpg" : "./icon/square.jpg", $pdf->GetX() + 2, $pdf->GetY() + 1, 4, 4);
$pdf->Cell(36, 6, '        Finish Goods', 'R', 0, 'L');
$pdf->Ln(6);

$product_status_arr = json_decode($data_arr['status_product'], true);
$pdf->SetLineWidth(0.3);
$pdf->SetFont('thsarabun', 'B', 10);
$pdf->Cell(40, 6, 'สถานะผลิตภัณฑ์', 'L', 0, 'L');
$pdf->SetFont('thsarabun', 0, 10);
$pdf->Image(strpos($data_arr['status_product'], 'ตู้ในโรงงาน WIP') ? "./icon/check-box.jpg" : "./icon/square.jpg", $pdf->GetX() + 2, $pdf->GetY() + 1, 4, 4);
$pdf->Cell(30, 6, '        ตู้ในโรงงาน WIP', 0, 0, 'L');
$pdf->Image(strpos($data_arr['status_product'], 'ตู้ในโรงงาน FG') ? "./icon/check-box.jpg" : "./icon/square.jpg", $pdf->GetX() + 2, $pdf->GetY() + 1, 4, 4);
$pdf->Cell(30, 6, '        ตู้ในโรงงาน FG', 0, 0, 'L');
$pdf->Image(strpos($data_arr['status_product'], 'ตู้นอกโรงงาน') ? "./icon/check-box.jpg" : "./icon/square.jpg", $pdf->GetX() + 2, $pdf->GetY() + 1, 4, 4);
$pdf->Cell(30, 6, '        ตู้นอกโรงงาน', 0, 0, 'L');
$pdf->Cell(30, 6, '', 0, 0, 'L');
$pdf->Cell(36, 6, '', 'R', 0, 'L');
$pdf->Ln(6);

$pdf->SetLineWidth(0.3);
$pdf->SetFont('thsarabun', 'B', 10);
$pdf->Cell(196, 6, '* หัวข้อการเปลี่ยนแปลง            (รายละเอียดและสาเหตุของการเปลี่ยนแปลงให้ระบุเพิ่มเติมด้านหลัง)', 'RL', 0, 'L');
$pdf->Ln(6);

$pdf->SetLineWidth(0.3);
$pdf->SetFont('thsarabun', 'U', 10);
$pdf->Cell(50, 6, 'อุปกรณ์ไฟฟ้า', 'L', 0, 'L');
$pdf->Cell(35, 6, 'บัสบาร์', '', 0, 'L');
$pdf->Cell(35, 6, 'งานเหล็ก', '', 0, 'L');
$pdf->Cell(35, 6, 'แบบ', '', 0, 'L');
$pdf->Cell(41, 6, 'ใบเปิดงาน', 'R', 0, 'L');
$pdf->Ln(6);

$data_detail = json_decode($data_arr['data_detail'], true);
foreach ($data_detail as $key => $value) {
    $detail_arr[$value['problem']] = $value;
}

foreach ($data_detail as $value) {
    foreach ($value['inputValue']['postponed'] as $k => $v) {
        $detail_custom[] = ($k === 1 && strtotime($v) && strpos($v, '/25') !== false)
            ? date('d-m-', strtotime($v)) . (date('Y', strtotime($v)) + 543)
            : str_replace("/", "-", $v);
    }
}

foreach ($detail_arr as $key => $value) {
    foreach ($value['inputChk'] as $key2 => $value2) {
        $inputChk[$value2] = $value2;
    }
}

// echo "<pre>";
// print_r($inputChk);
// exit();
$pdf->SetLineWidth(0.3);
$pdf->SetFont('thsarabun', 0, 10);
$pdf->Image($detail_arr['Electrical']['inputValue']['increase'][0] != '' ? "./icon/check-box.jpg" : "./icon/square.jpg", $pdf->GetX() + 2, $pdf->GetY() + 1, 4, 4);
$pdf->Cell(50, 6, '        เพิ่ม', 'L', 0, 'L');
$pdf->Image($detail_arr['Busbar']['inputValue']['increase'][0] != '' ? "./icon/check-box.jpg" : "./icon/square.jpg", $pdf->GetX() + 2, $pdf->GetY() + 1, 4, 4);
$pdf->Cell(35, 6, '        เพิ่ม', '', 0, 'L');
$pdf->Image($detail_arr['Ironwork']['inputValue']['increase'][0] != '' ? "./icon/check-box.jpg" : "./icon/square.jpg", $pdf->GetX() + 2, $pdf->GetY() + 1, 4, 4);
$pdf->Cell(35, 6, '        เพิ่ม', '', 0, 'L');
$pdf->Image($detail_arr['Model']['inputValue']['increase'][0] != '' ? "./icon/check-box.jpg" : "./icon/square.jpg", $pdf->GetX() + 2, $pdf->GetY() + 1, 4, 4);
$pdf->Cell(35, 6, '        แก้ไขเพิ่ม', '', 0, 'L');
$pdf->Image($detail_arr['Closingsheet']['inputValue']['increase'][0] != '' ? "./icon/check-box.jpg" : "./icon/square.jpg", $pdf->GetX() + 2, $pdf->GetY() + 1, 4, 4);
$pdf->Cell(41, 6, '        เพิ่ม', 'R', 0, 'L');
$pdf->Line($pdf->GetX() - 28, $pdf->GetY() + 5, $pdf->GetX() - 7, $pdf->GetY() + 5, 'dotted');
$pdf->Ln(6);

$pdf->SetLineWidth(0.3);
$pdf->SetFont('thsarabun', 0, 10);
$pdf->Image($detail_arr['Electrical']['inputValue']['reduce'][0] != '' ? "./icon/check-box.jpg" : "./icon/square.jpg", $pdf->GetX() + 2, $pdf->GetY() + 1, 4, 4);
$pdf->Cell(50, 6, '        ลด', 'L', 0, 'L');
$pdf->Image($detail_arr['Busbar']['inputValue']['reduce'][0] != '' ? "./icon/check-box.jpg" : "./icon/square.jpg", $pdf->GetX() + 2, $pdf->GetY() + 1, 4, 4);
$pdf->Cell(35, 6, '        ลด', '', 0, 'L');
$pdf->Image($detail_arr['Ironwork']['inputValue']['reduce'][0] != '' ? "./icon/check-box.jpg" : "./icon/square.jpg", $pdf->GetX() + 2, $pdf->GetY() + 1, 4, 4);
$pdf->Cell(35, 6, '        ลด', '', 0, 'L');
$pdf->Image($detail_arr['Model']['inputValue']['reduce'][0] != '' ? "./icon/check-box.jpg" : "./icon/square.jpg", $pdf->GetX() + 2, $pdf->GetY() + 1, 4, 4);
$pdf->Cell(35, 6, '        แก้ไขลด', '', 0, 'L');
$pdf->Image($detail_arr['Closingsheet']['inputValue']['reduce'][0] != '' ? "./icon/check-box.jpg" : "./icon/square.jpg", $pdf->GetX() + 2, $pdf->GetY() + 1, 4, 4);
$pdf->Cell(41, 6, '        ลด', 'R', 0, 'L');
$pdf->Line($pdf->GetX() - 28, $pdf->GetY() + 5, $pdf->GetX() - 7, $pdf->GetY() + 5, 'dotted');
$pdf->Ln(6);

$pdf->SetLineWidth(0.3);
$pdf->SetFont('thsarabun', 0, 10);
$electricalOther = truncateText($detail_arr['Electrical']['inputValue']['other'][0]);
$pdf->Image($electricalOther != '' ? "./icon/check-box.jpg" : "./icon/square.jpg", $pdf->GetX() + 2, $pdf->GetY() + 1, 4, 4);
$pdf->Cell(50, 6, '        อื่นๆ   ' . $electricalOther, 'L', 0, 'L');
$pdf->Line($pdf->GetX() - 38, $pdf->GetY() + 5, $pdf->GetX() - 9, $pdf->GetY() + 5, 'dotted');
$busbarOther = truncateText($detail_arr['Busbar']['inputValue']['other'][0]);
$pdf->Image($busbarOther != '' ? "./icon/check-box.jpg" : "./icon/square.jpg", $pdf->GetX() + 2, $pdf->GetY() + 1, 4, 4);
$pdf->Cell(35, 6, '        อื่นๆ   ' . $busbarOther, '', 0, 'L');
$pdf->Line($pdf->GetX() - 23, $pdf->GetY() + 5, $pdf->GetX() - 5, $pdf->GetY() + 5, 'dotted');
$ironworkOther = truncateText($detail_arr['Ironwork']['inputValue']['other'][0]);
$pdf->Image($ironworkOther != '' ? "./icon/check-box.jpg" : "./icon/square.jpg", $pdf->GetX() + 2, $pdf->GetY() + 1, 4, 4);
$pdf->Cell(35, 6, '        อื่นๆ   ' . $ironworkOther, '', 0, 'L');
$pdf->Line($pdf->GetX() - 23, $pdf->GetY() + 5, $pdf->GetX() - 5, $pdf->GetY() + 5, 'dotted');
$modelOther = truncateText($detail_arr['Model']['inputValue']['other'][0]);
$pdf->Image($modelOther != '' ? "./icon/check-box.jpg" : "./icon/square.jpg", $pdf->GetX() + 2, $pdf->GetY() + 1, 4, 4);
$pdf->Cell(35, 6, '        อื่นๆ   ' . $modelOther, '', 0, 'L');
$pdf->Line($pdf->GetX() - 23, $pdf->GetY() + 5, $pdf->GetX() - 5, $pdf->GetY() + 5, 'dotted');
$closingsheetOther = truncateText($detail_arr['Closingsheet']['inputValue']['other'][0]);
$pdf->Image($closingsheetOther != '' ? "./icon/check-box.jpg" : "./icon/square.jpg", $pdf->GetX() + 2, $pdf->GetY() + 1, 4, 4);
$pdf->Cell(41, 6, '        อื่นๆ   ' . $closingsheetOther, 'R', 0, 'L');
$pdf->Line($pdf->GetX() - 28, $pdf->GetY() + 5, $pdf->GetX() - 7, $pdf->GetY() + 5, 'dotted');
$pdf->Ln(6);

$pdf->SetLineWidth(0.3);
$pdf->SetFont('thsarabun', '', 10);
$pdf->Cell(50, 6, '* สาเหตุ', 'L', 0, 'L');
$pdf->Cell(35, 6, '', '', 0, 'L');
$pdf->Cell(35, 6, '', '', 0, 'L');
$pdf->Cell(35, 6, '', '', 0, 'L');
$pdf->Cell(41, 6, '', 'R', 0, 'L');
$pdf->Ln(6);

$pdf->SetLineWidth(0.3);
$pdf->SetFont('thsarabun', 0, 10);
$pdf->Image($inputChk[1] != '' ? "./icon/check-box.jpg" : "./icon/square.jpg", $pdf->GetX() + 2, $pdf->GetY() + 1, 4, 4);
$pdf->Cell(85, 6, '        อุปกรณ์ลูกค้าจัดไม่ครบ/ไม่สมบูรณ์', 'L', 0, 'L');
$pdf->Image($inputChk[3] != '' ? "./icon/check-box.jpg" : "./icon/square.jpg", $pdf->GetX() + 2, $pdf->GetY() + 1, 4, 4);
$pdf->Cell(70, 6, '        มีการเพิ่มชิ้นงาน', '', 0, 'L');
$pdf->Image($inputChk[5] != '' ? "./icon/check-box.jpg" : "./icon/square.jpg", $pdf->GetX() + 2, $pdf->GetY() + 1, 4, 4);
$pdf->Cell(41, 6, '        ลูกค้าขอเพิ่มข้อมูล', 'R', 0, 'L');
$pdf->Ln(6);

$pdf->SetLineWidth(0.3);
$pdf->SetFont('thsarabun', 0, 10);
$pdf->Image($inputChk[2] != '' ? "./icon/check-box.jpg" : "./icon/square.jpg", $pdf->GetX() + 2, $pdf->GetY() + 1, 4, 4);
$pdf->Cell(85, 6, '        ลูกค้าเปลี่ยนแปลงแบบ', 'L', 0, 'L');
$pdf->Image($inputChk[4] != '' ? "./icon/check-box.jpg" : "./icon/square.jpg", $pdf->GetX() + 2, $pdf->GetY() + 1, 4, 4);
$pdf->Cell(70, 6, '        มีการ Modify ชิ้นงาน', '', 0, 'L');
$pdf->Image($inputChk[6] != '' ? "./icon/check-box.jpg" : "./icon/square.jpg", $pdf->GetX() + 2, $pdf->GetY() + 1, 4, 4);
$pdf->Cell(41, 6, '        ลูกค้าแก้ไขฟังก์ชั่น', 'R', 0, 'L');
$pdf->Ln(6);

$file = array();
foreach ($file_arr as $key => $value) {
    // $filename = explode('/', $value['path_file']);
    $filename = $value['name_files'];
    // $file[] = $filename[2];
    $file[] = $filename;
}

$pdf->SetLineWidth(0.3);
$pdf->SetFont('thsarabun', 0, 10);
$inputChkLast = is_array($inputChk ?? null) && !empty($inputChk) ? end($inputChk) : '';
$pdf->Image(!is_numeric($inputChkLast) && $inputChkLast != '' ? "./icon/check-box.jpg" : "./icon/square.jpg", $pdf->GetX() + 2, $pdf->GetY() + 1, 4, 4);
$value_other = !is_numeric($inputChkLast) && $inputChkLast != '' ? $inputChkLast : "";
$pdf->Cell(85, 6, '        อื่นๆ  ' . truncateText($value_other, 40), 'L', 0, 'L');
$pdf->Line($pdf->GetX() - 73, $pdf->GetY() + 5, $pdf->GetX() - 9, $pdf->GetY() + 5, 'dotted');
$pdf->Image(count($file) != 0 ? "./icon/check-box.jpg" : "./icon/square.jpg", $pdf->GetX() + 2, $pdf->GetY() + 1, 4, 4);
$pdf->Cell(111, 6, '        เอกสารแนบ(โปรดระบุ)   ' . truncateText($file[0], 60), 'R', 0, 'L');
$pdf->Line($pdf->GetX() - 80, $pdf->GetY() + 5, $pdf->GetX() - 5, $pdf->GetY() + 5, 'dotted');
$pdf->Ln(6);

$pdf->SetLineWidth(0.3);
$pdf->SetFont('thsarabun', 0, 10);
$pdf->Image($inputChk[18] != '' ? "./icon/check-box.jpg" : "./icon/square.jpg", $pdf->GetX() + 2, $pdf->GetY() + 1, 4, 4);
$pdf->Cell(80, 6, '        ลูกค้าเลื่อนส่งสินค้าเป็นวันที่      ' . $detail_custom[0], 'L', 0, 'L');
$pdf->Line($pdf->GetX() - 45, $pdf->GetY() + 5, $pdf->GetX() - 4, $pdf->GetY() + 5, 'dotted');
$pdf->Cell(116, 6, 'เนื่องจาก  ' . $detail_custom[2], 'R', 0, 'L');
$pdf->Line($pdf->GetX() - 105, $pdf->GetY() + 5, $pdf->GetX() - 5, $pdf->GetY() + 5, 'dotted');
$pdf->Ln(6);

$pdf->SetLineWidth(0.3);
$pdf->SetFont('thsarabun', 0, 10);
$pdf->Cell(50, 6, 'เปลี่ยนแปลงครั้งที่            ' . $data_arr['rev'], 'L', 0, 'L');
$pdf->Line($pdf->GetX() - 30, $pdf->GetY() + 5, $pdf->GetX() - 15, $pdf->GetY() + 5, 'dotted');
$pdf->Cell(20, 6, '', '', 0, 'L');
$pdf->Cell(45, 6, 'กำหนดเสร็จ    ' . $detail_custom[1], '', 0, 'L');
$pdf->Line($pdf->GetX() - 30, $pdf->GetY() + 5, $pdf->GetX() - 9, $pdf->GetY() + 5, 'dotted');
$pdf->Cell(45, 6, 'มูลค่าใบเปิดงาน              ' . $data_arr['cost'], '', 0, 'L');
$pdf->Line($pdf->GetX() - 30, $pdf->GetY() + 5, $pdf->GetX() - 2, $pdf->GetY() + 5, 'dotted');
$pdf->Cell(36, 6, 'บาท', 'R', 0, 'L');
$pdf->Ln(6);

$pdf->SetLineWidth(0.3);
$pdf->SetFont('thsarabun', 'BU', 10);
$pdf->Cell('', 6, 'ส่วนของการวิเคราะห์ผลกระทบที่เกิดขึ้น', 'RLT', 0, 'L');
$pdf->Ln(6);

$job_remark = explode("\n", str_replace("\t", '    ', str_replace("\r\n", "\n", $data_arr['job_remark'])));
$pdf->SetLineWidth(0.3);
$pdf->SetFont('thsarabun', '', 10);
$pdf->Cell('', 5, 'ผลกระทบที่เกิดจากการเปลี่ยนแปลง         ' . $job_remark[0], 'RL', 0, 'L');
$pdf->Line($pdf->GetX() - 155, $pdf->GetY() + 5, $pdf->GetX() - 5, $pdf->GetY() + 5, 'dotted');
$pdf->Ln(5);

$pdf->SetLineWidth(0.3);
$pdf->SetFont('thsarabun', '', 10);
$pdf->Cell('', 5, '          ' . $job_remark[1], 'RL', 0, 'L');
$pdf->Line($pdf->GetX() - 190, $pdf->GetY() + 5, $pdf->GetX() - 5, $pdf->GetY() + 5, 'dotted');
$pdf->Ln(5);

$pdf->SetLineWidth(0.3);
$pdf->SetFont('thsarabun', '', 10);
$pdf->Cell('', 5, '          ' . $job_remark[2], 'RL', 0, 'L');
$pdf->Line($pdf->GetX() - 190, $pdf->GetY() + 5, $pdf->GetX() - 5, $pdf->GetY() + 5, 'dotted');
$pdf->Ln(5);

$pdf->SetLineWidth(0.3);
$pdf->SetFont('thsarabun', '', 10);
$pdf->Cell('', 6, '          ' . $job_remark[3], 'RL', 0, 'L');
$pdf->Line($pdf->GetX() - 190, $pdf->GetY() + 5, $pdf->GetX() - 5, $pdf->GetY() + 5, 'dotted');
$pdf->Ln(6);

$pdf->SetLineWidth(0.3);
$pdf->SetFont('thsarabun', 'U', 10);
$pdf->Cell('', 6, 'ประมาณการค่าใช้จ่ายที่เกิดขึ้น', 'RL', 0, 'L');
$pdf->Ln(6);

$pdf->SetLineWidth(0.3);
$pdf->SetFont('thsarabun', 0, 10);
$pdf->Cell(55, 6, 'ค่าใช้จ่ายครั้งนี้                   ' . $data_arr['expenses'], 'L', 0, 'L');
$pdf->Line($pdf->GetX() - 40, $pdf->GetY() + 5, $pdf->GetX() - 2, $pdf->GetY() + 5, 'dotted');
$pdf->Cell(10, 6, 'บาท', '', 0, 'L');
$pdf->Cell(45, 6, 'ค่าใช้จ่ายสะสมรวม             ' . $data_arr['expenses_total'], '', 0, 'L');
$pdf->Line($pdf->GetX() - 25, $pdf->GetY() + 5, $pdf->GetX() - 2, $pdf->GetY() + 5, 'dotted');
$pdf->Cell('', 6, 'บาท', 'R', 0, 'L');
$pdf->Ln(6);

$pdf->SetLineWidth(0.3);
$pdf->SetFont('thsarabun', 'U', 10);
$pdf->Cell('', 6, 'สรุปค่าใช้จ่ายที่เกิดขึ้น (โดยฝ่ายขาย)', 'RL', 0, 'L');
$pdf->Ln(6);

$expensesummary_arr = json_decode($data_arr['expensesummary'], true);
$pdf->SetLineWidth(0.3);
$pdf->SetFont('thsarabun', 0, 10);
$pdf->Image($expensesummary_arr['doc_charge'] != '' ? './icon/check-box.jpg' : './icon/square.jpg', $pdf->GetX() + 2, $pdf->GetY() + 1, 4, 4);
$pdf->Cell(15, 6, '        คิดเงิน', 'L', 0, 'L');
$pdf->Image($expensesummary_arr['doc_newwork'] != '' ? './icon/check-box.jpg' : './icon/square.jpg', $pdf->GetX() + 2, $pdf->GetY() + 1, 4, 4);
$doc_newwork = $expensesummary_arr['doc_newwork'] != '' ? $expensesummary_arr['doc_newwork'] : '';
$pdf->Cell(80, 6, '        เปิดงานใหม่เลขที่                               ' . $doc_newwork, '', 0, 'L');
$pdf->Line($pdf->GetX() - 45, $pdf->GetY() + 5, $pdf->GetX() - 2, $pdf->GetY() + 5, 'dotted');
$doc_charge = $expensesummary_arr['doc_charge'] != '' ? $expensesummary_arr['doc_charge'] : '';
$pdf->Cell(40, 6, '        จำนวนเงิน          ' . $doc_charge, '', 0, 'L');
$pdf->Line($pdf->GetX() - 20, $pdf->GetY() + 5, $pdf->GetX() - 2, $pdf->GetY() + 5, 'dotted');
$pdf->Cell(61, 6, 'บาท', 'R', 0, 'L');
$pdf->Ln(6);

$pdf->SetLineWidth(0.3);
$pdf->SetFont('thsarabun', 0, 10);
$pdf->Image($expensesummary_arr['inasmuch'] != '' ? './icon/check-box.jpg' : './icon/square.jpg', $pdf->GetX() + 2, $pdf->GetY() + 1, 4, 4);
$inasmuch = $expensesummary_arr['inasmuch'] != '' ? $expensesummary_arr['inasmuch'] : '';
$pdf->Cell('', 6, '        ไม่คิดเงินเนื่องจาก        ' . $inasmuch, 'LR', 0, 'L');
$pdf->Line($pdf->GetX() - 170, $pdf->GetY() + 5, $pdf->GetX() - 2, $pdf->GetY() + 5, 'dotted');
$pdf->Ln(6);

$pdf->SetLineWidth(0.3);
$pdf->SetFont('thsarabun', 'U', 10);
$pdf->Cell('', 6, '*กำหนดให้ผู้เกี่ยวข้องดำเนินการ: ', 'RL', 0, 'L');
$pdf->Ln(6);

$related_arr = json_decode($data_arr['related'], true);
foreach ($related_arr['divitext'] as $key => $value) {
    $related[$value] = $value;
}
$pdf->SetLineWidth(0.3);
$pdf->SetFont('thsarabun', 0, 10);
$pdf->Image($related[3] != '' ? "./icon/check-box.jpg" : "./icon/square.jpg", $pdf->GetX() + 2, $pdf->GetY() + 1, 4, 4);
$pdf->Cell(56, 5, '        ' . $division_list[3]['Division_Name'], 'L', 0, 'L');
$pdf->Image($related[4] != '' ? "./icon/check-box.jpg" : "./icon/square.jpg", $pdf->GetX() + 2, $pdf->GetY() + 1, 4, 4);
$pdf->Cell(40, 5, '        ' . $division_list[4]['Division_Name'], '', 0, 'L');
$pdf->Image($related[6] != '' ? "./icon/check-box.jpg" : "./icon/square.jpg", $pdf->GetX() + 2, $pdf->GetY() + 1, 4, 4);
$pdf->Cell(40, 5, '        ' . $division_list[6]['Division_Name'], '', 0, 'L');
$pdf->Image($related[11] != '' ? "./icon/check-box.jpg" : "./icon/square.jpg", $pdf->GetX() + 2, $pdf->GetY() + 1, 4, 4);
$pdf->Cell(60, 5, '        ' . $division_list[11]['Division_Name'], 'R', 0, 'L');
$pdf->Ln(5);

$pdf->SetLineWidth(0.3);
$pdf->SetFont('thsarabun', 0, 10);
$pdf->Image($related[1] != '' ? "./icon/check-box.jpg" : "./icon/square.jpg", $pdf->GetX() + 2, $pdf->GetY() + 1, 4, 4);
$pdf->Cell(56, 5, '        ' . $division_list[1]['Division_Name'], 'L', 0, 'L');
$pdf->Image($related[8] != '' ? "./icon/check-box.jpg" : "./icon/square.jpg", $pdf->GetX() + 2, $pdf->GetY() + 1, 4, 4);
$pdf->Cell(40, 5, '        ' . $division_list[8]['Division_Name'], '', 0, 'L');
$pdf->Image($related[9] != '' ? "./icon/check-box.jpg" : "./icon/square.jpg", $pdf->GetX() + 2, $pdf->GetY() + 1, 4, 4);
$pdf->Cell(40, 5, '        ' . $division_list[9]['Division_Name'], '', 0, 'L');
$pdf->Image($related_arr['divitext_other'] != '' ? './icon/check-box.jpg' : './icon/square.jpg', $pdf->GetX() + 2, $pdf->GetY() + 1, 4, 4);
$pdf->Cell(60, 5, '        อื่นๆ    ' . $related_arr['divitext_other'], 'R', 0, 'L');
$pdf->Line($pdf->GetX() - 48, $pdf->GetY() + 5, $pdf->GetX() - 20, $pdf->GetY() + 5, 'dotted');
$pdf->Ln(5);

$pdf->SetLineWidth(0.3);
$pdf->SetFont('thsarabun', 0, 10);
$pdf->Image($related[2] != '' ? './icon/check-box.jpg' : './icon/square.jpg', $pdf->GetX() + 2, $pdf->GetY() + 1, 4, 4);
$pdf->Cell(56, 6, '        ' . $division_list[2]['Division_Name'], 'L', 0, 'L');
$pdf->Image($related[5] != '' ? './icon/check-box.jpg' : './icon/square.jpg', $pdf->GetX() + 2, $pdf->GetY() + 1, 4, 4);
$pdf->Cell(40, 6, '        ' . $division_list[5]['Division_Name'], '', 0, 'L');
$pdf->Image($related[10] != '' ? './icon/check-box.jpg' : './icon/square.jpg', $pdf->GetX() + 2, $pdf->GetY() + 1, 4, 4);
$pdf->Cell(100, 6, '        ' . $division_list[10]['Division_Name'], 'R', 0, 'L');
$pdf->Ln(6);

$pdf->SetLineWidth(0.3);
$pdf->SetFont('thsarabun', 'B', 11);
$pdf->Cell('', 6, 'ส่วนของการทบทวน', 1, 0, 'C');
$pdf->Ln(6);

$pdf->SetLineWidth(0.3);
$pdf->SetFont('thsarabun', 'B', 11);
$pdf->Cell(65, 6, 'ผู้แจ้ง', 1, 0, 'C');
$pdf->Cell(65, 6, 'เจ้าหน้าที่เทคนิคฯ', 1, 0, 'C');
$pdf->Cell(66, 6, 'ผจก.แผนก/ฝ่ายเทคนิคฯ', 1, 0, 'C');
$pdf->Ln(6);

$apporve_arr_1 = json_decode($data_arr['CR_Approve1'], true);
$apporve_arr_2 = json_decode($data_arr['CR_Approve2'], true);
$apporve_arr_3 = json_decode($data_arr['CR_Approve3'], true);
$apporve_arr_4 = json_decode($data_arr['CR_Approve4'], true);
$apporve_arr_5 = json_decode($data_arr['CR_Approve5'], true);

$pdf->SetLineWidth(0.3);
$pdf->SetFont('thsarabun', 'B', 11);
$apporve = is_file2('https://erpapp.asefa.co.th/' . mydata($data_arr['userCreate'])['SignatureFile']) ?? '';
$pdf->Image($apporve, $pdf->GetX() + 25, $pdf->GetY() + 1, 15, 7);
$apporve_name = $apporve == '' ? '(        ' . mydata($data_arr['userCreate'])['FirstName'] . '        )' : '(                                      )';
$pdf->Cell(65, 10, $apporve_name, 'LR', 0, 'C');
$apporve = $apporve_arr_1['status_approve'] == '1' ? is_file2('https://erpapp.asefa.co.th/' . mydata($apporve_arr_1['Approve_1'])['SignatureFile']) : 'https://innovation.asefa.co.th/ChangeRequestForm/icon/bg-009.jpg';
$pdf->Image($apporve, $pdf->GetX() + 25, $pdf->GetY() + 1, 15, 7);
$pdf->Cell(65, 8, '(                                      )', 'LR', 0, 'C');
$apporve = $apporve_arr_2['status_approve'] == '1' ? is_file2('https://erpapp.asefa.co.th/' . mydata($apporve_arr_2['Approve_2'])['SignatureFile']) : 'https://innovation.asefa.co.th/ChangeRequestForm/icon/bg-009.jpg';
$pdf->Image($apporve, $pdf->GetX() + 25, $pdf->GetY() + 1, 15, 7);
$pdf->Cell(66, 8, '(                                      )', 'LR', 0, 'C');
$pdf->Ln(6);

$pdf->SetLineWidth(0.3);
$pdf->SetFont('thsarabun', 'B', 11);
$datetime = new DateTime($data_arr['dateCreate']);
$custom_date = explode('/', $datetime->format('d/m/Y'));
$custom_date[2] = $custom_date[2] + 543;
$pdf->Cell(65, 7, 'วันที่ ...... ' . $custom_date[0] . ' ......./...... ' . $custom_date[1] . ' ........../....... ' . $custom_date[2] . ' .........', 'LR', 0, 'C');

if ($apporve_arr_1['status_approve'] == '1') {
    $datetime = new DateTime($apporve_arr_1['date_approve']);
    $custom_date = explode('/', $datetime->format('d/m/Y'));
    $custom_date[2] = $custom_date[2] + 543;
    $pdf->Cell(65, 7, 'วันที่ ...... ' . $custom_date[0] . ' ......./...... ' . $custom_date[1] . ' ........../....... ' . $custom_date[2] . ' .........', 'LR', 0, 'C');
} else {
    $pdf->Cell(65, 8, 'วันที่ .............../................../...................', 'LRB', 0, 'C');
}

if ($apporve_arr_2['status_approve'] == '1') {
    $datetime = new DateTime($apporve_arr_2['date_approve']);
    $custom_date = explode('/', $datetime->format('d/m/Y'));
    $custom_date[2] = $custom_date[2] + 543;
    $pdf->Cell(66, 7, 'วันที่ ...... ' . $custom_date[0] . ' ......./...... ' . $custom_date[1] . ' ........../....... ' . $custom_date[2] . ' .........', 'LR', 0, 'C');
} else {
    $pdf->Cell(66, 8, 'วันที่ .............../................../...................', 'LRB', 0, 'C');
}
$pdf->Ln(7);

$pdf->SetLineWidth(0.3);
$pdf->SetFont('thsarabun', 'B', 11);
$pdf->Cell('', 6, 'ส่วนของการอนุมัติ', 1, 0, 'C');
$pdf->Ln(6);

$pdf->SetLineWidth(0.3);
$pdf->SetFont('thsarabun', 'B', 11);
$pdf->Cell(65, 6, 'พนักงานขาย', 1, 0, 'C');
$pdf->Cell(65, 6, 'ผจก.แผนก/ฝ่ายขาย', 1, 0, 'C');
$pdf->Cell(66, 6, 'ประธานเจ้าหน้าที่บริหาร', 1, 0, 'C');
$pdf->Ln(6);

$pdf->SetLineWidth(0.3);
$pdf->SetFont('thsarabun', 'B', 11);
$apporve = $apporve_arr_3['status_approve'] == '1' ? is_file2('https://erpapp.asefa.co.th/' . mydata($apporve_arr_3['Approve_3'])['SignatureFile']) : 'https://innovation.asefa.co.th/ChangeRequestForm/icon/bg-009.jpg';
$pdf->Image($apporve, $pdf->GetX() + 25, $pdf->GetY() + 1, 15, 7);
$pdf->Cell(65, 8, '(                                      )', 'LR', 0, 'C');
$apporve = $apporve_arr_4['status_approve'] == '1' ? is_file2('https://erpapp.asefa.co.th/' . mydata($apporve_arr_4['Approve_4'])['SignatureFile']) : 'https://innovation.asefa.co.th/ChangeRequestForm/icon/bg-009.jpg';
$pdf->Image($apporve, $pdf->GetX() + 25, $pdf->GetY() + 1, 15, 7);
$pdf->Cell(65, 8, '(                                      )', 'LR', 0, 'C');
$apporve = $apporve_arr_5['Approve_5'] != '' && $apporve_arr_5['Approve_5'] != '-' && $apporve_arr_5['status_approve'] == '1' ? is_file2('https://erpapp.asefa.co.th/' . mydata($apporve_arr_5['Approve_5'])['SignatureFile']) : 'https://innovation.asefa.co.th/ChangeRequestForm/icon/bg-009.jpg';
$pdf->Image($apporve, $pdf->GetX() + 25, $pdf->GetY() + 1, 15, 7);
$pdf->Cell(66, 8, '(                                      )', 'LR', 0, 'C');
$pdf->Ln(6);

$pdf->SetLineWidth(0.3);
$pdf->SetFont('thsarabun', 'B', 11);
if ($apporve_arr_3['status_approve'] == '1') {
    $datetime = new DateTime($apporve_arr_3['date_approve']);
    $custom_date = explode('/', $datetime->format('d/m/Y'));
    $custom_date[2] = $custom_date[2] + 543;
    $pdf->Cell(65, 8, 'วันที่ ...... ' . $custom_date[0] . ' ......./...... ' . $custom_date[1] . ' ........../....... ' . $custom_date[2] . ' .........', 'LRB', 0, 'C');
} else {
    $pdf->Cell(65, 8, 'วันที่ .............../................../...................', 'LRB', 0, 'C');
}

if ($apporve_arr_4['status_approve'] == '1') {
    $datetime = new DateTime($apporve_arr_4['date_approve']);
    $custom_date = explode('/', $datetime->format('d/m/Y'));
    $custom_date[2] = $custom_date[2] + 543;
    $pdf->Cell(65, 8, 'วันที่ ...... ' . $custom_date[0] . ' ......./...... ' . $custom_date[1] . ' ........../....... ' . $custom_date[2] . ' .........', 'LRB', 0, 'C');
} else {
    $pdf->Cell(65, 8, 'วันที่ .............../................../...................', 'LRB', 0, 'C');
}

if ($apporve_arr_5['date_approve'] != '' && $apporve_arr_5['status_approve'] == '1') {
    $datetime = new DateTime($apporve_arr_5['date_approve']);
    $custom_date = explode('/', $datetime->format('d/m/Y'));
    $custom_date[2] = $custom_date[2] + 543;
    $pdf->Cell(66, 8, 'วันที่ ...... ' . $custom_date[0] . ' ......./...... ' . $custom_date[1] . ' ........../....... ' . $custom_date[2] . ' .........', 'LRB', 0, 'C');
} else {
    $pdf->Cell(66, 8, 'วันที่ .............../................../...................', 'LRB', 0, 'C');
}
$pdf->Ln('');

$pdf->SetLineWidth(0.3);
$pdf->SetFont('thsarabun', '', 10);
$pdf->Cell(98, 4, 'ส่วนของการอนุมัติ กรณีมีค่าใช้จ่ายและผลกระทบ อ้างอิงตามขอบเขตอำนาจอนุมัติของฝ่ายขาย', '', 0, 'L');
$pdf->Ln(4);

// $pdf->Cell(98, 4, '*ต้องลงข้อมูลทุกครั้ง', 0, 1, 'L', false, '', 1, $pdf->GetX(), $pdf->GetY());

$html = '
	<div align="right">
		<strong>FM-TTTC-002 (02)<br>เริ่มใช้ 03 มี.ค. 2568</strong>
	</div>
';
$pdf->writeHTML($html, true, false, true, false, '');
$pdf->Ln(8);

$pdf->AddPage('P', 'A4');

$pdf->Ln(3);

$pdf->SetLineWidth(0.3);
$pdf->SetFont('thsarabun', 'BU', 11);
$pdf->Cell('', 6, 'รายละเอียดและสาเหตุของการเปลี่ยนแปลง', 'LRT', 0, 'L');
$pdf->Ln(6);

$pdf->SetLineWidth(0.3);
$pdf->SetFont('thsarabun', 'B', 11);
$pdf->Cell('', 6, '*โปรดระบุหัวข้อการเปลี่ยนแปลงก่อนการเขียนรายละเอียดการเปลี่ยนแปลง', 'LR', 0, 'L');
$pdf->Ln(6);

$details = explode("\n", str_replace("\t", '    ', str_replace("\r\n", "\n", $data_arr['details'])));
if (count($selectWA) > 2) {
    $details = array_merge($details, $data_arr_wa);
}
if (count($selectedSNs) > 2) {
    $details = array_merge($details, $data_arr_sn);
}
// if($_SESSION['ChangeRequest_user_id'] == '2776'){
//     echo "<pre>";
//     print_r($data_arr['details']);
//     exit();
// }
for ($i = 0; $i < 37; $i++) {
    $pdf->SetLineWidth(0.3);
    $pdf->SetFont('thsarabun', 'B', 11);
    $pdf->Cell('', 7, '      ' . $details[$i], 'LR', 0, 'L');
    $pdf->Line($pdf->GetX() - 192, $pdf->GetY() + 6, $pdf->GetX() - 5, $pdf->GetY() + 6, 'dotted');
    $pdf->Ln(7);
}

$pdf->Cell('', 7, ' ', 'T', 0, '');
$pdf->Ln(1);
$html = '
	<div align="right">
		<strong>FM-TTTC-002 (02)<br>เริ่มใช้ 03 มี.ค. 2568</strong>
	</div>
';
$pdf->writeHTML($html, true, false, true, false, '');
$pdf->Ln(8);

$start = 37;
$perPage = 37;
$total = count($details);

while ($start < $total) {
    $pdf->AddPage('P', 'A4');
    $pdf->Ln(3);

    $pdf->SetLineWidth(0.3);
    $pdf->SetFont('thsarabun', 'BU', 11);
    $pdf->Cell('', 6, 'รายละเอียดและสาเหตุของการเปลี่ยนแปลง', 'LRT', 0, 'L');
    $pdf->Ln(6);

    $pdf->SetLineWidth(0.3);
    $pdf->SetFont('thsarabun', 'B', 11);
    $pdf->Cell('', 6, '*โปรดระบุหัวข้อการเปลี่ยนแปลงก่อนการเขียนรายละเอียดการเปลี่ยนแปลง', 'LR', 0, 'L');
    $pdf->Ln(6);

    $end = min($start + $perPage, $total);
    $linesPrinted = 0;

    for ($i = $start; $i < $end; $i++) {
        $pdf->SetLineWidth(0.3);
        $pdf->SetFont('thsarabun', 'B', 11);
        $pdf->Cell('', 7, '      ' . $details[$i], 'LR', 0, 'L');
        $pdf->Line($pdf->GetX() - 192, $pdf->GetY() + 6, $pdf->GetX() - 5, $pdf->GetY() + 6, 'dotted');
        $pdf->Ln(7);
        $linesPrinted++;
    }

    while ($linesPrinted < $perPage) {
        $pdf->SetLineWidth(0.3);
        $pdf->Cell('', 7, '', 'LR', 0, 'L'); // ช่องว่างเพื่อรักษาโครงร่างตาราง
        $pdf->Line($pdf->GetX() - 192, $pdf->GetY() + 6, $pdf->GetX() - 5, $pdf->GetY() + 6, 'dotted');
        $pdf->Ln(7);
        $linesPrinted++;
    }

    $pdf->Cell('', 7, ' ', 'T', 0, '');
    $pdf->Ln(1);

    $html = '
        <div align="right">
            <strong>FM-TTTC-002 (02)<br>เริ่มใช้ 03 มี.ค. 2568</strong>
        </div>
    ';
    $pdf->writeHTML($html, true, false, true, false, '');
    $pdf->Ln(8);

    $start += $perPage;
}



$pdf->Output($CR_no . '.pdf', 'I');
