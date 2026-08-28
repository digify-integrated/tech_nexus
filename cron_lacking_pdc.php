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
require('model/supplier-model.php');
require('model/employee-model.php');
require('model/user-model.php');
require('model/pdc-management-model.php');
require('model/leasing-application-model.php');
require('model/tenant-model.php');
require('model/property-model.php');
require('model/sales-proposal-model.php');
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
$supplierModel = new SupplierModel($databaseModel);
$miscellaneousClientModel = new MiscellaneousClientModel($databaseModel);
$emailSettingModel = new EmailSettingModel(new DatabaseModel);
$notificationSettingModel = new NotificationSettingModel(new DatabaseModel);

$userModel = new UserModel($databaseModel, $systemModel);
$employeeModel = new EmployeeModel($databaseModel);
$salesProposalModel = new SalesProposalModel($databaseModel);
$pdcManagementModel = new PDCManagementModel($databaseModel);
$leasingApplicationModel = new LeasingApplicationModel($databaseModel);
$tenantModel = new TenantModel($databaseModel);
$propertyModel = new PropertyModel($databaseModel);

$groupedData = [];

$sql = $databaseModel->getConnection()->prepare('
    SELECT * 
    FROM loan_collections
    WHERE mode_of_payment = "Check"
    AND check_number LIKE "%LACKING%"
');
$sql->execute();
$options = $sql->fetchAll(PDO::FETCH_ASSOC);
$count = count($options);
$sql->closeCursor();

foreach ($options as $row) {

    $salesProposalID = $row['sales_proposal_id'];
    $loanNumber      = $row['loan_number'];
    $customerID      = $row['customer_id'];
    $productID       = $row['product_id'];
    $paymentAmount   = $row['payment_amount'];
    $leasingApplicationID = $row['leasing_application_id'];
    $pdcType         = $row['pdc_type'];

    $checkDateRaw = $row['check_date'];

    $customerDetails = $customerModel->getPersonalInformation($customerID);
    $customerName = $customerDetails['file_as'] ?? '';

    if($pdcType == 'Leasing'){
        $leasing = $leasingApplicationModel->getLeasingApplication($leasingApplicationID);

        $loanNumber = $leasing['leasing_application_number'] ?? '';

        $tenant = $tenantModel->getTenant($leasing['tenant_id'] ?? '');
        $customerName = $tenant['tenant_name'] ?? '';
    }

    $key = md5($customerName . '|' . $loanNumber);

    if(!isset($groupedData[$key])){
        $groupedData[$key] = [
            'customer' => $customerName,
            'loan_number' => $loanNumber,
            'check_count' => 0,
            'earliest_date' => $checkDateRaw,
            'payment_amount' => $paymentAmount
        ];
    }

    $groupedData[$key]['check_count']++;

    if(strtotime($checkDateRaw) < strtotime($groupedData[$key]['earliest_date'])){
        $groupedData[$key]['earliest_date'] = $checkDateRaw;
    }
}

$table = '
<table border="1" cellspacing="0" cellpadding="5">
    <thead>
        <tr>
            <th>Customer</th>
            <th>Loan Number</th>
            <th>No. of Checks</th>
            <th>Earliest Check Date</th>
            <th>Check Amount</th>
        </tr>
    </thead>
    <tbody>
';

foreach($groupedData as $group){

    $table .= '
        <tr>
            <td>'.$group['customer'].'</td>
            <td>'.$group['loan_number'].'</td>
            <td align="center">'.$group['check_count'].'</td>
            <td>'.date('m/d/Y', strtotime($group['earliest_date'])).'</td>
            <td align="right">'.number_format($group['payment_amount'],2).'</td>
        </tr>
    ';
}

$table .= '
    </tbody>
</table>
';


$emailSetting = $emailSettingModel->getEmailSetting(1);
$mailFromName = $emailSetting['mail_from_name'] ?? null;
$mailFromEmail = $emailSetting['mail_from_email'] ?? null;

$notificationSettingDetails = $notificationSettingModel->getNotificationSetting(24);
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
$mailer->addAddress('j.pineda@christianmotors.ph');
$mailer->addAddress('l.cabarles@christianmotors.ph');
$mailer->addAddress('christianbaguisa@christianmotors.ph');
$mailer->addAddress('ma.garsula@christianmotors.ph');
$mailer->Subject = $emailSubject;
$mailer->Body = $message;

if($count > 0){
    try {
        if ($mailer->send()) {
            file_put_contents($log_file, "Lacking PDC Email sent successfully at " . date("Y-m-d H:i:s") . "\n", FILE_APPEND);
            echo "Email sent successfully";
        } else {
            file_put_contents($log_file, "Lacking PDC Failed to send email. Error: " . $mailer->ErrorInfo . " at " . date("Y-m-d H:i:s") . "\n", FILE_APPEND);
            echo "Failed to send email. Error: " . $mailer->ErrorInfo;
        }
    } catch (Exception $e) {
        file_put_contents($log_file, "Lacking PDC Exception occurred: " . $e->getMessage() . " at " . date("Y-m-d H:i:s") . "\n", FILE_APPEND);
        echo "Exception occurred: " . $e->getMessage();
    }
}

file_put_contents($log_file, "Script ended at " . date("Y-m-d H:i:s") . "\n", FILE_APPEND);
?>