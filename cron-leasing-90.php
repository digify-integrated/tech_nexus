<?php
require('config/config.php');
require('model/database-model.php');
require('model/internal-dr-model.php');
require('model/security-model.php');
require('model/system-model.php');
require('model/customer-model.php');
require('model/notification-setting-model.php');
require('model/email-setting-model.php');
require('model/product-model.php');
require('model/tenant-model.php');
require('model/property-model.php');
require ('assets/libs/PHPMailer/src/PHPMailer.php');
require ('assets/libs/PHPMailer/src/Exception.php');
require ('assets/libs/PHPMailer/src/SMTP.php');

$log_file = '/home/u890127610/domains/cgmids.com/public_html/cron.log';
file_put_contents($log_file, "Script started at " . date("Y-m-d H:i:s") . "\n", FILE_APPEND);

$databaseModel = new DatabaseModel();
$systemModel = new SystemModel();
$securityModel = new SecurityModel();
$internalDRModel = new InternalDRModel($databaseModel);
$productModel = new ProductModel($databaseModel);
$customerModel = new CustomerModel($databaseModel);
$emailSettingModel = new EmailSettingModel(new DatabaseModel);
$notificationSettingModel = new NotificationSettingModel(new DatabaseModel);
$tenantModel = new TenantModel($databaseModel);
$propertyModel = new PropertyModel($databaseModel);

$table = '<table border="1" cellspacing="0" cellpadding="5">
            <thead>
                <tr>
                    <th>Leasing Application No.</th>
                    <th>Tenant</th>
                    <th>Contact Person</th>
                    <th>Property</th>
                    <th>Maturity Date</th>
                </tr>
            <thead>
            <tbody>';

$sql = $databaseModel->getConnection()->prepare('SELECT * FROM leasing_application WHERE application_status = "Active" AND maturity_date = CURDATE() + INTERVAL 90 DAY;');
$sql->execute();
$options = $sql->fetchAll(PDO::FETCH_ASSOC);
$sql->closeCursor();
$count = count($options);

foreach ($options as $row) {
    $leasing_application_id = $row['leasing_application_id'];
    $leasing_application_number = $row['leasing_application_number'];
    $tenant_id = $row['tenant_id'];
    $property_id = $row['property_id'];
    $maturityDate = $systemModel->checkDate('summary', $row['maturity_date'], '', 'm/d/Y', '');

    $tenantDetails = $tenantModel->getTenant($tenant_id);
    $tenantName = $tenantDetails['tenant_name'] ?? '--';
    $contactPerson = $tenantDetails['contact_person'] ?? '--';

    $propertyDetails = $propertyModel->getProperty($property_id);
    $propertyName = $propertyDetails['property_name'] ?? '--';

    $leasingApplicationIDEncrypted = $securityModel->encryptData($leasing_application_id);

    $table .= '<tr>
                    <td>
                        <a href="leasing-summary.php?id='. $leasingApplicationIDEncrypted .'">
                            '. $leasing_application_number .'
                        </a>
                    </td>
                    <td>'. $tenantName .'</td>
                    <td>'. $contactPerson .'</td>
                    <td>'. $propertyName .'</td>
                    <td>'. $maturityDate .'</td>
                </tr>';
}

$table .= '</tbody></table>';


$emailSetting = $emailSettingModel->getEmailSetting(1);
$mailFromName = $emailSetting['mail_from_name'] ?? null;
$mailFromEmail = $emailSetting['mail_from_email'] ?? null;

$notificationSettingDetails = $notificationSettingModel->getNotificationSetting(20);
$emailSubject = $notificationSettingDetails['email_notification_subject'] ?? null;
$emailBody = $notificationSettingDetails['email_notification_body'] ?? null;
$emailBody = str_replace('{TABLE}', $table, $emailBody);

$message = '<!DOCTYPE html>
                <html>
                <head>
                <meta charset="UTF-8">
                <title>{EMAIL_SUBJECT}</title>
                </head>
                <body>
                {EMAIL_CONTENT}
                </body>
                </html>';
$message = str_replace('{EMAIL_SUBJECT}', $emailSubject, $message);
$message = str_replace('{EMAIL_CONTENT}', $emailBody, $message);

$mailer = new PHPMailer\PHPMailer\PHPMailer();
$mailHost = $emailSetting['mail_host'] ?? MAIL_HOST;
$smtpAuth = empty($emailSetting['smtp_auth']) ? false : true;
$mailUsername = $emailSetting['mail_username'] ?? MAIL_USERNAME;
$mailPassword = !empty($password) ? $securityModel->decryptData($emailSetting['mail_password']) : MAIL_PASSWORD;
$mailEncryption = $emailSetting['mail_encryption'] ?? MAIL_SMTP_SECURE;
$port = $emailSetting['port'] ?? MAIL_PORT;

$mailer->isSMTP();
$mailer->isHTML(true);
$mailer->Host = $mailHost;
$mailer->SMTPAuth = $smtpAuth;
$mailer->Username = $mailUsername;
$mailer->Password = $mailPassword;
$mailer->SMTPSecure = $mailEncryption;
$mailer->Port = $port;

$mailer->setFrom($mailFromEmail, $mailFromName);
$mailer->addAddress('christianbaguisa@christianmotors.ph');
$mailer->addAddress('glenbonita@christianmotors.ph');
$mailer->addAddress('l.agulto@christianmotors.ph');
$mailer->addAddress('mb.marcelo@christianmotors.ph');
$mailer->Subject = $emailSubject;
$mailer->Body = $message;

if($count > 0){
    try {
        if ($mailer->send()) {
            file_put_contents($log_file, "Email sent successfully at " . date("Y-m-d H:i:s") . "\n", FILE_APPEND);
            echo "Email sent successfully";
        } else {
            file_put_contents($log_file, "Failed to send email. Error: " . $mailer->ErrorInfo . " at " . date("Y-m-d H:i:s") . "\n", FILE_APPEND);
            echo "Failed to send email. Error: " . $mailer->ErrorInfo;
        }
    } catch (Exception $e) {
        file_put_contents($log_file, "Exception occurred: " . $e->getMessage() . " at " . date("Y-m-d H:i:s") . "\n", FILE_APPEND);
        echo "Exception occurred: " . $e->getMessage();
    }
}

file_put_contents($log_file, "Script ended at " . date("Y-m-d H:i:s") . "\n", FILE_APPEND);
?>