<?php
require('config/config.php');
require('model/database-model.php');
require('model/sales-proposal-model.php');
require('model/security-model.php');
require('model/system-model.php');
require('model/customer-model.php');
require('model/notification-setting-model.php');
require('model/email-setting-model.php');
require ('assets/libs/PHPMailer/src/PHPMailer.php');
require ('assets/libs/PHPMailer/src/Exception.php');
require ('assets/libs/PHPMailer/src/SMTP.php');

$log_file = '/home/u890127610/domains/cgmids.com/public_html/cron.log';
file_put_contents($log_file, "Script started at " . date("Y-m-d H:i:s") . "\n", FILE_APPEND);

$databaseModel = new DatabaseModel();
$systemModel = new SystemModel();
$securityModel = new SecurityModel();
$salesProposalModel = new SalesProposalModel($databaseModel);
$customerModel = new CustomerModel($databaseModel);
$emailSettingModel = new EmailSettingModel(new DatabaseModel);
$notificationSettingModel = new NotificationSettingModel(new DatabaseModel);

$table = '<table border="1" cellspacing="0" cellpadding="5">
            <thead>
                <tr>
                    <td>Sales Proposal Number</td>
                    <td>Customer Name</td>
                    <td>For CI Date</td>
                    <td>Status</td>
                </tr>
            <thead>
            <tbody>';

$sql = $databaseModel->getConnection()->prepare('CALL cronSalesProposalOverdueForCI()');
$sql->execute();
$options = $sql->fetchAll(PDO::FETCH_ASSOC);
$sql->closeCursor();
$count = count($options);

foreach ($options as $row) {
    $customerID = $row['customer_id'];
    $salesProposalID = $row['sales_proposal_id'];
    $salesProposalNumber = $row['sales_proposal_number'];
    $forCIDate = $systemModel->checkDate('summary', $row['for_ci_date'], '', 'F d, Y', '');

    $customerDetails = $customerModel->getPersonalInformation($customerID);
    $customerName = $customerDetails['file_as'] ?? null;

    $today = date('Y-m-d');
    $overdueDate = $row['for_ci_date'];
    $diff = date_diff(date_create($overdueDate), date_create($today));
    $daysOverdue = $diff->days;

    $salesProposalIDEncrypted = $securityModel->encryptData($salesProposalID);

    $table .= '<tr>
                    <td><a href="cgmids.com/sales-proposal-for-ci.php?customer='. $securityModel->encryptData($customerID) .'&id='. $salesProposalIDEncrypted .'">
                                                    '. $salesProposalNumber .'
                                                </a></td>
                    <td>'. $customerName .'</td>
                    <td>'. $forCIDate .'</td>
                    <td>Overdue by '. $daysOverdue  .' days</td>
                </tr>';
}

$table .= '</tbody></table>';


$emailSetting = $emailSettingModel->getEmailSetting(1);
$mailFromName = $emailSetting['mail_from_name'] ?? null;
$mailFromEmail = $emailSetting['mail_from_email'] ?? null;

$notificationSettingDetails = $notificationSettingModel->getNotificationSetting(5);
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
$mailer->addAddress('ma.garsula@christianmotors.ph');
$mailer->addAddress('christianbaguisa@christianmotors.ph');
$mailer->addAddress('l.agulto@christianmotors.ph');
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


// Close database connections
$databaseModel->getConnection()->close();

file_put_contents($log_file, "Script ended at " . date("Y-m-d H:i:s") . "\n", FILE_APPEND);
?>