<?php

function download_image($url)
{
	$ch = curl_init($url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
	$data = curl_exec($ch);
	curl_close($ch);

	$temp_file = tempnam(sys_get_temp_dir(), 'tcpdf_') . uniqid() . '.png';
	file_put_contents($temp_file, $data);
	return $temp_file;
}

function is_file2($file_url)
{
	$file_path = str_replace("https://erpapp.asefa.co.th/", "D:/webserver/www/https_erpapp.asefa.co.th/", $file_url);
	if (is_file($file_path)) {
		return $file_path;
	}

	$replace_years = ['2023', '2022', '2021', '2020'];
	foreach ($replace_years as $year) {
		$new_path = str_replace("zdownload/", "zdownload$year/", $file_path);
		if (is_file($new_path)) {
			return $new_path;
		}
	}

	return $file_url ?? '';
}

/**
 * แปลง PNG ที่มี alpha channel เป็น temporary JPEG file
 * เพื่อหลีกเลี่ยง TCPDF error กับ PNG transparency
 */
function convertPngToJpeg($imagePath)
{
	// ถ้าไม่ใช่ PNG ให้ return ตามเดิม
	$ext = strtolower(pathinfo($imagePath, PATHINFO_EXTENSION));
	if ($ext !== 'png') {
		return $imagePath;
	}

	// ตรวจสอบว่าไฟล์มีอยู่
	if (!file_exists($imagePath)) {
		return $imagePath;
	}

	// สร้าง temp file path
	$tempDir = sys_get_temp_dir();
	$tempFile = $tempDir . '/tcpdf_' . md5($imagePath) . '.jpg';

	// ถ้า temp file มีอยู่แล้วและใหม่กว่า original ให้ใช้เลย
	if (file_exists($tempFile) && filemtime($tempFile) >= filemtime($imagePath)) {
		return $tempFile;
	}

	// โหลด PNG และแปลงเป็น JPEG
	$png = @imagecreatefrompng($imagePath);
	if ($png === false) {
		return $imagePath;
	}

	// สร้าง canvas สีขาว
	$width = imagesx($png);
	$height = imagesy($png);
	$jpg = imagecreatetruecolor($width, $height);
	$white = imagecolorallocate($jpg, 255, 255, 255);
	imagefill($jpg, 0, 0, $white);

	// วาง PNG ลงบน canvas (alpha จะถูก merge กับสีขาว)
	imagecopy($jpg, $png, 0, 0, 0, 0, $width, $height);

	// บันทึกเป็น JPEG
	imagejpeg($jpg, $tempFile, 95);

	// ล้าง memory
	imagedestroy($png);
	imagedestroy($jpg);

	return $tempFile;
}

function get_idref($username)
{
	global $konnext_lqsym;
	$sql            =    "
							SELECT		site_id_10  as 'id_ref'
							FROM		work_progress_010
							WHERE		site_f_365 = '" . $username . "'
                            AND site_f_3005 = '600'
						";
	$query        =    mysqli_query($konnext_lqsym, $sql) or die(mysqli_error($konnext_lqsym));
	$num = mysqli_num_rows($query);
	if ($num > 0) {
		while ($row    =    mysqli_fetch_array($query, MYSQLI_ASSOC)) {
			$id = $row['id_ref'];
		}
	}
	return $id;
}

function mydata($idref)
{
	global $konnext_lqsym;
	$sql            =    "
							SELECT		site_id_10  as 'id_ref',
                                        site_f_5824 as 'ApproveEmployeeText0',
										site_f_3877 as 'SignatureFile',
                                        site_f_2363 as 'TokenMD5',
                                        site_f_366 as 'FullName',
                                        site_f_2327 as 'FirstName',
                                        site_f_365 as 'Code'
							FROM		work_progress_010
							WHERE		(site_id_10 = '" . $idref . "' OR site_f_365 = '" . $idref . "')
                            AND site_f_3005 = '600'
						";
	$query        =    mysqli_query($konnext_lqsym, $sql) or die(mysqli_error($konnext_lqsym));
	$num = mysqli_num_rows($query);
	if ($num > 0) {
		while ($row    =    mysqli_fetch_array($query, MYSQLI_ASSOC)) {
			$data = $row;
		}
	}
	return $data;
}

function emplist()
{
	global $konnext_lqsym;

	$sql = " 
		SELECT
				site_id_10  as 'id_ref',
				site_f_366 as 'FullName',
				site_f_3877 as 'SignatureFile',
				site_f_365 as 'Code'
		FROM 	work_progress_010
		WHERE	site_f_3005 = '600'
		AND (
			site_f_398 = '0000-00-00' OR site_f_398 > CURRENT_DATE() 
		)
	";

	$query        =    mysqli_query($konnext_lqsym, $sql) or die(mysqli_error($konnext_lqsym));
	$num = mysqli_num_rows($query);
	if ($num > 0) {
		while ($row    =    mysqli_fetch_array($query, MYSQLI_ASSOC)) {
			$data[$row['Code']] = $row;
		}
	}
	return $data;
}

function ListDivision()
{
	global $konnext_lqsym;
	$sql            =    "
							SELECT		site_id_15  as 'id_ref',
                                        site_f_1144 as 'Code',
                                        site_f_1145 as 'Name'
							FROM		work_progress_015
							WHERE		site_f_3560 = '1' OR site_f_3560 = '2'
                            AND site_f_3010 = '600'
                            AND site_f_2994 = '0'
							AND site_f_1145 <> '-'
						";
	$query        =    mysqli_query($konnext_lqsym, $sql) or die(mysqli_error($konnext_lqsym));
	$num = mysqli_num_rows($query);
	if ($num > 0) {
		while ($row    =    mysqli_fetch_array($query, MYSQLI_ASSOC)) {
			$data[$row['id_ref']] = $row;
		}
	}
	return $data;
}

function ProductList()
{
	global $konnext_DB64;

	$sql = "
        SELECT
            Product_ID,
            Product_Name,
            Edit_By,
            Date_Edit
        FROM CR_Product
        WHERE status = 0
    ";
	$query  =    sqlsrv_query($konnext_DB64, $sql) or die(sqlsrv_errors($konnext_DB64));
	while ($row = sqlsrv_fetch_array($query, SQLSRV_FETCH_ASSOC)) {
		$data[$row['Product_ID']] = $row;
	}
	return $data;
}

function CauseList($type = null)
{
	global $konnext_DB64;

	$sql = "
        SELECT
            Cause_ID,
            Cause_Name,
			Cause_Type,
            Edit_By,
            Date_Edit
        FROM CR_Cause
        WHERE status = 0
    ";

	if ($type != null) {
		$sql .= " AND Cause_Type = '" . $type . "'";
	}

	$query  =    sqlsrv_query($konnext_DB64, $sql) or die(sqlsrv_errors($konnext_DB64));
	while ($row = sqlsrv_fetch_array($query, SQLSRV_FETCH_ASSOC)) {
		$data[$row['Cause_ID']] = $row;
	}
	return $data;
}

function DivisionList()
{
	global $konnext_DB64;

	$sql = "
        SELECT
            Division_ID,
            Division_Name,
            Edit_By,
            Date_Edit
        FROM CR_Division
        WHERE status = 0
    ";
	$query  =    sqlsrv_query($konnext_DB64, $sql) or die(sqlsrv_errors($konnext_DB64));
	while ($row = sqlsrv_fetch_array($query, SQLSRV_FETCH_ASSOC)) {
		$data[$row['Division_ID']] = $row;
	}
	return $data;
}

function SelectProduct($Product_ID)
{
	global $konnext_DB64;

	$sql = "
        SELECT
            Product_ID,
            Product_Name
        FROM CR_Product
        WHERE Product_ID = '" . $Product_ID . "'
    ";
	$query  =    sqlsrv_query($konnext_DB64, $sql) or die(sqlsrv_errors($konnext_DB64));
	$row = sqlsrv_fetch_array($query, SQLSRV_FETCH_ASSOC);
	$data = $row;

	return json_encode($data, true);
}

function SelectCause($Cause_ID)
{
	global $konnext_DB64;

	$sql = "
        SELECT
            Cause_ID,
            Cause_Name,
			Cause_Type
        FROM CR_Cause
        WHERE Cause_ID = '" . $Cause_ID . "'
    ";
	$query  =    sqlsrv_query($konnext_DB64, $sql) or die(sqlsrv_errors($konnext_DB64));
	$row = sqlsrv_fetch_array($query, SQLSRV_FETCH_ASSOC);
	$data = $row;

	return json_encode($data, true);
}

function SelectDivision($Division_ID)
{
	global $konnext_DB64;

	$sql = "
        SELECT
            Division_ID,
            Division_Code,
            Division_Name
        FROM CR_Division
        WHERE Division_ID = '" . $Division_ID . "'
    ";
	$query  =    sqlsrv_query($konnext_DB64, $sql) or die(sqlsrv_errors($konnext_DB64));
	$row = sqlsrv_fetch_array($query, SQLSRV_FETCH_ASSOC);
	$data = $row;

	return json_encode($data, true);
}

function AddProduct($auth, $product_name)
{
	global $konnext_DB64;

	$sql = "
			INSERT INTO CR_Product
			(
                Product_Name,
                Create_By,
                Date_Create,
                Edit_By,
                Date_Edit,
				status
			)VALUES (
				'" . $product_name . "',
				'" . $auth . "',
				'" . date("Y-m-d H:i:s") . "',
				'" . $auth . "',
				'" . date("Y-m-d H:i:s") . "',
				'0'
			)
		";
	$query  =    sqlsrv_query($konnext_DB64, $sql) or die(sqlsrv_errors($konnext_DB64));

	if ($query == true) {
		$data['status'] = 'true';
		$data['title'] = "บันทึกสำเร็จ";
		$data['html'] = "เพิ่มข้อมูลสินค้าเรียบร้อย";
		$data['icon'] = "success";
	} else {
		$data['status'] = 'false';
		$data['title'] = "บันทึกไม่สำเร็จ";
		$data['html'] = "มีปัญหาในการบันทึกข้อมูลโปรดติดต่อเจ้าหน้าที่";
		$data['icon'] = "error";
		// $data['errors'] = $errors;
	}

	return json_encode($data, true);
}

function AddCause($auth, $cause_name, $cause_type)
{
	global $konnext_DB64;

	$sql = "
			INSERT INTO CR_Cause
			(
                Cause_Name,
				Cause_Type,
                Create_By,
                Date_Create,
                Edit_By,
                Date_Edit,
				status
			)VALUES (
				'" . $cause_name . "',
				'" . $cause_type . "',
				'" . $auth . "',
				'" . date("Y-m-d H:i:s") . "',
				'" . $auth . "',
				'" . date("Y-m-d H:i:s") . "',
				'0'
			)
		";
	$query  =    sqlsrv_query($konnext_DB64, $sql) or die(sqlsrv_errors($konnext_DB64));

	// if ($query !== true) {
	// 	$errors[] = sqlsrv_errors($konnext_DB64);
	// }

	if ($query == true) {
		$data['status'] = 'true';
		$data['title'] = "บันทึกสำเร็จ";
		$data['html'] = "เพิ่มข้อมูลสาเหตุเรียบร้อย";
		$data['icon'] = "success";
	} else {
		$data['status'] = 'false';
		$data['title'] = "บันทึกไม่สำเร็จ";
		$data['html'] = "มีปัญหาในการบันทึกข้อมูลโปรดติดต่อเจ้าหน้าที่";
		$data['icon'] = "error";
		// $data['errors'] = $errors;
	}

	return json_encode($data, true);
}

function AddDivision($auth, $division_id, $division_name)
{
	global $konnext_DB64;

	$sql = "
			INSERT INTO CR_Division
			(
                Division_Code,
                Division_Name,
                Create_By,
                Date_Create,
                Edit_By,
                Date_Edit,
				status
			)VALUES (
				'" . $division_id . "',
				'" . $division_name . "',
				'" . $auth . "',
				'" . date("Y-m-d H:i:s") . "',
				'" . $auth . "',
				'" . date("Y-m-d H:i:s") . "',
				'0'
			)
		";
	$query  =    sqlsrv_query($konnext_DB64, $sql) or die(sqlsrv_errors($konnext_DB64));

	// if ($query !== true) {
	// 	$errors[] = sqlsrv_errors($konnext_DB64);
	// }

	if ($query == true) {
		$data['status'] = 'true';
		$data['title'] = "บันทึกสำเร็จ";
		$data['html'] = "เพิ่มแผนกที่เกี่ยวข้องเรียบร้อย";
		$data['icon'] = "success";
	} else {
		$data['status'] = 'false';
		$data['title'] = "บันทึกไม่สำเร็จ";
		$data['html'] = "มีปัญหาในการบันทึกข้อมูลโปรดติดต่อเจ้าหน้าที่";
		$data['icon'] = "error";
		// $data['errors'] = $errors;
	}

	return json_encode($data, true);
}

function DelProduct($auth, $product_id)
{
	global $konnext_DB64;

	$sql = "
			UPDATE CR_Product SET
                Edit_By = '" . $auth . "',
                Date_Edit = '" . date("Y-m-d H:i:s") . "',
                status = '1'
            WHERE Product_ID = " . $product_id . "
		";
	$query  =    sqlsrv_query($konnext_DB64, $sql) or die(sqlsrv_errors($konnext_DB64));

	// if ($query !== true) {
	// 	$errors[] = sqlsrv_errors($konnext_DB64);
	// }

	if ($query == true) {
		$data['status'] = 'true';
		$data['title'] = "บันทึกสำเร็จ";
		$data['html'] = "ลบข้อมูลสินค้าเรียบร้อย";
		$data['icon'] = "success";
	} else {
		$data['status'] = 'false';
		$data['title'] = "บันทึกไม่สำเร็จ";
		$data['html'] = "มีปัญหาในการบันทึกข้อมูลโปรดติดต่อเจ้าหน้าที่";
		$data['icon'] = "error";
		// $data['errors'] = $errors;
	}

	return json_encode($data, true);
}

function DelCause($auth, $Cause_ID)
{
	global $konnext_DB64;

	$sql = "
			UPDATE CR_Cause SET
                Edit_By = '" . $auth . "',
                Date_Edit = '" . date("Y-m-d H:i:s") . "',
                status = '1'
            WHERE Cause_ID = " . $Cause_ID . "
		";
	$query  =    sqlsrv_query($konnext_DB64, $sql) or die(sqlsrv_errors($konnext_DB64));

	// if ($query !== true) {
	// 	$errors[] = sqlsrv_errors($konnext_DB64);
	// }

	if ($query == true) {
		$data['status'] = 'true';
		$data['title'] = "บันทึกสำเร็จ";
		$data['html'] = "ลบข้อมูลสาเหตุเรียบร้อย";
		$data['icon'] = "success";
	} else {
		$data['status'] = 'false';
		$data['title'] = "บันทึกไม่สำเร็จ";
		$data['html'] = "มีปัญหาในการบันทึกข้อมูลโปรดติดต่อเจ้าหน้าที่";
		$data['icon'] = "error";
		// $data['errors'] = $errors;
	}

	return json_encode($data, true);
}

function DelDivision($auth, $Division_ID)
{
	global $konnext_DB64;

	$sql = "
			UPDATE CR_Division SET
                Edit_By = '" . $auth . "',
                Date_Edit = '" . date("Y-m-d H:i:s") . "',
                status = '1'
            WHERE Division_ID = " . $Division_ID . "
		";
	$query  =    sqlsrv_query($konnext_DB64, $sql) or die(sqlsrv_errors($konnext_DB64));

	// if ($query !== true) {
	// 	$errors[] = sqlsrv_errors($konnext_DB64);
	// }

	if ($query == true) {
		$data['status'] = 'true';
		$data['title'] = "บันทึกสำเร็จ";
		$data['html'] = "ลบข้อมูลแผนกที่เกี่ยวข้องเรียบร้อย";
		$data['icon'] = "success";
	} else {
		$data['status'] = 'false';
		$data['title'] = "บันทึกไม่สำเร็จ";
		$data['html'] = "มีปัญหาในการบันทึกข้อมูลโปรดติดต่อเจ้าหน้าที่";
		$data['icon'] = "error";
		// $data['errors'] = $errors;
	}

	return json_encode($data, true);
}

function EditProduct($auth, $product_id, $product_name)
{
	global $konnext_DB64;

	$sql = "
			UPDATE CR_Product SET
                Product_Name = '" . $product_name . "',
                Edit_By = '" . $auth . "',
                Date_Edit = '" . date("Y-m-d H:i:s") . "'
            WHERE Product_ID = " . $product_id . "
		";
	$query  =    sqlsrv_query($konnext_DB64, $sql) or die(sqlsrv_errors($konnext_DB64));

	// if ($query !== true) {
	// 	$errors[] = sqlsrv_errors($konnext_DB64);
	// }

	if ($query == true) {
		$data['status'] = 'true';
		$data['title'] = "บันทึกสำเร็จ";
		$data['html'] = "แก้ไขข้อมูลสินค้าเรียบร้อย";
		$data['icon'] = "success";
	} else {
		$data['status'] = 'false';
		$data['title'] = "บันทึกไม่สำเร็จ";
		$data['html'] = "มีปัญหาในการบันทึกข้อมูลโปรดติดต่อเจ้าหน้าที่";
		$data['icon'] = "error";
		// $data['errors'] = $errors;
	}

	return json_encode($data, true);
}

function EditCause($auth, $cause_id, $cause_name, $cause_type)
{
	global $konnext_DB64;

	$sql = "
			UPDATE CR_Cause SET
                Cause_Name = '" . $cause_name . "',
				Cause_Type = '" . $cause_type . "',
                Edit_By = '" . $auth . "',
                Date_Edit = '" . date("Y-m-d H:i:s") . "'
            WHERE Cause_ID = " . $cause_id . "
		";
	$query  =    sqlsrv_query($konnext_DB64, $sql) or die(sqlsrv_errors($konnext_DB64));

	// if ($query !== true) {
	// 	$errors[] = sqlsrv_errors($konnext_DB64);
	// }

	if ($query == true) {
		$data['status'] = 'true';
		$data['title'] = "บันทึกสำเร็จ";
		$data['html'] = "แก้ไขข้อมูลสาเหตุเรียบร้อย";
		$data['icon'] = "success";
	} else {
		$data['status'] = 'false';
		$data['title'] = "บันทึกไม่สำเร็จ";
		$data['html'] = "มีปัญหาในการบันทึกข้อมูลโปรดติดต่อเจ้าหน้าที่";
		$data['icon'] = "error";
		// $data['errors'] = $errors;
	}

	return json_encode($data, true);
}

function EditDivision($auth, $division_code, $division_name, $division_ID)
{
	global $konnext_DB64;

	$sql = "
			UPDATE CR_Division SET
                Division_Code = '" . $division_code . "',
                Division_Name = '" . $division_name . "',
                Edit_By = '" . $auth . "',
                Date_Edit = '" . date("Y-m-d H:i:s") . "'
            WHERE Division_ID = " . $division_ID . "
		";
	$query  =    sqlsrv_query($konnext_DB64, $sql) or die(sqlsrv_errors($konnext_DB64));

	// if ($query !== true) {
	// 	$errors[] = sqlsrv_errors($konnext_DB64);
	// }

	if ($query == true) {
		$data['status'] = 'true';
		$data['title'] = "บันทึกสำเร็จ";
		$data['html'] = "แก้ไขข้อมูลแผนกที่เกี่ยวข้องเรียบร้อย";
		$data['icon'] = "success";
	} else {
		$data['status'] = 'false';
		$data['title'] = "บันทึกไม่สำเร็จ";
		$data['html'] = "มีปัญหาในการบันทึกข้อมูลโปรดติดต่อเจ้าหน้าที่";
		$data['icon'] = "error";
		// $data['errors'] = $errors;
	}

	return json_encode($data, true);
}


function generateCRNo()
{
	global $konnext_DB64;

	$year = substr((date('Y') + 543), -2);
	$month = date('m');

	$sql = "SELECT MAX(CAST(SUBSTRING(CR_no, 8, 3) AS INT)) as last_sequence
            FROM CR_ChangeForm
            WHERE SUBSTRING(CR_no, 4, 2) = '$year'
            AND SUBSTRING(CR_no, 6, 2) = '$month'";
	$stmt = sqlsrv_query($konnext_DB64, $sql);
	if ($stmt === false) {
		die(print_r(sqlsrv_errors(), true));
	}
	$row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
	$last_sequence = isset($row['last_sequence']) ? (int)$row['last_sequence'] + 1 : 1;
	$sequence = str_pad($last_sequence, 3, '0', STR_PAD_LEFT);
	$CR_no = 'CR-' . $year . $month . $sequence;

	return $CR_no;
}


function List_ChangeForm($emp_no, $date_start = null, $date_end = null)
{
	global $konnext_DB64;

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
		WHERE CC.[doc_status] != 'Savedraft'
	";

	if ($emp_no != '') {
		$sql .= " AND CC.[userCreate] = '" . $emp_no . "'";
	}

	if ($date_start != null && $date_end != null) {
		$sql .= " AND CC.[dateCreate] BETWEEN '" . $date_start . " 00:00:00' AND '" . $date_end . " 23:59:59'";
	}

	$sql .= " 
		ORDER BY CASE CC.[doc_status]
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

	$query  =    sqlsrv_query($konnext_DB64, $sql) or die(sqlsrv_errors($konnext_DB64));
	while ($row = sqlsrv_fetch_array($query, SQLSRV_FETCH_ASSOC)) {
		$data[$row['CR_ID']] = $row;
	}

	return $data;
}

function List_ChangeForm_SaveDeaft($emp_no)
{
	global $konnext_DB64;

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
		WHERE CC.[userCreate] = '" . $emp_no . "'
		AND CC.[doc_status] = 'Savedraft'
	";

	$query  =    sqlsrv_query($konnext_DB64, $sql) or die(sqlsrv_errors($konnext_DB64));
	while ($row = sqlsrv_fetch_array($query, SQLSRV_FETCH_ASSOC)) {
		$data[$row['CR_ID']] = $row;
	}

	return $data;
}

function update_list_status($cr_no, $status, $emp_code)
{
	global $konnext_DB64;

	$sql = " 
		INSERT INTO [CR_StatusList]
		(
			[CR_no]
			,[Status_User]
			,[Status_Name]
			,[Status_Date]
		)
		VALUES
		(
			'" . $cr_no . "'
			,'" . $emp_code . "'
			,'" . $status . "'
			,'" . date("Y-m-d H:i:s") . "'
		)
	";

	$query  =    sqlsrv_query($konnext_DB64, $sql) or die(sqlsrv_errors($konnext_DB64));

	return $query;
}

function List_ChangeFormByID($CR_no)
{
	global $konnext_DB64;

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
			,CC.[cancel_remark]
			,CC.[rev]
            ,CC.[ncr_no]
            ,CC.[details]
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
		WHERE CC.[CR_no] = '" . $CR_no . "'
	";

	$query  =    sqlsrv_query($konnext_DB64, $sql) or die(sqlsrv_errors($konnext_DB64));
	$row = sqlsrv_fetch_array($query, SQLSRV_FETCH_ASSOC);
	$data = $row;
	$data = array_merge($data, ["files" => json_encode(List_Files($CR_no), true)]);

	return $data;
}

function List_Files($CR_no)
{
	global $konnext_DB64;

	$sql = "
		SELECT 
			[file_id]
			,[CR_no]
			,[path_file]
			,[name_files]
		FROM [ITService].[dbo].[CR_Files]
		WHERE [CR_no] = '" . $CR_no . "'
	";

	$query  =    sqlsrv_query($konnext_DB64, $sql) or die(sqlsrv_errors($konnext_DB64));
	while ($row = sqlsrv_fetch_array($query, SQLSRV_FETCH_ASSOC)) {
		$data[$row['file_id']] = $row;
	}

	return $data;
}

function Search_WA($jobno)
{
	global $konnext_DB64;

	$sql = "
		SELECT Doc_No
		FROM [cd-XPSQL-ASF7].dbo.Transection
		WHERE Doc_Type = 'WA'
		AND PO_No = ?
		GROUP BY Doc_No
	";

	$stmt = sqlsrv_query($konnext_DB64, $sql, array($jobno));

	if ($stmt === false) {
		echo json_encode(['error' => 'Query failed']);
		exit();
	}

	$data = [];
	while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
		$data[] = $row['Doc_No'];
	}
	$data[] = '-';

	return $data;
}

