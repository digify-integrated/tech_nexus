<?php
require('config/config.php');
require('model/database-model.php');
require('model/security-model.php');
require('model/email-setting-model.php');
require('assets/libs/PHPMailer/src/PHPMailer.php');
require('assets/libs/PHPMailer/src/Exception.php');
require('assets/libs/PHPMailer/src/SMTP.php');

$log_file = '/home/u890127610/domains/cgmids.com/public_html/cron.log';
file_put_contents($log_file, "Script started at " . date("Y-m-d H:i:s") . "\n", FILE_APPEND);

$databaseModel = new DatabaseModel();
$securityModel = new SecurityModel();
$emailSettingModel = new EmailSettingModel(new DatabaseModel);

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

$emailSubject = 'Reminder: Probationary Performance Evaluation';
$emailBody = '<p class="MsoNormal"><span style="font-family: \'Times New Roman\',serif;">Dear Team,</span></p>
<p class="MsoNormal"><span style="font-family: \'Times New Roman\',serif;">This is a reminder to conduct the monthly Performance Monitoring Evaluation (PME) for probationary employees.</span></p>
<p class="MsoNormal"><span style="font-family: \'Times New Roman\',serif;">Please ensure that evaluations are completed on time.</span></p>
<p class="MsoNormal"><span style="font-family: \'Times New Roman\',serif;">Thank you.</span></p>';

$message = '<!DOCTYPE html>
<html>
<head><meta charset="UTF-8"><title>' . $emailSubject . '</title></head>
<body>' . $emailBody . '</body>
</html>';

// =============================
// 1. Select probationary employees due for PME today (based on onboard_date day)
// =============================
$sql = "
    SELECT ei.contact_id, ei.manager_id
    FROM employment_information ei
    WHERE ei.employee_type_id = 8
      AND (ei.offboard_date IS NULL OR ei.offboard_date = '')
      AND ei.onboard_date IS NOT NULL
      AND (
          DATE_SUB(ei.onboard_date, INTERVAL 1 MONTH) = CURDATE()
          OR (
              DAY(ei.onboard_date) = DAY(CURDATE())
              AND TIMESTAMPDIFF(MONTH, ei.onboard_date, CURDATE()) >= 0
          )
      )
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

if (!empty($uniqueContacts)) {
    // =============================
    // 2. Get emails of these contacts
    // =============================
    $placeholders = rtrim(str_repeat('?,', count($uniqueContacts)), ',');
    $sqlEmails = "
        SELECT email AS email
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

    if (count($emails) > 0) {
        // =============================
        // 3. Send email
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
                file_put_contents($log_file, "PME Reminder sent to " . count($emails) . " recipients at " . date("Y-m-d H:i:s") . "\n", FILE_APPEND);
                echo "PME reminder emails sent.";
            } else {
                file_put_contents($log_file, "Failed to send PME reminder. Error: " . $mailer->ErrorInfo . "\n", FILE_APPEND);
                echo "Failed to send PME reminder. Error: " . $mailer->ErrorInfo;
            }
        } catch (Exception $e) {
            file_put_contents($log_file, "Exception sending PME reminder: " . $e->getMessage() . "\n", FILE_APPEND);
        }
    } else {
        file_put_contents($log_file, "No email addresses found for PME reminders.\n", FILE_APPEND);
    }
} else {
    file_put_contents($log_file, "No probationary employees due for PME today.\n", FILE_APPEND);
}

file_put_contents($log_file, "Script ended at " . date("Y-m-d H:i:s") . "\n", FILE_APPEND);
?>
