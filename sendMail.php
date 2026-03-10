<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../it-apps/ServiceJobs/vendor/autoload.php';

function sendMail($email, $name, $skull)
{
    $mail = new PHPMailer(true);

    //// <img src="https://innovation.asefa.co.th/ChangeRequestForm/icon/Zoom-Logo.jpg" alt="ASEFA Academy" width="10%">
    // <br>ลิงค์ Zoom Meeting 👇
    // <br><a href="https://erpapp.asefa.co.th/vx_ShortURL.php?ShortURLID=250921&openExternalBrowser=1">กดที่นี่</a>

    try {
        $smtpResponseLog = '';
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com'; //smtppro.zoho.com
        $mail->SMTPAuth   = true;
        $mail->Username   = 'asefa.academy@gmail.com';
        $mail->Password   = 'mbulhmulisstwioe';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->SMTPKeepAlive = true;
        // $mail->SMTPSecure = 'tls';
        $mail->SMTPOptions = [
            'ssl' => [
                'verify_peer'       => false,
                'verify_peer_name'  => false,
                'allow_self_signed' => true,
            ],
        ];
        $mail->Port       = 587;
        $mail->CharSet = 'UTF-8';


        // ตั้งค่าผู้ส่งและผู้รับ
        $mail->setFrom('asefa.academy@gmail.com', 'ASEFA Academy');
        $mail->addAddress($email);
        $mail->addReplyTo('asefa.academy@gmail.com');

        // เนื้อหาอีเมล
        $mail->isHTML(true);
        $mail->Subject = 'Link สัมมนาหลักสูตร : Switchboard Smart Solution';
        $mail->Body    = '
            <table border="0" cellpadding="0" cellspacing="0" width="100%">
                <tr>
                    <td height="150" style="background: url(\'https://innovation.asefa.co.th/ChangeRequestForm/icon/Header-1.png\') no-repeat center center/cover; background-size: cover; background-position: center center; background-repeat: no-repeat; vertical-align: middle;">

                    </td>
                </tr>
                <tr>
                    <td style="background: url(\'https://innovation.asefa.co.th/ChangeRequestForm/icon/Header-2.png\') no-repeat center center/cover; background-size: cover; background-position: center center; background-repeat: no-repeat; vertical-align: middle;">
                        <div style="height: 100px; text-align: center; padding-top: 15px; font-family: \'Prompt\', sans-serif;">
                            <div style="color: #fff; font-size: 30px; font-weight: 500;">ยินดีต้อนรับ</div>
                            <div style="color: #fff; font-size: 25px; font-weight: 400;">คุณ ' . $name . ' ' . $skull . '</div>
                        </div>
                    </td>
                </tr> 
                <tr>
                    <td height="870" style="color: #000; background: #fffffe url(\'https://innovation.asefa.co.th/ChangeRequestForm/icon/Content.png\') no-repeat center center/cover; background-size: cover; background-position: center center; background-repeat: no-repeat; vertical-align: middle;">
                        <div style="height: 100%; padding: 70px 30px; font-family: \'Prompt\', sans-serif;">
                            <table border="0" cellpadding="0" cellspacing="0" width="100%">
                                <tr>
                                    <td>
                                        <div style="padding-top: 30px; color: #000; font-size: 16px; font-weight: 700;">ขอขอบคุณที่ท่านได้จองสัมมนากับ บริษัท อาซีฟา จำกัด (มหาชน) ทางบริษัทฯ ขอแจ้งรายละเอียดดังต่อไปนี้</div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div style="padding-top: 30px; color: #000; font-size: 14px;">
                                            หลักสูตร : Switchboard Smart Solution
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div style="padding-top: 20px; color: #000; font-size: 14px;">
                                            วัน-เวลาที่สัมมนา : 1 พ.ย. 2568 เวลา 09.00-12.00 น.
                                            <br>วิทยากรผู้บรรยาย : คุณอรินทม์ ไม้รอด
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div style="padding-top: 20px; color: #000; font-size: 14px;">
                                            สถานที่จัดงาน : ออนไลน์โปรแกรม Zoom Meeting
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <table role="presentation" border="0" cellspacing="0" cellpadding="0">
                                            <tr>
                                                <td valign="middle" style="font-size:14px; color:#000; padding:20px 10px 20px 0;">
                                                ท่านสามารถเข้าสัมมนาหลักสูตรของท่านตามนี้
                                                </td>
                                                <td valign="middle">
                                                    <a href="https://erpapp.asefa.co.th/vx_ShortURL.php?ShortURLID=253482&openExternalBrowser=1">
                                                        <img src="https://innovation.asefa.co.th/ChangeRequestForm/icon/zoomclick.png" alt="ASEFA Academy" width="220" style="display:block; border:0;">
                                                    </a>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div style="padding-top: 20px; color: #000; font-size: 14px;">
                                            Meeting ID : 302 854 7284
                                            <br>Participant ID : 572239
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div style="padding-top: 30px; color: red; font-size: 14px;">
                                            📣 หมายเหตุ
                                            <br>1.รับจำนวนจำกัด 500 ท่านและ ขอสงวนสิทธิ์ให้ผู้ที่เข้าก่อนได้สิทธิ์ก่อน
                                            <br>(เข้าร่วมสัมมนาได้ตลอดเวลา ถ้าหากมีคนออกจากระบบค่ะ)
                                            <br>2. การสัมมนาจะมีการทดสอบ  (การสัมมนาจะได้ 3 PDU ถ้าสอบผ่าน จะได้รับ 6 PDU)
                                            <br>3. ท่านสามารถเข้าสัมมนาได้ในเวลา 08.45 น. นับคะแนน PDU เมื่อท่านทำแบบทดสอบเท่านั้น
                                            <br>4. หากลงทะเบียนแล้วไม่เข้าร่วมสัมมนา ทางผู้จัดขอสงวนสิทธิ์ในการจัดสรรโควต้าเข้าสัมมนาครั้งถัดไป
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div style="padding-top: 30px; color: #000; font-size: 14px;">
                                            “Please do not reply to this email” (อีเมลฉบับนี้ เป็นการแจ้งข้อมูลจากระบบอัตโนมัติ กรุณาอย่าตอบกลับ)
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div style="padding-top: 20px; color: #000; font-size: 14px;">
                                            ขอบคุณที่ใช้บริการ
                                            <br>หากท่านมีข้อสงสัยสามารถสอบถามได้ที่
                                            <br>
                                            <br>คุณวราลี ภาคาลาภ
                                            <br>มือถือ 086-408-5642
                                        </div>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td style="">
                        <img src="https://innovation.asefa.co.th/ChangeRequestForm/icon/Footer.png" alt="ASEFA Academy" width="100%">
                    </td>
                </tr>
            </table>
        ';
        $mail->AltBody = 'สวัสดี';

        $mail->SMTPDebug = 0;
        $mail->Debugoutput = function ($str, $level) use (&$smtpResponseLog) {
            $smtpResponseLog .= "$str\n";
        };

        $status = [];
        if ($mail->send()) {
            $status['status'] = true;
        } else {
            $status['status'] = false;
            $status['error'] = $mail->ErrorInfo;
        }
        $status['smtpResponseLog'] = $smtpResponseLog;

        return $status;
    } catch (Exception $e) {
        echo "ไม่สามารถส่งอีเมลได้: {$mail->ErrorInfo}";
    }
}