function Search_TC($jobno)
{
	global $konnext_DB64;

	$sql = "
		SELECT Lot_No
		FROM [cd-XPSQL-ASF7].dbo.Transection
		WHERE Doc_Type = 'WA'
		AND PO_No = ?
		GROUP BY Lot_No
	";

	$stmt = sqlsrv_query($konnext_DB64, $sql, array($jobno));

	if ($stmt === false) {
		echo json_encode(['error' => 'Query failed']);
		exit();
	}

	$data = [];
	while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
		if ($row['Lot_No'] != '') {
			if (!in_array($row['Lot_No'], $data)) {
				$data[] = $row['Lot_No'];
			}
		}
	}
	$data[] = '-';

	return $data;
}

function Search_SN($jobno)
{
	global $connection_ASF_VIEW;

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

	$options = '';
	$data = array();
	while ($row = sqlsrv_fetch_array($stmtsn, SQLSRV_FETCH_ASSOC)) {
		// $options .= '<option value="' . ($row['item']) . '">' . ($row['PartNo']) . '</option>';
		$data[] = $row;
	}
	$data = array_merge($data, [["item" => "-", "PartNo" => "-"]]);

	return $data;
}

// function generateDocNo($doc_type) {
// 	global $konnext_DB64;

//     $year = date('Y') + 543;
//     $yearSuffix = substr($year, -2);
//     $month = str_pad(date('m'), 2, '0', STR_PAD_LEFT);

