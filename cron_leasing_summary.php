<?php
require('config/config.php');
require('model/database-model.php');
require('model/sales-proposal-model.php');
require('model/security-model.php');
require('model/system-model.php');
require('model/customer-model.php');
require('model/notification-setting-model.php');
require('model/email-setting-model.php');
require('model/tenant-model.php');
require('model/property-model.php');
require('model/leasing-application-model.php');
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
$tenantModel = new TenantModel(new DatabaseModel);
$propertyModel = new PropertyModel(new DatabaseModel);
$leasingApplicationModel = new LeasingApplicationModel(new DatabaseModel);

$table = '<table border="1" cellspacing="0" cellpadding="5">
            <thead>
                <tr>
                    <th>Application Number</th>
                        <th>Tenant</th>
                        <th>Property</th>
                        <th>Total Amount Due</th>
                        <th>Unpaid Rental</th>
                        <th>Unpaid Electricity</th>
                        <th>Unpaid Water</th>
                        <th>Unpaid Charges</th>
                        <th>Floor Area</th>
                        <th>Term</th>
                        <th>Inception Date</th>
                        <th>Maturity</th>
                        <th>Security Deposit</th>
                        <th>Esc. Rate</th>
                        <th>Status</th>
                        <th>Outstanding Balance</th>
                </tr>
            <thead>
            <tbody>';

$sql = $databaseModel->getConnection()->prepare('CALL generateLeasingSummaryTable()');
$sql->execute();
$options = $sql->fetchAll(PDO::FETCH_ASSOC);
$sql->closeCursor();
$count = count($options);

foreach ($options as $row) {
    $leasingApplicationID = $row['leasing_application_id'];
    $leasingApplicationNumber = $row['leasing_application_number'];
    $tenantID = $row['tenant_id'];
    $propertyID = $row['property_id'];
    $floorArea = $row['floor_area'];
    $termLength = $row['term_length'];
    $termType = $row['term_type'];
    $securityDeposit = $row['security_deposit'];
    $escalationRate = $row['escalation_rate'];
    $initialBasicRental = $row['initial_basic_rental'];
    $contractDate = $systemModel->checkDate('summary', $row['contract_date'], '', 'm/d/Y', '');
    $maturityDate = $systemModel->checkDate('summary', $row['maturity_date'], '', 'm/d/Y', '');
    $leasingApplicationStatus = $leasingApplicationModel->getLeasingApplicationStatus($row['application_status']);

    $tenantDetails = $tenantModel->getTenant($tenantID);
    $tenantName = $tenantDetails['tenant_name'];
    $contactPerson = $tenantDetails['contact_person'];

    $propertyDetails = $propertyModel->getProperty($propertyID);
    $propertyName = $propertyDetails['property_name'];

    $leasingApplicationIDEncrypted = $securityModel->encryptData($leasingApplicationID);

    $unpaidRental = $leasingApplicationModel->getLeasingAplicationRepaymentTotal($leasingApplicationID, date('Y-m-d'), 'Unpaid Rental')['total'];
    $unpaidElectricity = $leasingApplicationModel->getLeasingAplicationRepaymentTotal($leasingApplicationID, date('Y-m-d'), 'Unpaid Electricity')['total'];
    $unpaidWater = $leasingApplicationModel->getLeasingAplicationRepaymentTotal($leasingApplicationID, date('Y-m-d'), 'Unpaid Water')['total'];
    $unpaidOtherCharges = $leasingApplicationModel->getLeasingAplicationRepaymentTotal($leasingApplicationID, date('Y-m-d'), 'Unpaid Other Charges')['total'];
    $outstandingBalance = $leasingApplicationModel->getLeasingAplicationRepaymentTotal($leasingApplicationID, date('Y-m-d'), 'Outstanding Balance')['total'];
    $totaloutstandingBalance = $leasingApplicationModel->getLeasingAplicationRepaymentTotal($leasingApplicationID, null, 'Total Outstanding Balance')['total'];

    if($unpaidRental <= 0){
        $unpaidRental = 0;
    }

    if($unpaidElectricity <= 0){
        $unpaidElectricity = 0;
    }

    if($unpaidWater <= 0){
        $unpaidWater = 0;
    }

    if($unpaidOtherCharges <= 0){
        $unpaidOtherCharges = 0;
    }

    if($outstandingBalance <= 0){
        $outstandingBalance = 0;
    }

    $table .= '<tr>
                    <td><a href="cgmids.com/leasing-summary.php?id='. $leasingApplicationIDEncrypted .'">
                                         <div class="row">
                                        <div class="col">
                                        <h6 class="mb-0">'. $leasingApplicationNumber .'</h6>
                                        </div>
                                    </div></td>
                    <td><a href="cgmids.com/leasing-summary.php?id='. $leasingApplicationIDEncrypted .'">
                                         <div class="row">
                                        <div class="col">
                                        <h6 class="mb-0">'. $tenantName .'</h6>
                                        <p class="f-12 mb-0">'. $contactPerson .'</p>
                                        </div>
                                    </div>
                        </a>
                        </td>
                    <td>'. $propertyName .'</td>
                    <td>'. number_format($unpaidRental, 2) .'</td>
                    <td>'. number_format($unpaidElectricity, 2) .'</td>
                    <td>'. number_format($unpaidWater, 2) .'</td>
                    <td>'. number_format($unpaidOtherCharges, 2) .'</td>
                    <td>'. number_format($outstandingBalance, 2) .'</td>
                    <td>'. number_format($floorArea, 2) .'</td>
                    <td>'. $termLength . ' ' . $termType .'</td>
                    <td>'. $contractDate .'</td>
                    <td>'. $maturityDate .'</td>
                    <td>'. number_format($securityDeposit, 2) .'</td>
                    <td>'. number_format($escalationRate, 2) .'</td>
                    <td>'. $leasingApplicationStatus .'</td>
                    <td>'. number_format($totaloutstandingBalance, 2) .'</td>
                </tr>';
}

$table .= '</tbody></table>';


$emailSetting = $emailSettingModel->getEmailSetting(1);
$mailFromName = $emailSetting['mail_from_name'] ?? null;
$mailFromEmail = $emailSetting['mail_from_email'] ?? null;

$emailSubject = 'Leasing Summary as of ' . date('F d, Y');
$emailBody = '<p>This is an automated message about leasing summary.</p>
<p>{TABLE}</p>';
$emailBody = str_replace('{TABLE}', $table, $emailBody);

$message = '<!DOCTYPE html>
                <html>
                <head>
                <meta charset="UTF-8">
                <title>Leasing Summary</title>
                </head>
                <body>
                {EMAIL_CONTENT}
                </body>
                </html>';
$message = str_replace('Leasing Summary', $emailSubject, $message);
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