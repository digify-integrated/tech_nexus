<?php
require('config/config.php');
require('model/database-model.php');
require('model/security-model.php');
require('model/email-setting-model.php');
require('assets/libs/PHPMailer/src/PHPMailer.php');
require('assets/libs/PHPMailer/src/Exception.php');
require('assets/libs/PHPMailer/src/SMTP.php');

$log_file = '/home/u890127610/domains/cgmids.com/public_html/cron.log';
file_put_contents($log_file, "Regular Employee PME Script started at " . date("Y-m-d H:i:s") . "\n", FILE_APPEND);

$databaseModel = new DatabaseModel();
$securityModel = new SecurityModel();
$emailSettingModel = new EmailSettingModel(new DatabaseModel);

// =============================
// Check if today is May 1 or September 1
// =============================
$currentMonth = date('n'); // 1-12 without leading zeros
$currentDay   = date('j'); // 1-31 without leading zeros

if (!(($currentMonth == 3 || $currentMonth == 9) && $currentDay == 1)) {
    file_put_contents($log_file, "Not May 1 or September 1 — no emails sent.\n", FILE_APPEND);
    exit;
}

// =============================
// Email Settings
// =============================
$emailSetting = $emailSettingModel->getEmailSetting(1);
$mailFromName = $emailSetting['mail_from_name'] ?? 'HR Department';
$mailFromEmail = $emailSetting['mail_from_email'] ?? 'no-reply@example.com';
$mailHost = $emailSetting['mail_host'] ?? MAIL_HOST;
$smtpAuth = empty($emailSetting['smtp_auth']) ? false : true;
$mailUsername = $emailSetting['mail_username'] ?? MAIL_USERNAME;
$mailPassword = !empty($emailSetting['mail_password']) ? $securityModel->decryptData($emailSetting['mail_password']) : MAIL_PASSWORD;
$mailEncryption = $emailSetting['mail_encryption'] ?? MAIL_SMTP_SECURE;
$port = $emailSetting['port'] ?? MAIL_PORT;

// =============================
// Email Content
// =============================
$emailSubject = 'Reminder: Semi-Annual Performance Evaluation';
$emailBody = '
<p class="MsoNormal"><span style="font-family: \'Times New Roman\',serif;">Dear Team,</span></p>
<p class="MsoNormal"><span style="font-family: \'Times New Roman\',serif;">This is a reminder about the upcoming <strong>semi-annual performance evaluations</strong> for all regular employees.</span></p>
<p class="MsoNormal"><span style="font-family: \'Times New Roman\',serif;">Please make sure you have completed all necessary self-assessments and feedback forms in a timely manner.</span></p>
<p class="MsoNormal"><strong><span style="font-family: \'Times New Roman\',serif;">Supervisors</span></strong><span style="font-family: \'Times New Roman\',serif;">, kindly ensure that you are providing feedback to your team members and that all evaluations are completed promptly.</span></p>
<p class="MsoNormal"><span style="font-family: \'Times New Roman\',serif;">Thank you for your cooperation.</span></p>
<p class="MsoNormal"><span style="font-family: \'Times New Roman\',serif;">Regards,<br>Human Resource Department</span></p>
';

$message = '<!DOCTYPE html>
<html>
<head><meta charset="UTF-8"><title>' . $emailSubject . '</title></head>
<body>' . $emailBody . '</body>
</html>';

// =============================
// 1. Get contact_id + manager_id of Regular Employees (active)
// =============================
$sql = "
    SELECT ei.contact_id, ei.manager_id
    FROM employment_information ei
    WHERE ei.employee_type_id = 1
      AND (ei.offboard_date IS NULL OR ei.offboard_date = '')
";
$stmt = $databaseModel->getConnection()->prepare($sql);
$stmt->execute();

$contacts = [];
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    if (!empty($row['contact_id'])) {
        $contacts[] = $row['contact_id'];
    }
    if (!empty($row['manager_id'])) {
        $contacts[] = $row['manager_id'];
    }
}

$uniqueContacts = array_values(array_unique($contacts));

if (empty($uniqueContacts)) {
    file_put_contents($log_file, "No contacts to send emails to.\n", FILE_APPEND);
    exit;
}

// =============================
// 2. Get their email addresses
// =============================
$placeholders = rtrim(str_repeat('?,', count($uniqueContacts)), ',');
$sqlEmails = "
    SELECT email
    FROM contact_information
    WHERE contact_information_type_id = 2
      AND contact_id IN ($placeholders)
";
$stmtEmails = $databaseModel->getConnection()->prepare($sqlEmails);
$stmtEmails->execute($uniqueContacts);

$emails = [];
while ($row = $stmtEmails->fetch(PDO::FETCH_ASSOC)) {
    if (!empty($row['email'])) {
        $emails[] = $row['email'];
    }
}
$emails = array_unique($emails);

if (empty($emails)) {
    file_put_contents($log_file, "No email addresses found for regular employees.\n", FILE_APPEND);
    exit;
}

// =============================
// 3. Send Email
// =============================
$mailer = new PHPMailer\PHPMailer\PHPMailer();
$mailer->isSMTP();
$mailer->isHTML(true);
$mailer->Host = $mailHost;
$mailer->SMTPAuth = $smtpAuth;
$mailer->Username = $mailUsername;
$mailer->Password = $mailPassword;
$mailer->SMTPSecure = $mailEncryption;
$mailer->Port = $port;

$mailer->setFrom($mailFromEmail, $mailFromName);
$mailer->Subject = $emailSubject;
$mailer->Body = $message;

foreach ($emails as $email) {
    $mailer->addAddress($email);
}

try {
    if ($mailer->send()) {
        file_put_contents($log_file, "Semi-annual PME reminder sent to " . count($emails) . " recipients on " . date("Y-m-d H:i:s") . "\n", FILE_APPEND);
        echo "Emails sent successfully.";
    } else {
        file_put_contents($log_file, "Failed to send PME reminder. Error: " . $mailer->ErrorInfo . "\n", FILE_APPEND);
        echo "Failed to send PME reminder.";
    }
} catch (Exception $e) {
    file_put_contents($log_file, "Exception occurred while sending PME reminder: " . $e->getMessage() . "\n", FILE_APPEND);
    echo "Exception: " . $e->getMessage();
}

file_put_contents($log_file, "Regular Employee PME Script ended at " . date("Y-m-d H:i:s") . "\n", FILE_APPEND);
?>