//     $query = "
// 		SELECT TOP 1 [doc_no] 
// 		FROM [ITService].[dbo].[CR_ChangeForm] 
// 		WHERE [doc_type] = '". $doc_type ."' 
// 		ORDER BY [doc_no] DESC
// 	";

//     $stmt = sqlsrv_query($konnext_DB64, $query);

//     if ($stmt === false) {
//         die(print_r(sqlsrv_errors(), true));
//     }

//     $lastDocNo = null;

//     if ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
//         $lastDocNo = $row['doc_no'];
//     }

//     $nextRunningNumber = '0001';

//     if ($lastDocNo) {
//         $lastRunningNumber = (int)substr($lastDocNo, -4);
//         $nextRunningNumber = str_pad($lastRunningNumber + 1, 4, '0', STR_PAD_LEFT);
//     }

//     $newDocNo = "$doc_type-$yearSuffix$month$nextRunningNumber";

//     return $newDocNo;
// }

// function generateDocNo($doc_type) {
//     global $konnext_DB64;

//     $year = date('Y') + 543;
//     $yearSuffix = substr($year, -2);
//     $month = str_pad(date('m'), 2, '0', STR_PAD_LEFT);

//     $query = "
//         SELECT TOP 1 [doc_no] 
//         FROM [ITService].[dbo].[CR_ChangeForm] 
//         WHERE [doc_type] = ? 
//         AND [doc_no] LIKE ?
//         ORDER BY [doc_no] DESC
//     ";

