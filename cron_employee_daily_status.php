<?php
  require('config/config.php');
  require('model/database-model.php');
  require('model/employee-model.php');

  $log_file = '/home/u890127610/domains/cgmids.com/public_html/cron.log';
  file_put_contents($log_file, "Script started at " . date("Y-m-d H:i:s") . "\n", FILE_APPEND);


  $databaseModel = new DatabaseModel();
  $employeeModel = new EmployeeModel($databaseModel);

  try {
    $employeeModel->cronTransferActiveEmployeeToAttendanceStatus(1);
    file_put_contents($log_file, "Added employee for daily attendance status at " . date("Y-m-d H:i:s") . "\n", FILE_APPEND);
} catch (Exception $e) {
    file_put_contents($log_file, "Exception occurred: " . $e->getMessage() . " at " . date("Y-m-d H:i:s") . "\n", FILE_APPEND);
    echo "Exception occurred: " . $e->getMessage();
}


file_put_contents($log_file, "Script ended at " . date("Y-m-d H:i:s") . "\n", FILE_APPEND);

?>