<?php
  require('config/config.php');
  require('model/database-model.php');
  require('model/sales-proposal-model.php');

  $log_file = '/home/u890127610/domains/cgmids.com/public_html/cron.log';
  file_put_contents($log_file, "Script started at " . date("Y-m-d H:i:s") . "\n", FILE_APPEND);


  $databaseModel = new DatabaseModel();
  $salesProposalModel = new SalesProposalModel($databaseModel);

  try {
    $salesProposalModel->cronSalesProposalCancelDraft(1);
    file_put_contents($log_file, "Cancel draft sales proposal successfully at " . date("Y-m-d H:i:s") . "\n", FILE_APPEND);
} catch (Exception $e) {
    file_put_contents($log_file, "Exception occurred: " . $e->getMessage() . " at " . date("Y-m-d H:i:s") . "\n", FILE_APPEND);
    echo "Exception occurred: " . $e->getMessage();
}

file_put_contents($log_file, "Script ended at " . date("Y-m-d H:i:s") . "\n", FILE_APPEND);

?>