//     $params = [$doc_type, "%$yearSuffix$month%"];
//     $stmt = sqlsrv_query($konnext_DB64, $query, $params);

//     if ($stmt === false) {
//         die(print_r(sqlsrv_errors(), true));
//     }

//     $lastDocNo = null;

//     if ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
//         $lastDocNo = $row['doc_no'];
//     }

//     $nextRunningNumber = '0001';

//     if ($lastDocNo) {
//         $lastRunningNumber = (int)substr($lastDocNo, -4);
//         $nextRunningNumber = str_pad($lastRunningNumber + 1, 4, '0', STR_PAD_LEFT);
//     }

//     $newDocNo = "$doc_type-$yearSuffix$month$nextRunningNumber";

//     return $newDocNo;
// }

function generateDocNo($doc_type)
{
	global $konnext_DB64;

	$year = date('Y') + 543;
	$yearSuffix = substr($year, -2);
	$month = str_pad(date('m'), 2, '0', STR_PAD_LEFT);

	$prefix = $doc_type . '-' . $yearSuffix . $month;
	// $prefix = $doc_type . '-6804';

	$sql = "
        SELECT [doc_no] 
        FROM [ITService].[dbo].[CR_ChangeForm] 
        WHERE [doc_type] = '" . $doc_type . "' 
        AND [doc_no] LIKE '%" . $prefix . "%'
        ORDER BY [doc_no] ASC
    ";

	$stmt = sqlsrv_query($konnext_DB64, $sql);
	$existingNumbers = [];

	while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
		$doc_no = $row['doc_no'];
		$numberPart = intval(substr($doc_no, strlen($prefix)));
		$existingNumbers[] = $numberPart;
	}

	if (empty($existingNumbers)) {
		return $prefix . '0001';
	}

	sort($existingNumbers);
	$max = end($existingNumbers);

	for ($i = 1; $i <= $max; $i++) {
		if (!in_array($i, $existingNumbers)) {
			return $prefix . str_pad($i, 4, '0', STR_PAD_LEFT);
		}
	}

	return $prefix . str_pad($max + 1, 4, '0', STR_PAD_LEFT);
}

