<?php
require('config/config.php');
require('model/database-model.php');
require('model/internal-dr-model.php');
require('model/security-model.php');
require('model/system-model.php');
require('model/customer-model.php');
require('model/miscellaneous-client-model.php');
require('model/notification-setting-model.php');
require('model/email-setting-model.php');
require('model/product-model.php');
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
$miscellaneousClientModel = new MiscellaneousClientModel($databaseModel);
$emailSettingModel = new EmailSettingModel(new DatabaseModel);
$notificationSettingModel = new NotificationSettingModel(new DatabaseModel);

$table = '<table border="1" cellspacing="0" cellpadding="5">
            <thead>
                <tr>
                    <th>Issuance No.</th>
                    <th>Company</th>
                    <th>Reference</th>
                    <th>Issuance Date</th>
                    <th>Released Date</th>
                    <th>Days Overdue</th>
                </tr>
            <thead>
            <tbody>';

$sql = $databaseModel->getConnection()->prepare('SELECT * FROM part_transaction WHERE part_transaction_status = "Released" AND company_id != 1 ORDER BY company_id');
$sql->execute();
$options = $sql->fetchAll(PDO::FETCH_ASSOC);
$sql->closeCursor();
$count = count($options);

foreach ($options as $row) {
    $issuance_no = $row['issuance_no'];
    $customer_type = $row['customer_type'];
    $customer_id = $row['customer_id'];
    $company_id = $row['company_id'];

    $released_date = $systemModel->checkDate('empty', $row['released_date'], '', 'm/d/Y', '');
    $issuance_date = $systemModel->checkDate('empty', $row['issuance_date'], '', 'm/d/Y', '');

    if($customer_type === 'Miscellaneous'){
        $customerDetails = $miscellaneousClientModel->getMiscellaneousClient($customer_id);
        $transaction_reference = $customerDetails['client_name'] ?? 'N/A';
    }
    else if($customer_type === 'Customer'){
        $customerDetails = $customerModel->getPersonalInformation($customer_id);
        $transaction_reference = $customerDetails['file_as'] ?? null;
    }
    else{
        $productDetails = $productModel->getProduct($customer_id);
        $stock_number = $productDetails['stock_number'];
        $transaction_reference = $stock_number;
    }

    if($company_id == '2'){
        $company_name = 'NE Truck Builders';
    }
    else{
        $company_name = 'FUSO Tarlac';
    }
                
    if(!empty($released_date)){
        $returnDate = DateTime::createFromFormat('m/d/Y', $released_date);
        $returnDate->setTime(0, 0, 0);
        $today = new DateTime('today');

        $daysDiff = (int) $returnDate->diff($today)->format('%R%a');
        
        if($daysDiff >= 2){
            $table .= '<tr>
                    <td>'. $issuance_no .'</td>
                    <td>'. $company_name .'</td>
                    <td>'. $transaction_reference .'</td>
                    <td>'. $issuance_date .'</td>
                    <td>'. $released_date .'</td>
                    <td>'. $daysDiff . ' Day(s)</td>
                </tr>';
        }
    }

    
}

$table .= '</tbody></table>';


$emailSetting = $emailSettingModel->getEmailSetting(1);
$mailFromName = $emailSetting['mail_from_name'] ?? null;
$mailFromEmail = $emailSetting['mail_from_email'] ?? null;

$notificationSettingDetails = $notificationSettingModel->getNotificationSetting(16);
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
$mailer->addAddress('k.baguisa@christianmotors.ph');
$mailer->addAddress('christianbaguisa@christianmotors.ph');
$mailer->addAddress('j.mendoza@christianmotors.ph');
$mailer->addAddress('k.magiwe@christianmotors.ph');
$mailer->addAddress('glenbonita@christianmotors.ph');
$mailer->addAddress('l.agulto@christianmotors.ph');
$mailer->addAddress('j.delacorte@christianmotors.ph');
$mailer->addAddress('jl.manzano.fuso@christianmotors.ph');
$mailer->addAddress('m.siapo.fuso@christianmotors.ph');
$mailer->addAddress('cj.agudo@christianmotors.ph');
$mailer->addAddress('l.samaniego@christianmotors.ph');
$mailer->addAddress('sc.lapuz@christianmotors.ph');
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