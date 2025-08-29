<?php
  require('config/config.php');
  require('model/database-model.php');
  require('model/sales-proposal-model.php');
  require('model/employee-model.php');
  require('model/company-model.php');
  require('model/department-model.php');
  require('model/job-position-model.php');
  require('model/system-model.php');
  require('model/email-setting-model.php');
  require('model/notification-setting-model.php');

  $log_file = '/home/u890127610/domains/cgmids.com/public_html/cron.log';
  file_put_contents($log_file, "Script started at " . date("Y-m-d H:i:s") . "\n", FILE_APPEND);


  $databaseModel = new DatabaseModel();
  $employeeModel = new EmployeeModel($databaseModel);
  $companyModel = new CompanyModel($databaseModel);
  $departmentModel = new DepartmentModel($databaseModel);
  $jobPositionModel = new JobPositionModel($databaseModel);
  $emailSettingModel = new EmailSettingModel($databaseModel);
  $notificationSettingModel = new NotificationSettingModel($databaseModel);
  $systemModel = new SystemModel();

  try {

    $emailSetting = $emailSettingModel->getEmailSetting(1);
    $mailFromName = $emailSetting['mail_from_name'] ?? null;
    $mailFromEmail = $emailSetting['mail_from_email'] ?? null;

    $sql = $databaseModel->getConnection()->prepare('CALL cronGetUnsentWelcomeEmail()');
    $sql->execute();
    $options = $sql->fetchAll(PDO::FETCH_ASSOC);
    $sql->closeCursor();
    $count = count($options);
    
    foreach ($options as $row) {
        $employeeID = $row['contact_id'];
        $getEmployeeWorkContactInformation = $employeeModel->getEmployeeWorkContactInformation($employeeID);
        $email = $getEmployeeWorkContactInformation['email'] ?? '--';
        $mobile = $getEmployeeWorkContactInformation['mobile'] ?? '--';

        $getPersonalInformation = $employeeModel->getPersonalInformation($employeeID);
        $fileAs = $getPersonalInformation['file_as'] ?? '--';
        $nickname = $getPersonalInformation['nickname'] ?? '--';
        $contactImage = $getPersonalInformation['contact_image'] ?? '--';
        $contactImage = str_replace('./', 'https://cgmids.com/', $contactImage);

        $employeeDetails = $employeeModel->getEmploymentInformation($employeeID);
        $departmentID = $employeeDetails['department_id'] ?? null;
        $companyID = $employeeDetails['company_id'] ?? null;
        $jobPositionID = $employeeDetails['job_position_id'] ?? null;
        $companyName = $companyModel->getCompany($companyID)['company_name'] ?? '--';
        $departmentName = $departmentModel->getDepartment($departmentID)['department_name'] ?? '--';
        $jobPositionName = $jobPositionModel->getJobPosition($jobPositionID)['job_position_name'] ?? null;
        $onboardDate = $systemModel->checkDate('empty', $employeeDetails['onboard_date'] ?? null, '', 'm/d/Y', '');

        if(empty($contactImage) || empty($fileAs) || empty($nickname) || empty($companyName) || empty($departmentName) || empty($jobPositionName) || empty($email) || empty($mobile) || empty($onboardDate)){
           continue;
        }

        $notificationSettingDetails = $notificationSettingModel->getNotificationSetting(12);
        $emailSubject = $notificationSettingDetails['email_notification_subject'] ?? null;
        $emailSubject = str_replace('#{NAME}', $fileAs, $emailSubject);

        $emailBody = $notificationSettingDetails['email_notification_body'] ?? null;
        $emailBody = str_replace('#{EMPLOYEE_IMAGE}', $contactImage, $emailBody);
        $emailBody = str_replace('#{NAME}', $fileAs, $emailBody);
        $emailBody = str_replace('#{NICKNAME}', $nickname, $emailBody);
        $emailBody = str_replace('#{COMPANY}', $companyName, $emailBody);
        $emailBody = str_replace('#{DEPARTMENT}', $departmentName, $emailBody);
        $emailBody = str_replace('#{JOB_POSITION}', $jobPositionName, $emailBody);
        $emailBody = str_replace('#{EMAIL}', $email, $emailBody);
        $emailBody = str_replace('#{MOBILE}', $mobile, $emailBody);
        $emailBody = str_replace('#{HIRING_DATE}', $onboardDate, $emailBody);

        $message = file_get_contents('email-template/default-email.html');
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
        $getEmployeeActiveWorkContactInformation = $employeeModel->getEmployeeActiveWorkContactInformation();

        foreach ($getEmployeeActiveWorkContactInformation as $row) {
            $mailer->addAddress($row['email']);
        }
        $mailer->Subject = $emailSubject;
        $mailer->Body = $message;

        try {
            if ($mailer->send()) {
                file_put_contents($log_file, "Email sent successfully at " . date("Y-m-d H:i:s") . "\n", FILE_APPEND);
                echo "Email sent successfully";
                $employeeModel->updateSentWelcomeEmail($employeeID, $userID);
            } else {
                file_put_contents($log_file, "Failed to send email. Error: " . $mailer->ErrorInfo . " at " . date("Y-m-d H:i:s") . "\n", FILE_APPEND);
                echo "Failed to send email. Error: " . $mailer->ErrorInfo;
            }
        } catch (Exception $e) {
            file_put_contents($log_file, "Exception occurred: " . $e->getMessage() . " at " . date("Y-m-d H:i:s") . "\n", FILE_APPEND);
            echo "Exception occurred: " . $e->getMessage();
        }
    }

    file_put_contents($log_file, "Sent welcome email successfully at " . date("Y-m-d H:i:s") . "\n", FILE_APPEND);
} catch (Exception $e) {
    file_put_contents($log_file, "Exception occurred: " . $e->getMessage() . " at " . date("Y-m-d H:i:s") . "\n", FILE_APPEND);
    echo "Exception occurred: " . $e->getMessage();
}

file_put_contents($log_file, "Script ended at " . date("Y-m-d H:i:s") . "\n", FILE_APPEND);

?>