function generateDocNoTest($doc_type)
{
	global $konnext_DB64;

	$year = date('Y') + 543;
	$yearSuffix = substr($year, -2);
	$month = str_pad(date('m'), 2, '0', STR_PAD_LEFT);

	$prefix = $doc_type . '-' . $yearSuffix . $month;
	// $prefix = $doc_type . '-6804';

	$sql = "
        SELECT [doc_no] 
        FROM [ITService].[dbo].[CR_ChangeForm] 
        WHERE [doc_type] = '" . $doc_type . "' 
        AND [doc_no] LIKE '%" . $prefix . "%'
        ORDER BY [doc_no] ASC
    ";

	$stmt = sqlsrv_query($konnext_DB64, $sql);
	$existingNumbers = [];

	while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
		$doc_no = $row['doc_no'];
		$numberPart = intval(substr($doc_no, strlen($prefix)));
		$existingNumbers[] = $numberPart;
	}

	if (empty($existingNumbers)) {
		return $prefix . '0001';
	}

	sort($existingNumbers);
	$max = end($existingNumbers);

	for ($i = 1; $i <= $max; $i++) {
		if (!in_array($i, $existingNumbers)) {
			return $prefix . str_pad($i, 4, '0', STR_PAD_LEFT);
		}
	}

	return print_r($existingNumbers, true);
}

function Notify($titelnoti, $message, $usernoti, $url)
{

	// $titelnoti = "แจ้งเตือนขอเบิกของ (". $rowSelectOrder['order_Number'] .")";
	// $message = $order_Name . "\nได้สร้างเอกสารเพื่อขออนุมัติเบิกของ" . "\nเมื่อ " . $date;

	$post = [
		'notify_type'    =>    'msg',
		'TOWEB'            =>    'TOWEB',
		'url'            =>    base64_encode($url),
		'notify_title'    =>    $titelnoti,
		'notify_msg'    =>    $message,
		'user_username'    =>    $usernoti,
		'Notification_Mode' =>    'other'
	];
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, 'https://innovation.asefa.co.th/applications/notification/push_notification');
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	$result = curl_exec($ch);
	curl_close($ch);

	if ($result == "true") {
		$status = "true";
	}
	return $status;
}

function Get_Time_Status($cr_no, $status)
{
	global $konnext_DB64;

	$sql = " 
		SELECT TOP 1
			[Status_User]
			,[Status_Date]
		FROM [ITService].[dbo].[CR_StatusList]
		WHERE [CR_no] = '" . $cr_no . "' AND [Status_Name] = '" . $status . "'
		ORDER BY [Status_ID] DESC
	";

	$query  =    sqlsrv_query($konnext_DB64, $sql) or die(sqlsrv_errors($konnext_DB64));
	$row = sqlsrv_fetch_array($query, SQLSRV_FETCH_ASSOC);

	return $row;
}

function Get_Status($cr_no, $emp_code, $status, $order_by)
{
	global $konnext_DB64;

	$sql = " 
		SELECT TOP 1
			[Status_User]
			,[Status_Date]
		FROM [ITService].[dbo].[CR_StatusList]
		WHERE [CR_no] = '" . $cr_no . "'AND [Status_User] = '" . $emp_code . "' AND [Status_Name] = '" . $status . "'
		ORDER BY [Status_ID] " . $order_by . "
	";

	$query  =    sqlsrv_query($konnext_DB64, $sql) or die(sqlsrv_errors($konnext_DB64));
	$row = sqlsrv_fetch_array($query, SQLSRV_FETCH_ASSOC);

	return $row;
}

function Update_Status_Approve($cr_no, $approveSelect, $text_table, $status = '1')
{
	global $konnext_DB64;

	$sql = "
		UPDATE [ITService].[dbo].[CR_Approve]
		SET 
	";

	if ($text_table == 'Approve_1') {
		$sql .= "
			[CR_Approve1] = '" . json_encode(['Approve_1' => $approveSelect, 'status_approve' => $status, 'date_approve' => date("Y-m-d H:i:s")]) . "'";
	} else if ($text_table == 'Approve_2') {
		$sql .= "
			[CR_Approve2] = '" . json_encode(['Approve_2' => $approveSelect, 'status_approve' => $status, 'date_approve' => date("Y-m-d H:i:s")]) . "'";
	} else if ($text_table == 'Approve_3') {
		$sql .= "
			[CR_Approve3] = '" . json_encode(['Approve_3' => $approveSelect, 'status_approve' => $status, 'date_approve' => date("Y-m-d H:i:s")]) . "'";
	} else if ($text_table == 'Approve_4') {
		$sql .= "
			[CR_Approve4] = '" . json_encode(['Approve_4' => $approveSelect, 'status_approve' => $status, 'date_approve' => date("Y-m-d H:i:s")]) . "'";
	} else if ($text_table == 'Approve_5') {
		$sql .= "
			[CR_Approve5] = '" . json_encode(['Approve_5' => $approveSelect, 'status_approve' => $status, 'date_approve' => date("Y-m-d H:i:s")]) . "'";
	}

	$sql .= " WHERE [CR_no] = '" . $cr_no . "'";

	$query  =    sqlsrv_query($konnext_DB64, $sql) or die(sqlsrv_errors($konnext_DB64));

	return $query;
}

function get_status_count($status)
{
	global $konnext_DB64;

	$sql = "
		SELECT COUNT(*) AS count 
		FROM [ITService].[dbo].[CR_ChangeForm] 
		WHERE [doc_status] = '" . $status . "'
	";

	$query  =    sqlsrv_query($konnext_DB64, $sql) or die(sqlsrv_errors($konnext_DB64));
	$row = sqlsrv_fetch_array($query, SQLSRV_FETCH_ASSOC);

	return $row['count'];
}

function select_job_project()
{
	global $konnext_DB64;

	$sql = "
		SELECT 
			[jobno]
		FROM [ITService].[dbo].[CR_ChangeForm]
		WHERE [doc_status] = 'Close'
		GROUP BY [jobno]
	";

	$query  =    sqlsrv_query($konnext_DB64, $sql) or die(sqlsrv_errors($konnext_DB64));
	while ($row = sqlsrv_fetch_array($query, SQLSRV_FETCH_ASSOC)) {
		$data[] = $row['jobno'];
	}

	return $data;
}

function select_job_project_noclose()
{
	global $konnext_DB64;

	$sql = "
		SELECT 
			[jobno]
		FROM [ITService].[dbo].[CR_ChangeForm]
		GROUP BY [jobno]
	";

	$query  =    sqlsrv_query($konnext_DB64, $sql) or die(sqlsrv_errors($konnext_DB64));
	while ($row = sqlsrv_fetch_array($query, SQLSRV_FETCH_ASSOC)) {
		$data[] = $row['jobno'];
	}

	return $data;
}

function delete_approve($cr_no)
{
	global $konnext_DB64;

	$sql = "
	DELETE FROM [ITService].[dbo].[CR_Approve]
	WHERE [CR_no] = '" . $cr_no . "'
	";

	$query  =    sqlsrv_query($konnext_DB64, $sql) or die(sqlsrv_errors($konnext_DB64));

	return $query;
}

function list_permission($name)
{
	global $konnext_DB64;

	$sql = "
		SELECT 
			[Per_ID]
			,[Per_Name]
			,[Per_Json]
			,[Per_Divi_Json]
			,[userupdate]
			,[dateupdate]
		FROM [ITService].[dbo].[CR_Permission]
	";

	if ($name != 'All') {
		$sql .= " WHERE [Per_Name] = '" . $name . "'";
	}

	$query  =    sqlsrv_query($konnext_DB64, $sql) or die(sqlsrv_errors($konnext_DB64));
	while ($row = sqlsrv_fetch_array($query, SQLSRV_FETCH_ASSOC)) {
		$data[] = $row;
	}

	return $data;
}

function get_Waiting($emp)
{
	global $konnext_DB64;

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

	return $data;
}

function getStatusByIndex($i)
{
	switch ($i) {
		case 1:
			return 'Review';
		case 2:
			return 'Check';
		case 3:
			return 'Recheck';
		case 4:
		case 5:
			return 'Approve';
		default:
			return '';
	}
}

function getColorByStatus($status)
{
	switch ($status) {
		case 'New':
		case 'Draf':
			return '#ffc107';
		case 'Review':
			return '#0dcaf0';
		case 'Check':
			return '#0d6efd';
		case 'Recheck':
			return '#6610f2';
		case 'Approve':
			return '#198754';
		case 'Not Approve':
			return '#dc3545';
		case 'Close':
			return '#6c757d';
		case 'Rework':
			return '#fd7e14';
		default:
			return '#adb5bd';
	}
}
